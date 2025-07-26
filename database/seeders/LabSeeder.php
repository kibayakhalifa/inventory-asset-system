<?php

namespace Database\Seeders;

use App\Models\Lab;
use Illuminate\Database\Seeder;

class LabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $labs = [
            [
                'name' => 'Computer Lab',
                'location' => 'Block A, Room 101',
                'description' => 'Lab for computer science and IT practicals.',
            ],
            [
                'name' => 'Physics Lab',
                'location' => 'Block B, Room 202',
                'description' => 'Lab for physics experiments and electronics.',
            ],
            [
                'name' => 'Chemistry Lab',
                'location' => 'Block C, Room 303',
                'description' => 'Lab for chemistry practicals and analysis.',
            ],
            [
                'name' => 'Biology Lab',
                'location' => 'Block D, Room 404',
                'description' => 'Lab for biology dissections and microscopy.',
            ],
        ];

        foreach ($labs as $lab) {
            Lab::create($lab);
        }
    }
}

