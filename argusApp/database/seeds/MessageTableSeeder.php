<?php

use Illuminate\Database\Seeder;
use App\Message;
use Illuminate\Database\Eloquent\Model;

class MessageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $message = new Message();
        $message->id = 1;
        $message->user_id = 1;
        $message->type = 'gas';
        $message->generated_at = strtotime("23-04-2015");
        $message->message = "warning: currently detecting high levels of carbon monoxide, please move plant.";
        $message->save();

        $message = new Message();
        $message->id = 2;
        $message->user_id = 1;
        $message->type = 'temp';
        $message->generated_at = strtotime("23-04-2019");
        $message->message = "plant is currently too hot, please move to a cooler area.";
        $message->save();

        $message = new Message();
        $message->id = 3;
        $message->user_id = 1;
        $message->type = 'light';
        $message->generated_at = null;
        $message->message = null;
        $message->save();

        //use this one for testing purposes, it won't update the generated_at time because
        //despite the fact that the message is still true, the message generated is the same as the current one.
        //this means we can see how long this message has been shown for.
        $message = new Message();
        $message->id = 4;
        $message->user_id = 1;
        $message->type = 'moisture';
        $message->generated_at = null;
        $message->message = null;
        // $message->generated_at = strtotime("22-03-2022");
        // $message->message = "plant is currently too dry, please water.";
        $message->save();

        $message = new Message();
        $message->id = 5;
        $message->user_id = 2;
        $message->type = 'gas';
        $message->generated_at = null;
        $message->message = null;
        $message->save();

        $message = new Message();
        $message->id = 6;
        $message->user_id = 2;
        $message->type = 'temp';
        $message->generated_at = null;
        $message->message = null;
        $message->save();

        $message = new Message();
        $message->id = 7;
        $message->user_id = 2;
        $message->type = 'light';
        $message->generated_at = strtotime("23-04-2021");
        $message->message = "plant is currently getting too much direct light, please move plant into a shadier area.";
        $message->save();

        $message = new Message();
        $message->id = 8;
        $message->user_id = 2;
        $message->type = 'moisture';
        $message->generated_at = strtotime("23-04-2020");
        $message->message = "plant is currently too dry, please water.";
        $message->save();
    }
}
