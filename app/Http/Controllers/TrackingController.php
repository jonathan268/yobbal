<?php

namespace App\Http\Controllers;

use App\Models\Colis;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    /**
     * Page de résultat de tracking (accessible sans connexion).
     * URL : /track?n=YBL-000001
     */
    public function index(Request $request)
    {
        $numero = $request->query('n');
        $colis  = null;
        $error  = null;

        if ($numero) {
            // Format attendu : YBL-000001 → on extrait l'ID
            if (preg_match('/YBL-(\d+)/i', $numero, $matches)) {
                $id    = (int) $matches[1];
                $colis = Colis::find($id);
            }

            if (!$colis) {
                $error = "Aucun colis trouvé pour le numéro « {$numero} ». Vérifiez le numéro et réessayez.";
            }
        }

        return view('tracking.show', compact('colis', 'numero', 'error'));
    }
}
