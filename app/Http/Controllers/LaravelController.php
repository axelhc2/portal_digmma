<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Models\AppLaravel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Throwable;

class LaravelController extends Controller
{
    public function index(): View
    {
        $apps = AppLaravel::with('license')->get();
        return view('laravel.index', compact('apps'));
    }

    public function create(): View
    {
        return view('laravel.create');
    }

    public function store(Request $request): JsonResponse
    {
        try {
            try {
                $validated = $request->validate([
                    'license' => ['required', 'string', 'unique:licenses,license'],
                    'domains' => ['required', 'array', 'min:1'],
                    'domains.*' => ['required', 'string'],
                    'ips' => ['required', 'array', 'min:1'],
                    'ips.*' => ['required', 'string', 'ip'],
                    'lifetime' => ['nullable', 'in:1'],
                    'duration_value' => ['required_unless:lifetime,1', 'nullable', 'integer', 'min:1'],
                    'duration_unit' => ['required_unless:lifetime,1', 'nullable', 'in:days,months,years'],
                    'first_name' => ['required', 'string', 'max:255'],
                    'last_name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'email', 'max:255', 'unique:app_laravel,email'],
                    'domain' => ['required', 'string', 'max:255'],
                    'site_name' => ['required', 'string', 'max:255'],
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                return response()->json([
                    'message' => 'Erreur de validation',
                    'errors' => $e->errors()
                ], 422);
            }

            $result = DB::transaction(function () use ($request) {
                $isLifetime = $request->boolean('lifetime');
                $expirationDate = null;
                
                if (!$isLifetime) {
                    $value = (int) $request->duration_value;
                    $unit = $request->duration_unit;
                    
                    $expirationDate = match($unit) {
                        'days' => now()->addDays($value),
                        'months' => now()->addMonths($value),
                        'years' => now()->addYears($value),
                        default => now(),
                    };
                }

                $license = License::create([
                    'license' => strval($request->license),
                    'domain' => array_map('strval', (array) $request->domains),
                    'ip' => array_map('strval', (array) $request->ips),
                    'lifetime' => $isLifetime,
                    'expiration_date' => $expirationDate,
                    'status' => 'active'
                ]);

                $appLaravel = AppLaravel::create([
                    'first_name' => strval($request->first_name),
                    'last_name' => strval($request->last_name),
                    'email' => strval($request->email),
                    'domain' => strval($request->domain),
                    'license_id' => $license->id,
                    'site_name' => strval($request->site_name)
                ]);

                return [
                    'license' => $license,
                    'app' => $appLaravel
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'La licence a été créée avec succès',
                'data' => $result
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la création de la licence',
                'error' => $e->getMessage(),
                'details' => config('app.debug') ? [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ] : null
            ], 500);
        }
    }

    public function online(): View
    {
        try {
            $localApps = AppLaravel::with('license')->get();
            
            $appsWithStatus = $localApps->map(function ($app) {
                try {
                    $response = Http::timeout(10)->get("https://{$app->domain}");
                    $status = $response->successful();
                } catch (\Exception $e) {
                    try {
                        $response = Http::timeout(10)->get("http://{$app->domain}");
                        $status = $response->successful();
                    } catch (\Exception $e) {
                        $status = false;
                    }
                }

                return [
                    'local_info' => $app,
                    'online_status' => [
                        'status' => $status ? 'En ligne' : 'Hors ligne',
                        'domain' => $app->domain,
                        'protocol' => $status ? ($response->effectiveUri()->getScheme() === 'https' ? 'HTTPS' : 'HTTP') : 'N/A'
                    ]
                ];
            });

            return view('laravel.online', [
                'apps' => $appsWithStatus,
                'error' => null
            ]);

        } catch (\Exception $e) {
            return view('laravel.online', [
                'apps' => collect(),
                'error' => 'Impossible de récupérer les applications. Veuillez réessayer plus tard.'
            ]);
        }
    }

    public function edit($id): View
    {
        $app = AppLaravel::with('license')->findOrFail($id);
        return view('laravel.edit', compact('app'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $app = AppLaravel::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'site_name' => 'required|string|max:255',
        ]);

        $domain = strtolower(str_replace(' ', '', $request->site_name)) . '.digmma.site';

        $app->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'site_name' => $request->site_name,
            'domain' => $domain,
        ]);

        return redirect()->route('laravel.index')
            ->with('success', 'Application Laravel mise à jour avec succès.');
    }

    public function destroy($id): RedirectResponse
    {
        $app = AppLaravel::findOrFail($id);
        
        try {
            $response = Http::withHeaders([
                'token-auth' => '8f7c2e2e-1f6e-42f4-9f1b-965f9f4d6ab9',
                'token-secret' => 'b64fdf1c-1e96-4ac4-83fc-b9f78e2c38c1',
                'Content-Type' => 'application/json'
            ])->delete('http://31.59.234.92:3000/delete', [
                'domaine' => $app->domain
            ]);

            DB::transaction(function () use ($app) {
                if ($app->license) {
                    $app->license->delete();
                }
                $app->delete();
            });

            return redirect()->route('laravel.index')
                ->with('success', 'Application Laravel et sa licence supprimées avec succès.');

        } catch (\Exception $e) {
            DB::transaction(function () use ($app) {
                if ($app->license) {
                    $app->license->delete();
                }
                $app->delete();
            });

            return redirect()->route('laravel.index')
                ->with('success', 'Application supprimée localement, mais une erreur est survenue lors de la suppression sur le serveur distant.');
        }
    }
} 