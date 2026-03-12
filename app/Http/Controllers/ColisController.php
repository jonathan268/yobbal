<?php

namespace App\Http\Controllers;

use App\Models\Colis;
use Illuminate\Http\Request;

class ColisController extends Controller
{
    /**
     * Liste tous les colis de l'utilisateur connecté.
     */
    public function index()
    {
      $colis = Colis::where('user_id', auth()->id())->latest()->get();

        return view('colis.index', compact('colis'));
    }

    /**
     * Affiche le formulaire de création d'un colis.
     */
    public function create()
    {
        return view('colis.create');
    }

    /**
     * Enregistre un nouveau colis en base.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'expediteur'   => 'required|string|max:255',
            'destinataire' => 'required|string|max:255',
            'ville_depart' => 'required|string|max:255',
            'ville_arrivee'=> 'required|string|max:255',
            'poids'        => 'required|numeric|min:0',
        ]);

        // Statut par défaut + utilisateur connecté
        $validated['statut']  = 'en_attente';
        $validated['user_id'] = auth()->id();

        Colis::create($validated);

        return redirect()
            ->route('colis.index')
            ->with('success', 'Colis enregistré avec succès !');
    }

    /**
     * Affiche les détails d'un colis spécifique.
     */
    public function show(Colis $colis)
    {
        abort_if($colis->user_id !== auth()->id(), 403);
    return view('colis.show', compact('colis'));
    }

    /**
     * Affiche le formulaire de modification d'un colis.
     */
    public function edit(Colis $colis)
    {
        return view('colis.edit', compact('colis'));
    }

    /**
     * Met à jour un colis en base.
     */
    public function update(Request $request, Colis $colis)
    {
        $validated = $request->validate([
            'expediteur'   => 'required|string|max:255',
            'destinataire' => 'required|string|max:255',
            'ville_depart' => 'required|string|max:255',
            'ville_arrivee'=> 'required|string|max:255',
            'poids'        => 'required|numeric|min:0',
            'statut'       => 'required|in:en_attente,en_transit,livre,retourne',
        ]);

        $colis->update($validated);

        return redirect()
            ->route('colis.show', $colis)
            ->with('success', 'Colis mis à jour avec succès !');
    }

    /**
     * Supprime un colis.
     */
    public function destroy(Colis $colis)
    {
        $colis->delete();

        return redirect()
            ->route('colis.index')
            ->with('success', 'Colis supprimé.');
    }
}
