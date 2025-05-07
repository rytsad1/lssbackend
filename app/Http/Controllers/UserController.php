<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class UserController extends Controller
{
    public function index()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json([
                'authenticated_user' => $user,
                'data' => UserResource::collection(User::all())
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Tokenas netinkamas arba neprisijungta', 'error' => $e->getMessage()], 401);
        }
    }

    public function show(User $user)
    {
        return response(new UserResource($user), 200);
    }

    public function register(CreateRequest $request)
    {
        $user = User::create($request->validated());
        return response(new UserResource($user), 201);
    }

    public function update(UpdateRequest $request, User $user)
    {
        $user->update($request->validated());
        return response(new UserResource($user), 200);
    }

    public function destroy(User $user)
    {
        $tokenUserId = JWTAuth::parseToken()->getPayload()->get('sub');

        if ($user->id_User == $tokenUserId) {
            JWTAuth::invalidate(); // jei trinamas save – išjungti token
        }

        $user->delete();
        return response('', 204);
    }

    public function login(LoginRequest $request)
    {
        JWTAuth::factory()->setTTL(60); // token galiojimo laikas: 5 minutės

        $user = User::where('Email', $request->Email)->first();

        if ($user && Hash::check($request->Password, $user->Password)) {
            $token = JWTAuth::fromUser($user);

            return response([
                'message' => 'Prisijungta sėkmingai!',
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Carbon::now()->addMinutes(5)->toDateTimeString()
            ], 200);
        }

        return response(['message' => 'Neteisingi prisijungimo duomenys!'], 401);
    }

    public function logout()
    {
        try {
            $token = JWTAuth::getToken();
            if (!$token) {
                return response(['message' => 'Žetonas nerastas!'], 400);
            }

            JWTAuth::invalidate($token);
            return response(['message' => 'Atsijungta sėkmingai!'], 200);
        } catch (TokenExpiredException $e) {
            return response(['message' => 'Žetonas nebegalioja.'], 200);
        } catch (TokenInvalidException $e) {
            return response(['message' => 'Neteisingas žetonas!'], 403);
        } catch (\Exception $e) {
            return response(['message' => 'Atsijungimo klaida!'], 500);
        }
    }

    public function refreshToken()
    {
        try {
            $token = JWTAuth::getToken();
            if (!$token) {
                throw new HttpResponseException(response(['message' => 'Žetonas nenurodytas!'], 400));
            }

            JWTAuth::factory()->setTTL(5);
            $newToken = JWTAuth::refresh($token);

            return response([
                'message' => 'Žetonas atnaujintas',
                'access_token' => $newToken,
                'token_type' => 'bearer',
                'expires_in' => Carbon::now()->addMinutes(5)->toDateTimeString()
            ], 200);

        } catch (TokenExpiredException $e) {
            return response(['message' => 'Prisijungimo sesija baigėsi.'], 401);
        } catch (TokenInvalidException $e) {
            return response(['message' => 'Neteisingas žetonas!'], 403);
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()], 500);
        }
    }
}
