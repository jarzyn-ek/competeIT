<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','competitors_per_game',
    ];

    // public function competitions()
    // {
    //     return $this->belongsTo(\App\Competition::class);
    // }
}
