<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AreaSeeder::class);
        $this->call(GenreSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(RestaurantSeeder::class);
<<<<<<< HEAD
    }
}
=======
}
>>>>>>> 05f4165d1c1be9028597313d63f6a2f82b749930
