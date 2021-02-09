<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, DB;
use App\Model\Alumno;
use App\Model\Persona;

class AlumnoController extends Controller
{
 public function index(Request $request)
    {
      $request->user()->authorizeRoles(['admin','prof']);

      $alumnos = Alumno::with(['persona','carrera'])->get();

      return response()->json([
        "ok" => true,
        "data" => $alumnos
      ]);
    }

    public function mostrar_borrados(Request $request)
    {
      $request->user()->authorizeRoles(['admin']);
      
      $alumnos = Alumno::with(['persona','carrera'])
      ->onlyTrashed()
      ->get();

      return response()->json([
        "ok" => true,
        "data" => $alumnos
      ]);
    }

    public function recuperar_borrado(Request $request, $id)
    {
      $request->user()->authorizeRoles(['admin']);
     DB::beginTransaction();

      $alumno = Alumno::withTrashed()->where('id', '=', $id)->first();

      $input = $request->all();

        try{

          if ($alumno == false) {
             return response()->json([
              'ok' => false, 
              'error' => "No se encontro o no ha sido eliminado esta alumno"
            ]);
          }

          $alumno->restore();
          DB::commit();

          return response()->json([
              'ok' => true, 
              'message' => "Se restauro el alumno con exito"
            ]);

          }catch(\Exception $ex){
            
          DB::rollBack();
            
            return response()->json([
                'ok' => false, 
                'error' => $ex->getMessage()
            ]);
          }
    }

    public function destroy_all2(Request $request, $id)
    {
      $request->user()->authorizeRoles(['admin']);
      DB::beginTransaction();
      $request = $request["array"];
      $alumno;
      try{

        foreach (json_decode($request) as $i) {
          $alumno = Alumno::find($i);

          if ($alumno != false) {

            $alumno->delete();

          }else 

          return response()->json([
              'ok' => true, 
              'error' => "Hubo un error al eliminar, revise los campos que seleccionó"
          ]);
        }

        if ($alumno != false) {
          DB::commit();
          return response()->json([
            "ok" => true,
            "message" => "Alumnos eliminados con exito"
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
      $request->user()->authorizeRoles(['admin']);
      DB::beginTransaction();
      $request = $request["array"];
      $Alumnos;

        foreach (json_decode($request) as $i) {
          $Alumnos = Alumno::withTrashed()->where('id', '=', $i)->first();

          if ($Alumnos != false) {

            $Alumnos->restore();

          }else 

          return response()->json([
              'ok' => true, 
              'error' => "Hubo un error al eliminar, revise los campos que seleccionó"
          ]);
        }

        if ($Alumnos != false) {
          DB::commit();
          return response()->json([
            "ok" => true,
            "message" => "Alumnos restaurados con exito"
          ]);
        }

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $request->user()->authorizeRoles(['admin']);
      DB::beginTransaction();

        $input = $request->all();

        $validator = Validator::make($input, [
          'dni' => 'required|max:11|unique:personas',
          'nombre' => 'required|max:65',
          'segundo_nombre' => 'max:65',
          'apellido' => 'required|max:65',
          'segundo_apellido' => 'max:65',
          'carrera_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
              'ok' => false, 
              'error' => $validator->messages()
            ]);
        }

        try{
            $persona = new Persona();
            $persona->dni = $request->dni;
            $persona->nombre = $request->nombre;
            $persona->segundo_nombre = $request->segundo_nombre;
            $persona->apellido = $request->apellido;
            $persona->segundo_apellido = $request->segundo_apellido;
            $persona->save();

        }catch(\Exception $ex){
              
            DB::rollBack();

            return response()->json([
                'ok' => false, 
                'error' => $ex->getMessage()
            ]);
        }


        try{

            Alumno::create([
                'persona_id' => $persona->id,
                'carrera_id' => $request->carrera_id
            ]);

            DB::commit();
          
            return response()->json([
              'ok' => true, 
              'message' => "Se registro el alumno con exito"
            ]);
          
        }catch(\Exception $ex){
          
          DB::rollBack();

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
    public function show(Request $request, $id)
    {
      $request->user()->authorizeRoles(['admin', 'prof']);
      $alumnos = Alumno::with(['persona','carrera'])
      ->where("alumnos.id", $id)
      ->first();

      return response()->json([
        "ok" => true,
        "data" => $alumnos
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
      $request->user()->authorizeRoles(['admin']);
      DB::beginTransaction();
      $alumno = Alumno::findOrFail($id);

      $input = $request->all();

        $validator = Validator::make($input, [
          'nombre' => 'max:65',
          'segundo_nombre' => 'max:65',
          'apellido' => 'max:65',
          'segundo_apellido' => 'max:65',
          'carrera_id' => 'numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
              'ok' => false, 
              'error' => $validator->messages()
            ]);
        }

        try{

            DB::table('personas')
            ->where('id', $alumno->persona_id)
            ->update([
              'nombre' => $request->nombre,
              'segundo_nombre' => $request->segundo_nombre,
              'apellido' => $request->apellido,
              'segundo_apellido' => $request->segundo_apellido,
            ]);

        }catch(\Exception $ex){
              
            DB::rollBack();

            return response()->json([
                'ok' => false, 
                'error' => $ex->getMessage()
            ]);
        }

        try{

            DB::table('alumnos')
            ->where('id', $id)
            ->update([
              'carrera_id' => $request->carrera_id,
            ]);

            DB::commit();
          
          return response()->json([
              'ok' => true, 
              'message' => "Se registro con exito"
            ]);

        }catch(\Exception $ex){
          
          DB::rollBack();

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
    public function destroy(Request $request, $id)
    {
      $request->user()->authorizeRoles(['admin']);

        try{

          $Alumno = Alumno::findOrFail($id);

          if ($Alumno == false) {
             return response()->json([
              'ok' => false, 
              'error' => "No se encontro esta Alumno"
            ]);
          }

          $Alumno->delete();

          return response()->json([
              'ok' => true, 
              'message' => "Se elimino con exito",
            ]);

          }catch(\Exception $ex){
            
            return response()->json([
                'ok' => false, 
                'error' => $ex->getMessage()
            ]);
          }
    }


    
public function destroy_all(Request $request)
    {
      $request->user()->authorizeRoles(['admin']);
     
      DB::beginTransaction();

      $request = $request["array"];
      $alumno;

      
      try{

        foreach (($request) as $i) {
          $alumno = Alumno::find($i);

          if ($alumno != false) {

            $alumno->delete();

          }else 

          return response()->json([
              'ok' => true, 
              'error' => "Hubo un error al eliminar, revise los campos que seleccionó"
          ]);
        }

        if ($alumno != false) {
          DB::commit();
          return response()->json([
            "ok" => true,
            "message" => "alumno eliminadas con exito"
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
