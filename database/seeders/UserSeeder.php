<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //create roles using spatie
        $adminRole = Role::firstOrCreate(['name'=>'admin']);
        $staffRole = Role::firstOrCreate(['name'=> 'staff']);

        //create users and assign roles
        User::factory(5)->create()->each(function($user) use ($staffRole){
            $user->assignRole($staffRole);
        });
        
        // Create 1 super admin that is me
        $admin = User::factory()->create([
            'name'=> 'super admin',
            'email'=> 'superadmin@test.com',
        ]);
        $admin->assignRole($adminRole);
    }
}
