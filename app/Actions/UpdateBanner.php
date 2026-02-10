<?php

namespace App\Actions;

use App\Enums\BannerStatus;
use App\Models\Banner;
use App\Services\Cache\BannerCacheService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UpdateBanner
{
    public function __construct(protected BannerCacheService $cacheService) {}

    /**
     * Update an existing banner.
     */
    public function execute(
        Banner $banner,
        string $title,
        ?string $placement,
        string $targetUrl,
        ?string $linkText,
        BannerStatus $status,
        ?UploadedFile $image = null
    ): Banner {
        $data = [
            'title' => $title,
            'placement' => $placement,
            'target_url' => $targetUrl,
            'link_text' => $linkText,
            'status' => $status,
        ];

        if ($image) {
            // Delete old image if it exists
            if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
                Storage::disk('public')->delete($banner->image_path);
            }

            // Store new image
            $data['image_path'] = $image->store('banners', 'public');
        }

        $banner->update($data);

        $this->cacheService->clearCache();

        return $banner;
    }
}
