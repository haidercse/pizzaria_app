<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('id','desc')->get();
        return view('backend.pages.permissions.index', compact('permissions'));
    }

    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'name.*'      => 'required|string|max:100',
            'group_name'  => 'required|string|max:100',
            'id'          => 'nullable|exists:permissions,id'
        ]);

        $permissions = [];

        if ($request->id) {
            // Edit
            if (Permission::where('name', $request->name[0])
                ->where('guard_name', 'web')
                ->where('id', '!=', $request->id)
                ->exists()
            ) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Permission with this name already exists!'
                ], 422);
            }

            $permission = Permission::findOrFail($request->id);
            $permission->name = $request->name[0];
            $permission->group_name = $request->group_name;
            $permission->save();

            $permissions[] = $permission;
            $message = 'Permission updated successfully!';
        } else {
            // Add multiple
            foreach ($request->name as $name) {
                if (!Permission::where('name', $name)->where('guard_name', 'web')->exists()) {
                    $permission = Permission::create([
                        'name'       => $name,
                        'guard_name' => 'web',
                        'group_name' => $request->group_name,
                    ]);
                    $permissions[] = $permission;
                }
            }
            $message = 'Permissions added successfully!';
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'permissions' => $permissions
        ]);
    }



    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Permission deleted successfully!',
        ]);
    }
}
