<?php

namespace App\Http\Controllers;

use App\Models\skins;
use Illuminate\Http\Request;

class SkinsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $skins = skins::all();
        $skins = skins::whereNotIn('id', [1, 2,15,16,17,18])->get();
        return response()->json($skins);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
    public function show(skins $skins)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(skins $skins)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, skins $skins)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(skins $skins)
    {
        //
    }
}
