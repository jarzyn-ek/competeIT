<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Competition;
use Faker\Generator as Faker;

$factory->define(Competition::class, function (Faker $faker) {
    return [
        'name' => $faker->word
    ];
});
