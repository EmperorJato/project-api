<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         //Super Admin Account
         $user = User::create([
            "name" => "Admin",
            "email" => "admin@gmail.com",
            "password" => Hash::make("password"),
        ]);

        $adminRole = Role::admin()->first();

        $user->roles()->attach($adminRole->id);
    }
}
