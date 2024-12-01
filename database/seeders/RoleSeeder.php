<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['admin',
                  'client',
                  'accountant',
                  'account-manager',
                  'purchasing-manager',
                  'storekeeper'
            ];


        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

    }
}
