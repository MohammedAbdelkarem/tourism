<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Country;
use App\Models\Facility;
use App\Models\Guides_backups;
use App\Models\Trip;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Country::factory(10)->create();
        // Facility::factory(10)->create();
        // Guides_backups::factory(10)->create();
        // Trip::factory(10)->create();
        $this->call([
            // AdminSeeder::class
        ]);
    }
}
