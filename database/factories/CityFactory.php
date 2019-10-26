<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\City;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(City::class, function (Faker $faker) {
    $city_slug =  $faker->unique()->city;
    return [
        'name'          => $city_slug,
        'name_slug'    => Str::slug($city_slug,'-')
        //
    ];
});
