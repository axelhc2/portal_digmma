<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function update(Request $request, $id)
    {
        try {
            $entreprise = Entreprise::findOrFail($id);
            
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'category_id' => 'required|array',
                'type' => 'required|string|in:SAS/SASU,SARL,Micro/Entrepreneur',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
            ]);

            $entreprise->name = $request->name;
            $entreprise->email = $request->email;
            $entreprise->category_id = $request->category_id;
            $entreprise->type = $request->type;
            $entreprise->first_name = $request->first_name;
            $entreprise->last_name = $request->last_name;
            $entreprise->save();

            return response()->json([
                'success' => true, 
                'message' => 'Entreprise modifiée avec succès',
                'entreprise' => [
                    'id' => $entreprise->id,
                    'nom_entreprise' => $entreprise->name,
                    'email' => $entreprise->email,
                    'categorie' => implode(', ', $entreprise->category_id),
                    'type' => $entreprise->type,
                    'prenom' => $entreprise->first_name,
                    'nom_famille' => $entreprise->last_name,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Erreur lors de la modification de l\'entreprise'
            ], 500);
        }
    }
} 