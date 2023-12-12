<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function adminIndex(){
        $usuaris = User::all();
        return view('usuaris.index', ['usuaris' => $usuaris]);
    }

    public function adminFiltra(Request $request)
    {
        $usuaris = DB::table('users')
        ->where('id', 'like', '%' . $request->filtre . '%')        
        ->orWhere('username', 'like', '%' . $request->filtre . '%')
        ->orWhere('email', 'like', '%' . $request->filtre . '%')
        ->get();

    return view('usuaris.index', ['usuaris' => $usuaris]);
    }
    
    public function adminStore(Request $request)
    {

        $fields = $request->validate([
            'username' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);
        
        $user = new User;
        $user->username = $fields['username'];
        $user->email = $fields['email'];
        $user->password = bcrypt($fields['password']);
        $user->save();

        return redirect()->route('usuaris')->with('success', 'Usuari afegit correctament');

    }

    public function adminUpdate(Request $request, string $id)
    {
        $fields = $request->validate([
            'username' => 'required|string',
            'email' => 'required|email|unique:users,email',
        ]);

        $usuari = User::find($id);
        $usuari->update($request->all());
        return redirect()->route('usuaris')->with('success', 'Usuari modificat correctament');
    }

    public function adminShow($id){
        $usuari = User::find($id);
        return view('usuaris.modificar', ['usuari' => $usuari]);
    }



    public function adminDelete($id) {
        $usuari = User::find($id);
        $usuari->delete();

        return redirect()->route('usuaris')->with('success', 'Usuari eliminat correctament'); 
    }
}