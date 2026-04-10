<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Helpers\IUserRole;
use App\Helpers\IUserPermission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        $user = User::create($this->_seedUserUsers());
        $user->assignRole(IUserRole::ADMIN);
        $user->syncPermissions(Permission::all());
    }

    /**
     * Seed Admin Users
     *
     * @return array
     */
    private function _seedAdminUsers(): array
    {
        $password = Hash::make('123456789');
        $data = [

                'name'              => 'Shahroz',
                'email'             => 'shahrozzahid75@gmail.com',
                'password'          => $password,
                'created_at'        => now(),
                'updated_at'        => now(),
                'email_verified_at' => now()

        ];
        return $data;
    }

    /**
     * Seed User Users
     *
     * @return void
     * @return void
     */
    private function _seedUserUsers() : array
    {
        return $this->_seedAdminUsers();
    }
}
