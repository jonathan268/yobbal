@extends('layouts.app')

@section('title', 'Modifier le Colis')

@section('content')
    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-blue-600">✏️ Modifier le colis #{{ $colis->id }}</h2>

        <form action="{{ route('colis.update', $colis) }}" method="POST">
            @csrf
            @method('PUT') <!-- L'astuce pour simuler une requête PUT -->

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Expéditeur</label>
                <input type="text" name="expediteur" value="{{ $colis->expediteur }}" class="w-full border p-2 rounded" required>
            </div>

            <!-- Répétez pour les autres champs (destinataire, villes, poids)... -->
            <!-- Pour faire court, je ne mets que l'essentiel ici -->

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded hover:bg-blue-700 transition">
                Mettre à jour
            </button>
        </form>
    </div>
@endsection
