<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntrepriseCategory extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Obtenir les entreprises associées à cette catégorie.
     */
    public function entreprises()
    {
        return $this->hasMany(Entreprise::class, 'category_id');
    }
} 