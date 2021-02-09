<?php

namespace App\Model;

use Illuminate\Database\Eloquent\softDeletes;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    use SoftDeletes;

    public $table = "carreras";
    
  	protected $fillable = [
  		"nombre",
    	"deleted_at"
  	];

  	public function asignaturas(){  
    	return $this->hasMany(Seccion::class); // Muchos a muchos
	}

	public function alumnos(){  
    	return $this->hasMany(Alumno::class); // Muchos a muchos
	}
}
