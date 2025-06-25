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
use Illuminate\Support\Facades\Mail;

class LaravelController extends Controller
{
    public function index(): View
    {
        Log::info('Affichage de la liste des applications Laravel');
        $apps = AppLaravel::with('license')->get();
        Log::info('Applications récupérées', ['count' => $apps->count()]);
        return view('laravel.index', compact('apps'));
    }

    public function create(): View
    {
        Log::info('Affichage du formulaire de création d\'application Laravel');
        return view('laravel.create');
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            Log::info('Début de la création d\'une nouvelle application Laravel', [
                'request_data' => $request->except(['_token']),
                'headers' => [
                    'content_type' => $request->header('Content-Type'),
                    'accept' => $request->header('Accept')
                ]
            ]);

            try {
                Log::info('Validation des données de la requête');
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
                Log::info('Données validées avec succès', ['validated_data' => $validated]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                Log::error('Erreur de validation', [
                    'errors' => $e->errors(),
                    'request_data' => $request->all()
                ]);
                return redirect()->route('laravel.index')
                    ->with('error', 'Erreur de validation des données.');
            }

            $result = DB::transaction(function () use ($request) {
                Log::info('Début de la transaction de création');
                
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
                    'domain' => array_map(function ($domain) {
                        $domain = strval($domain);
                        return str_starts_with($domain, 'https://') ? $domain : 'https://' . $domain;
                    }, (array) $request->domains),
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

                $response = Http::timeout(180)->withHeaders([
                    'token-auth' => '8f7c2e2e-1f6e-42f4-9f1b-965f9f4d6ab9',
                    'token-secret' => 'b64fdf1c-1e96-4ac4-83fc-b9f78e2c38c1',
                    'Content-Type' => 'application/json'
                ])->post('http://31.59.234.92:3000/auth', [
                    'site_name' => $appLaravel->site_name,
                    'user_first_name' => $appLaravel->first_name,
                    'user_last_name' => $appLaravel->last_name,
                    'license_key' => $license->license,
                    'domaine' => $appLaravel->domain
                ]);

                Log::info('Réponse reçue du serveur d\'authentification', [
                    'status' => $response->status(),
                    'body' => $response->json()
                ]);

                return [
                    'license' => $license,
                    'app' => $appLaravel
                ];
            });

            // Envoi de l'email de confirmation de création
            Log::info('Envoi de l\'email de confirmation de création à l\'utilisateur', [
                'email' => $result['app']->email,
                'site_name' => $result['app']->site_name
            ]);
            Mail::to($result['app']->email)->send(new \App\Mail\LaravelAppCreatedMail($result['app'], $result['app']->domain));

            return redirect()->route('laravel.index')
                ->with('success', 'Application Laravel créée avec succès');

        } catch (Throwable $e) {
            Log::error('Erreur lors de la création', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('laravel.index')
                ->with('error', 'Une erreur est survenue lors de la création.');
        }
    }

    public function online(): View
    {
        try {
            Log::info('Vérification du statut en ligne des applications');
            $localApps = AppLaravel::with('license')->get();
            Log::info('Applications locales récupérées', ['count' => $localApps->count()]);
            
            $appsWithStatus = $localApps->map(function ($app) {
                Log::info('Vérification du statut pour l\'application', ['domain' => $app->domain]);
                try {
                    $response = Http::timeout(10)->get("https://{$app->domain}");
                    $status = $response->successful();
                    Log::info('Statut HTTPS vérifié', [
                        'domain' => $app->domain,
                        'status' => $status
                    ]);
                } catch (\Exception $e) {
                    Log::warning('Échec de la vérification HTTPS', [
                        'domain' => $app->domain,
                        'error' => $e->getMessage()
                    ]);
                    try {
                        $response = Http::timeout(10)->get("http://{$app->domain}");
                        $status = $response->successful();
                        Log::info('Statut HTTP vérifié', [
                            'domain' => $app->domain,
                            'status' => $status
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Échec de la vérification HTTP', [
                            'domain' => $app->domain,
                            'error' => $e->getMessage()
                        ]);
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

            Log::info('Vérification des statuts terminée');
            return view('laravel.online', [
                'apps' => $appsWithStatus,
                'error' => null
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la vérification des statuts', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return view('laravel.online', [
                'apps' => collect(),
                'error' => 'Impossible de récupérer les applications. Veuillez réessayer plus tard.'
            ]);
        }
    }

    public function edit($id): View
    {
        Log::info('Affichage du formulaire d\'édition', ['app_id' => $id]);
        $app = AppLaravel::with('license')->findOrFail($id);
        Log::info('Application trouvée', ['app' => $app->toArray()]);
        return view('laravel.edit', compact('app'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        Log::info('Début de la mise à jour de l\'application', [
            'app_id' => $id,
            'request_data' => $request->all()
        ]);

        $app = AppLaravel::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'site_name' => 'required|string|max:255',
        ]);

        $domain = strtolower(str_replace(' ', '', $request->site_name)) . '.digmma.site';
        Log::info('Nouveau domaine généré', ['domain' => $domain]);

        $app->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'site_name' => $request->site_name,
            'domain' => $domain,
        ]);

        Log::info('Application mise à jour avec succès', ['app' => $app->toArray()]);
        return redirect()->route('laravel.index')
            ->with('success', 'Application Laravel mise à jour avec succès.');
    }

    public function destroy($id): RedirectResponse
    {
        Log::info('Début de la suppression de l\'application', ['app_id' => $id]);
        $app = AppLaravel::findOrFail($id);
        
        try {
            Log::info('Envoi de la requête de suppression au serveur distant', ['domain' => $app->domain]);
            $response = Http::withHeaders([
                'token-auth' => '8f7c2e2e-1f6e-42f4-9f1b-965f9f4d6ab9',
                'token-secret' => 'b64fdf1c-1e96-4ac4-83fc-b9f78e2c38c1',
                'Content-Type' => 'application/json'
            ])->delete('http://31.59.234.92:3000/delete', [
                'domaine' => $app->domain
            ]);

            Log::info('Suppression locale de l\'application et de sa licence');
            DB::transaction(function () use ($app) {
                if ($app->license) {
                    $app->license->delete();
                    Log::info('Licence supprimée', ['license_id' => $app->license->id]);
                }
                $app->delete();
                Log::info('Application supprimée', ['app_id' => $app->id]);
            });

            Log::info('Suppression terminée avec succès');
            return redirect()->route('laravel.index')
                ->with('success', 'Application Laravel et sa licence supprimées avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            Log::info('Tentative de suppression locale uniquement');
            DB::transaction(function () use ($app) {
                if ($app->license) {
                    $app->license->delete();
                    Log::info('Licence supprimée localement', ['license_id' => $app->license->id]);
                }
                $app->delete();
                Log::info('Application supprimée localement', ['app_id' => $app->id]);
            });

            return redirect()->route('laravel.index')
                ->with('success', 'Application supprimée localement, mais une erreur est survenue lors de la suppression sur le serveur distant.');
        }
    }
} 