<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Database\Eloquent\Model;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->id = 1;
        $user->name = 'jake54';
        $user->email = 'jake@jake.com';
        $user->password = Hash::make('password');
        $user->thumbnail = '/images/jakeProfile.jpg';
        $user->save();

        $user = new User();
        $user->id = 2;
        $user->name = 'natasha';
        $user->email = 'natasha@natasha.com';
        $user->password = Hash::make('password');
        $user->thumbnail = '/images/defaultProfile.jpg';
        $user->save();
    }
}
