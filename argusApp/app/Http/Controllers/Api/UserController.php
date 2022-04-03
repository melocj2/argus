<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Update User
     *
     * @param Request $request
     * @param UserTransformer $userTransformer
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(
        Request $request,
        UserTransformer $userTransformer,
        $id
    ) {
        $user = User::find($id);

        if ($request->thumbnail) {
            $imageName = Storage::putFile('public/thumbnail', $request->thumbnail);
            $user->thumbnail = Storage::url($imageName);
        }

        //this should be checking to see if new_password matches password_confirmation
        //AND it should then check if the old_password matches the current password before updating

        if ($request->old_password) {
            $tester = Hash::make($request->old_password);
            if (($tester === $user->password) && ($request->new_password === $request->new_password_confirm)) {
                $user->password = Hash::make($request->new_password);
            }
        }

        if ($request->new_name) {
            $user->name = $request->new_name;
        }

        if ($request->new_email) {
            $user->email = $request->new_email;
        }

        if ($request->new_location) {
            $user->location = $request->new_location;
        }

        if ($request->new_phone) {
            $user->email = $request->new_phone;
        }

        $user->save();

        $user->fresh();

        return response()->json($userTransformer->transform($user));
    }
}
