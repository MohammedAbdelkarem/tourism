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

        Day::factory()->create([
            'id' => 1,
            'name'=> 'Sunday'
       ] );
       Day::factory()->create([
        'id' => 2,
        'name'=> 'Monday'
         ] );
        Day::factory()->create([
            'id' => 3,
            'name'=> 'Tuesday'
        ] );
        Day::factory()->create([
            'id' => 4,
            'name'=> 'Wednesday'
        ] );
        Day::factory()->create([
            'id' => 5,
            'name'=> 'Thursday'
        ] );
        Day::factory()->create([
            'id' => 6,
            'name'=> 'Friday'
        ] );
        Day::factory()->create([
            'id' => 7,
            'name'=> 'Saturday'
        ] );

        Facility::factory(20)->create();

        Guides_backups::factory(20)->create();

        Guide::factory(20)->create();

        User::factory(10)->create();

        UsersBackup::factory(10)->create();

        // Admin::factory(10)->create();
        
       
        Admin::factory()->create([
        'name' => 'Admin_1' ,

        'email' => 'mayagritaabouasali@gmail.com',
        'password' => bcrypt('password1'),
        'role' => 'super_admin',
        // 'ratio' => 10,
        'wallet' => 0,
        ]);
        Admin::factory()->create([
            'name' => 'Admin_2',
            'email' => null,
            'password' => bcrypt('password2'),
            'role' => 'sub_admin',
            // 'ratio' => 10,
            'wallet' => 0,
        ]);

        Admin::factory()->create([
            'name' => 'Admin_3',
            'email' => null,
            'password' => bcrypt('password3'),
            'role' => 'sub_admin',
            // 'ratio' => 10,
            'wallet' => 0,
        ]);

        Admin::factory()->create([
            'name' => 'Admin_4',
            'email' => null,
            'password' => bcrypt('password4'),
            'role' => 'sub_admin',
            // 'ratio' => 10,
            'wallet' => 0,
        ]);
        Trip::factory(40)->create();

        FacilityDay::factory(20)->create();

        FacilityInDay::factory(20)->create();

        AvailableGuide::factory(20)->create();

        Reservatoin::factory(20)->create();

        Photo::factory(20)->create();

        Favourite::factory(20)->create();

        FacilityRate::factory(20)->create();

        TripComment::factory(20)->create();

        UserTransaction::factory(20)->create();

        GuideTransaction::factory(20)->create();

        $this->call([
                
        // AdminSeeder::class;
        ]);
    }
}
