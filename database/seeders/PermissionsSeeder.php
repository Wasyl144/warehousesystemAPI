<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            "auth.register",
            "user.show",
            "user.index",
            "user.update",
            "user.destroy",
            "permission.index",
            "role.destroy",
            "role.index",
            "role.show",
            "role.store",
            "role.update",
            "permissions.update",
            "permissions.edit",
            "items.store",
            "items.destroy",
            "items.update",
            "items.index",
            "items.show",
            "categories.destroy",
            "categories.show",
            "categories.update",
            "categories.store",
            "categories.index",
            "user.store",
        ];

        foreach ($permissions as $permission) {
            Permission::create([
               'name' => $permission,
               'guard_name' => 'web',
            ]);
        }

        $role = Role::create([
           'name' => 'Administrator',
            'guard_name' => 'web',
        ]);

    }
}
