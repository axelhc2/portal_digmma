<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Entreprise;
use App\Models\EnterpriseCategory;
use App\Models\EntrepriseEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendEmailEntrepriseCommand extends Command
{
    protected $signature = 'send:email:entreprise';
    protected $description = 'Envoie des emails aux entreprises avec le statut waiting_send';

    public function handle()
    {
        try {
            $entreprises = Entreprise::where('status', 'waiting_send')->get();

            foreach ($entreprises as $entreprise) {
                try {
                    $categories = EnterpriseCategory::whereIn('id', $entreprise->category_id)->get();
                    
                    $services = [];
                    foreach ($categories as $category) {
                        switch ($category->name) {
                            case 'Développement Web':
                                $services[] = "Développement Web : Création ou refonte de site internet (vitrine, boutique en ligne, application web), avec un design moderne et une navigation fluide.";
                                break;
                            case 'Audiovisuel':
                                $services[] = "Vidéos promotionnelles : Réalisation de vidéos sur mesure pour présenter votre entreprise, vos produits ou services de manière professionnelle.";
                                break;
                            case 'Graphisme':
                                $services[] = "Identité visuelle : Conception de logo, charte graphique, cartes de visite, flyers, bannières, etc., afin de renforcer la cohérence visuelle de votre marque.";
                                break;
                            case 'Impression':
                                $services[] = "Impressions : Impression de supports de communication tels que cartes de visite, flyers, roll-ups, banderoles et autres matériels.";
                                break;
                            case 'Marketing':
                                $services[] = "Conseils en marketing : Accompagnement pour optimiser votre présence en ligne, améliorer votre notoriété et booster votre chiffre d'affaires.";
                                break;
                        }
                    }

                    $salutation = $entreprise->type === 'Micro/Entrepreneur' 
                        ? "Bonjour {$entreprise->first_name} {$entreprise->last_name},"
                        : "Bonjour,";

                    $mailContent = view('emails.entreprise', [
                        'salutation' => $salutation,
                        'services' => $services
                    ])->render();

                    // Envoyer l'email
                    Mail::send([], [], function ($message) use ($entreprise, $mailContent) {
                        $message->to($entreprise->email)
                            ->subject('Proposition de services pour booster la visibilité de votre entreprise - Digmma')
                            ->setBody($mailContent, 'text/html');
                    });

                    // Enregistrer l'email envoyé
                    EntrepriseEmail::create([
                        'email' => $entreprise->email,
                        'name' => $entreprise->name,
                        'entreprise_id' => $entreprise->id,
                        'mail_context' => $mailContent
                    ]);

                    // Mettre à jour le statut
                    $entreprise->update(['status' => 'send']);

                    $this->info("Email envoyé avec succès à {$entreprise->email}");
                } catch (\Exception $e) {
                    Log::error("Erreur lors de l'envoi de l'email à {$entreprise->email}: " . $e->getMessage());
                    $entreprise->update(['status' => 'error']);
                    $this->error("Erreur lors de l'envoi de l'email à {$entreprise->email}");
                }
            }

            $this->info('Traitement terminé');
        } catch (\Exception $e) {
            Log::error("Erreur générale: " . $e->getMessage());
            $this->error('Une erreur est survenue lors du traitement');
        }
    }
} 