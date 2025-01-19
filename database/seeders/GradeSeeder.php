<?php

namespace Database\Seeders;

use App\Models\Grades;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Grades::create([
            'Grade' => 'A1',
            'Description' => 'The best grade'
        ]);

        Grades::create([
            'Grade' => 'A2',
            'Description' => 'The second best grade'
        ]);

        Grades::create([
            'Grade' => 'B1',
            'Description' => 'The third best grade'
        ]);
    }
}
