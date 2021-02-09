<?php

namespace App\Model;

use Illuminate\Database\Eloquent\softDeletes;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
  use SoftDeletes;

	public $table = "notas";
    
  protected $fillable = [
    "corte", 
    "nota", 
    "evaluacion", 
    "seccion_id", 
    "alumno_id",
    "deleted_at"
  ];

  public function seccion(){
    return $this->belongsTo(Seccion::class);
  }

  public function alumno(){
    return $this->belongsTo(Alumno::class);
  }

}
