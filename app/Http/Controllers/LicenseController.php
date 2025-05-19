<?php

namespace App\Http\Controllers;

use App\Models\License;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LicenseController extends Controller
{
    public function index()
    {
        $licenses = License::all();
        return view('licenses.index', compact('licenses'));
    }

    public function create()
    {
        return view('licenses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'license' => 'required|string|unique:licenses,license',
            'domains' => 'required|array|min:1',
            'domains.*' => 'required|string',
            'ips' => 'required|array|min:1',
            'ips.*' => 'required|string|ip',
            'lifetime' => 'nullable|in:1',
            'duration_value' => 'required_unless:lifetime,1|nullable|integer|min:1',
            'duration_unit' => 'required_unless:lifetime,1|nullable|in:days,months,years',
        ], [
            'license.required' => 'La licence est requise',
            'license.unique' => 'Cette licence existe déjà',
            'domains.required' => 'Au moins un domaine est requis',
            'domains.min' => 'Au moins un domaine est requis',
            'domains.*.required' => 'Le domaine est requis',
            'ips.required' => 'Au moins une adresse IP est requise',
            'ips.min' => 'Au moins une adresse IP est requise',
            'ips.*.required' => 'L\'adresse IP est requise',
            'ips.*.ip' => 'L\'adresse IP n\'est pas valide',
            'lifetime.in' => 'Le type de licence n\'est pas valide',
            'duration_value.required_unless' => 'La durée est requise pour une licence temporaire',
            'duration_value.integer' => 'La durée doit être un nombre entier',
            'duration_value.min' => 'La durée doit être supérieure à 0',
            'duration_unit.required_unless' => 'L\'unité de durée est requise pour une licence temporaire',
            'duration_unit.in' => 'L\'unité de durée n\'est pas valide',
        ]);

        $isLifetime = $request->has('lifetime');
        $expirationDate = null;
        
        if (!$isLifetime) {
            $value = (int) $request->duration_value;
            $unit = $request->duration_unit;
            
            $expirationDate = Carbon::now();
            switch ($unit) {
                case 'days':
                    $expirationDate->addDays($value);
                    break;
                case 'months':
                    $expirationDate->addMonths($value);
                    break;
                case 'years':
                    $expirationDate->addYears($value);
                    break;
            }
        }

        $license = License::create([
            'license' => $request->license,
            'domain' => $request->domains,
            'ip' => $request->ips,
            'lifetime' => $isLifetime,
            'expiration_date' => $expirationDate,
            'status' => 'active'
        ]);

        return redirect()->route('licenses.index')
            ->with('success', 'La licence a été créée avec succès');
    }

    public function edit(License $license)
    {
        return view('licenses.edit', compact('license'));
    }

    public function update(Request $request, License $license)
    {
        $request->validate([
            'license' => 'required|string|unique:licenses,license,' . $license->id,
            'domains' => 'required|array|min:1',
            'domains.*' => 'required|string',
            'ips' => 'required|array|min:1',
            'ips.*' => 'required|string|ip',
            'lifetime' => 'nullable|in:1',
            'duration_value' => 'required_unless:lifetime,1|nullable|integer|min:1',
            'duration_unit' => 'required_unless:lifetime,1|nullable|in:days,months,years',
            'status' => 'required|in:active,suspended',
        ], [
            'license.required' => 'La licence est requise',
            'license.unique' => 'Cette licence existe déjà',
            'domains.required' => 'Au moins un domaine est requis',
            'domains.min' => 'Au moins un domaine est requis',
            'domains.*.required' => 'Le domaine est requis',
            'ips.required' => 'Au moins une adresse IP est requise',
            'ips.min' => 'Au moins une adresse IP est requise',
            'ips.*.required' => 'L\'adresse IP est requise',
            'ips.*.ip' => 'L\'adresse IP n\'est pas valide',
            'lifetime.in' => 'Le type de licence n\'est pas valide',
            'duration_value.required_unless' => 'La durée est requise pour une licence temporaire',
            'duration_value.integer' => 'La durée doit être un nombre entier',
            'duration_value.min' => 'La durée doit être supérieure à 0',
            'duration_unit.required_unless' => 'L\'unité de durée est requise pour une licence temporaire',
            'duration_unit.in' => 'L\'unité de durée n\'est pas valide',
            'status.required' => 'Le statut est requis',
            'status.in' => 'Le statut n\'est pas valide',
        ]);

        $isLifetime = $request->has('lifetime');
        $expirationDate = null;
        
        if (!$isLifetime) {
            $value = (int) $request->duration_value;
            $unit = $request->duration_unit;
            
            $expirationDate = Carbon::now();
            switch ($unit) {
                case 'days':
                    $expirationDate->addDays($value);
                    break;
                case 'months':
                    $expirationDate->addMonths($value);
                    break;
                case 'years':
                    $expirationDate->addYears($value);
                    break;
            }
        }

        $license->update([
            'license' => $request->license,
            'domain' => $request->domains,
            'ip' => $request->ips,
            'lifetime' => $isLifetime,
            'expiration_date' => $expirationDate,
            'status' => $request->status,
        ]);

        return redirect()->route('licenses.index')
            ->with('success', 'La licence a été mise à jour avec succès');
    }

    public function destroy(License $license)
    {
        $license->delete();
        return redirect()->route('licenses.index')
            ->with('success', 'La licence a été supprimée avec succès');
    }

    public function toggleStatus(License $license)
    {
        $license->update([
            'is_active' => !$license->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Le statut de la licence a été mis à jour avec succès.',
            'is_active' => $license->is_active
        ]);
    }

    public function verifyLicense($license)
    {
        $license = License::where('license', $license)->first();

        if (!$license) {
            return response()->json([
                'success' => false,
                'message' => 'Licence non trouvée'
            ], 404);
        }

        $response = [
            'success' => true,
            'license' => $license->license,
            'status' => $license->status,
            'domains' => $license->domain,
            'ips' => $license->ip,
            'lifetime' => $license->lifetime
        ];

        if (!$license->lifetime) {
            $now = Carbon::now();
            $expiration = Carbon::parse($license->expiration_date);
            
            $response['expiration'] = [
                'date' => $expiration->format('Y-m-d H:i:s'),
                'is_expired' => $now->greaterThan($expiration),
                'remaining_days' => $now->diffInDays($expiration, false)
            ];
        }

        return response()->json($response);
    }
} 