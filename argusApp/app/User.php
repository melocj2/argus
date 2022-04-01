<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'thumbnail', 'phone', 'location'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * return whether user has an avatar set
     *
     * @return Boolean
     */
    public function getHasAvatarAttribute()
    {
        return ! empty($this->thumbnail);
    }

    /**
     * get avatar URI from Storage Class
     *
     * @return String|null
     */
    public function getAvatarUriAttribute()
    {
        return $this->hasAvatar
            ? Storage::url($this->thumbnail)
            : null;
    }
}
