<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppLaravel extends Model
{
    protected $table = 'app_laravel';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'domain',
        'license_id',
        'site_name'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }
} 