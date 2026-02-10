<?php

namespace App\Actions;

use App\Enums\BannerStatus;
use App\Models\Banner;
use App\Services\Cache\BannerCacheService;
use Illuminate\Http\UploadedFile;

class CreateBanner
{
    public function __construct(protected BannerCacheService $cacheService) {}

    /**
     * Create a new banner.
     */
    public function execute(
        string $title,
        string $targetUrl,
        ?string $linkText,
        BannerStatus $status,
        UploadedFile $image
    ): Banner {
        // Store the image in the 'banners' directory on the 'public' disk
        $path = $image->store('banners', 'public');

        $banner = Banner::create([
            'title' => $title,
            'target_url' => $targetUrl,
            'link_text' => $linkText,
            'status' => $status,
            'image_path' => $path,
        ]);

        $this->cacheService->clearCache();

        return $banner;
    }
}
