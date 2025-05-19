<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LogoutAllUsers extends Command
{
    protected $signature = 'users:logout-all';
    protected $description = 'Déconnecte tous les utilisateurs connectés';

    public function handle()
    {
        // Supprime toutes les sessions
        DB::table('sessions')->truncate();
        
        $this->info('Tous les utilisateurs ont été déconnectés avec succès.');
    }
} 