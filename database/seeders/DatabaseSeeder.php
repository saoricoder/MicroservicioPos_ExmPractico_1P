<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      // Crear usuarios primero para asegurar que los posts referencien usuarios válidos
      User::factory(10)->create();

      // Usuario conocido para pruebas locales (opcional)
      User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
      ]);

      // Crear posts (usar user_id entre los usuarios creados)
      Post::factory(58)->create();
    }
}
