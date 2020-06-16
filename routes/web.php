<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/competitions', function () {
    return view('competitions');
});

Auth::routes(['verify' => true]);

Route::middleware(['verified'])->group(function() {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/competitions', 'CompetitionController@index')->name('competitions_index');
    Route::get('/competitions/mine', 'CompetitionController@my_index')->name('competitions_my_index');
    Route::get('/competitions/create', 'CompetitionController@create')->name('competitions_create');
    Route::get('/competitions/{competition}/edit', 'CompetitionController@edit')->name('competitions_edit');
    Route::post('/competitions', 'CompetitionController@store')->name('competitions_store');
    Route::get('/competitions/check_competition_time', 'CompetitionController@check_competition_time')->name('competitions_check_competition_time');
    Route::put('/competitions/{competition}', 'CompetitionController@update')->name('competitions_update');
    Route::get('/competitions/{id}', 'CompetitionController@show')->name('competitions_show');
    Route::post('/competitions/{id}/signin', 'CompetitionController@sign_in')->name('competitions_sign_in');
    Route::get('/competitions/{competition}/tournament', 'TournamentController@show')->name('tournaments_show');
    Route::post('/competitions/{competition}/tournament/place', 'TournamentController@place')->name('place');
});
