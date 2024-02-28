<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      $faker = \Faker\Factory::create();
      
      $superAdmin = UserRole::factory()->create([
        'role' => 'super-admin',
      ]);

      $admin = UserRole::factory()->create([
        'role' => 'admin',
      ]);

      $editor = UserRole::factory()->create([
        'role' => 'editor',
      ]);
      
      User::factory()->create([
        'role_id' => $superAdmin->id,
        'name' => 'CheffJeff',
        'email' => 'jeffrey@cheffjeff.nl',
        'password' => Hash::make("adminadmin", ['rounds' => 12]),
      ]);
      
      User::factory()->create([
        'role_id' => $admin->id,
        'name' => $faker->firstName(),
        'email' => $faker->unique()->safeEmail(),
        'password' => Hash::make("adminadmin", ['rounds' => 12]),
      ]);

      User::factory()->create([
        'role_id' => $editor->id,
        'name' => $faker->firstName(),
        'email' => $faker->unique()->safeEmail(),
        'password' => Hash::make("adminadmin", ['rounds' => 12]),
      ]);
    }
}
