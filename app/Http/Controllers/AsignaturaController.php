<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Model\Asignatura;
use Validator, DB;

class AsignaturaController extends Controller
{
  
   /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      $asignaturas = Asignatura::with(['tipo_asignatura'])
      ->where('disponibilidad',1)
      ->get();

      return response()->json([
        "ok" => true,
        "data" => $asignaturas
      ]);
    }


    public function mostrar_borrados()
    {
      $asignaturas = Asignatura::with(['tipo_asignatura'])
      ->where('disponibilidad',0)
      ->get();

      return response()->json([
        "ok" => true,
        "data" => $asignaturas
      ]);
    }

    public function recuperar_borrado(Request $request, $id)
    {
     DB::beginTransaction();

        $asignatura = Asignatura::with(['tipo_asignatura'])
        ->where("asignaturas.id", $id)
        ->where("disponibilidad", 0)
        ->first();

        $input = $request->all();

        try{

          if ($asignatura == false) {
             return response()->json([
              'ok' => false, 
              'error' => "No se encontro o no ha sido eliminado esta asignatura"
            ]);
          }

          $asignatura->update([
            'disponibilidad' => $asignatura->disponibilidad == 1 ? 0 : 1,
          ]);          
          DB::commit();

          return response()->json([
              'ok' => true, 
              'message' => "Se restauro la asignatura con exito"
            ]);

          }catch(\Exception $ex){
            
          DB::rollBack();
            
            return response()->json([
                'ok' => false, 
                'error' => $ex->getMessage()
            ]);
          }
    }

    public function destroy_all(Request $request)
    {
    
      DB::beginTransaction();
      $request = $request["array"];
      $asignatura;
      try{

        foreach (($request) as $i) {
          $asignatura = Asignatura::find($i);

          if ($asignatura != false) {

            $asignatura->update([
            'disponibilidad' => $asignatura->disponibilidad == 1 ? 0 : 1,
            ]);

          }else 

          return response()->json([
              'ok' => true, 
              'error' => "Hubo un error al eliminar, revise los campos que seleccionó"
          ]);
        }

        if ($asignatura != false) {
          DB::commit();
          return response()->json([
            "ok" => true,
            "message" => "asignatura eliminadas con exito"
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
      $asignatura;
      try{

        foreach (json_decode($request) as $i) {
          $asignatura = Asignatura::find($i);

          if ($asignatura != false) {

            $asignatura->update([
            'disponibilidad' => $asignatura->disponibilidad == 1 ? 0 : 1,
            ]);

          }else 

          return response()->json([
              'ok' => true, 
              'error' => "Hubo un error al recuperar, revise los campos que seleccionó"
          ]);
        }

        if ($asignatura != false) {
          DB::commit();
          return response()->json([
            "ok" => true,
            "message" => "Asignaturas recuperardas con exito"
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



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      DB::beginTransaction();

        $input = $request->all();

        $validator = Validator::make($input, [
          'nombre' => 'required|max:65|unique:asignaturas',
          'tipo_asignatura_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
              'ok' => false, 
              'error' => $validator->messages()
            ]);
        }

        try{

          Asignatura::create($input);
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $asignaturas = Asignatura::with(['tipo_asignatura'])
      ->where("asignaturas.id", $id)
      ->where("disponibilidad", 1)
      ->first();

      return response()->json([
        "ok" => true,
        "data" => $asignaturas
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
      DB::beginTransaction();

        $input = $request->all();

        $validator = Validator::make($input, [
          'nombre' => 'max:255',
          'tipo_asignatura_id' => 'numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
              'ok' => false, 
              'error' => $validator->messages()
            ]);
        }

        try{

          $asignatura = Asignatura::find($id);

          if ($asignatura == false) {
             return response()->json([
              'ok' => false, 
              'error' => "No se encontro esta asignatura"
            ]);
          }

          $asignatura->update($input);
          DB::commit();

          return response()->json([
              'ok' => true, 
              'message' => "Se modifico con exito"
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
    public function destroy($id)
    {
        try{

          $asignatura = Asignatura::find($id);

          if ($asignatura == false) {
             return response()->json([
              'ok' => false, 
              'error' => "No se encontro esta asignatura"
            ]);
          }

          $asignatura->update([
            'disponibilidad' => $asignatura->disponibilidad == 1 ? 0 : 1,
          ]);

          return response()->json([
              'ok' => true, 
              'message' => "Se elimino la asignatura con exito"
            ]);

          }catch(\Exception $ex){
            
            return response()->json([
                'ok' => false, 
                'error' => $ex->getMessage()
            ]);
          }
    } 
}
