<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\User;
use App\Models\Student;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Item::count() === 0 || User::count() === 0 || Student::count() === 0){
            $this->command->warn('Skipping TransactionSeeder: Items, Users, or Students not found.');
            return;
        }
        Transaction::factory(100)->create();
    }
}
