<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'p:user:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Créer un nouvel utilisateur avec prénom, nom, email, mot de passe, statut et rôle administrateur';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $firstName = $this->ask('Prénom');
        
        $lastName = $this->ask('Nom');
        
        $email = $this->ask('Email');
        
        // Valider l'email
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|email|unique:users,email',
        ]);
        
        if ($validator->fails()) {
            $this->error('Email invalide ou déjà utilisé.');
            return 1;
        }
        
        $password = $this->secret('Mot de passe');
        
        // Demander le statut avec validation
        do {
            $statusLetter = strtoupper($this->ask('Statut (A = active, I = inactive, C = closed)'));
        } while (!in_array($statusLetter, ['A', 'I', 'C']));
        
        // Convertir la lettre en statut complet
        $status = match($statusLetter) {
            'A' => 'active',
            'I' => 'inactive',
            'C' => 'closed',
            default => $statusLetter
        };
        
        // Demander si l'utilisateur est administrateur
        $isAdmin = $this->confirm('L\'utilisateur est-il administrateur?', false);
        
        // Créer l'utilisateur
        $user = new User();
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->status = $status;
        $user->admin = $isAdmin ? 'yes' : 'no';
        $user->save();
        
        $this->info('Utilisateur créé avec succès!');
        
        $this->table(
            ['ID', 'Prénom', 'Nom', 'Email', 'Statut', 'Admin'],
            [[$user->id, $user->first_name, $user->last_name, $user->email, $user->status, $user->admin]]
        );
        
        return 0;
    }
}
