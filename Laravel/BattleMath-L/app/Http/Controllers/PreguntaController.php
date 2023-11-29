<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pregunta;
use Illuminate\Support\Facades\DB;

class PreguntaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $preguntes = Pregunta::all(); 

        return response()->json($preguntes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pregunta' => 'required',
            'dificultat_id' => 'required',
            'tema_id' => 'required'
        ]);

        $mostrar=Pregunta::create($request->all());

        return response()->json($mostrar);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $mostrarPreg = Pregunta::find($id);

        return response()->json($mostrarPreg);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pregunta = Pregunta::find($id);
        $pregunta->update($request->all());

        return response()->json($pregunta);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // MÈTODES DE LA PART D'ADMINISTRACIÓ
    public function adminIndex()
    {
        $preguntes = Pregunta::all();

        return view('preguntes.index', ['preguntes' => $preguntes]);
    }
}
