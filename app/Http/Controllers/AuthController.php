<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class AuthController extends Controller
{
    public function logout(Request $request){
        // Eliminar la variable 'user' de la sesión
        Session::forget('user');

        return redirect()->route('login');
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return response()->json([
                'error' => 'Las credenciales ingresadas son incorrectas, intente nuevamente'
            ], 422);
        }
    
        if (Hash::check($request->password, $user->password)) {
            // Guardar los datos del usuario en la sesión
            Session::put('user', $user);
    
            //Redirigir según el rol
            $redirect = '';
            if ($user->role === 'admin') {
                $redirect = route('admin.dashboard');
            }
            else{
                $redirect = route('client.dashboard');
            }
    
            return response()->json([
                'result' => true,
                'user' => $user,
                'redirect' => $redirect
            ]);            
        } else {
            return response()->json([
                'error' => 'Las credenciales ingresadas son incorrectas, intente nuevamente'
            ], 422);
        }
    } 
    
    public function register(Request $request)
    {
        //Si ya existe un cliente con ese email
        $check_email = User::where('email', $request->email)->first();
        if($check_email){
            return response()->json([
                'error' => 'Ya existe un cliente con el email digitado'
            ], 422);
        }

        if ($request->password === $request->password_confirmation) {
            $client = new User;
            $client->role = 'client';
            $client->email = $request->email;
            $client->password = Hash::make($request->password);
            $client->name = $request->name;
            $client->save();

            // Guardar los datos del usuario en la sesión
            Session::put('user', $client);

            return response()->json([
                'result' => true,
                'user' => $client,
                'redirect' => route('client.dashboard'),
            ]); 
        }

        return response()->json([
            'result' => false,
            'error' => 'Las contraseñas no coinciden'
        ]); 
    }
}
