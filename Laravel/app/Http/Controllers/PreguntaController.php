<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pregunta;
use App\Models\Resposta;
use App\Models\Dificultat;
use Illuminate\Support\Facades\DB;

class PreguntaController extends Controller
{   
    

    public function index()
    {
        $preguntes = Pregunta::all();

        return response()->json($preguntes);
    }

    public function showPregDif($dif)
    {
        $mostrarPreg = Pregunta::where('dificultat_id', $dif)->get();
        $count = Pregunta::where('dificultat_id', $dif)->count();

        return response()->json([
            'preguntas' => $mostrarPreg,
            'count' => $count
        ]); 
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

        $mostrar = Pregunta::create($request->all());

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
        $pregunta = Pregunta::find($id);

        if (!$pregunta) {
            return response()->json(['message' => 'No s\'ha trobat cap resposta amb aquest id!'], 404);
        }

        $pregunta->delete();

        return response()->json(['message' => 'Pregunta eliminada']);
    }


    // MÈTODES DE LA PART D'ADMINISTRACIÓ
    public function adminIndex()
    {
        $preguntes = Pregunta::all();
        
        foreach ($preguntes as $pregunta) {
            $resposta = Resposta::where('id', $pregunta->resposta_correcta_id)->first();
            $dificultat = Dificultat::where('id', $pregunta->dificultat_id)->first();
            $tema = DB::table('temes')->where('id', $pregunta->tema_id)->first();
            $pregunta->resposta = $resposta ? $resposta->resposta : null;
            $pregunta->dificultat = $dificultat ? $dificultat->nom_dificultat : null;
            $pregunta->tema = $tema ? $tema->nom_tematica : null;
            
        }
        return view('preguntes.index', [
            'preguntes' => $preguntes
        ]);


    }


    public function adminStore(Request $request)
    {

        $request->validate([
            'pregunta' => 'required',
            'resposta' => 'required',
            'tema_id' => 'required',
            'dificultat_id' => 'required'
        ]);
        $resposta = new Resposta;
        $resposta->resposta = $request->resposta;
        $resposta->tema_id = $request->tema_id;
        $resposta->dificultat_id = $request->dificultat_id;
        $resposta->save();

        $pregunta = new Pregunta;
        $pregunta->pregunta = $request->pregunta;
        $pregunta->tema_id = $request->tema_id;
        $pregunta->dificultat_id = $request->dificultat_id;
        $pregunta->resposta_correcta_id = $resposta->id;
        $pregunta->save();

        return redirect()->route('preguntes')->with('success', 'Pregunta afegida correctament');
    }

    public function adminShow($id)
    {
        $pregunta = Pregunta::find($id);

        $resposta = Resposta::where('id', $pregunta->resposta_correcta_id)->first(); 
        $pregunta->resposta = $resposta ? $resposta->resposta : null;
       
        
        $dificultat = Dificultat::where('id', $pregunta->dificultat_id)->first();
        $pregunta->dificultat = $dificultat ? $dificultat->nom_dificultat : null;
        
        $dificultats = Dificultat::all();


        $pregunta->dificultat = $dificultats->pluck('nom_dificultat');


        return view('preguntes.modificar', ['pregunta' => $pregunta, 'dificultats' => $dificultats, 'dificultat' => $dificultat]);
    }
    

    public function adminUpdate(Request $request, $id)
{
    $pregunta = Pregunta::find($id); 

    
    $pregunta->update([
        'pregunta' => $request->pregunta,
        'tema_id' => $request->tema_id,
        'dificultat_id' => $request->dificultat_id,
    ]);

   
    $resposta = Resposta::find($pregunta->resposta_correcta_id);
    if ($resposta) {
        $resposta->update(['resposta' => $request->resposta]);
    }

    return redirect()->route('preguntes')->with('success', 'La pregunta ha sido actualizada correctamente');
}

    

    public function adminDelete($id)
    {
        $pregunta = Pregunta::find($id);
        $pregunta->delete();

        return redirect()->route('preguntes')->with('success', 'pregunta eliminada correctament');
    }
}
