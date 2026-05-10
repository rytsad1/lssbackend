<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreUserMeasurementRequest;
use App\Http\Resources\Inventory\UserMeasurementResource;
use App\Models\Inventory\UserMeasurement;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserMeasurementController extends Controller
{
    /**
     * Vieno nario dydžiai.
     */
    public function show(User $user)
    {
        $measurement = UserMeasurement::firstOrNew(['user_id' => $user->id_User]);

        return new UserMeasurementResource($measurement);
    }

    /**
     * Atnaujina arba sukuria nario dydžius.
     */
    public function upsert(StoreUserMeasurementRequest $request, User $user)
    {
        $measurement = UserMeasurement::updateOrCreate(
            ['user_id' => $user->id_User],
            $request->validated()
        );

        return new UserMeasurementResource($measurement);
    }

    /**
     * Pašalina nario matavimus.
     */
    public function destroy(User $user): JsonResponse
    {
        UserMeasurement::where('user_id', $user->id_User)->delete();

        return response()->json([
            'message' => 'Matavimai pašalinti.',
        ]);
    }
}
