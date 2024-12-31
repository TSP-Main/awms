<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //create roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $dealer = Role::firstOrCreate(['name' => 'dealer']);
    }
}
