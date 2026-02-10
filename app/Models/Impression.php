<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Impression extends Model
{
    /** @use HasFactory<\Database\Factories\ImpressionFactory> */
    use HasFactory;

    protected $fillable = [
        'banner_id',
        'ip_address',
        'user_agent',
        'referer',
    ];
}
