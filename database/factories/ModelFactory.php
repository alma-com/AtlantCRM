<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});


$factory->define(App\Project::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
    ];
});


$factory->define(App\Task::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->text($maxNbChars = 200),
        'description' => $faker->text($maxNbChars = 400),
        'date_start' => Carbon\Carbon::now(),
        'deadline' => Carbon\Carbon::now()->addWeeks(2),
        'planned_duration' => $faker->randomDigitNotNull(),
    ];
});

$factory->define(App\TaskReport::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->text($maxNbChars = 200),
        'comment' => $faker->text($maxNbChars = 400),
        'hours' => $faker->randomDigitNotNull(),
    ];
});
