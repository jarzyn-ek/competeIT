<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CompetitionUser extends Pivot
{
    public function competition() 
    {
        return $this->belongsTo(\App\Competition::class);
    }

    public function user() 
    {
        return $this->belongsTo(\App\User::class);
    }
}