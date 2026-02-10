<?php

namespace App\Models;

use App\Enums\BannerStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    /** @use HasFactory<\Database\Factories\BannerFactory> */
    use HasFactory;

    protected $fillable = [
        'uuid',
        'title',
        'image_path',
        'target_url',
        'link_text',
        'placement',
        'status',
    ];

    protected $casts = [
        'status' => BannerStatus::class,
    ];

    // If needed in the path, use the trait. But simple uuid generation in creation is fine.
    protected static function booted()
    {
        static::creating(function ($banner) {
            $banner->uuid = \Illuminate\Support\Str::uuid();
        });
    }
}
