# SponsorAds MVP Documentation

Welcome to the SponsorAds MVP documentation. This directory contains comprehensive guides for implementing and using the banner embed system.

## 📚 Documentation Index

### Quick Start

- **[QuickStart.md](QuickStart.md)** - Get started in 5 minutes
    - For website owners: How to add the embed script
    - For developers: Building and testing
    - Common troubleshooting tips

### Implementation Guides

- **[EmbedSystemGuide.md](EmbedSystemGuide.md)** - Complete implementation guide
    - Database schema details
    - API endpoint documentation
    - Caching strategy
    - Deployment instructions
    - Security considerations
    - Performance optimization

- **[ImplementationSummary.md](ImplementationSummary.md)** - What was built
    - Completed tasks checklist
    - Files created/modified
    - Deployment steps
    - Next steps for production

### Technical Documentation

- **[SystemArchitecture.md](SystemArchitecture.md)** - System design and architecture
    - Visual system flow diagrams
    - Component breakdown
    - Data flow explanations
    - Performance optimizations
    - Scalability considerations

- **[ProjectPlan.md](ProjectPlan.md)** - Original project requirements
    - Core features
    - Technical stack
    - System flow
    - Performance strategy

## 🎯 Quick Links

### For Website Owners

1. Read [OneScriptSetup.md](OneScriptSetup.md) - The simplest way
2. Add the one-line embed script
3. Add placement containers
4. Done!

### For Developers

1. Read [CDN_Build_Workflow.md](CDN_Build_Workflow.md) - How to build for CDN
2. Check [SystemArchitecture.md](SystemArchitecture.md) for system design
3. Build script: `npm run build:embed`

### For System Administrators

1. Review [EmbedSystemGuide.md](EmbedSystemGuide.md) - Deployment section
2. Configure CDN for script hosting
3. Set up Redis caching
4. Configure CORS and rate limiting
5. Monitor performance metrics

## 🚀 Getting Started

### Minimum Requirements

- PHP 8.3+
- Laravel 12
- MySQL 8.0+
- Redis 6.0+
- Node.js 18+

### Installation

```bash
# Install dependencies
composer install
npm install

# Run migrations
php artisan migrate

# Build minified script
npm run build:embed
```

### Testing

```bash
# Start Laravel server
php artisan serve

# Open test page
open test_embed.html
```

## 📊 System Overview

### What It Does

SponsorAds is a placement-based banner advertising system that allows external websites to display ads using a simple JavaScript embed script.

### Key Features

- ✅ Placement-based ad organization
- ✅ Minified CDN-ready script (3.20 KB)
- ✅ Automatic impression tracking
- ✅ Click tracking
- ✅ Redis caching for performance
- ✅ Retry logic for reliability
- ✅ IntersectionObserver for smart tracking

### How It Works

1. Website owner adds embed script to their site
2. Script detects placement containers
3. Makes API call to fetch banners
4. Renders banners in appropriate locations
5. Tracks impressions when visible
6. Tracks clicks when users interact

## 🔧 Configuration

### Environment Variables

```env
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Script Configuration

Edit `public/js/sponsor-ads.js` before building:

```javascript
const CONFIG = {
    apiBaseUrl: window.SPONSOR_ADS_API_URL || 'http://127.0.0.1:8000/api',
    placementClass: 'sponsor-ad',
    placementAttribute: 'data-placement',
    retryAttempts: 3,
    retryDelay: 1000,
};
```

## 📁 File Structure

```
.docs/
├── README.md                    # This file
├── QuickStart.md               # Quick start guide
├── EmbedSystemGuide.md         # Complete implementation guide
├── ImplementationSummary.md    # What was built
├── SystemArchitecture.md       # Technical architecture
└── ProjectPlan.md              # Original requirements

public/js/
├── sponsor-ads.js              # Source embed script (7.96 KB)
└── sponsor-ads.min.js          # Minified script (3.20 KB)

app/Http/Controllers/Api/
├── BannerEmbedController.php   # Legacy iframe embed
└── PlacementController.php     # Placement-based API

app/Services/Cache/
└── BannerCacheService.php      # Redis caching service

scripts/
└── build-embed.js              # Minification build script
```

## 🎨 Usage Examples

### Basic Implementation

```html
<script>
    window.SPONSOR_ADS_API_URL = 'https://your-domain.com/api';
</script>
<script src="https://cdn.example.com/sponsor-ads.min.js"></script>

<div class="sponsor-ad" data-placement="homepage"></div>
```

### Multiple Placements

```html
<header>
    <div class="sponsor-ad" data-placement="header"></div>
</header>

<main>
    <div class="sponsor-ad" data-placement="homepage"></div>
</main>

<aside>
    <div class="sponsor-ad" data-placement="sidebar"></div>
</aside>

<footer>
    <div class="sponsor-ad" data-placement="footer"></div>
</footer>
```

### Manual Control

```javascript
// Reload all ads
window.SponsorAds.reload();

// Access configuration
console.log(window.SponsorAds.config);
```

## 🧪 Testing

### Create Test Banner

```php
use App\Models\Banner;

Banner::create([
    'title' => 'Test Homepage Banner',
    'image_path' => 'banners/test.jpg',
    'target_url' => 'https://example.com',
    'link_text' => 'Click Here',
    'placement' => 'homepage',
    'status' => 'active',
]);
```

### Test Locally

1. Run `php artisan serve`
2. Open `test_embed.html` in browser
3. Check console for loading messages
4. Verify banners appear

## 📈 Performance Metrics

- **Script Size**: 3.20 KB (minified, 59.79% reduction)
- **HTTP Requests**: 1 script + 1 API call (all placements)
- **Cache TTL**: 1 hour (Redis)
- **Tracking**: Only when 50% visible

## 🔒 Security

- CORS configured for cross-origin requests
- Input validation on all endpoints
- Rate limiting on tracking endpoints
- XSS protection for banner content
- HTTPS required for production

## 🚀 Deployment Checklist

- [ ] Build minified script: `npm run build:embed`
- [ ] Upload to CDN
- [ ] Configure Redis
- [ ] Set up CORS
- [ ] Enable rate limiting
- [ ] Configure monitoring
- [ ] Test on staging
- [ ] Deploy to production

## 📞 Support

For questions or issues:

1. Check the documentation in this directory
2. Review `test_embed.html` for examples
3. Check browser console for errors
4. Review Laravel logs in `storage/logs/`

## 🎓 Learning Resources

### Recommended Reading Order

1. **QuickStart.md** - Get familiar with basic usage
2. **ImplementationSummary.md** - Understand what was built
3. **EmbedSystemGuide.md** - Deep dive into implementation
4. **SystemArchitecture.md** - Understand system design

### For Different Roles

**Website Owners:**

- QuickStart.md (For Website Owners section)

**Frontend Developers:**

- QuickStart.md
- EmbedSystemGuide.md (Embed Script Usage section)

**Backend Developers:**

- ImplementationSummary.md
- EmbedSystemGuide.md (API Endpoints, Caching sections)
- SystemArchitecture.md

**System Architects:**

- SystemArchitecture.md
- EmbedSystemGuide.md (Performance, Security sections)

**DevOps Engineers:**

- EmbedSystemGuide.md (CDN Deployment section)
- SystemArchitecture.md (Scalability section)

## 🎯 Next Steps

### For Production

1. Set up CDN for global script distribution
2. Configure monitoring and alerting
3. Add rate limiting to prevent abuse
4. Set up analytics dashboard
5. Create admin interface for banner management

### Future Enhancements

- A/B testing support
- Frequency capping
- Geographic targeting
- Device targeting
- Weighted ad rotation
- Real-time analytics dashboard

## 📝 Version History

### v1.0.0 (Current)

- ✅ Placement-based banner system
- ✅ Minified embed script (3.20 KB)
- ✅ Impression and click tracking
- ✅ Redis caching
- ✅ Comprehensive documentation

---

**Last Updated**: February 10, 2026  
**Version**: 1.0.0  
**Status**: Production Ready
