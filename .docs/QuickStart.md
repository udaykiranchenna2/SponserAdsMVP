# SponsorAds Embed - Quick Start Guide

## 🚀 For Website Owners

### Step 1: Add the Script

```html
<script>
    window.SPONSOR_ADS_API_URL = 'https://your-domain.com/api';
</script>
<script src="https://d1djuuv7ufovj8.cloudfront.net/sponsor-ads.min.js"></script>
```

### Step 2: Add Placements

```html
<!-- Homepage Banner -->
<div class="sponsor-ad" data-placement="homepage"></div>

<!-- Sidebar Banner -->
<div class="sponsor-ad" data-placement="sidebar"></div>

<!-- Footer Banner -->
<div class="sponsor-ad" data-placement="footer"></div>
```

### That's It! 🎉

The script will automatically:

- Find all placement containers
- Load appropriate banners
- Track impressions when visible
- Track clicks when users interact

---

## 🛠️ For Developers

### Build Minified Script

```bash
npm run build:embed
```

### Create Test Banner

```php
Banner::create([
    'title' => 'My Banner',
    'image_path' => 'banners/image.jpg',
    'target_url' => 'https://example.com',
    'link_text' => 'Click Here',
    'placement' => 'homepage',
    'status' => 'active',
]);
```

### Clear Cache

```php
app(BannerCacheService::class)->clearAllPlacementCaches();
```

### Manual Reload (JavaScript)

```javascript
window.SponsorAds.reload();
```

---

## 📍 Available Placements

Default placements (you can create custom ones):

- `homepage` - Main homepage banner
- `header` - Header area banner
- `sidebar` - Sidebar banner
- `footer` - Footer banner

---

## 🎨 Styling (Optional)

```css
.sponsor-ad {
    margin: 20px 0;
    min-height: 150px;
}

.sponsor-ad-banner img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
}
```

---

## 📊 API Endpoints

| Endpoint                           | Method | Purpose                  |
| ---------------------------------- | ------ | ------------------------ |
| `/api/placements/banners`          | POST   | Get banners by placement |
| `/api/placements/track/impression` | POST   | Track impression         |
| `/api/placements/track/click`      | POST   | Track click              |

---

## ⚡ Performance

- **Script Size**: 3.20 KB (minified)
- **Requests**: 1 script + 1 API call
- **Caching**: Redis-backed (1 hour TTL)
- **Tracking**: Only when visible (IntersectionObserver)

---

## 🔍 Troubleshooting

**Ads not showing?**

- Check browser console for errors
- Verify API URL is correct
- Ensure banners exist for placement
- Check banner status is 'active'

**Tracking not working?**

- Verify CORS is configured
- Check API endpoints are accessible
- Review server logs

---

## 📚 Full Documentation

See `.docs/EmbedSystemGuide.md` for complete documentation.

---

## 🎯 Example Implementation

```html
<!DOCTYPE html>
<html>
    <head>
        <title>My Website</title>
        <script>
            window.SPONSOR_ADS_API_URL = 'https://ads.example.com/api';
        </script>
        <script src="https://cdn.example.com/sponsor-ads.min.js"></script>
    </head>
    <body>
        <header>
            <div class="sponsor-ad" data-placement="header"></div>
        </header>

        <main>
            <h1>Welcome</h1>
            <div class="sponsor-ad" data-placement="homepage"></div>
            <p>Content here...</p>
        </main>

        <aside>
            <div class="sponsor-ad" data-placement="sidebar"></div>
        </aside>

        <footer>
            <div class="sponsor-ad" data-placement="footer"></div>
        </footer>
    </body>
</html>
```

---

**Need Help?** Check the full documentation or contact support.
