<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Grades;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AllSeeder extends Seeder
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
            'customer' => true,
        ]);

        // Assign roles
        $super_admin->roles()->attach(Role::where('name', 'super_admin')->first());
        $admin->roles()->attach(Role::where('name', 'admin')->first());
        $customer->roles()->attach(Role::where('name', 'customer')->first());

        $suppliers = [
            [
                // 'kode_batch' => 'NSR',
                'kode_supplier' => 'NSR',
                'nama_supplier' => 'Nasrullah',
                'phone' => '08123456789',
                'provinsi' => 'Sulawesi Selatan',
                'kabupaten' => 'Gowa',
                'kecamatan' => 'Sungguminasa',
                'jalan' => 'Jl. Poros Sungguminasa',
            ],
            [
                // 'kode_batch' => 'MRG',
                'kode_supplier' => 'MRG',
                'nama_supplier' => 'Martgurejo',
                'phone' => '08123456789',
                'provinsi' => 'Sulawesi Selatan',
                'kabupaten' => 'Bone',
                'kecamatan' => 'Libureng',
                'jalan' => 'Jl. Poros Libureng',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }


        $grades = [
            [
                'Grade' => 'A1',
                'Description' => 'The best grade',
            ],
            [
                'Grade' => 'A2',
                'Description' => 'The second best grade',
            ],
            [
                'Grade' => 'B1',
                'Description' => 'The third best grade',
            ],
        ];

        foreach ($grades as $grade) {
            Grades::create($grade);
        }


        $product = [
            [
                'nama' => 'Slice',
                'Kode' => 'SLC',
                'berat' => 2.5,
                'harga' => 10000,
                'customer_group' => 'USA',
            ],
            [
                'nama' => 'Block',
                'Kode' => 'BLK',
                'berat' => 5,
                'harga' => 10000,
                'customer_group' => 'USA',
            ],
            [
                'nama' => 'Cube',
                'Kode' => 'CUB',
                'berat' => 1,
                'harga' => 10000,
                'customer_group' => 'USA',
            ],
            [
                'nama' => 'Loin Fresh',
                'Kode' => 'Loin',
                'berat' => 4,
                'harga' => 10000,
                'customer_group' => 'USA',
            ],
        ];

        foreach ($product as $product) {
            Product::create($product);
        }
    }
}
