<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Profesor;
use App\Model\Persona;
use Faker\Generator as Faker;

$factory->define(Profesor::class, function (Faker $faker) {

	$persona = DB::table('personas')->pluck('id')->toArray();

    return [
    	'persona_id' => $faker->randomElement($persona),
    ];
});
