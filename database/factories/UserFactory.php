<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
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

$factory->define(User::class, function (Faker $faker) {
    return [
        'name'                  => $faker->userName,
        'email'                 => $faker->unique()->safeEmail,
        'email_verified_at'     => now(),
        'password'              => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token'        => Str::random(10),
        'birth_year'            => $faker->numberBetween(1950,2001),
        'description'           => $faker->text,
        'city_id'               => $faker->numberBetween(1,5),
        'hidden_status'         => $faker->numberBetween(0,2),
        'relationship_status'   =>$faker->numberBetween(0,1),
        'picture'               => 'default-picture.png'

    ];
});
