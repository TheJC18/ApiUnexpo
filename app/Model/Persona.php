<?php

namespace App\Model;

use Illuminate\Database\Eloquent\softDeletes;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use SoftDeletes;

    public $table = "personas";
    
  	protected $fillable = [
  		"dni", 
  		"nombre", 
  		"segundo_nombre",
  		"apellido", 
      "segundo_apellido",
  		"deleted_at",
  	];

  	public function users(){  
    	return $this->hasOne(User::class); // Muchos a muchos
	  }

    public function profesors(){
        return $this->hasMany(Profesor::class);
    }

    public function alumnos(){
        return $this->hasMany(Alumno::class);
    }

}
