<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('corte');
            $table->string('nota');
            $table->string('evaluacion');

            $table->unsignedBigInteger('seccion_id'); // Relación con seccions
            $table->foreign('seccion_id')->references('id')->on('seccions'); // clave foranea
            
            $table->bigInteger('alumno_id')->unsigned(); // Relación con alumnos
            $table->foreign('alumno_id')->references('id')->on('alumnos'); // clave foranea
            
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
        Schema::dropIfExists('notas');
    }
}
