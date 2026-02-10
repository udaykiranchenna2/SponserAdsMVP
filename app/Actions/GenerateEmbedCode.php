<?php

namespace App\Actions;

use App\Models\Banner;

class GenerateEmbedCode
{
    /**
     * Generate the embed code for a banner.
     */
    public function execute(Banner $banner): string
    {
        $url = route('api.banners.embed', ['uuid' => $banner->uuid]);
        // Default size, can be adjusted or made dynamic
        $width = '100%';
        $height = 'auto';
        $aspectRatio = '2/1'; // Example aspect ratio, adjust as needed or use banner dimensions if stored

        // Using a responsive iframe approach
        return sprintf(
            '<iframe src="%s" width="%s" height="%s" frameborder="0" scrolling="no" style="aspect-ratio: %s; width: 100%%; border: none; overflow: hidden;"></iframe>',
            $url,
            $width,
            $height,
            $aspectRatio
        );
    }
}
