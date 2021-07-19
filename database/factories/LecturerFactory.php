<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Lecturer;
use Faker\Generator as Faker;

$factory->define(Lecturer::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'address' => $faker->address,
        'phone' => $faker->name,
        'specialized' => rand(0,1),
    ];
});
