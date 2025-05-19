<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnterpriseCategory extends Model
{
    use HasFactory;

    protected $table = 'enterprise_categories';

    protected $fillable = [
        'name'
    ];

    public function entreprises()
    {
        return $this->hasMany(Entreprise::class, 'category_id');
    }
} 