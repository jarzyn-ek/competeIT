<?php 
use \App\Http\Controllers\CompetitionController; 
use Carbon\Carbon;
?>

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>
                    </h3>
                </div>
                <div class="card-body">
                    <div class="tournament">
                        @foreach ($phases_by_stages as $phases)
                            <div class="tournament-box">
                                @foreach ($phases as $phase)
                                    <div class="tournament-phase">
                                        @foreach ($phase->phase_competition_users as $phase_competitor)
                                            <div class="tournament-competitor">
                                                {{ $phase_competitor->competition_user->user->name }}
                                                @if (Auth::id() == $phase_competitor->competition_user->user->id && $phase_competitor->place == null)
                                                    <?php //dd($phase->phase_competition_users); ?>
                                                    <span data-id="{{ $phase_competitor->id }}" data-launch="modal">
                                                        <a class="btn btn-primary tournament-results" data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="{{__('Insert your score.')}}" type="button">...</a>
                                                    </span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
<div class="modal fade" id="resultsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('place', ['competition' => $competition->id]) }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{__('Sign in')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    @csrf

                    <input type="hidden" name="record_id" id="record_id" value="">

                    <div class="form-group">
                        <select id="place" class="form-control @error('place') is-invalid @enderror"
                        name="place" required>
                            @for ($i = 1; $i <= $per_game; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                    <button type="submit" class="btn btn-primary">{{__('Save')}}</button>
                </div>
            </div>
        </form>
    </div>
</div>
