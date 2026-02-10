<?php

namespace App\Actions;

use App\Models\Banner;
use App\Services\Cache\BannerCacheService;
use Illuminate\Support\Facades\Storage;

class DeleteBanner
{
    public function __construct(protected BannerCacheService $cacheService) {}

    /**
     * Delete a banner.
     */
    public function execute(Banner $banner): void
    {
        if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
            Storage::disk('public')->delete($banner->image_path);
        }

        $banner->delete();

        $this->cacheService->clearCache();
    }
}
