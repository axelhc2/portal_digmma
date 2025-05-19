<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    protected $fillable = [
        'license',
        'domain',
        'ip',
        'status',
        'lifetime',
        'expiration_date',
    ];

    protected $casts = [
        'domain' => 'array',
        'ip' => 'array',
        'lifetime' => 'boolean',
        'expiration_date' => 'datetime',
    ];

    public static function generateLicenseKey()
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $segments = [];
        
        for ($i = 0; $i < 3; $i++) {
            $segment = '';
            for ($j = 0; $j < 6; $j++) {
                $segment .= $chars[rand(0, strlen($chars) - 1)];
            }
            $segments[] = $segment;
        }
        
        return 'digmma-' . implode('-', $segments);
    }
} 