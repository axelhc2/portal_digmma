<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Auth\CreateA2FController;
use App\Http\Controllers\Auth\A2FController;
use App\Http\Middleware\RequireA2F;
use App\Http\Controllers\EnterpriseCategoryController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\LaravelController;

Route::get('/', function () {
    return view('index');
});
Route::get('/api/verif/license/{license}', [LicenseController::class, 'verifyLicense'])->name('licenses.verify');
// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
});

Route::middleware(['auth', RequireA2F::class])->group(function () {
    Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
    Route::get('/', function () {
        return view('index');
    });
    
    Route::post('/admin/logout-all', function () {
        Artisan::call('users:logout-all');
        return redirect()->back()->with('success', 'Tous les utilisateurs ont été déconnectés.');
    })->middleware(['admin'])->name('admin.logout-all');

    Route::get('two-factor/create', [CreateA2FController::class, 'show'])->name('two-factor.create');
    Route::get('two-factor', [A2FController::class, 'show'])->name('two-factor');
    Route::get('/entreprise', [App\Http\Controllers\Entreprise\ViewController::class, 'index'])->name('entreprises.index');
    
    // Routes pour la gestion des entreprises
    Route::get('/entreprise/create', [App\Http\Controllers\Entreprise\CreateController::class, 'index'])->name('entreprises.create');
    Route::post('/entreprise/store', [App\Http\Controllers\Entreprise\CreateController::class, 'store'])->name('entreprises.store');
    Route::delete('/entreprise/{id}', [App\Http\Controllers\Entreprise\DeleteController::class, 'destroy'])->name('entreprises.destroy');
    Route::put('/entreprise/{id}', [App\Http\Controllers\Entreprise\UpdateController::class, 'update'])->name('entreprises.update');

    Route::post('two-factor', [A2FController::class, 'verify'])->name('two-factor.verify');
    Route::post('/auth/a2f', [CreateA2FController::class, 'verify'])->name('create.a2f.user');

    Route::get('/license/views', [EnterpriseCategoryController::class, 'index'])->name('license.views');


    Route::get('/entreprise/groupe', [EnterpriseCategoryController::class, 'index'])->name('entreprise.groupe');
    Route::put('/entreprise/categories/{category}', [EnterpriseCategoryController::class, 'update'])->name('entreprise.categories.update');
    Route::delete('/entreprise/categories/{category}', [EnterpriseCategoryController::class, 'destroy'])->name('entreprise.categories.destroy');

    // Routes pour les catégories d'entreprises
    Route::prefix('entreprise')->group(function () {
        Route::get('/categories', [App\Http\Controllers\Entreprise\CategoryController::class, 'index'])->name('entreprise.categories.index');
        Route::post('/categories', [App\Http\Controllers\Entreprise\CategoryController::class, 'store'])->name('entreprise.categories.store');
        Route::put('/categories/{category}', [App\Http\Controllers\Entreprise\CategoryController::class, 'update'])->name('entreprise.categories.update');
        Route::delete('/categories/{category}', [App\Http\Controllers\Entreprise\CategoryController::class, 'destroy'])->name('entreprise.categories.destroy');
    });

    // Routes pour la gestion des licences
    Route::resource('licenses', LicenseController::class);
    Route::post('/licenses/{license}/toggle-status', [LicenseController::class, 'toggleStatus'])->name('licenses.toggle-status');

    // Routes pour Laravel
    Route::get('/laravel', [LaravelController::class, 'online'])->name('laravel.index');
    Route::get('/laravel/create', [LaravelController::class, 'create'])->name('laravel.create');
    Route::post('/laravel', [LaravelController::class, 'store'])->name('laravel.store');
    Route::get('/laravel/{id}/edit', [LaravelController::class, 'edit'])->name('laravel.edit');
    Route::put('/laravel/{id}', [LaravelController::class, 'update'])->name('laravel.update');
    Route::delete('/laravel/{id}', [LaravelController::class, 'destroy'])->name('laravel.destroy');
});

