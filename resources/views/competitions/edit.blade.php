@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="POST" action="{{ route('competitions_update',['competition' => $competition]) }}">
                <div class="card">
                    <div class="card-header">{{ __('Edit') }}</div>

                    <div class="card-body">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ $competition->name }}" required autocomplete="name" autofocus>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="discipline"
                                class="col-md-4 col-form-label text-md-right">{{ __('Discipline') }}</label>

                            <div class="col-md-6">
                                <select id="discipline" class="form-control @error('discipline') is-invalid @enderror"
                                    name="discipline_id" required autocomplete="discipline" autofocus>
                                    @foreach ($disciplines as $discipline)
                                    <option value="{{ $discipline->id }}" @if($discipline==$competition->discipline)
                                        {{ 'selected' }} @endif>{{ $discipline->id }}: {{ $discipline->name }}
                                    </option>
                                    @endforeach
                                </select>

                                @error('discipline')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="time" class="col-md-4 col-form-label text-md-right">{{ __('Time') }}</label>

                            <div class="col-md-6">
                                    <input type="text" id="time" value="{{$competition->competition_time}}"
                                        name="competition_time" class="form-control datetimepicker"
                                        data-target="#datetimepicker"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="deadline"
                                class="col-md-4 col-form-label text-md-right">{{ __('Deadline') }}</label>

                            <div class="col-md-6">
                                <input type="text" id="deadline" value="{{$competition->deadline}}" name="deadline"
                                    class="form-control datetimepicker" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="sponsors"
                                class="col-md-4 col-form-label text-md-right">{{ __('Sponsors') }}</label>

                            <div class="col-md-6">
                                <select id="sponsors" name="sponsors[]" multiple="multiple" class="form-control"
                                    multiple>
                                    @foreach ($sponsors as $sponsor)
                                    <option value="{{ $sponsor->id }}" @if($competition->sponsors->contains($sponsor))
                                        {{ 'selected' }} @endif>{{ $sponsor->id }}: {{ $sponsor->name }}
                                    </option>
                                    @endforeach
                                </select>

                                @error('sponsors')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="limit" class="col-md-4 col-form-label text-md-right">{{ __('Limit') }}</label>

                            <div class="col-md-6">
                                <input type="number" min="{{$competition->limit}}" id="limit"
                                    value="{{$competition->limit}}" class="form-control" name="limit" />

                                @error('surname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">{{ __('Localization') }}</label>
                            <div class="embed-responsive embed-responsive-16by9">
                                <div class="embed-responsive-item" id="map">
                                    {{-- <script>
                                        initMap($competition->latitude,$competition->longitude);
                                    </script> --}}
                                </div>

                                <input type="hidden" value="{{$competition->latitude}}" id="latitude" name="latitude">
                                <input type="hidden" value="{{$competition->longitude}}" id="longitude"
                                    name="longitude">
                            </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="col-md-6 offset-md-3">
                            <button type="submit" class="btn btn-success btn-lg btn-block">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endsection

    <script>
        var map, marker;

        function initMap(lat, lng) {
            if (!document.getElementById("map")) return;

            var $map = $('#map')

            var center = {
                lat: parseFloat(document.getElementById("latitude").value),
                lng: parseFloat(document.getElementById("longitude").value)
            }


            map = new google.maps.Map(document.getElementById("map"), {
                center: center,
                zoom: 8
            });

            placeMarker(center, map);

            function placeMarker(position, map) {
                if (!marker) {
                    marker = new google.maps.Marker({
                        position: position,
                        map: map
                    });
                } else {
                    marker.setPosition(position);
                }

                $("#latitude").val(position.lat);
                $("#longitude").val(position.lng);
            }

            map.addListener("click", function(e) {
                placeMarker(e.latLng, map);
            });

        }
    </script>