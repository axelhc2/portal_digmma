<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Entreprise;
use App\Models\EnterpriseCategory;

class CreateController extends Controller
{
    public function index()
    {
        $categories = EnterpriseCategory::all();
        return view('entreprise.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $existingName = Entreprise::where('name', $request->name)->first();
        if ($existingName) {
            return back()->withErrors(['name' => 'Ce nom d\'entreprise est déjà utilisé.'])->withInput();
        }

        $existingEmail = Entreprise::where('email', $request->email)->first();
        if ($existingEmail) {
            return back()->withErrors(['email' => 'Cet email de contact est déjà utilisé.'])->withInput();
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'category_id' => 'required|array',
            'type' => 'required|string|in:SAS/SASU,SARL,Micro/Entrepreneur',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);

        $entreprise = new Entreprise();
        $entreprise->name = $request->name;
        $entreprise->email = $request->email;
        $entreprise->status = 'waiting_send';
        $entreprise->category_id = $request->category_id;
        $entreprise->type = $request->type;
        $entreprise->first_name = $request->first_name;
        $entreprise->last_name = $request->last_name;
        $entreprise->save();

        return redirect()->route('entreprises.index')->with('success', 'Entreprise créée avec succès');
    }
} 