<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
