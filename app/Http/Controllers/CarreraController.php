<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Model\Carrera;
use Validator, DB;

class CarreraController extends Controller
{  
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      $carreras = Carrera::select("carreras.*")
      ->get();

      return response()->json([
        "ok" => true,
        "data" => $carreras
      ]);
    }

    public function mostrar_borrados()
    {
      $carreras = Carrera::select("carreras.*")
      ->onlyTrashed()
      ->get();

      return response()->json([
        "ok" => true,
        "data" => $carreras
      ]);
    }

   public function recuperar_borrado(Request $request, $id)
    {
     DB::beginTransaction();

      $carrera = Carrera::withTrashed()->where('id', '=', $id)->first();

        $input = $request->all();

        try{

          if ($carrera == false) {
             return response()->json([
              'ok' => false, 
              'error' => "No se encontro o no ha sido eliminado esta carrera"
            ]);
          }

          $carrera->restore();
          DB::commit();

          return response()->json([
              'ok' => true, 
              'message' => "Se restauro el carrera con exito"
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
      $carreras;
      try{

        foreach (json_decode($request) as $i) {
          $carreras = Carrera::find($i);

          if ($carreras != false) {

            $carreras->delete();

          }else 

          return response()->json([
              'ok' => true, 
              'error' => "Hubo un error al eliminar, revise los campos que seleccionó"
          ]);
        }

        if ($carreras != false) {
          DB::commit();
          return response()->json([
            "ok" => true,
            "message" => "Carreras eliminadas con exito"
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
      $carreras;
      try{

        foreach (json_decode($request) as $i) {
          $carreras = Carrera::withTrashed()->where('id', '=', $i)->first();

          if ($carreras != false) {

            $carreras->restore();

          }else 

          return response()->json([
              'ok' => true, 
              'error' => "Hubo un error al eliminar, revise los campos que seleccionó"
          ]);
        }

        if ($carreras != false) {
          DB::commit();
          return response()->json([
            "ok" => true,
            "message" => "Carreras restauradas con exito"
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
          'nombre' => 'required|max:65|unique:carreras',
        ]);

        if ($validator->fails()) {
            return response()->json([
              'ok' => false, 
              'error' => $validator->messages()
            ]);
        }

        try{

          Carrera::create($input);
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
      $carreras = Carrera::select("carreras.*")
      ->where("carreras.id", $id)
      ->first();

      return response()->json([
        "ok" => true,
        "data" => $carreras
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
          'nombre' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
              'ok' => false, 
              'error' => $validator->messages()
            ]);
        }

        try{

          $carrera = Carrera::find($id);

          if ($carrera == false) {
             return response()->json([
              'ok' => false, 
              'error' => "No se encontro esta carrera"
            ]);
          }

          $carrera->update($input);
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

          $carrera = Carrera::findOrFail($id);

          if ($carrera == false) {
             return response()->json([
              'ok' => false, 
              'error' => "No se encontro esta carrera"
            ]);
          }

          $carrera->delete();

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
}
