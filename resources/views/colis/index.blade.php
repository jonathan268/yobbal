@extends('layouts.app')

@section('title', 'Liste des Colis')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h2 class="mb-6 text-3xl font-bold">📦 Colis en attente</h2>
        <p class="p-1 mt-5 mb-3 text-center text-white bg-green-600 rounded-md badge"> Colis disponible dans l'entrepot : {{ $colis->count() }}</p>

        <div class="grid gap-4 mt-1">

            @foreach ($colis as $c)
                <div class="flex items-center justify-between p-4 mt-4 bg-white rounded-lg shadow">
                    <div>
                        <h3 class="text-lg font-bold">{{ $c->expediteur }} → {{ $c->destinataire }}</h3>
                        <p class="text-gray-500">Trajet : {{ $c->ville_depart }} vers {{ $c->ville_arrivee }}</p>
                    </div>
                    <div class="text-right">
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">
                            {{ $c->poids }} kg
                        </span>
                        <p class="mt-1 text-sm text-gray-400">{{ $c->statut }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="py-6 mt-5">
            <a href="{{ route('colis.create') }}" class="px-6 py-3 font-bold text-white transition bg-blue-600 rounded-md hover:bg-blue-700">
            Expédier un colis 🚀
        </a>
        </div>

    </div>
@endsection
