<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class LoginController extends Controller
{

    

public function login(Request $request)
{
    $credentials = $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    if ($credentials['username'] != 'admin') {
        return redirect()->back()->withErrors(['error' => 'Només l\' administrador pot iniciar sessió']);
    }

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $user->createToken('BattleMath')->plainTextToken;

        // Autenticación correcta
        return redirect()->route('preguntes');
    } else {
        // La autenticación falló
        return redirect()->back()->withErrors(['error' => 'Les credencials no són correctes']);
    }
}


    public function logout(Request $request)
    {
        Auth::logout();

        return redirect('/');
    }

}



// $fields = $request->validate([
//     $user = 'user' => 'required|string',
//      'password' => 'required|string',
//  ]);

//  // Comprovar usuari i contrasenya
//  if (!$user == 'admin') {

//      return response(['message' => 'L\'Usiari no es administrador'], 400);

//  } else if (!Hash::check($fields['password'], $user->password)) {
//      return response(['message' => 'La contrasenya és incorrecta'], 400);
//  } else {
//      $token = $user->createToken('BattleMath')->plainTextToken;


//      $response = [
//          'user' => $user,
//          'token' => $token,-
//      ];

//      return response()->json(['success' => 'Has iniciat sessió', 'data' => $response], 200);

//  }