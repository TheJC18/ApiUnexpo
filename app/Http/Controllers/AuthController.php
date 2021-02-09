<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Persona;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator, DB, Hash, Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        DB::beginTransaction();

        $credentials = $request->only('email', 'password', 'departamento');
        $rules = [
            'password' => 'required',
            'email' => 'required|email|max:255|unique:users'
        ];

        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            return response()->json(['ok'=> false, 'error'=> $validator->messages()]);
        }

        $email = $request->email;
        $password = $request->password;

        
        try{
            $persona = new Persona();
            $persona->dni = $request->dni;
            $persona->nombre = $request->nombre;
            $persona->segundo_nombre = $request->segundo_nombre;
            $persona->apellido = $request->apellido;
            $persona->segundo_apellido = $request->segundo_apellido;
            $persona->save();

            $name = $persona->nombre;

        }catch(\Exception $ex){
              
              DB::rollBack();

              return response()->json([
                  'ok' => false, 
                  'error' => $ex->getMessage()
              ]);
            }


        try{
            if ($departamento = null) {
                $user = User::create([
                    'email' => $email, 
                    'password' => Hash::make($password), 
                    'persona_id' => $persona->id
                ]);

                return response()->json([
                  'ok' => true, 
                  'message' => "Se registro con exito"
                ]);
                DB::commit();

            }else{
                $user = User::create([
                    'email' => $email, 
                    'departamento' => $departamento, 
                    'password' => Hash::make($password), 
                    'persona_id' => $persona->id
                ]);

                return response()->json([
                  'ok' => true, 
                  'message' => "Se registro con exito"
                ]);
                DB::commit();
            }

        }catch(\Exception $ex){
              
              DB::rollBack();

              return response()->json([
                  'ok' => false, 
                  'error' => $ex->getMessage()
              ]);
            }        

        $verification_code = str_random(30); //Generate verification code
        DB::table('user_verifications')->insert(['user_id'=>$user->id,'token'=>$verification_code]);

        $subject = "Verificación de registro.";
        Mail::send('email.verify', compact('name', 'verification_code'),
            function($mail) use ($email, $name, $subject){
                $mail->from(getenv('FROM_EMAIL_ADDRESS'), "Api-Unexpo");
                $mail->to($email, $name);
                $mail->subject($subject);
            });

        return response()->json(['ok'=> true, 'message'=> 'Gracias por registrarte! Por favor revise su correo electrónico para completar su registro.']);
    }

    public function verifyUser($verification_code)
    {
        $check = DB::table('user_verifications')->where('token',$verification_code)->first();

        if(!is_null($check)){
            $user = User::find($check->user_id);

            if($user->is_verified == 1){
                return view("verificar",[
                    'ok'=> true,
                    'message'=> 'Esta cuenta ya esta verificada..'
                ]);
            }

            DB::table('users')->where('id',$check->user_id)->$seccion->update([
                'is_verified'=>1, 
                'email_verified_at' => now(),
            ]);

            DB::table('user_verifications')->where('token',$verification_code)->delete();

            return view("verificar",[
                'ok'=> true,
                'message'=> 'La verificación a sido un exito..'
            ]);
        }

        return view("verificar",['ok'=> false, 'message'=> "Codigo de verificación invalido."]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            return response()->json(['ok'=> false, 'error'=> $validator->messages()], 401);
        }
        
        $credentials['is_verified'] = 1;
        
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['ok' => false, 'error' => 'Verifique los datos ingresados.'], 404);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['ok' => false, 'error' => 'Fallo la conexión, intente nuevamente.'], 500);
        }

        // all good so return the token
        $user = User::select("users.*")
          ->where('email', $request->email)
          ->get();
        return response()->json(['ok' => true, 'data'=> [ 'token' => $token, 'departamento' => $user[0]->departamento]], 200);
    }

    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     * @param Request $request
     */
    public function logout(Request $request) {
            
        try {
            JWTAuth::invalidate($request->input('token'));
            return response()->json(['ok' => true, 'message'=> "Adios, vuelve pronto."]);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['ok' => false, 'error' => 'Fallo el cierre de sesion, intente nuevamente.'], 500);
        }
    }

    public function recover(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $error_message = "Your email address was not found.";
            return response()->json(['ok' => false, 'error' => ['email'=> $error_message]], 401);
        }

        try {
            Password::sendResetLink($request->only('email'), function (Message $message) {
                $message->subject('Your Password Reset Link');
            });

        } catch (\Exception $e) {
            //Return with error
            $error_message = $e->getMessage();
            return response()->json(['ok' => false, 'error' => $error_message], 401);
        }

        return response()->json([
            'ok' => true, 'data'=> ['message'=> 'A reset email has been sent! Please check your email.']
        ]);
    }

    public function destroy($id)
    {
        try{

          $User = User::findOrFail($id);

          if ($User == false) {
             return response()->json([
              'ok' => false, 
              'error' => "No se encontro este User"
            ]);
          }

          $User->delete();

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
}
