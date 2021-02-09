<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, DB;
use App\Model\User;
use App\Model\Persona;

class UserController extends Controller
{
 public function index()
    {
      $Users = User::with(['persona'])->get();

      return response()->json(["ok" => true,"data" => $Users]);
    }

    public function mostrar_borrados()
    {
      $Users = User::with(['persona'])
      ->onlyTrashed()
      ->get();

      return response()->json([
        "ok" => true,
        "data" => $Users
      ]);
    }

    public function recuperar_borrado(Request $request, $id)
    {
     DB::beginTransaction();

      $User = User::withTrashed()->where('id', '=', $id)->first();

      $input = $request->all();

        try{

          if ($User == false) {
             return response()->json([
              'ok' => false, 
              'error' => "No se encontro o no ha sido eliminado esta User"
            ]);
          }

          $User->restore();
          DB::commit();

          return response()->json([
              'ok' => true, 
              'message' => "Se restauro el User con exito"
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
      DB::beginTransaction();
      $request = $request["array"];
      $User;
      try{

        foreach (json_decode($request) as $i) {
          $User = User::find($i);

          if ($User != false) {

            $User->delete();

          }else 

          return response()->json([
              'ok' => true, 
              'error' => "Hubo un error al eliminar, revise los campos que seleccionó"
          ]);
        }

        if ($User != false) {
          DB::commit();
          return response()->json([
            "ok" => true,
            "message" => "Users eliminados con exito"
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
      $Users;

        foreach (json_decode($request) as $i) {
          $Users = User::withTrashed()->where('id', '=', $i)->first();

          if ($Users != false) {

            $Users->restore();

          }else 

          return response()->json([
              'ok' => true, 
              'error' => "Hubo un error al eliminar, revise los campos que seleccionó"
          ]);
        }

        if ($Users != false) {
          DB::commit();
          return response()->json([
            "ok" => true,
            "message" => "Users restaurados con exito"
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

            User::create([
                'persona_id' => $persona->id,
                'carrera_id' => $request->carrera_id
            ]);

            DB::commit();
          
            return response()->json([
              'ok' => true, 
              'message' => "Se registro el User con exito"
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
      $Users = User::with(['persona','carrera'])
      ->where("Users.id", $id)
      ->first();

      return response()->json([
        "ok" => true,
        "data" => $Users
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
      $User = User::findOrFail($id);

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
            ->where('id', $User->persona_id)
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

            DB::table('Users')
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
    public function destroy($id)
    {
        try{

          $User = User::findOrFail($id);

          if ($User == false) {
             return response()->json([
              'ok' => false, 
              'error' => "No se encontro esta User"
            ]);
          }

          $User->delete();

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
    //

     
      DB::beginTransaction();

      $request = $request["array"];
      $User;

      
      try{

        foreach (($request) as $i) {
          $User = User::find($i);

          if ($User != false) {

            $User->delete();

          }else 

          return response()->json([
              'ok' => true, 
              'error' => "Hubo un error al eliminar, revise los campos que seleccionó"
          ]);
        }

        if ($User != false) {
          DB::commit();
          return response()->json([
            "ok" => true,
            "message" => "User eliminadas con exito"
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
