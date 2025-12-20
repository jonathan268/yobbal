@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
        <h2 class="text-3xl font-bold text-blue-600 mb-4">Bienvenue sur Yobbal</h2>
        <p class="text-lg text-gray-700 mb-6">
            La solution la plus simple pour gérer vos livraisons sur tout le térritoire camerounais.
        </p>
        <a href="{{ route('colis.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-full font-bold hover:bg-blue-700 transition">
            Expédier un colis 🚀
        </a>
    </div>
@endsection
