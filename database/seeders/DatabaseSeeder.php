<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LabSeeder::class,
            ItemSeeder::class,
            StudentSeeder::class,
            UserSeeder::class,
            TransactionSeeder::class,
            ItemStudentSeeder::class,
            LogsSeeder::class,
        ]);
    }
}
