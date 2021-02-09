<?php

namespace App\Model;

use Illuminate\Database\Eloquent\softDeletes;
use Illuminate\Database\Eloquent\Model;

class Seccion extends Model
{
  use SoftDeletes;

	public $table = "seccions";
    
  protected $fillable = [
    "alias", 
    "lapso",
    "matricula",
    "fecha_inicio",
    "fecha_fin",  
    "asignatura_id", 
    "profesor_id",
    "estado",
    "deleted_at"
  ];

  public function asignatura(){   
    return $this->belongsTo(Asignatura::class); // 1 A 1.	
  }

  public function profesor(){  
    return $this->belongsTo(Profesor::class); // 1 A 1.	
  }
 public function alumnos(){  
    return $this->belongsToMany(Alumno::class); // 1 A 1. 
  }

  public function notas(){  
    return $this->belongsToMany(Nota::class); // 1 A 1. 
  }

}
