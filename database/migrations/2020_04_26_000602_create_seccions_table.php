<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeccionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_asignaturas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->timestamps();
        });

        Schema::create('asignaturas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->boolean('disponibilidad')->default(1);

            $table->unsignedBigInteger('tipo_asignatura_id');
            $table->foreign('tipo_asignatura_id')->references('id')->on('tipo_asignaturas');

            $table->timestamps();
        });

        Schema::create('carreras', function (Blueprint $table) {
            $table->Bigincrements('id');
            $table->String('nombre',40)->unique();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('seccions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('alias',65);
            $table->string('lapso',65);
            $table->integer('matricula');
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_fin')->nullable();

            $table->integer('estado') ->default(1);

            $table->unsignedBigInteger('asignatura_id');
            $table->unsignedBigInteger('profesor_id'); // Relación con asignaturas

            $table->foreign('asignatura_id')->references('id')->on('asignaturas');
            $table->foreign('profesor_id')->references('id')->on('profesors');//

            //claves foraneas
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
        Schema::dropIfExists('asignaturas');
        Schema::dropIfExists('carreras');
        Schema::dropIfExists('tipo_asignaturas');
        Schema::dropIfExists('secciones');
    }
}