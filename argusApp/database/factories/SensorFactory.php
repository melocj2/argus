<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Sensor;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(Sensor::class, function (Faker $faker) {


    $today = Carbon::now()->timestamp;

    return [
        'moisture' => random_int(70, 99),
        'light'=> random_int(50, 99),
        'temp' => random_int(15, 30), //for ease of use, I have pre-converted the fake data to already be in degrees celcius
        'gas' => random_int(35, 55),
        'type' => 1,
        'recorded_at' => date("Y-m-d H:i:s", mt_rand(strtotime('today - 30 days'), $today))
    ];
});
