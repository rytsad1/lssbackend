<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $requiredPermission)
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json(['message' => 'Neleista – neprisijungęs naudotojas.'], 403);
        }

        $user->loadMissing('userRoles.role.rolePermissions.permission');

        $permissions = $user->userRoles
            ->flatMap(function ($userRole) {
                return optional(optional($userRole->role)->rolePermissions)->map(function ($rp) {
                    return optional($rp->permission)->Name;
                });
            })
            ->filter()
            ->map(fn($p) => strtolower(trim($p)))
            ->unique();

        if ($permissions->contains('everything') || $permissions->contains(strtolower($requiredPermission))) {
            return $next($request);
        }

        return response()->json(['message' => 'Prieiga uždrausta – neturite reikiamos teisės.'], 403);
    }
}
