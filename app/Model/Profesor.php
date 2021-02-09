<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;

class Profesor extends Model
{
    use SoftDeletes;
    
    public $table = "profesors";

    protected $fillable = [
        "persona_id",
        "deleted_at"
    ];

    public function secciones(){  
    	return $this->hasMany(Seccion::class); 
	}

    public function persona(){
        return $this->belongsTo(Persona::class);
    }


}

