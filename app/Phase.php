<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phase extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'competition_id','stage',
    ];

    public function competition_users()
    {
        return $this->hasManyThrough(\App\CompetitionUser::class, \App\PhaseCompetitionUser::class, 'phase_id', 'id', 'id', 'competition_user_id');
    }

    public function competition()
    {
        return $this->belongsTo(\App\Competition::class);
    }

    public function phase_competition_users() {
        return $this->hasMany(\App\PhaseCompetitionUser::class);
    }
}
