<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Entreprise;
use App\Models\EnterpriseCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ViewController extends Controller
{
    public function index()
    {
        try {
            $entreprises = Entreprise::select(
                'entreprises.id',
                'entreprises.name as nom_entreprise',
                'entreprises.email',
                'entreprises.status',
                'entreprises.category_id',
                'entreprises.type',
                'entreprises.first_name as prenom',
                'entreprises.last_name as nom_famille',
                'entreprises.created_at as date_ajout',
                'entreprises.updated_at'
            )
            ->get()
            ->map(function($entreprise) {
                $date = Carbon::parse($entreprise->date_ajout);
                
                $categoryIds = $entreprise->category_id ?? [];

                            $categories = EnterpriseCategory::whereIn('id', $categoryIds)->get();
                $categorieNames = $categories->pluck('name')->implode(', ');
                
                return [
                    'id' => $entreprise->id,
                    'nom_entreprise' => $entreprise->nom_entreprise ?? '',
                    'email' => $entreprise->email ?? '',
                    'status' => $entreprise->status ?? 'waiting_send',
                    'categorie' => $categorieNames,
                    'category_ids' => $categoryIds,
                    'type' => $entreprise->type ?? '',
                    'prenom' => $entreprise->prenom ?? '',
                    'nom_famille' => $entreprise->nom_famille ?? '',
                    'date_ajout' => $date->format('d/m/Y à H:i:s'),
                ];
            });

            $entreprises = collect($entreprises);
            $categories = EnterpriseCategory::select('id', 'name')->get();

            return view('entreprise.view', compact('entreprises', 'categories'));
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des entreprises: ' . $e->getMessage());
            return view('entreprise.view', ['entreprises' => collect([]), 'categories' => collect([])]);
        }
    }
} 