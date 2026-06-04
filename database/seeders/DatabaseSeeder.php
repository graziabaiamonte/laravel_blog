<?php

namespace Database\Seeders;

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
      // ogni seeder dipende da quelli sopra di lui
      $this->call([
        UserSeeder::class,
        RolesPermissionsSeeder::class,
        CategorySeeder::class,
        TagSeeder::class,
        ArticleSeeder::class,
      ]);
    }
}
