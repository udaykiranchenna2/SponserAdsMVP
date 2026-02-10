# API & Embed System Progress Tracker

**Status**: Planning
**Started**: 2026-02-10

## Checklist

### Caching Strategy

- [ ] Implement `BannerCacheService` (Get, Set, Flush)
- [ ] Add Observers/Events to trigger cache updates on Banner changes
- [ ] Ensure `active` banners are cached structure for fast retrieval

### Public API

- [x] Create `BannerApiController` (Implemented as `BannerEmbedController`)
- [x] Endpoint: `GET /api/banners/{uuid}` (Serve banner data/HTML)
- [ ] Endpoint: `GET /api/banners/serve/random` (Optional: for rotation if needed later, but plan says "unique banner identifier" in embed)

### Embed System

- [x] Create `GenerateEmbedCode` action (returns HTML snippet)
- [x] Add `embed_code` attribute to Banner model/resource
- [x] Add "Copy Embed Code" button to Banner Index page
- [ ] Create generic JS loader endpoint (`/js/banner-loader.js`) (Using iframe approach for now as per implementation)

### Tracking

- [ ] Create `Impression` model & migration
- [ ] Create `Click` model & migration
- [ ] Endpoint: `POST /api/tracking/impression`
- [ ] Endpoint: `GET /api/tracking/click/{uuid}` (Redirects to target URL)

## Implementation Details

- **Cache Keys**: `banners:active`, `banner:{uuid}`
- **Embed Code**: Should be an `iframe` or a generic `div` populated by JS to avoid CSS conflicts.
- **Tracking**: Use Redis to buffer writes if high traffic, or direct DB for MVP.
