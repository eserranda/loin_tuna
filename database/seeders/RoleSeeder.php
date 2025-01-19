<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['super_admin', 'admin', 'customer', 'setting', 'receiving', 'cutting', 'retouching', 'packing'];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }
    }
}
