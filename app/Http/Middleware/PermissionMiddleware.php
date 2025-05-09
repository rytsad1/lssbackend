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

        // Pakraunami visi reikalingi ryšiai
        $user->load('userRoles.role.rolePermissions.premission');

        // Surenkami leidimai iš visų naudotojo rolių
        $permissions = $user->userRoles
            ->flatMap(function ($userRole) {
                return $userRole->role->rolePermissions
                    ->map(fn($rp) => $rp->premission->Name ?? null);
            })
            ->filter()
            ->unique();

        // Debug
        // dd($permissions); // gali įjungti testavimui

        if ($permissions->contains('everything') || $permissions->contains($requiredPermission)) {
            return $next($request);
        }

        return response()->json(['message' => 'Prieiga uždrausta'], 403);
    }
}
