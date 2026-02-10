<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Services\Cache\BannerCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlacementController extends Controller
{
    public function __construct(protected BannerCacheService $cacheService) {}

    /**
     * Get banners for specific placements.
     */
    public function getBanners(Request $request): JsonResponse
    {
        $placements = $request->input('placements', []);

        if (empty($placements) || ! is_array($placements)) {
            return response()->json(['error' => 'Invalid placements'], 400);
        }

        $banners = [];

        foreach ($placements as $placement) {
            // Try to get from cache first
            $cachedBanner = $this->cacheService->getBannerByPlacement($placement);

            if ($cachedBanner) {
                $banners[$placement] = $cachedBanner;
            } else {
                // Fallback to database
                $banner = Banner::where('placement', $placement)
                    ->where('status', 'active')
                    ->inRandomOrder()
                    ->first();

                if ($banner) {
                    $banners[$placement] = [
                        'uuid' => $banner->uuid,
                        'title' => $banner->title,
                        'image_url' => asset('storage/'.$banner->image_path),
                        'target_url' => $banner->target_url,
                        'link_text' => $banner->link_text,
                        'placement' => $banner->placement,
                    ];

                    // Cache for future requests
                    $this->cacheService->cacheBannerByPlacement($placement, $banners[$placement]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'banners' => $banners,
        ]);
    }

    /**
     * Track banner impression.
     * Supports single UUID or batch of UUIDs.
     */
    public function trackImpression(Request $request): JsonResponse
    {
        $uuids = $request->input('uuids');
        $uuid = $request->input('uuid');

        // Handle single UUID (backward compatibility)
        if ($uuid && ! $uuids) {
            $uuids = [$uuid];
        }

        if (empty($uuids) || ! is_array($uuids)) {
            return response()->json(['error' => 'UUIDs required'], 400);
        }

        $banners = Banner::whereIn('uuid', $uuids)->get();

        if ($banners->isEmpty()) {
            return response()->json(['error' => 'No banners found'], 404);
        }

        $records = [];
        $now = now();
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $referer = $request->header('referer');

        foreach ($banners as $banner) {
            $records[] = [
                'banner_id' => $banner->id,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'referer' => $referer,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (! empty($records)) {
            \App\Models\Impression::insert($records);
        }

        return response()->json(['success' => true, 'count' => count($records)]);
    }

    /**
     * Track banner click.
     */
    public function trackClick(Request $request): JsonResponse
    {
        $uuid = $request->input('uuid');

        if (! $uuid) {
            return response()->json(['error' => 'UUID required'], 400);
        }

        $banner = Banner::where('uuid', $uuid)->first();

        if (! $banner) {
            return response()->json(['error' => 'Banner not found'], 404);
        }

        // Create click record
        \App\Models\Click::create([
            'banner_id' => $banner->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referer' => $request->header('referer'),
        ]);

        return response()->json(['success' => true]);
    }
}
