<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Asignatura extends Model
{
	public $table = "asignaturas";
    
  	protected $fillable = [
  		"disponibilidad", 
      "nombre",
  		"tipo_asignatura_id",
  	];

  	public function secciones(){  
    	return $this->hasMany(Seccion::class); // Muchos a muchos
	  }

    public function tipo_asignatura(){
          return $this->belongsTo(TipoAsignatura::class);
    }
}
