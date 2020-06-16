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
                        {{ $competition->name }}
                    </h3>
                    <div>
                        @if (CompetitionController::show_sign_in_button($competition))
                        <button type="button" class="btn btn-success btn-lg" data-toggle="modal"
                            data-target="#exampleModal">
                            {{ __('Sign in') }}
                        </button>
                        @else
                        <button type="button" class="btn btn-secondary btn-lg" data-toggle="tooltip"
                            data-placement="top"
                            title="{{__('Number of competitors exceeded limit or deadline has passed.')}}" disabled>
                            {{ __('Sign in') }}
                        </button>
                        @endif
                        @if ($competition->competition_time <= Carbon::now()) 
                            <a type="button" href="{{ route('tournaments_show', ['competition' => $competition]) }}"
                            class="btn btn-primary btn-lg" data-toggle="tooltip" data-placement="top">
                            {{ __('Tournament') }}
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <table class="table">
                                <tr>
                                    <th>
                                        {{__('Discipline')}}
                                    </th>
                                    <td>
                                        {{ $competition->discipline->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ __('Time')}}
                                    </th>
                                    <td>
                                        {{ $competition->competition_time }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ __('Deadline')}}
                                    </th>
                                    <td>
                                        {{$competition->deadline}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{__('Participants limit')}}
                                    </th>
                                    <td>
                                        {{$competition->limit}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ __('Organizer')}}
                                    </th>
                                    <td>
                                        {{$competition->user->name}}
                                        {{$competition->user->surname}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ __('Competitors')}}
                                    </th>
                                    <td>
                                        @foreach ($competition->users as $user)
                                        {{$user->name}}
                                        {{$user->surname}}
                                        <br>
                                        @endforeach
                                    </td>
                                </tr>
                            </table>
                            <div class="form-group">
                                <label class="col-form-label text-md-right">{{ __('Localization') }}</label>
                                <div class="embed-responsive embed-responsive-16by9">
                                    <div class="embed-responsive-item" id="map" data-lat="{{$competition->latitude}}"
                                        data-lng="{{$competition->longitude}}">
                                    </div>

                                    <input type="hidden" id="latitude" name="latitude">
                                    <input type="hidden" id="longitude" name="longitude">
                                </div>
                            </div>
                            <label class="col-form-label text-md-right">{{ __('Sponsors') }}</label>
                            <div class="d-flex sponsors">
                                @foreach ($competition->sponsors as $sponsor)
                                <img src="{{$sponsor->image_path}}" />
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('competitions_sign_in', ['id'=>$competition->id]) }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{__('Sign in')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    @csrf

                    <div class="form-group">
                        <input type="text" class="form-control" name="license_number"
                            placeholder="{{__('License number')}}" />
                    </div>

                    <div class="form-group">
                        <input type="number" class="form-control" name="ranking_position"
                            placeholder="{{__('Ranking position')}}" />
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
<script>
    var map;

    function initMap() {
        if (!document.getElementById("map")) return;

        var $map = $('#map')

        var position = {
            lat: $map.data('lat'),
            lng: $map.data('lng')
        }


        map = new google.maps.Map(document.getElementById("map"), {
            center: position,
            zoom: 8
        });

        placeMarker(position, map);

        function placeMarker(position, map) {
            new google.maps.Marker({
                    position: position,
                    map: map
                });

            map.panTo(position);
        }
    }
</script>