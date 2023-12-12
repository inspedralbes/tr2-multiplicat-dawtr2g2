<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $existUser = User::where('email', $request->email)->first();

        if ($existUser) {
            return response()->json(['message' => 'El correu ja s\'està utilitzant '], 400);
        } elseif (strcmp($request->password, $request->password_confirmation) !== 0) {
            return response()->json(['message' => 'La contrasenya no coincideix '], 400);
        } else{
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => bcrypt($request->password_confirmation)
            ]);
    
            $token = $user->createToken('BattleMath')->plainTextToken;
    
            $response = [
                'user' => $user,
                'token' => $token
            ];
    
            return response()->json(['success' => 'Usuari creat correctament', 'success' => $response], 200);

        }
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // Comprovar email
        $user = User::where('email', $fields['email'])->first();

        // Comprovar usuari i contrasenya
        if (!$user) {

            return response(['message' => 'L\'usuari no existeix'], 400);

        } else if (!Hash::check($fields['password'], $user->password)) {
            return response(['message' => 'La contrasenya és incorrecta'], 400);
        } else {
            $token = $user->createToken('BattleMath')->plainTextToken;

            $response = [
                'user' => $user,
                'token' => $token
            ];

            return response()->json(['success' => 'Usuari logged', 'success' => $response], 200);


        }
    }

    public function logout(Request $request)
    {
        //auth()->user()->tokens()->delete();

        return [
            'message' => 'S\'ha tancat la sessió',
        ];
    }
}
