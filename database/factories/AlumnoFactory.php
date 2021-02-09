<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Alumno;
use App\Model\Persona;
use Faker\Generator as Faker;

$factory->define(Alumno::class, function (Faker $faker) {

	$persona = DB::table('personas')->pluck('id')->toArray();
	$carrera = DB::table('carreras')->pluck('id')->toArray();

    return [

    	'persona_id' => $faker->randomElement($persona),
    	'carrera_id' => $faker->randomElement($carrera),

    ];
});
