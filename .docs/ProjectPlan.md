# Project Plan – Banner Embed System

## Project Overview

This project is a pure Laravel and Inertia JS application that allows creation and management of banners and generation of embeddable code for displaying banners on any external website.

The system will provide:

- Banner creation and management
- Automatic embed code generation
- Public API for banner delivery
- Redis based caching for fast performance
- Automatic cache reset on every update
- JavaScript based click and impression tracking
- Placement based ad rendering
- Text based banner links

No roles, no publishers, no sponsors, no admin module in this phase.  
This is a simple single-user banner management platform.

---

## Core Features

### 1. Banner Management

The system will allow:

- Create banner with:
    - Title
    - Image
    - Target URL
    - Link text
    - Placement type
    - Status active or inactive

- Edit banners
- Delete banners
- Enable or disable banners

---

### 2. Placement Based System

Ads will be controlled using placement identifiers.

Example placements:

- homepage
- sidebar
- footer
- header

Number of ads shown on a website will be determined by the number of placement containers added on that website.

The system will not randomly decide how many ads to show.  
The external website will control this by placing ad slots.

---

### 3. Embed Code Generation

The system will generate a universal embed code.

External websites will only need to add:

- one JavaScript file
- one or more placement containers

Example structure:

- global embed script
- HTML div elements with placement id

The embed script will automatically find placements and load correct ads.

---

### 4. Public Banner API

A lightweight public API will be created to:

- Deliver banner content based on placement
- Serve banner HTML and data
- Track impressions
- Track clicks

The API will be optimized for speed and minimal response time.

---

### 5. Redis Caching

Caching strategy:

- All active banners will be cached in Redis
- Placement wise data will be stored in cache
- No database query on every request
- Fast response for external sites

---

### 6. Cache Reset Logic

On every update event such as:

- Banner create
- Banner update
- Banner delete
- Status change

System will automatically:

- Clear related Redis cache
- Rebuild fresh cache

This ensures latest banners are always served.

---

### 7. Click Tracking

Click tracking will be implemented using JavaScript.

Process:

- User clicks banner
- JavaScript sends AJAX request to portal
- Click is logged in backend
- User is redirected to sponsor URL

No forced redirect through portal is required.

---

### 8. Impression Tracking

Impressions will be counted when:

- API delivers banner to external site
- Placement is successfully rendered

Tracking will be handled from backend API calls.

---

## Embed Script Behavior

The embed system will work as follows:

1. External site includes one embed script
2. Script runs when DOM is ready
3. Script finds all ad slot elements
4. Script collects placement ids
5. API request is sent to portal
6. Banners are returned
7. Script renders banners inside correct placements

The script does not wait for full page load.  
It executes as soon as the DOM structure is ready.

---

## Technical Stack

### Backend

- Laravel framework
- Inertia JS
- Redis for caching
- MySQL for database
- Public JSON API

### Frontend

- Inertia JS
- Vue based UI
- Tailwind CSS

---

## Database Components

Main entities:

- banners
- clicks
- impressions
- placement cache

---

## System Flow

1. User creates or updates a banner
2. System stores data in database
3. Redis cache is refreshed
4. Embed code is generated
5. External website loads embed script
6. Script calls API with placement ids
7. API serves banner from cache
8. Clicks and impressions are logged

---

## Performance Strategy

- All banner data cached in Redis
- Minimal API responses
- Images served directly
- Lightweight JavaScript embed
- Fast placement based loading
- No heavy processing on requests

---

## Deliverables

- Working Laravel Inertia application
- Banner CRUD system
- Placement management
- Embed code generator
- Public API for banner delivery
- Redis caching implementation
- JavaScript based tracking

---

## Future Scope

- Multiple user roles
- Publisher and sponsor modules
- Advanced rotation algorithms
- Detailed analytics
- Payment integration
- Weighted placements

---

End of Project Plan
