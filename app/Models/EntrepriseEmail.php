<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntrepriseEmail extends Model
{
    use HasFactory;

    protected $table = 'entreprise_emails';

    protected $fillable = [
        'email',
        'name',
        'entreprise_id',
        'mail_context'
    ];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }
} 