# 🚀 CDN Build Workflow

This guide explains how to build the embed script with your API URL baked in, ready for CDN upload.

## How it Works

The build script (`scripts/build-embed.js`) automatically reads your **LOCAL .env file** to find the API URL. It injects this URL into the minified script.

## Step-by-Step Guide

### 1. Set your Production URL

Open your `.env` file and ensure `APP_URL` is set to your production domain:

```env
APP_URL=https://ads.yourdomain.com
```

### 2. Run the Build Command

Run the build script in your terminal:

```bash
npm run build:embed
```

You should see output like:

```
🔨 Building embed script...
📍 Injecting API URL: https://ads.yourdomain.com/api
✅ Build complete!
```

### 3. Upload to CDN

The file is created at:
`public/js/sponsor-ads.min.js`

Upload this **exact file** to your CDN (CloudFront, S3, etc.).

### 4. Use in Websites

Website owners can now use the CDN link directly:

```html
<script src="https://d1djuuv7ufovj8.cloudfront.net/sponsor-ads.min.js"></script>
```

---

## Why this is better?

- **No extra requests**: The script knows exactly where to call.
- **One file**: No separate configuration needed on client websites.
- **Fast**: Minified and pre-configured.

## Troubleshooting

- **Testing Locally**: Set `APP_URL=http://localhost:8000` in `.env` and run `npm run build:embed`.
- **Wrong URL?**: Check your `.env` file and rebuild.
