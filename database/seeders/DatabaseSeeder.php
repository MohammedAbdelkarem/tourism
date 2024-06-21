<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admin;
use App\Models\AvailableGuide;
use App\Models\Day;
use App\Models\FacilityDay;
use App\Models\Trip;
use App\Models\Guide;
use App\Models\Country;
use App\Models\Facility;
use App\Models\FacilityComment;
use App\Models\FacilityDayNotes;
use App\Models\FacilityInDay;
use App\Models\FacilityRate;
use App\Models\Favourite;
use App\Models\Guides_backups;
use App\Models\GuideTransaction;
use App\Models\Photo;
use App\Models\Reservatoin;
use App\Models\TripComment;
use App\Models\User;
use App\Models\UsersBackup;
use App\Models\UserTransaction;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Country::factory(10)->create();

        Day::factory(10)->create();

        Facility::factory(10)->create();

        Guides_backups::factory(10)->create();

        Guide::factory(10)->create();

        User::factory(10)->create();

        UsersBackup::factory(10)->create();

        Admin::factory(10)->create();

        Trip::factory(10)->create();

        FacilityDay::factory(10)->create();

        FacilityInDay::factory(10)->create();

        FacilityDayNotes::factory(10)->create();

        AvailableGuide::factory(10)->create();

        Reservatoin::factory(10)->create();

        Photo::factory(10)->create();

        Favourite::factory(10)->create();

        FacilityRate::factory(10)->create();

        TripComment::factory(10)->create();

        UserTransaction::factory(10)->create();

        GuideTransaction::factory(10)->create();

        $this->call([
            // AdminSeeder::class
        ]);
    }
}
