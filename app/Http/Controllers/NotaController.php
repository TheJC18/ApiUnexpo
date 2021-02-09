<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Seccion;
use App\Model\AlumnoSeccion;
use App\Model\Nota;
use Validator, DB;


class NotaController extends Controller
{
    public function ver_nota(Request $request)
    {
      DB::beginTransaction();
      $secciones = Seccion::find($request->seccion_id);
      $valid;
 
      $input = $request->all();
      $validator = Validator::make($input, [
          'seccion_id' => 'required|numeric',
          'alumno_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
              'ok' => false, 
              'error' => $validator->messages()
            ]);
        }

      try{
		//json_decode

          $valid = AlumnoSeccion::select("alumno_seccion.*")
          ->where("seccion_id", $request->seccion_id)
          ->where("alumno_id", $request->alumno_id)
          ->first();

          if ($valid != false) {

          	   $nota = Nota::with(['alumno', 'alumno.persona','seccion', 'seccion.asignatura'])
      			    ->where("seccion_id", $request->seccion_id)
                		->where("alumno_id", $request->alumno_id)
      			    ->first();

          	if ($nota != false) {

          		$nota = Nota::with(['alumno', 'alumno.persona','seccion', 'seccion.asignatura'])
			    ->where("seccion_id", $request->seccion_id)
          		->where("alumno_id", $request->alumno_id)
			    ->get();

		          DB::commit();
		          return response()->json([
		            "ok" => true,
		            "data" => $nota
		          ]);

            }else 

            return response()->json([
	            "ok" => true,
		        "message" => "Este alumno no tiene notas cargadas en esta sección"
		    ]);

          }else 

          	DB::rollBack();
        	return response()->json([
                'ok' => true, 
                'error' => "Este alumno no pertenece a esta sección"
            ]);

        }catch(\Exception $ex){
          
          DB::rollBack();

          return response()->json([
              'ok' => false, 
              'error' => $ex->getMessage()
          ]);
        }
    }



    public function agregar_nota(Request $request)
    {
      DB::beginTransaction();
      $secciones = Seccion::find($request->seccion_id);
      $valid;
 
      $input = $request->all();
      $validator = Validator::make($input, [
          'corte' => 'required|numeric',
          'nota' => 'required|numeric',
          'evaluacion' => 'required|max:75',
          'seccion_id' => 'required|numeric',
          'alumno_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
              'ok' => false, 
              'error' => $validator->messages()
            ]);
        }

      try{
		//json_decode

          $valid = AlumnoSeccion::select("alumno_seccion.*")
          ->where("seccion_id", $request->seccion_id)
          ->where("alumno_id", $request->alumno_id)
          ->first();

          if ($valid != false) {
          	
          	if ($secciones != false) {

				    $nota = new Nota();
	            $nota->corte = $request->corte;
              $nota->nota = $request->nota;
	            $nota->evaluacion = $request->evaluacion;
	            $nota->seccion_id = $request->seccion_id;
	            $nota->alumno_id = $request->alumno_id;
	            $nota->save();

	            if ($nota != false) {
		          DB::commit();
		          return response()->json([
		            "ok" => true,
		            "message" => "Nota agregada exitosamente"
		          ]);
		        }

            }else 

            return response()->json([
                'ok' => true, 
                'error' => "Hubo un error al agrergar la nota, revise los campos que seleccionó"
            ]);

              	

          }else 

          	DB::rollBack();

        	return response()->json([
                'ok' => true, 
                'error' => "Este alumno no pertenece a esta sección"
            ]);



        }catch(\Exception $ex){
          
          DB::rollBack();

          return response()->json([
              'ok' => false, 
              'error' => $ex->getMessage()
          ]);
        }
    }


    public function agregar_varias_notas(Request $request)
    {
      DB::beginTransaction();
      $valid;
      $input = $request->all();
      $nota = $request["array"];
      $secciones = Seccion::find($request->seccion_id);
 
      $input = $request->all();
      $validator = Validator::make($input, [
          'corte' => 'required|numeric',
          'evaluacion' => 'required|max:75',
          'seccion_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
              'ok' => false, 
              'error' => $validator->messages()
            ]);
        }

            try{
//json_decode
        foreach (json_decode($nota) as $a) {

          $valid = AlumnoSeccion::select("alumno_seccion.*")
          ->where("seccion_id", $request->seccion_id)
          ->where("alumno_id", $request->alumno_id)
          ->first();

          // if ($valid != false) {

            if ($secciones != false) {

              $nota = new Nota();
              $nota->corte = $request->corte;
              $nota->nota = $a;
              $nota->evaluacion = $request->evaluacion;
              $nota->seccion_id = $request->seccion_id;
              $nota->alumno_id = $request->alumno_id;
              $nota->save();

            }else 

            return response()->json([
                'ok' => true, 
                'error' => "Hubo un error al agregar alumnos, revise los campos que seleccionó"
            ]);

            // }else 

            // DB::rollBack();
            // return response()->json([
            //       'ok' => true, 
            //       'error' => "Este alumno no pertenece a esta sección",
            //       'data' => $a
            // ]);

        }

        if ($secciones != false) {
          DB::commit();
          return response()->json([
            "ok" => true,
            "message" => "Notas agregados a la sección con exito"
          ]);
        }

        }catch(\Exception $ex){
          
          DB::rollBack();

          return response()->json([
              'ok' => false, 
              'error' => $ex->getMessage()
          ]);
        }
    }

}
