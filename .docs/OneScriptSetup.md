# 🎯 One-Script Setup Guide

## Super Simple - Just One Line!

Website owners only need to add **ONE script tag** to their website:

```html
<script src="https://d1djuuv7ufovj8.cloudfront.net/sponsor-ads.min.js"></script>
```

That's it! No configuration needed.

---

## Complete Example

```html
<!DOCTYPE html>
<html>
    <head>
        <title>My Website</title>

        <!-- Load SponsorAds (ONE LINE) -->
        <script src="https://d1djuuv7ufovj8.cloudfront.net/sponsor-ads.min.js"></script>
    </head>
    <body>
        <header>
            <div class="sponsor-ad" data-placement="header"></div>
        </header>

        <main>
            <h1>Welcome</h1>
            <div class="sponsor-ad" data-placement="homepage"></div>
            <p>Your content here...</p>
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

## How It Works

1. **Add the script** - One line in your HTML
2. **Add placement containers** - `<div class="sponsor-ad" data-placement="homepage"></div>`
3. **Done!** - Ads load automatically

---

## Before Deploying

**IMPORTANT**: You need to update the API URL in the script before uploading to CDN:

1. Open `public/js/sponsor-ads.js`
2. Find line 16:
    ```javascript
    apiBaseUrl: 'https://your-production-domain.com/api',
    ```
3. Replace with your actual domain:
    ```javascript
    apiBaseUrl: 'https://ads.yourdomain.com/api',
    ```
4. Rebuild the minified version:
    ```bash
    npm run build:embed
    ```
5. Upload `public/js/sponsor-ads.min.js` to CloudFront

---

## Available Placements

- `homepage` - Main homepage banner
- `header` - Header area
- `sidebar` - Sidebar area
- `footer` - Footer area
- Custom - Create any placement name you want!

---

## Styling (Optional)

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

## That's All!

No API keys, no configuration, no complexity. Just one script tag and placement containers. 🎉

**Script Size**: 3.18 KB (minified)  
**CDN**: CloudFront  
**Tracking**: Automatic (impressions & clicks)
