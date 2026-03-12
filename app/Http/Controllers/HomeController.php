<?php

namespace App\Http\Controllers;

use App\Models\Colis;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $totalColis      = Colis::where('user_id', $userId)->count();
        $colisEnTransit  = Colis::where('user_id', $userId)->where('statut', 'en_transit')->count();
        $colisLivres     = Colis::where('user_id', $userId)->where('statut', 'livre')->count();
        $colisEnAttente  = Colis::where('user_id', $userId)->where('statut', 'en_attente')->count();

        // 5 derniers colis pour le tableau du dashboard
        $recentColis = Colis::where('user_id', $userId)->latest()->take(5)->get();

        return view('home', compact(
            'totalColis',
            'colisEnTransit',
            'colisLivres',
            'colisEnAttente',
            'recentColis'
        ));
    }
}
