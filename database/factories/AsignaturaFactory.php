<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Asignatura;
use Faker\Generator as Faker;

$factory->define(Asignatura::class, function (Faker $faker) {
    $tipo = DB::table('tipo_asignaturas')->pluck('id')->toArray();
    return [
        'nombre' => $faker->name,        
        'disponibilidad' => 1,
        'tipo_asignatura_id' => $faker->randomElement($tipo),
    ];
});
