<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    public function destroy($id)
    {
        try {
            $entreprise = Entreprise::findOrFail($id);
            $entreprise->delete();
            return response()->json(['success' => true, 'message' => 'Entreprise supprimée avec succès']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression de l\'entreprise'], 500);
        }
    }
} 