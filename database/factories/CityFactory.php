<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\City;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(City::class, function (Faker $faker) {
    $city_slug =  $faker->unique()->city;
    return [
        'name'          => $city_slug,
        'name_slug'    => Str::slug($city_slug,'-')
        //
    ];
});
