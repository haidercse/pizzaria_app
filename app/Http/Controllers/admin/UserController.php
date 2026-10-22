<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        
        $users = User::with(['contract', 'roles'])->orderBy('id', 'DESC')->get();
        return view('backend.pages.users.user_list', compact('users'));
    }

    public function create()
    {
        
        return response()->json([
            'message' => 'Create page not used. Use modal form instead.'
        ], 200);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|string|email|max:255|unique:users',
            'phone'  => 'nullable|string|max:20',
            'salary' => 'nullable|numeric',
            'contract_type' => 'required|in:full_time,part_time',
            'role' => 'required|exists:roles,id', // validate role id
        ]);

        $user = new User();
        $user->name  = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make('12345678');
        $user->color = $this->nextScheduleColor();
        $user->save();

        // Assign role by ID
        $role = \Spatie\Permission\Models\Role::find($request->role);
        if ($role) {
            $user->assignRole($role);
        }

        // Contract info save
        $contract = new Contract();
        $contract->user_id = $user->id;
        $contract->type = $request->contract_type;
        $contract->hourly_rate = $request->salary;
        $contract->save();

        return response()->json([
            'success' => true,
            'message' => 'User created successfully!',
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'contract_type' => $user->contract->type ?? '-',
                'salary' => $user->contract->hourly_rate ?? '-',
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::with('contract')->findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone'  => 'nullable|string|max:20',
            'salary' => 'nullable|numeric',
            'contract_type' => 'required|in:full_time,part_time',
            'role' => 'required|exists:roles,id',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();

        // Update role
        $role = \Spatie\Permission\Models\Role::find($request->role);
        if ($role) {
            $user->syncRoles([$role]); // remove old roles and assign new one
        }

        // Contract update
        if ($user->contract) {
            $user->contract->update([
                'type' => $request->contract_type,
                'hourly_rate' => $request->salary,
            ]);
        } else {
            Contract::create([
                'user_id' => $user->id,
                'type' => $request->contract_type,
                'hourly_rate' => $request->salary,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully!',
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'contract_type' => $user->contract->type ?? '-',
                'salary' => $user->contract->hourly_rate ?? '-',
            ]
        ]);
    }

    public function updateColor(Request $request, $id)
    {
        $validated = $request->validate([
            'color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        $user = User::findOrFail($id);
        $user->color = $validated['color'];
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Schedule color updated successfully.',
        ]);
    }

    private function nextScheduleColor(): string
    {
        $palette = [
            '#2563eb',
            '#059669',
            '#7c3aed',
            '#dc2626',
            '#d97706',
            '#0891b2',
            '#db2777',
            '#4f46e5',
        ];
        $usedColors = User::whereIn('color', $palette)->pluck('color')->countBy();

        return collect($palette)
            ->sortBy(fn ($color) => $usedColors->get($color, 0))
            ->first();
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully!'
        ]);
    }
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $user->password = Hash::make('12345678');
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully! Default password: 12345678'
        ]);
    }
}
