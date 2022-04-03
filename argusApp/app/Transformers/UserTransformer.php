<?php

namespace App\Transformers;

class UserTransformer extends Transformer
{
    /**
     * Transform an item
     *
     * @param $user
     * @return mixed
     */
    public function transform($user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'hasAvatar' => $user->has_avatar,
            'thumbnail' => $user->thumbnail,
            'location' => $user->location,
            'phone' => $user->phone,
        ];
    }
}
