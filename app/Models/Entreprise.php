<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    use HasFactory;

    protected $table = 'entreprises';

    protected $fillable = [
        'name',
        'email',
        'status',
        'category_id',
        'type',
        'first_name',
        'last_name'
    ];

    protected $casts = [
        'category_id' => 'array',
    ];
    

    public function category()
    {
        return $this->belongsTo(EnterpriseCategory::class, 'category_id');
    }
} 