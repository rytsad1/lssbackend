<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonFormat
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $data = $request->getContent();
            if ($data)
                json_decode($data, false, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            return response(['message' => 'Blogas JSON formatas'], 400);
        }
        return $next($request);
    }
}
