<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{


    protected $table = 'sensors';

    public $primaryKey = 'id';

    public $timestamps = true;

    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'moisture',
        'light',
        'temp',
        'gas'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'recorded_at' => 'datetime',
    ];
}
