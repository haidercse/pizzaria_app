<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdminRole = Role::firstOrCreate(['name' => 'super admin']);
        $userRole       = Role::firstOrCreate(['name' => 'employee']);

        $permissionGroups = [
            'dashboard' => ['admin.dashboard'],

            'dough' => ['dough.index', 'dough.destroy'],

            //only for admin
            'settings' => ['users.index', 'day_tasks.index', 'holidays.index', 'events.index'],

            //only for admin
            'roles' => [
                'roles.index',
                'roles.create',
                'roles.edit',
                'roles.update',
                'roles.delete',
                'permissions.index'
            ],
            //only for admin
            'shift-management' => [
                'shift-manager.index',
                'shift.show',
                'checkout.monthly_overview',
                'availability.edit'
            ],

            'task' => ['tasks.monthly.matrix', 'tasks.daily', 'tasks.checklist'],


            'preps' => ['preps.list', 'preps.create', 'preps.delete', 'preps.index'],

            'dough-making-table-update' => ['phase.update.inline'],
            
        ];

        $userPermission = ['admin.dashboard'];

        foreach ($permissionGroups as $group => $permissions) {
            foreach ($permissions as $permissionName) {
                if (empty($permissionName)) continue;

                $permission = Permission::firstOrCreate(
                    ['name' => $permissionName, 'guard_name' => 'web'],
                    ['group_name' => $group]
                );

                $superAdminRole->givePermissionTo($permission);

                if (in_array($permissionName, $userPermission)) {
                    $userRole->givePermissionTo($permission);
                }
            }
        }
    }
}
