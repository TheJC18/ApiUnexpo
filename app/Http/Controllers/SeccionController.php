<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Seccion;
use App\Model\Profesor;
use App\Model\Alumno;
use App\Model\Carrera;
use App\Model\AlumnoSeccion;
use Validator, DB, Arr;

class SeccionController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
     $seccions = Seccion::with(['asignatura','asignatura.tipo_asignatura','profesor','profesor.persona'])->where('estado',1)->get();

      return response()->json([
        "ok" => true,
        "data" => $seccions
      ]);
    }

    public function alumnos_disponibles(Request $request)
    {
      $seccion = Seccion::find($request->id);
      
      $secciones = Seccion::with(['asignatura','asignatura.tipo_asignatura','profesor','profesor.persona'])
      ->where('asignatura_id',$seccion->asignatura_id)
      ->get();

      $alumno_seccion = AlumnoSeccion::all();

      $alumnos = Alumno::all();

      foreach ($alumno_seccion as $as) {

        foreach ($secciones as $sec ) {
            
            if ($as->seccion_id == $sec->id) {
                echo $as->alumno_id;            
            }
          }
        }

      return response()->json([
        "ok" => true,
       // "data" => $disponible
      ]);
    }

      public function mostrar_borrados()
    {
      $seccions = Seccion::with(['asignatura','asignatura.tipo_asignatura','profesor','profesor.persona'])
      ->where('estado',0)
      ->get();

      return response()->json([
        "ok" => true,
        "data" => $seccions
      ]);
    }

    public function listado_alumnos($id)
    {
      $seccions = Seccion::with(["alumnos"])
      ->where('id', $id)
      ->get();

      return response()->json([
        "ok" => true,
        "data" => $seccions->toArray()
      ]);
    }


    public function destroy_all(Request $request)
    {
    
      DB::beginTransaction();
      $request = $request["array"];
      $secciones;
      try{

        foreach (json_decode($request) as $i) {
          $secciones = Seccion::find($i);

          if ($secciones != false) {

            $secciones->update([
            'estado' => $seccion->estado == 1 ? 0 : 1,
            ]);

          }else 

          return response()->json([
              'ok' => true, 
              'error' => "Hubo un error al eliminar, revise los campos que seleccionó"
          ]);
        }

        if ($secciones != false) {
          DB::commit();
          return response()->json([
            "ok" => true,
            "message" => "secciones eliminadas con exito"
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

    public function recuperar_varios(Request $request)
    {
    
      DB::beginTransaction();
      $request = $request["array"];
      $secciones;
      try{

        foreach (json_decode($request) as $i) {
          $secciones = Seccion::find($i);

          if ($secciones != false) {

            $secciones->update([
            'estado' => $seccion->estado == 1 ? 0 : 1,
            ]);

          }else 

          return response()->json([
              'ok' => true, 
              'error' => "Hubo un error al eliminar, revise los campos que seleccionó"
          ]);
        }

        if ($secciones != false) {
          DB::commit();
          return response()->json([
            "ok" => true,
            "message" => "secciones eliminadas con exito"
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

    public function agregar_alumnos(Request $request)
    {
      DB::beginTransaction();

      $input = $request->all();

      $id = $request->id;
      $request = $request["array"];
      $secciones = Seccion::find($id);
      $valid;

        $validator = Validator::make($input, [
          'array' => 'required',
          'id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
              'ok' => false, 
              'error' => $validator->messages()
            ]);
        }

      try{
//json_decode
        foreach (json_decode($request) as $i) {

          $valid = AlumnoSeccion::select("alumno_seccion.*")
          ->where("seccion_id", $id)
          ->where("alumno_id", $i)
          ->first();


            if ($secciones != false) {

              $secciones->alumnos()->attach($i);

              // $secciones = DB::insert('INSERT into alumno_seccion (seccion_id, alumno_id) values (?, ?)', [
              //   $id, 
              //   $i
              // ]);

            }else 

            return response()->json([
                'ok' => true, 
                'error' => "Hubo un error al agregar alumnos, revise los campos que seleccionó"
            ]);

        }

        if ($secciones != false) {
          DB::commit();
          return response()->json([
            "ok" => true,
            "message" => "Alumnos agregados a la sección con exito"
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


    // *
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
     
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
          'alias' => 'required|max:65',
          'lapso' => 'required|max:65',
          'matricula' => 'required|numeric',
          'fecha_inicio' => 'required',
          'fecha_fin' => 'required',
          'asignatura_id' => 'required|numeric',
          'profesor_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
              'ok' => false, 
              'error' => $validator->messages()
            ]);
        }

        try{

          Seccion::create($input);

          return response()->json([
              'ok' => true, 
              'message' => "Se registro con exito"
            ]);

        }catch(\Exception $ex){
          
          return response()->json([
              'ok' => false, 
              'error' => $ex->getMessage()
          ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $seccions = Seccion::select("seccions.*", "asignaturas.nombre as nombre_asignatura")
      ->join("asignaturas", "seccions.asignatura_id", "=", "asignaturas.id")
      ->where("seccions.id", $id)
      ->first();

      return response()->json([
        "ok" => true,
        "data" => $seccions
      ]);
    }
    
     public function showStudens($id)
    {
      $seccions = Seccion::with(["alumnos"])->get();

      return response()->json([
        "ok" => true,
        "data" => $seccions
      ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
          'alias' => 'required|max:65',
          'lapso' => 'required|max:65',
          'matricula' => 'required|numeric',
          'fecha_inicio' => 'required',
          'fecha_fin' => 'required',
          'asignatura_id' => 'required|numeric',
          'profesor_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
              'ok' => false, 
              'error' => $validator->messages()
            ]);
        }

        try{

          $seccion = Seccion::find($id);

          if ($seccion == false) {
             return response()->json([
              'ok' => false, 
              'error' => "No se encontro esta sección"
            ]);
          }

          $seccion->update($input);

          return response()->json([
              'ok' => true, 
              'message' => "Se modifico con exito"
            ]);

          }catch(\Exception $ex){
            
            return response()->json([
                'ok' => false, 
                'error' => $ex->getMessage()
            ]);
          }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{

          $seccion = Seccion::find($id);

          if ($seccion == false) {
             return response()->json([
              'ok' => false, 
              'error' => "No se encontro esta sección"
            ]);
          }

          $seccion->update([
            'estado' => $seccion->estado == 1 ? 0 : 1,
          ]);

          return response()->json([
              'ok' => true, 
              'message' => "Se elimino con exito"
            ]);

          }catch(\Exception $ex){
            
            return response()->json([
                'ok' => false, 
                'error' => $ex->getMessage()
            ]);
          }
    }
}