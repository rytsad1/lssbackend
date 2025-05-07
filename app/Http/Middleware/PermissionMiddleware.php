<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $requiredPermission)
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json(['message' => 'Neleista'], 403);
        }

        $permissions = $user->userRoles
            ->filter(fn($ur) => $ur->role && $ur->role->rolePermissions)
            ->flatMap(fn($ur) => $ur->role->rolePermissions->pluck('permission.Name')) // arba 'Key'
            ->unique();

        if ($permissions->contains('everything') || $permissions->contains($requiredPermission)) {
            return $next($request);
        }

        return response()->json(['message' => 'Prieiga uždrausta'], 403);
    }
}
