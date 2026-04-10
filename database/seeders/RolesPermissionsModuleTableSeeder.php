<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\VarDumper\VarDumper;

class RolesPermissionsModuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $config = config('roles-permission-module');

        $roles = $config['roles-set'];
        $permissions = $config['permissions-set'];

        foreach ($permissions as $permission => $value)
        {
            VarDumper::dump("Adding Permission $value :: $permission");
            Permission::findOrCreate($permission, 'web');
        }

        foreach ($roles as $role => $value)
        {
            VarDumper::dump("Adding Role $value :: $role");
            Role::findOrCreate($role, 'web');
        }
    }
}
