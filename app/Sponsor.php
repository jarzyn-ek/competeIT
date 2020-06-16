<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'image_path',
    ];

    public function competitions()
    {
        return $this->belongsToMany(\App\Competition::class);
    }
}
