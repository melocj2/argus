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
        'moisture',
        'light',
        'temp',
        'gas',
        'type',
        'recorded_at'
    ];

}
