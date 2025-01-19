<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\PermissionBereich;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('value', 'desc')->get();
        $permissions = Permission::all()->groupBy('bereich');
        $bereiche = PermissionBereich::all();
        return view('admin.member.roles', compact('roles', 'permissions', 'bereiche'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'level' => 'required|string|max:50',
            'permissions' => 'required|array',
            'value' => 'required|integer',
        ]);

        $userRole = Auth::user()->role;

        if (!$userRole || $request->value >= $userRole->value) {
            return redirect()->route('roles.index')->with('error', 'You cannot create a role with a value higher than or equal to your own.');
        }

        Role::create([
            'name' => $request->name,
            'level' => $request->level,
            'permissions' => $request->permissions,
            'value' => $request->value,
        ]);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $userRole = Auth::user()->role;

        if (!$userRole || $role->value >= $userRole->value) {
            return redirect()->route('roles.index')->with('error', 'You cannot edit a role with a value higher than or equal to your own.');
        }

        $permissions = Permission::all()->groupBy('bereich');
        $bereiche = PermissionBereich::all();
        return view('admin.member.edit_role', compact('role', 'permissions', 'bereiche'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'level' => 'required|string|max:50',
            'permissions' => 'required|array',
            'value' => 'required|integer',
        ]);

        $userRole = Auth::user()->role;

        if (!$userRole || $request->value >= $userRole->value) {
            return redirect()->route('roles.index')->with('error', 'You cannot update a role with a value higher than or equal to your own.');
        }

        $role->update([
            'name' => $request->name,
            'level' => $request->level,
            'permissions' => $request->permissions,
            'value' => $request->value,
        ]);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $userRole = Auth::user()->role;

        if (!$userRole || $role->value >= $userRole->value) {
            return redirect()->route('roles.index')->with('error', 'You cannot delete a role with a value higher than or equal to your own.');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}