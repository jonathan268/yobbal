@extends('layouts.app')

@section('title', 'Modifier le Colis')

@section('content')
    <div class="max-w-md p-6 mx-auto bg-white rounded-lg shadow-lg">
        <h2 class="mb-6 text-2xl font-bold text-blue-600">✏️ Modifier le colis #{{ $colis->id }}</h2>

        <form action="{{ route('colis.update', $colis) }}" method="POST">
            @csrf
            @method('PUT') <!-- L'astuce pour simuler une requête PUT -->

            <div class="mb-4">
                <label class="block mb-2 font-bold text-gray-700">Expéditeur</label>
                <input type="text" name="expediteur" value="{{ $colis->expediteur }}" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block mb-2 font-bold text-gray-700">Expéditeur</label>
                <input type="text" name="destinataire" value="{{ $colis->destinataire }}" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block mb-2 font-bold text-gray-700">Expéditeur</label>
                <input type="text" name="ville_depart" value="{{ $colis->ville_depart }}" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block mb-2 font-bold text-gray-700">Expéditeur</label>
                <input type="text" name="ville_arrivee" value="{{ $colis->ville_arrivee }}" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block mb-2 font-bold text-gray-700">Expéditeur</label>
                <input type="text" name="poids" value="{{ $colis->poids }}" class="w-full p-2 border rounded" required>
            </div>

            <!-- Répétez pour les autres champs (destinataire, villes, poids)... -->
            <!-- Pour faire court, je ne mets que l'essentiel ici -->

            <button type="submit" class="w-full py-3 font-bold text-white transition bg-blue-600 rounded hover:bg-blue-700">
                Mettre à jour
            </button>
        </form>
    </div>
@endsection
