<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\skins;
use Illuminate\Support\Facades\Auth;

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
                'password' => bcrypt($request->password_confirmation),
                'skin_id'=>$request->skin_id,
            ]);
    
            $token = $user->createToken('BattleMath')->plainTextToken;
            $response = [
                'user' => $user,
                'token' => $token
            ];
    
            return response()->json(['success' => 'Usuari creat correctament', 'data' => $response], 200);

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

            $skin= skins::where('id', $user->skin_id)->pluck('name')->first();

            $response = [
                'user' => $user,
                'token' => $token,
                'skin' => $skin
            ];

            return response()->json(['success' => 'Has iniciat sessió', 'data' => $response], 200);


        }
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if ($user) {
            $user->update($request->all());
            return response()->json(['success' => 'Usuari modificat correctament', 'data' => $user], 200);
        } else {
            return response()->json(['message' => 'Usuari no trobat'], 404);
        }
    }

    public function logout(Request $request)
    {
        if (Auth::user()) {
            Auth::user()->tokens()->delete();
            return response()->json(['success' => 'S\'ha tancat la sessió'], 200);
        } else {
            return response()->json(['message' => 'No hi ha cap sessió iniciada'], 400);
        }

        return [
            'success' => 'S\'ha tancat la sessió',
        ];
    }
}
