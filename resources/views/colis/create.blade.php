@extends('layouts.app')

@section('title', 'Nouveau Colis')

@section('content')
    <div class="max-w-md p-6 mx-auto bg-white rounded-lg shadow-lg">
        <h2 class="mb-6 text-2xl font-bold text-gray-800">📦 Enregistrer un colis</h2>

        <!-- Si validation échoue, Laravel renvoie ici avec les erreurs -->
        @if ($errors->any())
            <div class="p-3 mb-4 text-red-700 bg-red-100 rounded">
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
                <label class="block mb-2 font-bold text-gray-700">Expéditeur</label>
                <input type="text" name="expediteur" value="{{ old('expediteur') }}" class="w-full p-2 border rounded" placeholder="Qui envoie ?">
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-bold text-gray-700">Destinataire</label>
                <input type="text" name="destinataire" value="{{ old('destinataire') }}" class="w-full p-2 border rounded" placeholder="Qui reçoit ?">
            </div>

            <div class="flex gap-4 mb-4">
                <div class="w-1/2">
                    <label class="block mb-2 font-bold text-gray-700">Départ</label>
                    <input type="text" name="ville_depart" value="{{ old('ville_depart') }}" class="w-full p-2 border rounded" placeholder="Ex: Douala">
                </div>
                <div class="w-1/2">
                    <label class="block mb-2 font-bold text-gray-700">Arrivée</label>
                    <input type="text" name="ville_arrivee" value="{{ old('ville_arrivee') }}" class="w-full p-2 border rounded" placeholder="Ex: Yaoundé">
                </div>
            </div>

            <div class="mb-6">
                <label class="block mb-2 font-bold text-gray-700">Poids (kg)</label>
                <input type="number" step="0.1" name="poids" value="{{ old('poids') }}" class="w-full p-2 border rounded" placeholder="0.0">
            </div>

            <button type="submit" class="w-full py-3 font-bold text-white transition bg-green-600 rounded hover:bg-green-700">
                Valider l'enregistrement
            </button>
        </form>
    </div>
@endsection
