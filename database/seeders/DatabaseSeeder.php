<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Location::create([
            'city_name' => 'New York',
            'lon' => '-74.006',
            'lat' => '40.7143'
        ]);
        \App\Models\Location::create([
            'city_name' => 'London',
            'lon' => '-0.1257',
            'lat' => '51.5085'
        ]);
        \App\Models\Location::create([
            'city_name' => 'Paris',
            'lon' => '2.3488',
            'lat' => '48.8534'
        ]);
        \App\Models\Location::create([
            'city_name' => 'Berlin',
            'lon' => '13.4105',
            'lat' => '52.5244'
        ]);
        \App\Models\Location::create([
            'city_name' => 'Tokyo',
            'lon' => '139.6917',
            'lat' => '35.6895'
        ]);
    }
}
