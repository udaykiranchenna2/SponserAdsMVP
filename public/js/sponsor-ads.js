/**
 * SponsorAds Embed Script
 * 
 * This script automatically finds ad placement containers and loads banners.
 * 
 * Usage:
 * 1. Add this script to your website: <script src="https://your-cdn.com/sponsor-ads.min.js"></script>
 * 2. Add placement containers: <div class="sponsor-ad" data-placement="homepage"></div>
 */

(function() {
    'use strict';

    // Configuration
    const CONFIG = {
        apiBaseUrl: '__API_URL_PLACEHOLDER__',
        placementClass: 'sponsor-ad',
        placementAttribute: 'data-placement',
        loadedAttribute: 'data-sponsor-loaded',
        retryAttempts: 3,
        retryDelay: 1000,
    };

    // State
    let impressionTracked = new Set();
    let clickTracked = new Set();
    let pendingImpressions = [];
    let impressionTimer = null;

    /**
     * Find all ad placement containers on the page
     */
    function findPlacements() {
        const elements = document.querySelectorAll(`.${CONFIG.placementClass}[${CONFIG.placementAttribute}]:not([${CONFIG.loadedAttribute}])`);
        const placements = [];
        const placementMap = new Map();

        elements.forEach(element => {
            const placement = element.getAttribute(CONFIG.placementAttribute);
            if (placement) {
                placements.push(placement);
                if (!placementMap.has(placement)) {
                    placementMap.set(placement, []);
                }
                placementMap.set(placement, [...placementMap.get(placement), element]);
            }
        });

        return { placements: [...new Set(placements)], placementMap };
    }

    /**
     * Fetch banners from API
     */
    async function fetchBanners(placements, attempt = 1) {
        try {
            const response = await fetch(`${CONFIG.apiBaseUrl}/placements/banners`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ placements }),
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            return data.banners || {};
        } catch (error) {
            console.error(`[SponsorAds] Failed to fetch banners (attempt ${attempt}):`, error);

            if (attempt < CONFIG.retryAttempts) {
                await new Promise(resolve => setTimeout(resolve, CONFIG.retryDelay * attempt));
                return fetchBanners(placements, attempt + 1);
            }

            return {};
        }
    }

    /**
     * Flush pending impressions to the server
     */
    async function flushImpressions() {
        if (pendingImpressions.length === 0) return;

        const uuids = [...pendingImpressions];
        pendingImpressions = [];
        impressionTimer = null;

        /* Tracking disabled for now
        try {
            await fetch(`${CONFIG.apiBaseUrl}/placements/track/impression`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ uuids }),
            });
            console.log('[SponsorAds] Tracked batch impressions:', uuids.length);
        } catch (error) {
            console.error('[SponsorAds] Failed to track batch impressions:', error);
            // On failure, you might want to re-queue, but for now we skip to avoid loops
        }
        */
       console.log('[SponsorAds] Impression tracking disabled (would have tracked:', uuids.length, ')');
    }

    /**
     * Track impression (batched)
     */
    function trackImpression(uuid) {
        if (impressionTracked.has(uuid)) {
            return;
        }

        impressionTracked.add(uuid);
        pendingImpressions.push(uuid);

        // Debounce: Wait 500ms for other impressions before sending
        if (impressionTimer) {
            clearTimeout(impressionTimer);
        }
        impressionTimer = setTimeout(flushImpressions, 500);
    }

    /**
     * Track click
     */
    async function trackClick(uuid) {
        if (clickTracked.has(uuid)) {
            return;
        }

        clickTracked.add(uuid);

        /* Tracking disabled for now
        try {
            await fetch(`${CONFIG.apiBaseUrl}/placements/track/click`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ uuid }),
            });
        } catch (error) {
            console.error('[SponsorAds] Failed to track click:', error);
        }
        */
       console.log('[SponsorAds] Click tracking disabled');
    }

    /**
     * Create banner HTML
     */
    function createBannerHTML(banner) {
        const container = document.createElement('div');
        container.className = 'sponsor-ad-banner';
        container.style.cssText = 'width: 100%; height: auto;';

        const link = document.createElement('a');
        link.href = banner.target_url;
        link.target = '_blank';
        link.rel = 'noopener noreferrer sponsored';
        link.style.cssText = 'display: block; text-decoration: none;';
        link.setAttribute('data-sponsor-uuid', banner.uuid);

        const img = document.createElement('img');
        img.src = banner.image_url;
        img.alt = banner.title;
        img.style.cssText = 'width: 100%; height: auto; display: block;';
        img.loading = 'lazy';

        link.appendChild(img);

        if (banner.link_text) {
            const text = document.createElement('div');
            text.textContent = banner.link_text;
            text.style.cssText = 'margin-top: 8px; text-align: center; font-size: 14px; color: #666;';
            link.appendChild(text);
        }

        // Track click on link
        link.addEventListener('click', function(e) {
            trackClick(banner.uuid);
        });

        container.appendChild(link);

        return container;
    }

    /**
     * Render banner in placement
     */
    function renderBanner(element, banner) {
        // Mark as loaded
        element.setAttribute(CONFIG.loadedAttribute, 'true');
        element.classList.remove('loading');

        // Clear existing content
        element.innerHTML = '';

        // Create and append banner
        const bannerHTML = createBannerHTML(banner);
        element.appendChild(bannerHTML);

        // Track impression when banner is visible
        trackImpressionWhenVisible(element, banner.uuid);
    }

    /**
     * Track impression when element is visible
     */
    function trackImpressionWhenVisible(element, uuid) {
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        trackImpression(uuid);
                        observer.unobserve(element);
                    }
                });
            }, {
                threshold: 0.5, // Track when 50% visible
            });

            observer.observe(element);
        } else {
            // Fallback: track immediately
            trackImpression(uuid);
        }
    }

    /**
     * Load and render all banners
     */
    async function loadBanners() {
        const { placements, placementMap } = findPlacements();

        if (placements.length === 0) {
            return;
        }

        console.log('[SponsorAds] Found placements:', placements);

        const banners = await fetchBanners(placements);

        console.log('[SponsorAds] Loaded banners:', banners);

        // Render banners
        placementMap.forEach((elements, placement) => {
            const banner = banners[placement];
            if (banner) {
                elements.forEach(element => {
                    renderBanner(element, banner);
                });
            } else {
                console.warn(`[SponsorAds] No banner found for placement: ${placement}`);
                elements.forEach(element => {
                    element.setAttribute(CONFIG.loadedAttribute, 'true');
                    element.classList.remove('loading');
                });
            }
        });
    }

    /**
     * Initialize the embed script
     */
    function init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', loadBanners);
        } else {
            loadBanners();
        }

        // Watch for dynamically added placements
        if ('MutationObserver' in window) {
            let mutationTimer;
            const observer = new MutationObserver(() => {
                if (mutationTimer) clearTimeout(mutationTimer);
                mutationTimer = setTimeout(loadBanners, 100);
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true,
            });
        }
    }

    // Start
    init();

    // Expose API for manual control
    window.SponsorAds = {
        reload: loadBanners,
        config: CONFIG,
    };
})();
