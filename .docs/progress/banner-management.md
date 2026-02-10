# Banner Management Progress Tracker

**Status**: In Progress
**Started**: 2026-02-10

## Checklist

### Database

- [x] Migrations created (banners table)
- [x] Models created (Banner)
- [x] Relationships defined (if any)
- [x] Seeders / Factories created

### Logic (Backend)

- [x] Action Classes implemented (CreateBanner, UpdateBanner, DeleteBanner)
- [x] Form Requests created (StoreBannerRequest, UpdateBannerRequest)
- [x] Controllers & Routes setup (BannerController)
- [x] API Resources (BannerResource)

### Frontend

- [x] Components created (BannerForm, BannerList)
- [x] Pages / Views built (Index, Create, Edit)
- [x] State management (if applicable)

### Quality Assurance

- [ ] Automated Tests (Feature/Unit) written & passing
- [ ] Manual testing performed

## Implementation Details

- Banner table schema:
    - id
    - title (string)
    - image_path (string)
    - target_url (string)
    - link_text (string, nullable)
    - status (enum: active, inactive)
    - timestamps
