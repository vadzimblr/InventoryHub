<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        $superAdminName = getenv('ADMIN_NAME');
        $superAdminEmail = getenv('ADMIN_EMAIL');
        $superAdminPassword = getenv('ADMIN_PASSWORD');

        $user = User::create([
            'name' => $superAdminName,
            'email' => $superAdminEmail,
            'password' => Hash::make($superAdminPassword),
        ]);

        $role = Role::findByName('admin');
        $user->assignRole($role);

    }
}
