<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    protected $fillable = [
        'name', 'discipline_id', 'user_id', 'competition_time', 
        'latitude', 'longitude', 'limit', 'deadline',
    ];

    public function discipline() 
    {
        return $this->belongsTo(\App\Discipline::class);
    }

    public function user() 
    {
        return $this->belongsTo(\App\User::class);
    }

    public function sponsors() 
    {
        return $this->belongsToMany(\App\Sponsor::class);
    }

    public function users() 
    {
        return $this->belongsToMany(\App\User::class)->withPivot(['id', 'license_number', 'ranking_position']);
    }

    public function phases() 
    {
        return $this->hasMany(\App\Phase::class)->orderBy('stage', 'ASC');
    }

    public function phase_competition_users()
    {
        return $this->hasManyThrough(\App\PhaseCompetitionUser::class, \App\Phase::class);
    }
}
