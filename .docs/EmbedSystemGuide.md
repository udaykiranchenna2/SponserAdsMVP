# SponsorAds Embed System - Implementation Guide

## Overview

The SponsorAds embed system allows external websites to display banner advertisements using a simple JavaScript embed script. The system is placement-based, meaning ads are organized by placement identifiers (e.g., `homepage`, `sidebar`, `footer`).

## Features

✅ **Placement-Based System** - Organize ads by placement type  
✅ **Minified CDN Script** - Optimized JavaScript (59.79% size reduction)  
✅ **Automatic Ad Loading** - Script finds and populates placement containers  
✅ **Impression Tracking** - Tracks when ads become visible (IntersectionObserver)  
✅ **Click Tracking** - Tracks user clicks on advertisements  
✅ **Redis Caching** - Fast banner delivery with cache invalidation  
✅ **Retry Logic** - Automatic retry with exponential backoff  
✅ **Dynamic Loading** - Detects dynamically added placements

## Database Schema

### Banners Table

- `id` - Primary key
- `uuid` - Unique identifier for public API
- `title` - Banner title
- `image_path` - Path to banner image
- `target_url` - Click destination URL
- `link_text` - Optional link text
- `placement` - Placement identifier (homepage, sidebar, footer, etc.)
- `status` - active/inactive
- `created_at`, `updated_at`

### Impressions Table

- `id` - Primary key
- `banner_id` - Foreign key to banners
- `ip_address` - Visitor IP
- `user_agent` - Browser user agent
- `referer` - Referring page
- `created_at`, `updated_at`

### Clicks Table

- `id` - Primary key
- `banner_id` - Foreign key to banners
- `ip_address` - Visitor IP
- `user_agent` - Browser user agent
- `referrer` - Referring page
- `created_at`, `updated_at`

## API Endpoints

### Get Banners by Placement

```
POST /api/placements/banners
Content-Type: application/json

{
  "placements": ["homepage", "sidebar", "footer"]
}
```

**Response:**

```json
{
  "success": true,
  "banners": {
    "homepage": {
      "uuid": "d3c4ccec-4813-4618-a293-3ac1873600d2",
      "title": "Banner Title",
      "image_url": "http://example.com/storage/banners/image.jpg",
      "target_url": "https://example.com",
      "link_text": "Learn More",
      "placement": "homepage"
    },
    "sidebar": { ... }
  }
}
```

### Track Impression

```
POST /api/placements/track/impression
Content-Type: application/json

{
  "uuid": "d3c4ccec-4813-4618-a293-3ac1873600d2"
}
```

### Track Click

```
POST /api/placements/track/click
Content-Type: application/json

{
  "uuid": "d3c4ccec-4813-4618-a293-3ac1873600d2"
}
```

## Embed Script Usage

### Step 1: Add the Script

Add the minified embed script to your website's `<head>` or before `</body>`:

```html
<!-- Configure API URL (optional, defaults to current domain) -->
<script>
    window.SPONSOR_ADS_API_URL = 'https://your-domain.com/api';
</script>

<!-- Load the embed script -->
<script src="https://your-cdn.com/js/sponsor-ads.min.js"></script>
```

### Step 2: Add Placement Containers

Add placement containers wherever you want ads to appear:

```html
<!-- Homepage banner -->
<div class="sponsor-ad" data-placement="homepage"></div>

<!-- Sidebar banner -->
<div class="sponsor-ad" data-placement="sidebar"></div>

<!-- Footer banner -->
<div class="sponsor-ad" data-placement="footer"></div>

<!-- Header banner -->
<div class="sponsor-ad" data-placement="header"></div>
```

### Step 3: Style the Containers (Optional)

```css
.sponsor-ad {
    margin: 20px 0;
    min-height: 150px;
}

.sponsor-ad-banner {
    width: 100%;
}

.sponsor-ad-banner img {
    max-width: 100%;
    height: auto;
}
```

## How It Works

1. **Script Initialization**
    - Script loads when DOM is ready
    - Finds all elements with class `sponsor-ad` and `data-placement` attribute
    - Collects unique placement identifiers

2. **Banner Fetching**
    - Makes POST request to `/api/placements/banners` with placement array
    - Receives banner data for each placement
    - Caches results in Redis for fast subsequent requests

3. **Banner Rendering**
    - Creates HTML structure for each banner
    - Inserts into corresponding placement containers
    - Marks containers as loaded to prevent duplicate requests

4. **Impression Tracking**
    - Uses IntersectionObserver to detect when banner is 50% visible
    - Sends impression tracking request once per banner
    - Fallback to immediate tracking if IntersectionObserver unavailable

5. **Click Tracking**
    - Attaches click event listener to banner links
    - Sends click tracking request when user clicks
    - Tracks only once per banner per page load

## Advanced Features

### Manual Reload

```javascript
// Reload all ads on the page
window.SponsorAds.reload();
```

### Access Configuration

```javascript
// View current configuration
console.log(window.SponsorAds.config);
```

### Custom API URL

```javascript
// Set before loading the script
window.SPONSOR_ADS_API_URL = 'https://custom-api.com/api';
```

## Caching Strategy

### Banner Cache

- Active banners cached in Redis indefinitely
- Cache key: `banners:active`
- Cleared on banner create/update/delete

### Placement Cache

- Individual placement banners cached for 1 hour
- Cache key: `banners:placement:{placement_name}`
- Cleared when related banners change

### Cache Invalidation

The `BannerCacheService` provides methods to clear caches:

```php
// Clear all active banners cache
$cacheService->clearCache();

// Clear specific placement cache
$cacheService->clearPlacementCache('homepage');

// Clear all placement caches
$cacheService->clearAllPlacementCaches();

// Refresh cache
$cacheService->refreshCache();
```

## Building the Minified Script

### Development

```bash
# The source file is at:
public/js/sponsor-ads.js
```

### Build Minified Version

```bash
npm run build:embed
```

This will:

- Read `public/js/sponsor-ads.js`
- Minify using Terser
- Output to `public/js/sponsor-ads.min.js`
- Show size reduction statistics

### Build Output Example

```
🔨 Building embed script...
✅ Build complete!
   Original: 7.96 KB
   Minified: 3.20 KB
   Reduction: 59.79%
```

## CDN Deployment

### Option 1: Self-Hosted

Serve the minified script from your Laravel public directory:

```
https://your-domain.com/js/sponsor-ads.min.js
```

### Option 2: External CDN

Upload `public/js/sponsor-ads.min.js` to:

- AWS CloudFront
- Cloudflare
- DigitalOcean Spaces
- Any CDN provider

Update embed instructions with your CDN URL.

## Testing

### Local Testing

1. Open `test_embed.html` in a browser
2. Ensure Laravel server is running (`php artisan serve`)
3. Check browser console for loading messages
4. Verify banners appear in placement containers

### Create Test Banners

```bash
php artisan tinker
```

```php
// Create test banners for different placements
Banner::create([
    'title' => 'Homepage Banner',
    'image_path' => 'banners/homepage.jpg',
    'target_url' => 'https://example.com',
    'link_text' => 'Learn More',
    'placement' => 'homepage',
    'status' => 'active',
]);

Banner::create([
    'title' => 'Sidebar Banner',
    'image_path' => 'banners/sidebar.jpg',
    'target_url' => 'https://example.com',
    'link_text' => 'Click Here',
    'placement' => 'sidebar',
    'status' => 'active',
]);
```

## Configuration

### Script Configuration

Edit `public/js/sponsor-ads.js` and rebuild:

```javascript
const CONFIG = {
    apiBaseUrl: window.SPONSOR_ADS_API_URL || 'http://127.0.0.1:8000/api',
    placementClass: 'sponsor-ad', // CSS class for containers
    placementAttribute: 'data-placement', // Attribute for placement ID
    loadedAttribute: 'data-sponsor-loaded', // Marks loaded containers
    retryAttempts: 3, // Number of retry attempts
    retryDelay: 1000, // Base retry delay (ms)
};
```

## Security Considerations

1. **CORS**: Ensure your API allows cross-origin requests from external websites
2. **Rate Limiting**: Implement rate limiting on tracking endpoints
3. **Input Validation**: Validate placement names and UUIDs
4. **XSS Protection**: Banner content is sanitized
5. **HTTPS**: Always serve the embed script over HTTPS in production

## Performance Optimization

1. **Redis Caching**: All banner data cached for fast delivery
2. **Lazy Loading**: Images use `loading="lazy"` attribute
3. **Minification**: 59.79% size reduction
4. **Intersection Observer**: Impressions tracked only when visible
5. **Single Request**: Fetches all placements in one API call

## Troubleshooting

### Ads Not Loading

- Check browser console for errors
- Verify API URL is correct
- Ensure banners exist for the placement
- Check banner status is 'active'

### Tracking Not Working

- Verify API endpoints are accessible
- Check CORS configuration
- Review server logs for errors

### Cache Issues

- Clear Redis cache manually
- Restart Redis server
- Check cache service configuration

## Future Enhancements

- [ ] A/B testing support
- [ ] Frequency capping
- [ ] Geographic targeting
- [ ] Device targeting
- [ ] Weighted rotation
- [ ] Analytics dashboard
- [ ] Real-time reporting

## Support

For issues or questions:

- Check the documentation
- Review test_embed.html for examples
- Inspect browser console for errors
- Check Laravel logs in `storage/logs/`
