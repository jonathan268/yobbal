@extends('layouts.app')

@section('title', 'Nouveau Colis')

@section('content')
    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">📦 Enregistrer un colis</h2>

        <!-- Si validation échoue, Laravel renvoie ici avec les erreurs -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('colis.store') }}" method="POST">
            @csrf <!-- LE TAMPON DE SÉCURITÉ OBLIGATOIRE -->

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Expéditeur</label>
                <input type="text" name="expediteur" class="w-full border p-2 rounded" placeholder="Qui envoie ?" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Destinataire</label>
                <input type="text" name="destinataire" class="w-full border p-2 rounded" placeholder="Qui reçoit ?" required>
            </div>

            <div class="flex gap-4 mb-4">
                <div class="w-1/2">
                    <label class="block text-gray-700 font-bold mb-2">Départ</label>
                    <input type="text" name="ville_depart" class="w-full border p-2 rounded" placeholder="Ex: Douala">
                </div>
                <div class="w-1/2">
                    <label class="block text-gray-700 font-bold mb-2">Arrivée</label>
                    <input type="text" name="ville_arrivee" class="w-full border p-2 rounded" placeholder="Ex: Yaoundé">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Poids (kg)</label>
                <input type="number" step="0.1" name="poids" class="w-full border p-2 rounded" placeholder="0.0">
            </div>

            <button type="submit" class="w-full bg-green-600 text-white font-bold py-3 rounded hover:bg-green-700 transition">
                Valider l'enregistrement
            </button>
        </form>
    </div>
@endsection
