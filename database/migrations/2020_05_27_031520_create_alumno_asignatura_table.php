<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlumnoAsignaturaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumno_asignatura', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('asignatura_id'); // Relación con asignaturas
            $table->foreign('asignatura_id')->references('id')->on('asignaturas'); // clave foranea

            $table->bigInteger('alumno_id')->unsigned(); // Relación con alumnos
            $table->foreign('alumno_id')->references('id')->on('alumnos'); // clave foranea

            $table->string('estado');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alumno_asignatura');
    }
}
