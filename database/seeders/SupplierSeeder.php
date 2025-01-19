<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Supplier::create([
            'kode_batch' => 'NSR',
            'kode_supplier' => '001',
            'nama_supplier' => 'Nasrullah',
            'phone' => '08123456789',
            'provinsi' => 'Sulawesi Selatan',
            'kabupaten' => 'Gowa',
            'kecamatan' => 'Sungguminasa',
            'jalan' => 'Jl. Poros Sungguminasa'
        ]);

        Supplier::create([
            'kode_batch' => 'MRG',
            'kode_supplier' => '002',
            'nama_supplier' => 'Martgurejo',
            'phone' => '08123456789',
            'provinsi' => 'Sulawesi Selatan',
            'kabupaten' => 'Bone',
            'kecamatan' => 'Libureng',
            'jalan' => 'Jl. Poros Libureng'
        ]);
    }
}
