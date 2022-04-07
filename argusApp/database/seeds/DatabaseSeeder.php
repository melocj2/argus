<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([SensorTableSeeder::class, PlantTableSeeder::class, UserTableSeeder::class, MessageTableSeeder::class, TipsTableSeeder::class]);
    }
}
