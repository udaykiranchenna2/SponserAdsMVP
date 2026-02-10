<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use App\Services\Cache\BannerCacheService;
use Illuminate\Http\Response;

class BannerEmbedController extends Controller
{
    public function __construct(protected BannerCacheService $cacheService) {}

    /**
     * Get banner data (JSON).
     *
     * @return mixed
     */
    public function show(string $uuid)
    {
        $banner = $this->cacheService->getBannerByUuid($uuid);

        if (! $banner) {
            $banner = Banner::where('uuid', $uuid)->firstOrFail();
        }

        return new BannerResource($banner);
    }

    /**
     * Render the banner HTML for embedding (iframe).
     *
     * @return Response
     */
    public function embed(string $uuid)
    {
        $banner = $this->cacheService->getBannerByUuid($uuid);

        if (! $banner) {
            $banner = Banner::where('uuid', $uuid)->firstOrFail();
        }

        // We'll return a raw HTML response for the iframe
        $html = view('banners.embed', ['banner' => $banner])->render();

        return response($html)->header('Content-Type', 'text/html');
    }
}
