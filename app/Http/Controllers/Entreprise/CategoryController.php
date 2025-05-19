<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use App\Models\EnterpriseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Affiche la liste des catégories
     */
    public function index()
    {
        $categories = EnterpriseCategory::orderBy('name')->get();
        return view('entreprise.groupe', compact('categories'));
    }

    /**
     * Crée une nouvelle catégorie
     */
    public function store(Request $request)
    {
        // Vérifier si la requête est en JSON
        if (!$request->isJson()) {
            return response()->json([
                'success' => false,
                'message' => 'La requête doit être en format JSON.'
            ], 415);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:enterprise_categories,name'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Le nom de la catégorie est invalide ou existe déjà.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $category = EnterpriseCategory::create([
                'name' => $request->name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Catégorie créée avec succès',
                'category' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la création de la catégorie.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Met à jour une catégorie existante
     */
    public function update(Request $request, EnterpriseCategory $category)
    {
        // Vérifier si la requête est en JSON
        if (!$request->isJson()) {
            return response()->json([
                'success' => false,
                'message' => 'La requête doit être en format JSON.'
            ], 415);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:enterprise_categories,name,' . $category->id
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Le nom de la catégorie est invalide ou existe déjà.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $category->update([
                'name' => $request->name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Catégorie mise à jour avec succès',
                'category' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la mise à jour de la catégorie.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprime une catégorie
     */
    public function destroy(EnterpriseCategory $category)
    {
        try {
            // Vérifier si la catégorie est utilisée par des entreprises
            if ($category->entreprises()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer cette catégorie car elle est utilisée par des entreprises.'
                ], 422);
            }

            $category->delete();
            return response()->json([
                'success' => true,
                'message' => 'Catégorie supprimée avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la suppression de la catégorie.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 