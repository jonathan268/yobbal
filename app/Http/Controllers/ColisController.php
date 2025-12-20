<?php

namespace App\Http\Controllers;

use App\Models\Colis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ColisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $colis = Auth::user()?->colis ?? collect();
        return view('colis.index', compact('colis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('colis.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'expediteur|required|string|max:255',
            'destinataire|required|string|max:255',
            'ville_depart|required|string|',
            'ville_arrivee|required|string',
            'poids|required|numeric|min:0.1',
        ]);

       $request->user()->colis()->create($request->save()->all());

        return redirect()->route('colis.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Colis $colis)
    {
        if ($colis->user_id !== auth()->id()) {
        abort(403);
    }

    return view('colis.edit', compact('colis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Colis $colis)
    {
         if ($colis->user_id !== auth()->id()) {
        abort(403);
    }

    $request->validate([
        'expediteur' => 'required|string|max:255',
        'destinataire' => 'required|string|max:255',
        'ville_depart' => 'required|string',
        'ville_arrivee' => 'required|string',
        'poids' => 'required|numeric|min:0.1',
    ]);

    // Mise à jour simple
    $colis->update($request->all());

    return redirect()->route('colis.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Colis $colis)
    {
         if ($colis->user_id !== auth()->id()) {
        abort(403);
    }

    $colis->delete();

    return redirect()->route('colis.index');
    }
}
