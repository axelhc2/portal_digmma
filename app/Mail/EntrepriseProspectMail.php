<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;


class EntrepriseProspectMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $categories;
    public $type;
    public $first_name;
    public $last_name;

    /**
     * Créer une nouvelle instance de message.
     *
     * @param  array  $mailData
     * @return void
     */
    public function __construct($mailData)
    {
        $this->name = $mailData['name'];
        $this->email = $mailData['email'];
        $this->categories = $mailData['categories'];
        $this->type = $mailData['type'];
        $this->first_name = $mailData['first_name'];
        $this->last_name = $mailData['last_name'];
    }

    /**
     * Obtenir l'enveloppe du message.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Proposition de services pour booster la visibilité de votre entreprise - Digmma',
            cc: [
                new Address('axel.chetail@digmma.fr', 'Axel Chetail'),
            ],
        );
    }

    /**
     * Obtenir la définition du contenu du message.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.entreprise', // Vue Blade qui génère le corps du mail
        );
    }

    /**
     * Obtenir les pièces jointes pour le message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
