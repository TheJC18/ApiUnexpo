<?php

namespace App\Http\Controllers;

use DB,Validator,Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Model\Profesor;
use App\Model\Persona;
use App\Model\User;

class ProfesorController extends Controller
{  
    public function index()
    {
      $profesors = Profesor::with(['persona'])->get();

      return response()->json([
        "ok" => true,
        "data" => $profesors
      ]);
    }

    public function mostrar_borrados()
    {
      $profesors =  Profesor::with(['persona'])
      ->onlyTrashed()
      ->get();


      return response()->json([
        "ok" => true,
        "data" => $profesors
      ]);
    }

    public function recuperar_borrado(Request $request, $id)
    {
     DB::beginTransaction();

      $profesor = Profesor::withTrashed()->where('id', '=', $id)->first();
      $user = User::withTrashed()->where('persona_id', '=', $profesor->persona_id)->first();

        $input = $request->all();

        try{

          if ($profesor == false) {
             return response()->json([
              'ok' => false, 
              'error' => "No se encontro o no ha sido eliminado esta profesor"
            ]);
          }

          $profesor->restore();
          $user->restore();
          DB::commit();

          return response()->json([
              'ok' => true, 
              'message' => "Se restauro el profesor con exito"
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
      $profesores;
      $user;
      try{

        foreach (json_decode($request) as $i) {
          $profesores = Profesor::find($i);

          $user = User::where('persona_id', $profesores->persona_id)->first();

          if ($profesores != false) {

            $profesores->delete();
            $user->delete();
          }else 

          return response()->json([
              'ok' => true, 
              'error' => "Hubo un error al eliminar, revise los campos que seleccionó"
          ]);
        }

        if ($profesores != false) {
          DB::commit();
          return response()->json([
            "ok" => true,
            "message" => "profesores eliminadas con exito"
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
      $profesores;
      $user;

        foreach (json_decode($request) as $i) {
          $profesores = Profesor::withTrashed()->where('id', '=', $i)->first();
          $user = User::withTrashed()->where('persona_id', '=', $profesores->persona_id)->first();

          if ($profesores != false) {

            $profesores->restore();
            $user->restore();

          }else 

          return response()->json([
              'ok' => true, 
              'error' => "Hubo un error al eliminar, revise los campos que seleccionó"
          ]);
        }

        if ($profesores != false) {
          DB::commit();
          return response()->json([
            "ok" => true,
            "message" => "Profesores restaurados con exito"
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
          'dni' => 'required|max:9|unique:personas',
          'nombre' => 'required|max:65',
          'segundo_nombre' => 'required|max:65',
          'apellido' => 'required|max:65',
          'segundo_apellido' => 'required|max:65',
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

            Profesor::create([
                'persona_id' => $persona->id
            ]);
          
        }catch(\Exception $ex){
          
          DB::rollBack();

          return response()->json([
              'ok' => false, 
              'error' => $ex->getMessage()
          ]);
        }


        try{

            $user = User::create([
              'email' => $request->email, 
              'password' => Hash::make($request->password), 
              'persona_id' => $persona->id
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $profesors = Profesor::with(['persona'])
      ->where("profesors.id", $id)
      ->first();

      return response()->json([
        "ok" => true,
        "data" => $profesors
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
      $profesor = Profesor::findOrFail($id);

        $input = $request->all();
        $validator = Validator::make($input, [
          'nombre' => 'required|max:65',
          'segundo_nombre' => 'required|max:65',
          'apellido' => 'required|max:65',
          'segundo_apellido' => 'required|max:65',
        ]);

        if ($validator->fails()) {
            return response()->json([
              'ok' => false, 
              'error' => $validator->messages()
            ]);
        }

        try{

            DB::table('personas')
            ->where('id', $profesor->persona_id)
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

            DB::table('users')
            ->where('persona_id', $profesor->persona_id)
            ->update([
              'email' => $request->email,
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

          $profesor = Profesor::findOrFail($id);

          $user = User::where('persona_id', $profesor->persona_id)->first();

          if ($profesor == false) {
             return response()->json([
              'ok' => false, 
              'error' => "No se encontro esta profesor"
            ]);
          }

          $profesor->delete();
          $user->delete();

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
}
