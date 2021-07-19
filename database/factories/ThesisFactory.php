<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Thesis;
use Faker\Generator as Faker;

$factory->define(Thesis::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->paragraph,
        'attaches' => $faker->title(),
        'student_id' => rand(1,100),
        'lecturer_id' => rand(1,50),
    ];
});
