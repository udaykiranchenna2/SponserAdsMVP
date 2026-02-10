# Creating Test Banners

## Quick Command

Run this command to create test banners for all placements:

```bash
php artisan tinker --execute="
\$banners = [
    ['title' => 'Homepage Banner', 'placement' => 'homepage'],
    ['title' => 'Header Banner', 'placement' => 'header'],
    ['title' => 'Sidebar Banner', 'placement' => 'sidebar'],
    ['title' => 'Footer Banner', 'placement' => 'footer'],
];

foreach (\$banners as \$data) {
    \App\Models\Banner::create([
        'title' => \$data['title'],
        'image_path' => 'banners/placeholder.jpg',
        'target_url' => 'https://example.com',
        'link_text' => 'Learn More',
        'placement' => \$data['placement'],
        'status' => 'active',
    ]);
}

echo 'Created ' . count(\$banners) . ' test banners';
"
```

## Manual Creation

```bash
php artisan tinker
```

Then run:

```php
Banner::create([
    'title' => 'My Test Banner',
    'image_path' => 'banners/test.jpg',
    'target_url' => 'https://example.com',
    'link_text' => 'Click Here',
    'placement' => 'homepage', // or 'header', 'sidebar', 'footer'
    'status' => 'active',
]);
```

## Upload Banner Images

1. Create directory:

    ```bash
    mkdir -p storage/app/public/banners
    ```

2. Upload your banner images to `storage/app/public/banners/`

3. Make sure storage is linked:
    ```bash
    php artisan storage:link
    ```

## View Created Banners

```bash
php artisan tinker --execute="
\$banners = \App\Models\Banner::all(['id', 'title', 'placement', 'status']);
echo \$banners->toJson(JSON_PRETTY_PRINT);
"
```

## Clear All Test Banners

```bash
php artisan tinker --execute="
\App\Models\Banner::truncate();
echo 'All banners deleted';
"
```

## Notes

- The warnings "No banner found for placement: X" are normal when no banners exist for that placement
- Once you create banners, they will appear automatically on the next page load
- The system caches banners for 1 hour, so changes may take up to 1 hour to appear (or clear cache manually)

## Clear Cache

```bash
php artisan tinker --execute="
app(\App\Services\Cache\BannerCacheService::class)->clearAllPlacementCaches();
echo 'Cache cleared';
"
```
