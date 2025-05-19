<?php

namespace App\Http\Controllers;

use App\Models\EnterpriseCategory;
use Illuminate\Http\Request;

class EnterpriseCategoryController extends Controller
{
    public function index()
    {
        $categories = EnterpriseCategory::all();
        return view('entreprise.groupe', compact('categories'));
    }

    public function update(Request $request, EnterpriseCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category->update([
            'name' => $request->name
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy(EnterpriseCategory $category)
    {
        $category->delete();
        return response()->json(['success' => true]);
    }
} 