<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\Entreprise;
use App\Models\EnterpriseCategory;
use App\Mail\EntrepriseProspectMail;
use Exception;

class SendEmailEntreprise extends Command
{
    protected $signature = 'send:email:entreprise';
    protected $description = 'Envoie des emails aux entreprises avec status waiting_send';

    public function handle()
    {
        $entreprises = Entreprise::where('status', 'waiting_send')->get();

        foreach ($entreprises as $entreprise) {
            try {
                // Vérification du type de category_id (tableau ou JSON)
                $categories = is_array($entreprise->category_id) 
                    ? $entreprise->category_id 
                    : json_decode($entreprise->category_id, true);

                // Si json_decode échoue, on initialise $categories comme un tableau vide
                if ($categories === null) {
                    $categories = [];
                }

                $categoriesNoms = EnterpriseCategory::whereIn('id', $categories)->pluck('name')->toArray();

                $mailData = [
                    'name' => $entreprise->name,
                    'email' => $entreprise->email,
                    'categories' => $categoriesNoms,
                    'type' => $entreprise->type,
                    'first_name' => $entreprise->first_name ?? '',
                    'last_name' => $entreprise->last_name ?? '',
                ];

                // Envoi de l'email avec Mailable
                Mail::to($mailData['email'], $mailData['name'])
                    ->send(new EntrepriseProspectMail($mailData));

                // Sauvegarde dans la table entreprise_email
                DB::table('entreprise_email')->insert([
                    'email' => $entreprise->email,
                    'name' => $entreprise->name,
                    'entreprise_id' => $entreprise->id,
                    'mail_context' => view('emails.entreprise', $mailData)->render(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Mise à jour du statut
                $entreprise->update(['status' => 'send']);
                $this->info("Email envoyé à {$entreprise->email}");

            } catch (Exception $e) {
                $entreprise->update(['status' => 'error']);
                $this->error("Erreur pour {$entreprise->email} : " . $e->getMessage());
            }
        }

        return 0;
    }
}
