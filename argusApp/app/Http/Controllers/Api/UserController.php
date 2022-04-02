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

        if ($request->has('upload_avatar')) {
            $avatarName = Storage::putFile('public/thumbnail', $request->avatar);

            $user->thumbnail = $avatarName;
            $user->save();
        }

        if ($request->has('old_password')) {
            $user->password = Hash::make($request->new_password);
            $user->save();
        }

        if ($request->has('new_name')) {
            $user->name = $request->new_name;
            $user->save();
        }

        if ($request->has('new_email')) {
            $user->email = $request->new_email;
            $user->save();
        }

        $user->fresh();

        return response()->json($userTransformer->transform($user));
    }
}
