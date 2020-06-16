<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PhaseCompetitionUser extends Pivot
{
    public function phase() 
    {
        return $this->belongsTo(\App\Phase::class);
    }

    public function competition_user() 
    {
        return $this->belongsTo(\App\CompetitionUser::class);
    }
}