<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Resposta;


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

        $mostrar=Resposta::create($request->all());

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
}
