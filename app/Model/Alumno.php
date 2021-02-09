<?php

namespace App\Model;

use Illuminate\Database\Eloquent\softDeletes;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use SoftDeletes;
    
        public $table = "alumnos";
    	protected $fillable = [
    		"persona_id",
    		"carrera_id",
            "deleted_at",
    	];

    	public function persona(){
        	return $this->belongsTo(Persona::class);
    	}

    	public function carrera(){
        	return $this->belongsTo(Carrera::class);
    	}

        public function alumno_seccion(){
            return $this->hasMany(AlumnoSeccion::class);
        }

        public function alumnos(){  
            return $this->belongsToMany(Alumno::class); // 1 A 1. 
        }
}
