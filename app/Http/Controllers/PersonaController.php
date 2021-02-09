<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, DB;
use App\Model\Persona;

class PersonaController extends Controller
{

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

          Persona::create($input);
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

          $Persona = Persona::find($id);

          if ($Persona == false) {
             return response()->json([
              'ok' => false, 
              'error' => "No se encontro esta Persona"
            ]);
          }

          $Persona->update($input);
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

          $persona = Persona::find($id);

          if ($persona == false) {
             return response()->json([
              'ok' => false, 
              'error' => "No se encontro esta Persona"
            ]);
          }

          $persona->update($input);
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

          $persona = Persona::findOrFail($id);

          if ($persona == false) {
             return response()->json([
              'ok' => false, 
              'error' => "No se encontro esta persona"
            ]);
          }
        
          $persona->delete();

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


    public function recuperar_borrado(Request $request, $id)
    {
     DB::beginTransaction();

      $Persona = Persona::withTrashed()->where('id', '=', $id)->first();

        $input = $request->all();

        try{

          if ($Persona == false) {
             return response()->json([
              'ok' => false, 
              'error' => "No se encontro o no ha sido eliminado esta Persona"
            ]);
          }

          $Persona->restore();
          DB::commit();

          return response()->json([
              'ok' => true, 
              'message' => "Se restauro el Persona con exito"
            ]);

          }catch(\Exception $ex){
            
          DB::rollBack();
            
            return response()->json([
                'ok' => false, 
                'error' => $ex->getMessage()
            ]);
          }
    }
}
