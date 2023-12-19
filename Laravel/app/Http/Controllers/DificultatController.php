<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dificultat;

class DificultatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getDamage(string $id)
    {
        $dif = Dificultat::find($id);
        return response($dif->punts_damage);
    }
}
