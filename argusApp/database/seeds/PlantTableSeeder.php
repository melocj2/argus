<?php

use Illuminate\Database\Seeder;
use App\Plant;
use Illuminate\Database\Eloquent\Model;

class PlantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plant = new Plant();
        $plant->id = 1;
        $plant->user_id = 1;
        $plant->name = 'sage';
        $plant->type = 'snake plant';
        $plant->image = '/images/snakePlant.jpg';
        $plant->date_planted = strtotime("23-04-2020");
        $plant->location = 'living room';
        $plant->save();

        $plant = new Plant();
        $plant->id = 2;
        $plant->user_id = 1;
        $plant->name = 'rowan';
        $plant->type = 'croton';
        $plant->image = '/images/croton.jpg';
        $plant->date_planted = strtotime("23-01-2022");
        $plant->location = 'master bedroom';
        $plant->save();

        $plant = new Plant();
        $plant->id = 3;
        $plant->user_id = 2;
        $plant->name = 'aspen';
        $plant->type = 'pothos';
        $plant->image = '/images/pothos.jpg';
        $plant->date_planted = strtotime("23-04-2021");
        $plant->location = 'dining room';
        $plant->save();

        $plant = new Plant();
        $plant->id = 4;
        $plant->user_id = 2;
        $plant->name = 'al';
        $plant->type = 'aloe vera';
        $plant->image = '/images/aloeVera.jpg';
        $plant->date_planted = strtotime("01-01-2022");
        $plant->location = 'kitchen';
        $plant->save();
    }
}
