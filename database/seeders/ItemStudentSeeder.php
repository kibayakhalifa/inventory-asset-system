<?php

namespace Database\Seeders;

use App\Models\ItemStudent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //create 50 random item-student issue records
        ItemStudent::factory()->count(50)->create();
    }
}
