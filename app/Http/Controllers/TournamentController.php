<?php

namespace App\Http\Controllers;

use App\Competition;
use App\Phase;
use App\PhaseCompetitionUser;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show tournament
     * 
     * @param Competition $competition 
     * @return View|Factory 
     * @throws BindingResolutionException 
     */
    public function show(Competition $competition)
    {
        $per_game = $competition->discipline->competitors_per_game;
        $phases = $competition->phases;
        $phase_competition_users = $competition->phase_competition_users;
        $phases_by_stages = $phases->groupBy('stage');
        //dd($phases_by_stages);
        return view("tournaments.show", compact('competition', 'phases_by_stages', 'per_game'));
    }

    /**
     * Assign place to competitor
     * 
     * @param Request $request 
     * @param Competition $competition 
     * @return void 
     */
    public function place(Request $request, Competition $competition)
    {
        $phase_competition_user = PhaseCompetitionUser::find($request->input("record_id"));

        $phase = $phase_competition_user->phase;
        $phase_reseted = false;

        $phase->phase_competition_users->each(function ($them_phase_competition_user) use ($phase_competition_user, $phase, $competition, $request, &$phase_reseted) {
            if ($them_phase_competition_user != $phase_competition_user && $them_phase_competition_user->place == $request->input('place')) {
                $this->reset_phase($phase, $competition);
                $phase_reseted = true;
                return false;
            }
        });

        switch (true) {
            case $phase_reseted == true:
                $request->session()->flash('message.level', 'danger');
                $request->session()->flash('message.content', __('All places has been reseted due to conflict'));
                break;
            case $phase_competition_user != null:
                $phase_competition_user->place = $request->input("place");
                $phase_competition_user->save();
                $request->session()->flash('message.level', 'success');
                $request->session()->flash('message.content', __('Your place has been added!'));
                break;
            default:
                $request->session()->flash('message.level', 'warning');
                $request->session()->flash('message.content', __('Your place has been added earlier!'));
                break;
        }

        $this->check_phase($competition);
        
        return redirect()->route('tournaments_show', ['competition' => $competition]);
    }

    public function check_phase(Competition $competition) {
        $phases_by_stages = $competition->phases->groupBy('stage');
        $phases_in_last_stage = $phases_by_stages->last();
        $current_stage = $phases_in_last_stage[0]->stage;

        $stage_completed = true;
        foreach ($phases_in_last_stage as $phase) {
            foreach ($phase->phase_competition_users as $phase_competition_user) {
                if ($phase_competition_user->place == null) {
                    $stage_completed = false;
                    break;
                }
            }
            if (!$stage_completed) {
                break;
            }
        }

        if ($stage_completed) {
            $phase_groups = $phases_in_last_stage->chunk($competition->discipline->competitors_per_game);

            foreach ($phase_groups as $phase_group) {
                $phase = new Phase([
                    'competition_id' => $competition->id,
                    'stage' => $current_stage + 1,
                ]);
                
                $phase->save();

                foreach ($phase_group as $group) {
                    $best = $group->phase_competition_users->sortBy("place")->first();

                    $phase_competition_user = new PhaseCompetitionUser([
                        'phase_id' => $phase->id,
                        'competition_user_id' => $best->competition_user->id,
                    ]);

                    $phase_competition_user->save();
                }
            }
        }
    }

    public function reset_phase(Phase $phase)
    {
        $phase->phase_competition_users->each(function ($phase_competition_user) {
            $phase_competition_user->place = null;
            $phase_competition_user->save();
        });
    }
}
