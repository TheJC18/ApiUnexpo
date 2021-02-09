<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Persona;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Persona::class, function (Faker $faker) {

    return [
        'dni' => $faker->unique()->randomNumber($nbDigits = 8, $strict = false),
        'nombre' => $faker->name, 
        'segundo_nombre' => $faker->firstName, 
        'apellido' => $faker->lastname, 
        'segundo_apellido' => $faker->lastname, 
    ];
});
