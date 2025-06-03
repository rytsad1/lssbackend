<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RolePremission;
use Illuminate\Http\Request;
use App\Models\Premission;

class RolePremissionController extends Controller
{
    // Gauti visus leidimus konkrečiai rolei
    public function index($roleId)
    {
        $role = Role::with('rolePermissions.permission')->findOrFail($roleId);
        return response()->json([
            'permissions' => $role->rolePermissions->pluck('permission')
        ]);
    }

    // Atnaujinti rolės leidimus
    public function update(Request $request, $roleId)
    {
        $request->validate([
            'permission_ids' => ['required', 'array'],
            'permission_ids.*' => ['exists:premission,id_Premission'],
        ]);

        $role = Role::findOrFail($roleId);

        // Ištrinti senus leidimus
        RolePremission::where('fk_Role', $role->id_Role)->delete();

        // Įrašyti naujus
        foreach ($request->permission_ids as $permId) {
            RolePremission::create([
                'fk_Role' => $role->id_Role,
                'fk_Permission' => $permId,
            ]);
        }

        return response()->json(['message' => 'Rolės leidimai atnaujinti sėkmingai.']);
    }
}
