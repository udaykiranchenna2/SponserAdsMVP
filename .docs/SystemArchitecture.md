# SponsorAds System Architecture

## System Flow Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                        External Website                         │
│                                                                  │
│  ┌────────────────────────────────────────────────────────┐    │
│  │  HTML Page                                              │    │
│  │                                                          │    │
│  │  <script src="sponsor-ads.min.js"></script>            │    │
│  │                                                          │    │
│  │  <div class="sponsor-ad" data-placement="homepage">    │    │
│  │  <div class="sponsor-ad" data-placement="sidebar">     │    │
│  │  <div class="sponsor-ad" data-placement="footer">      │    │
│  └────────────────────────────────────────────────────────┘    │
│                              │                                   │
│                              │ 1. Script loads                   │
│                              │ 2. Finds placements               │
│                              ▼                                   │
│  ┌────────────────────────────────────────────────────────┐    │
│  │  sponsor-ads.min.js (3.20 KB)                          │    │
│  │  • Detects placement containers                        │    │
│  │  • Collects placement IDs                              │    │
│  │  • Makes API request                                   │    │
│  └────────────────────────────────────────────────────────┘    │
└──────────────────────────────┬───────────────────────────────────┘
                               │
                               │ POST /api/placements/banners
                               │ { placements: ["homepage", "sidebar", "footer"] }
                               │
                               ▼
┌─────────────────────────────────────────────────────────────────┐
│                      SponsorAds Server                          │
│                                                                  │
│  ┌────────────────────────────────────────────────────────┐    │
│  │  PlacementController                                    │    │
│  │  • Receives placement request                           │    │
│  │  • Checks Redis cache first                            │    │
│  │  • Falls back to database if needed                    │    │
│  └────────────────────────────────────────────────────────┘    │
│                              │                                   │
│                              ▼                                   │
│  ┌────────────────────────────────────────────────────────┐    │
│  │  Redis Cache                                            │    │
│  │  Key: banners:placement:homepage                        │    │
│  │  TTL: 1 hour                                            │    │
│  │                                                          │    │
│  │  {                                                       │    │
│  │    uuid: "...",                                         │    │
│  │    title: "Banner Title",                               │    │
│  │    image_url: "...",                                    │    │
│  │    target_url: "...",                                   │    │
│  │    placement: "homepage"                                │    │
│  │  }                                                       │    │
│  └────────────────────────────────────────────────────────┘    │
│                              │                                   │
│                              │ Cache miss?                       │
│                              ▼                                   │
│  ┌────────────────────────────────────────────────────────┐    │
│  │  MySQL Database                                         │    │
│  │                                                          │    │
│  │  banners                                                │    │
│  │  ├── id                                                 │    │
│  │  ├── uuid                                               │    │
│  │  ├── title                                              │    │
│  │  ├── image_path                                         │    │
│  │  ├── target_url                                         │    │
│  │  ├── placement ◄── Indexed                             │    │
│  │  └── status                                             │    │
│  │                                                          │    │
│  │  impressions                clicks                      │    │
│  │  ├── banner_id              ├── banner_id              │    │
│  │  ├── ip_address             ├── ip_address             │    │
│  │  └── created_at             └── created_at             │    │
│  └────────────────────────────────────────────────────────┘    │
│                              │                                   │
│                              │ Return banner data                │
│                              ▼                                   │
│  ┌────────────────────────────────────────────────────────┐    │
│  │  JSON Response                                          │    │
│  │  {                                                       │    │
│  │    success: true,                                       │    │
│  │    banners: {                                           │    │
│  │      homepage: { uuid, title, image_url, ... },        │    │
│  │      sidebar: { uuid, title, image_url, ... },         │    │
│  │      footer: { uuid, title, image_url, ... }           │    │
│  │    }                                                     │    │
│  │  }                                                       │    │
│  └────────────────────────────────────────────────────────┘    │
└──────────────────────────────┬───────────────────────────────────┘
                               │
                               │ Response with banner data
                               │
                               ▼
┌─────────────────────────────────────────────────────────────────┐
│                        External Website                         │
│                                                                  │
│  ┌────────────────────────────────────────────────────────┐    │
│  │  sponsor-ads.min.js                                     │    │
│  │  • Receives banner data                                 │    │
│  │  • Creates HTML for each banner                         │    │
│  │  • Inserts into placement containers                    │    │
│  │  • Sets up IntersectionObserver                         │    │
│  └────────────────────────────────────────────────────────┘    │
│                              │                                   │
│                              ▼                                   │
│  ┌────────────────────────────────────────────────────────┐    │
│  │  Rendered Banners                                       │    │
│  │                                                          │    │
│  │  ┌──────────────────────┐                              │    │
│  │  │ [Homepage Banner]    │ ◄── 50% visible?             │    │
│  │  │  Click Here          │     Track impression         │    │
│  │  └──────────────────────┘                              │    │
│  │                                                          │    │
│  │  ┌──────────────────────┐                              │    │
│  │  │ [Sidebar Banner]     │ ◄── User clicks?             │    │
│  │  │  Learn More          │     Track click              │    │
│  │  └──────────────────────┘                              │    │
│  └────────────────────────────────────────────────────────┘    │
│                              │                                   │
│                              │ Tracking events                   │
│                              ▼                                   │
│  POST /api/placements/track/impression                          │
│  POST /api/placements/track/click                               │
└──────────────────────────────┬───────────────────────────────────┘
                               │
                               ▼
┌─────────────────────────────────────────────────────────────────┐
│                      SponsorAds Server                          │
│                                                                  │
│  ┌────────────────────────────────────────────────────────┐    │
│  │  PlacementController                                    │    │
│  │  • trackImpression()                                    │    │
│  │  • trackClick()                                         │    │
│  │  • Stores in database                                   │    │
│  └────────────────────────────────────────────────────────┘    │
│                              │                                   │
│                              ▼                                   │
│  ┌────────────────────────────────────────────────────────┐    │
│  │  Database                                               │    │
│  │  INSERT INTO impressions (banner_id, ip_address, ...)  │    │
│  │  INSERT INTO clicks (banner_id, ip_address, ...)       │    │
│  └────────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────────┘
```

## Component Breakdown

### 1. Embed Script (`sponsor-ads.min.js`)

- **Size**: 3.20 KB (minified)
- **Purpose**: Client-side ad loading and tracking
- **Features**:
    - Automatic placement detection
    - Single API call for all placements
    - IntersectionObserver for impression tracking
    - Click event tracking
    - Retry logic with exponential backoff
    - Dynamic placement detection

### 2. API Layer

- **PlacementController**: Handles banner requests and tracking
- **BannerCacheService**: Manages Redis caching
- **Endpoints**:
    - `POST /api/placements/banners` - Get banners
    - `POST /api/placements/track/impression` - Track views
    - `POST /api/placements/track/click` - Track clicks

### 3. Caching Layer (Redis)

- **Banner Cache**: `banners:active` (permanent until cleared)
- **Placement Cache**: `banners:placement:{name}` (1 hour TTL)
- **Invalidation**: Automatic on banner create/update/delete

### 4. Database Layer (MySQL)

- **banners**: Store banner information
- **impressions**: Track when banners are viewed
- **clicks**: Track when banners are clicked

## Data Flow

### Banner Loading Flow

```
1. User visits external website
2. sponsor-ads.min.js loads
3. Script finds placement containers
4. Single API request with all placements
5. Server checks Redis cache
6. If cache miss, query database
7. Return banner data as JSON
8. Script renders banners in containers
9. IntersectionObserver watches for visibility
```

### Impression Tracking Flow

```
1. Banner becomes 50% visible
2. IntersectionObserver triggers
3. POST to /api/placements/track/impression
4. Server stores impression in database
5. Includes: banner_id, ip_address, user_agent, referer
```

### Click Tracking Flow

```
1. User clicks banner link
2. Click event listener triggers
3. POST to /api/placements/track/click
4. Server stores click in database
5. User redirected to target URL
```

## Performance Optimizations

### 1. Caching Strategy

- **L1 Cache**: Redis (1 hour TTL)
- **L2 Cache**: Database with indexed queries
- **Cache Warming**: Automatic on banner updates

### 2. Network Optimization

- **Minification**: 59.79% size reduction
- **Single Request**: All placements in one call
- **CDN Delivery**: Script served from CDN

### 3. Rendering Optimization

- **Lazy Loading**: Images use `loading="lazy"`
- **Intersection Observer**: Track only when visible
- **Deduplication**: Track each banner only once

## Security Measures

1. **CORS**: Configured for cross-origin requests
2. **Input Validation**: Placement names and UUIDs validated
3. **Rate Limiting**: Applied to tracking endpoints
4. **XSS Protection**: Banner content sanitized
5. **HTTPS**: Required for production deployment

## Scalability Considerations

### Current Capacity

- **Redis**: Handles thousands of requests/second
- **Database**: Indexed queries for fast lookups
- **CDN**: Global distribution for embed script

### Future Scaling

- **Database Sharding**: By placement or date
- **Read Replicas**: For analytics queries
- **Queue Workers**: For async tracking processing
- **CDN Caching**: For banner images

## Monitoring Points

1. **Script Load Time**: CDN performance
2. **API Response Time**: Server performance
3. **Cache Hit Rate**: Redis effectiveness
4. **Tracking Success Rate**: Data integrity
5. **Error Rate**: System reliability
