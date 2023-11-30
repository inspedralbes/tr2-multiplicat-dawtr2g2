<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pregunta;
use App\Models\Dificultat;
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

    public function showPregDif($dif)
    {
        $mostrarPreg = Pregunta::where('dificultat_id', $dif)->get();

        return response()->json($mostrarPreg);

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
        $pregunta = Pregunta::find($id);
    
        if (!$pregunta) {
            return response()->json(['message' => 'No s\'ha trobat cap resposta amb aquest id!'], 404);
        }
    
        $pregunta->delete();
    
        return response()->json(['message' => 'Resposta eliminada']);
    }
    

    // MÈTODES DE LA PART D'ADMINISTRACIÓ
    public function adminIndex()
    {
        $preguntes = Pregunta::all();

        return view('preguntes.index', ['preguntes' => $preguntes]);
    }

    public function adminStore(Request $request)
    {
        
            $request->validate([
                'pregunta' => 'required',
                'resposta_correcta_id' => 'required',
                'tema_id' => 'required',
                'dificultat_id' => 'required'
            ]);
    
            $pregunta = new Pregunta;
            $pregunta->pregunta = $request->pregunta;
            $pregunta->resposta_correcta_id = $request->resposta_correcta_id;
            $pregunta->tema_id = $request->tema_id;
            $pregunta->dificultat_id = $request->dificultat_id;
            $pregunta->save();
    
            return redirect()->route('view-afegir-pregunta')->with('success', 'Pregunta afegida correctament');
    }

    public function adminShow($id) {
        $pregunta = Pregunta::find($id);
        return view('preguntes.modificar', ['pregunta' => $pregunta]);
    }
    
    public function adminUpdate(Request $request, $id) {

        $pregunta = Pregunta::find($id);
        $pregunta->update($request->all());
        
       

        return redirect()->route('view-modificar-resposta', ['id' => $pregunta->id])->with('success', 'La pregunta ha estat actualitzada correctament');
    }

    public function adminDelete($id) {
        $pregunta = Pregunta::find($id);
        $pregunta->delete();

        return redirect()->route('preguntes')->with('success', 'pregunta eliminada correctament'); 
    }
}
