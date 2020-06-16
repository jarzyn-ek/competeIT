<?php

namespace App\Http\Controllers;

use App\Competition;
use App\Discipline;
use App\Phase;
use App\PhaseCompetitionUser;
use App\Sponsor;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{
    private $paginate = 10;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $competitions = Competition::paginate($this->paginate);

        if ($search = $request->input('search')) {
            $competitions = Competition::where('name', 'LIKE', "%" . $search . "%")->paginate($this->paginate);
        }

        return view('competitions.index', [
            'competitions' => $competitions
        ]);
    }

    public function my_index() 
    {
        $competitions = Competition::where(['user_id' => auth()->user()->id]);
        return view('competitions.index', [
            'competitions' => $competitions->paginate($this->paginate)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $disciplines = Discipline::all();
        $users = User::all();
        $sponsors = Sponsor::all();
        return view('competitions.create', compact('disciplines', 'users', 'sponsors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dump($request->input());
        // dd(auth()->user());

        $competition = new Competition($request->input());
        $competition->user_id = auth()->user()->id;
        // Competition::insert($competition);
        $competition->deadline = Carbon::parse($competition->deadline);
        $competition->competition_time = Carbon::parse($competition->competition_time);

        if ($competition->deadline > $competition->competition_time)
        {
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', __('Wrong data!'));
            return redirect()->route('competitions_create')->withInput();
        }

        $competition->save();

        $sponsors = Sponsor::find($request->input('sponsors'));
        $competition->sponsors()->attach($sponsors);

        $request->session()->flash('message.level', 'success');
        $request->session()->flash('message.content', __('Competition has been added!'));
        return redirect()->route('competitions_show', ['id' => $competition->id]);
        // dd($competition);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Competition  $competition
     * @return \Illuminate\Http\Response
     */
    public function show(Competition $competition, $id)
    {
        $competition = Competition::find($id);
        return view('competitions.show', [
            'competition' => $competition
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Competition  $competition
     * @return \Illuminate\Http\Response
     */
    public function edit(Competition $competition)
    {
        //dd($competition);
        $disciplines = Discipline::all();
        $sponsors = Sponsor::all();
        // dump($sponsors);
        // dd($competition->sponsors);
        return view('competitions.edit', [
            'competition' => $competition,
            'disciplines' => $disciplines,
            'sponsors' => $sponsors
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Competition  $competition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Competition $competition)
    {
        $competition = Competition::find($competition->id);
        $competition->update($request->input());
        $competition->deadline = Carbon::parse($competition->deadline);
        $competition->competition_time = Carbon::parse($competition->competition_time);

        if ($competition->deadline > $competition->competition_time)
        {
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', __('Wrong data!'));
            return redirect()->route('competitions_edit', ['id' => $competition->id]);
        }
        $sponsors = Sponsor::find($request->input('sponsors'));
        $competition->sponsors()->detach();
        $competition->sponsors()->attach($sponsors);
        $competition->save();

        $request->session()->flash('message.level', 'success');
        $request->session()->flash('message.content', __('Competition is updated!'));
        return redirect()->route('competitions_show', ['id' => $competition->id]);
        // $competition->name = $r
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Competition  $competition
     * @return \Illuminate\Http\Response
     */
    public function destroy(Competition $competition)
    {
        //
    }

    public function sign_in(Request $request, $id)
    {
        $competition = Competition::find($id);

        $user = auth()->user();

        try {
            $competition->users()->attach(
                $user->id,
                array(
                    'license_number' => $request->input('license_number'),
                    'ranking_position' => $request->input('ranking_position')
                )
            );
        } catch (\Illuminate\Database\QueryException $e) {
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', __('Wrong data'));
            return redirect()->route('competitions_show', ['id' => $competition->id]);
        }

        $request->session()->flash('message.level', 'success');
        $request->session()->flash('message.content', __('You\'ve been successfully added!'));
        return redirect()->route('competitions_show', ['id' => $competition->id]);
        //dd($request, $competition);
    }

    public function check_competition_time() {
        $competitions = Competition::whereDate(
            'competition_time', '<=', Carbon::now()
        )->doesntHave('phases')->get();

        foreach ($competitions as $competition) {
            $per_game = $competition->discipline->competitors_per_game;

            $groups = $competition->users->shuffle()->chunk($per_game)->all();

            foreach($groups as $group) {
                $phase = new Phase([
                    'competition_id' => $competition->id,
                    'stage' => 0,
                ]);
                
                $phase->save();

                foreach($group as $key => $competitor) {
                    $phase_competition_user = new PhaseCompetitionUser([
                        'phase_id' => $phase->id,
                        'competition_user_id' => $competitor->pivot->id,
                    ]);
                    $phase_competition_user->save();
                }
            }
        }
    }

    public static function show_sign_in_button(Competition $competition) {
        return (count($competition->users) < $competition->limit) && ($competition->competition_time >=  Carbon::now()) && ($competition->deadline >= Carbon::now());
    }
}
