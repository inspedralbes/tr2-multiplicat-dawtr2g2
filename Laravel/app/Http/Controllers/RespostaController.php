<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resposta;
use App\Models\Dificultat;
use Illuminate\Support\Facades\DB;


class RespostaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $preguntes = Resposta::all();

        return response()->json($preguntes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'resposta' => 'required',
            'dificultat_id' => 'required',
            'tema_id' => 'required'
        ]);

        $mostrar = Resposta::create($request->all());

        return response()->json($mostrar);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $mostrarResp = Resposta::find($id);

        return response()->json($mostrarResp);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $resposta = Resposta::find($id);
        $resposta->update($request->all());

        return response()->json($resposta);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $resposta = Resposta::find($id);

        if (!$resposta) {
            return response()->json(['message' => 'No s\'ha trobat cap resposta amb aquest id!'], 404);
        }

        $resposta->delete();

        return response()->json(['message' => 'Resposta eliminada']);
    }

    // MÈTODES DE LA PART D'ADMINISTRACIÓ
    public function adminIndex()
    {
        $respostes = Resposta::all();

        foreach ($respostes as $resposta) {
            $dificultat = Dificultat::where('id', $resposta->dificultat_id)->first();
            $tema = DB::table('temes')->where('id', $resposta->tema_id)->first();
    
            $resposta->dificultat = $dificultat ? $dificultat->nom_dificultat : null;
            $resposta->tema = $tema ? $tema->nom_tematica : null;
        }
        return view('respostes.index', ['respostes' => $respostes]);
    }

    public function adminStore(Request $request)
    {

        $request->validate([
            'resposta' => 'required',
            'tema_id' => 'required',
            'dificultat_id' => 'required'
        ]);

        $resposta = new Resposta;
        $resposta->resposta = $request->resposta;
        $resposta->tema_id = $request->tema_id;
        $resposta->dificultat_id = $request->dificultat_id;
        $resposta->save();

        return redirect()->route('view-afegir-resposta')->with('success', 'Resposta afegida correctament');
    }

    public function adminShow($id)
    {
        $resposta = Resposta::find($id);
        
        $dificultat = Dificultat::where('id', $resposta->dificultat_id)->first();
        $resposta->dificultat = $dificultat ? $dificultat->nom_dificultat : null;
        
        $dificultats = Dificultat::all();


        $resposta->dificultat = $dificultats->pluck('nom_dificultat');
        return view('respostes.modificar', ['resposta' => $resposta, 'dificultats' => $dificultats, 'dificultat' => $dificultat]);
    }

    public function adminUpdate(Request $request, $id)
    {

        $resposta = Resposta::find($id);
        $resposta->update($request->all());



        return redirect()->route('respostes', ['id' => $resposta->id])->with('success', 'La resposta a estat actualitzada correctament');
    }

    public function adminDelete($id)
    {
        $resposta = Resposta::find($id);
        $resposta->delete();

        return redirect()->route('respostes')->with('success', 'resposta eliminada correctament');
    }
}
