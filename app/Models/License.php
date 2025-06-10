<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class License extends Model
{
    protected $fillable = [
        'license',
        'domain',
        'ip',
        'lifetime',
        'expiration_date',
        'status'
    ];

    protected $casts = [
        'domain' => 'array',
        'ip' => 'array',
        'lifetime' => 'boolean',
        'expiration_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function appLaravel(): HasOne
    {
        return $this->hasOne(AppLaravel::class);
    }
} 