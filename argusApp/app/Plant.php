<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{

    protected $table = 'plants';

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
        'name',
        'image',
        'type',
        'date_planted',
        'location'
    ];
}
