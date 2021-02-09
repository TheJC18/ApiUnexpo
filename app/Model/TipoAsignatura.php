<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TipoAsignatura extends Model
{
    public $table = "tipo_asignaturas";

   	protected $fillable = [
    	"nombre",
    ];

    public function asignaturas(){
        return $this->hasMany(Asignatura::class);
    }
}
