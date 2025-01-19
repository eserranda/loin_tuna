<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $super_admin = User::create([
            'name' => 'Super Admin',
            'username' => 'super_admin',
            'email' => 'super_admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $admin = User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $customer = User::create([
            'name' => 'Customer 1',
            'username' => 'customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
        ]);

        // $super_admin->roles()->attach($super_admin_role);
        // $admin->roles()->attach($admin_role);
        // $customer->roles()->attach($customer_role);
        // Assign roles
        $super_admin->roles()->attach(Role::where('name', 'super_admin')->first());
        $admin->roles()->attach(Role::where('name', 'admin')->first());
        $customer->roles()->attach(Role::where('name', 'customer')->first());
    }
}
