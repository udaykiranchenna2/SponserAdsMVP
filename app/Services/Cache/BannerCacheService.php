<?php

namespace App\Services\Cache;

use App\Enums\BannerStatus;
use App\Models\Banner;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class BannerCacheService
{
    private const CACHE_KEY_ACTIVE = 'banners:active';

    private const CACHE_TTL = 3600; // 1 hour (or explicitly 'forever' until cleared)

    /**
     * Get all active banners from cache, or database if missing.
     *
     * @return Collection<int, Banner>
     */
    public function getActiveBanners(): Collection
    {
        return Cache::rememberForever(self::CACHE_KEY_ACTIVE, function () {
            return $this->fetchActiveBanners();
        });
    }

    /**
     * Fetch active banners from the database.
     *
     * @return Collection<int, Banner>
     */
    protected function fetchActiveBanners(): Collection
    {
        return Banner::where('status', BannerStatus::Active)
            ->latest()
            ->get();
    }

    /**
     * Get a specific active banner by UUID from the cache.
     */
    public function getBannerByUuid(string $uuid): ?Banner
    {
        return $this->getActiveBanners()->firstWhere('uuid', $uuid);
    }

    /**
     * Clear the banner cache.
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY_ACTIVE);
    }

    /**
     * Refresh the banner cache.
     */
    public function refreshCache(): void
    {
        $this->clearCache();
        $this->getActiveBanners();
    }

    /**
     * Get a banner for a specific placement from cache.
     */
    public function getBannerByPlacement(string $placement): ?array
    {
        $cacheKey = "banners:placement:{$placement}";

        return Cache::get($cacheKey);
    }

    /**
     * Cache a banner for a specific placement.
     */
    public function cacheBannerByPlacement(string $placement, array $bannerData): void
    {
        $cacheKey = "banners:placement:{$placement}";
        Cache::put($cacheKey, $bannerData, self::CACHE_TTL);
    }

    /**
     * Clear placement-specific cache.
     */
    public function clearPlacementCache(string $placement): void
    {
        $cacheKey = "banners:placement:{$placement}";
        Cache::forget($cacheKey);
    }

    /**
     * Clear all placement caches.
     */
    public function clearAllPlacementCaches(): void
    {
        // Get all unique placements
        $placements = Banner::distinct()->pluck('placement');

        foreach ($placements as $placement) {
            $this->clearPlacementCache($placement);
        }
    }
}
