<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AlumnoSeccion extends Model
{
    public $table = "alumno_seccion";

    protected $fillable = [
  		"seccion_id",
    	"alumno_id"
  	];

  	public function seccion(){
        	return $this->belongsTo(Seccion::class);
   	}

   	public function alumno(){
        	return $this->belongsTo(Alumno::class);
    	}



}
