<?php

use Illuminate\Database\Seeder;
use App\Sensor;
use Illuminate\Database\Eloquent\Model;

class SensorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Sensor::class, 1000)->create();
    }
}
