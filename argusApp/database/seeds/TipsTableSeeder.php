<?php

use Illuminate\Database\Seeder;
use App\Tips;
use Illuminate\Database\Eloquent\Model;

class TipsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tips = new Tips();
        $tips->id = 1;
        $tips->protip = 'Prevent overwatering by touching the soil. If it feels damp, it does not need to be watered right away.';
        $tips->save();

        $tips = new Tips();
        $tips->id = 2;
        $tips->protip = 'If some leaves on your plant seem dry or discolored, consider giving a bit more water than usual.';
        $tips->save();

        $tips = new Tips();
        $tips->id = 3;
        $tips->protip = 'To find out if it is time to start repotting your plants, always check their roots first.';
        $tips->save();

        $tips = new Tips();
        $tips->id = 4;
        $tips->protip = 'Positioning is everything when it comes to indoor plants! Find a spot with the right amount of light.';
        $tips->save();

        $tips = new Tips();
        $tips->id = 5;
        $tips->protip = 'Make sure to prune your plants and get rid of old-growth. This will help your plants grow again!';
        $tips->save();

        $tips = new Tips();
        $tips->id = 6;
        $tips->protip = 'When potting plants for the first time, make sure the soil is moist so the roots can begin working.';
        $tips->save();

        $tips = new Tips();
        $tips->id = 7;
        $tips->protip = 'Ensure the roots of your plant are receiving enough air by placing them in pots with drainage holes.';
        $tips->save();

        $tips = new Tips();
        $tips->id = 8;
        $tips->protip = 'Dust your plants! If too much dust collects on the leaves, they may not get enough sunlight.';
        $tips->save();

        $tips = new Tips();
        $tips->id = 9;
        $tips->protip = 'Keep in mind that for many plants, higher humidity levels are crucial in helping them thrive.';
        $tips->save();

        $tips = new Tips();
        $tips->id = 10;
        $tips->protip = 'Try and keep temperatures stable as major changes to the environment can stress plants out.';
        $tips->save();

        $tips = new Tips();
        $tips->id = 11;
        $tips->protip = 'When repotting, consider choosing a planter that is only 1 - 3 inches larger than its current container.';
        $tips->save();

        $tips = new Tips();
        $tips->id = 12;
        $tips->protip = 'Fertilizer is always helpful, but using too much of it can do your plant more harm than good!';
        $tips->save();
    }
}