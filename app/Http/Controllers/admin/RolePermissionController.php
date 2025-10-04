<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user(); 
        if (!$user || !$user->hasPermissionTo('roles.list')) {
            abort(403, 'Unauthorized access to see the list!');
        }

        $roles = Role::all();
        return view('backend.pages.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        if (!$user || !$user->hasPermissionTo('roles.create')) {
            abort(403, 'Unauthorized access to create role!');
        }

        $all_permissions = Permission::all();
        $permission_groups = User::getpermissionGroups();
        return view('backend.pages.roles.create', compact('all_permissions', 'permission_groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->hasPermissionTo('roles.create')) {
            abort(403, 'Unauthorized access to create this!');
        }

        $request->validate([
            'name' => 'required',
        ]);

        $role = Role::create(['name' => $request->name]);

        $permissions = $request->input('permissions');
        if (!empty($permissions)) {
            $role->syncPermissions($permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = Auth::user();
        if (!$user || !$user->hasPermissionTo('roles.edit')) {
            abort(403, 'Unauthorized access to edit this!');
        }

        $all_permissions = Permission::all();
        $permission_groups = User::getpermissionGroups();
        $role = Role::findById($id);

        return view('backend.pages.roles.edit', compact('role', 'all_permissions', 'permission_groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || !$user->hasPermissionTo('roles.edit')) {
            abort(403, 'Unauthorized access to update this!');
        }

        $request->validate([
            'name' => 'required|max:100|unique:roles,name,' . $id
        ]);

        $role = Role::findById($id);
        $permissions = $request->input('permissions');

        $role->name = $request->name;
        $role->save();

        if (!empty($permissions)) {
            $role->syncPermissions($permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if (!$user || !$user->hasPermissionTo('roles.delete')) {
            abort(403, 'Unauthorized access to delete this!');
        }

        $role = Role::findById($id);
        if ($role) {
            $role->delete();
        }

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully!');
    }
}
