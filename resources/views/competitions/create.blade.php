@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="POST" action="{{ route('competitions_store') }}">
                <div class="card">
                    <div class="card-header">{{ __('Add') }}</div>

                    <div class="card-body">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

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
                                    name="discipline_id" required>
                                    @foreach ($disciplines as $discipline)
                                    <option value="{{ $discipline->id }}" {{ (collect(old("discipline_id"))->contains($discipline->id)) ? "selected" : "" }}>{{ $discipline->id }}: {{ $discipline->name }}
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
                                <input type="text" id="time" value="{{ old('competition_time')}}" name="competition_time"
                                    class="form-control datetimepicker" data-target="#datetimepicker" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="deadline"
                                class="col-md-4 col-form-label text-md-right">{{ __('Deadline') }}</label>

                            <div class="col-md-6">
                                <input type="text" id="deadline" name="deadline" value="{{ old('deadline')}}"
                                    class="form-control datetimepicker" data-target="#datetimepicker1" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="sponsors"
                                class="col-md-4 col-form-label text-md-right">{{ __('Sponsors') }}</label>

                            <div class="col-md-6">
                                <select id="sponsors" name="sponsors[]" multiple="multiple" class="form-control"
                                    autofocus multiple>
                                    @foreach ($sponsors as $sponsor)
                                    <option value="{{ $sponsor->id }}" {{ (collect(old("sponsors"))->contains($sponsor->id)) ? "selected" : "" }}>{{ $sponsor->id }}: {{ $sponsor->name }}
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
                                <input type="number" min="0" id="limit" value="{{ old('limit')}}" class="form-control" name="limit" />

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

                                </div>

                                <input type="hidden" id="latitude" value="{{ old('latitude')}}" name="latitude">
                                <input type="hidden" id="longitude" value="{{ old('longitude')}}" name="longitude">
                            </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="col-md-6 offset-md-3">
                            <button type="submit" class="btn btn-success btn-lg btn-block">
                                {{ __('Add') }}
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

        function initMap() {
            if (!document.getElementById("map")) return;

            map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: 51.9357328, lng: 16.8866025 },
                zoom: 8
            });

            map.addListener("click", function(e) {
                placeMarker(e.latLng, map);
            });

            function placeMarker(position, map) {
                if (!marker) {
                    marker = new google.maps.Marker({
                        position: position,
                        map: map
                    });
                } else {
                    marker.setPosition(position);
                }

                $("#latitude").val(position.lat());
                $("#longitude").val(position.lng());

                map.panTo(position);
            }
        }
    </script>