/**
 * Frontend tracking — site genelinde click/page_view eventlerini
 * server-side endpoint'e ve Meta Pixel'e gönderir.
 *
 * Usage:
 *   <a data-track="whatsapp" data-track-payload='{"campaign":"Bayram"}'>...</a>
 *   <button data-track="phone">...</button>
 *
 * window.ArsiTracking.trackEvent(eventName, payload) — manuel çağrı için
 */

const API_ENDPOINT = '/api/track-event';

function generateUUID() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, c => {
        const r = Math.random() * 16 | 0;
        return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
    });
}

function mapToMetaEvent(name) {
    // Frontend'deki default mapping (admin'de override edilir, server-side decide eder)
    const map = {
        whatsapp_click: 'Lead',
        phone_click: 'Contact',
        email_click: 'Contact',
        lead_form_submit: 'Lead',
        campaign_click: 'InitiateCheckout',
        room_view: 'ViewContent',
        gallery_view: 'ViewContent',
        scroll_depth: 'CustomEvent',
        time_on_page: 'CustomEvent',
    };
    return map[name] || 'CustomEvent';
}

window.ArsiTracking = {
    async trackEvent(eventName, payload = {}) {
        const eventId = generateUUID();

        // 1. Client-side Meta Pixel (varsa)
        if (typeof fbq !== 'undefined') {
            try {
                fbq('track', mapToMetaEvent(eventName), payload, { eventID: eventId });
            } catch (e) { /* sessizce geç */ }
        }

        // 2. Google
        if (typeof gtag !== 'undefined') {
            try {
                gtag('event', eventName, payload);
            } catch (e) { /* sessizce geç */ }
        }

        // 3. Server-side (CAPI + log)
        try {
            await window.axios.post(API_ENDPOINT, {
                event_name: eventName,
                event_id: eventId,
                payload,
                page: window.location.pathname,
                referrer: document.referrer,
            });
        } catch (e) {
            // Network hatası — sessizce geç, kullanıcı tarafı etkilenmesin
        }
    },
};

// Scroll derinliği (25/50/75/90%) — her eşik sayfa başına bir kez fire
function initScrollDepthTracking() {
    const thresholds = [25, 50, 75, 90];
    const fired = new Set();
    let ticking = false;

    function check() {
        const docHeight = document.documentElement.scrollHeight;
        const viewport = window.innerHeight;
        // Sayfa scroll edilemiyorsa atla
        if (docHeight <= viewport + 50) return;

        const scrolled = window.scrollY + viewport;
        const pct = Math.round((scrolled / docHeight) * 100);

        for (const t of thresholds) {
            if (pct >= t && !fired.has(t)) {
                fired.add(t);
                window.ArsiTracking.trackEvent('scroll_depth', {
                    depth_percent: t,
                    page: window.location.pathname,
                });
            }
        }
    }

    window.addEventListener('scroll', () => {
        if (!ticking) {
            requestAnimationFrame(() => { check(); ticking = false; });
            ticking = true;
        }
    }, { passive: true });
}

// Aktif sayfada geçirilen süre (30s/60s/180s) — sekme arka planda ise saymaz
function initTimeOnPageTracking() {
    const thresholds = [30, 60, 180]; // saniye
    const fired = new Set();
    let activeMs = 0;
    let lastActiveAt = Date.now();

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            activeMs += Date.now() - lastActiveAt;
        } else {
            lastActiveAt = Date.now();
        }
    });

    setInterval(() => {
        if (document.hidden) return;
        const currentActiveMs = activeMs + (Date.now() - lastActiveAt);
        const secs = Math.floor(currentActiveMs / 1000);

        for (const t of thresholds) {
            if (secs >= t && !fired.has(t)) {
                fired.add(t);
                window.ArsiTracking.trackEvent('time_on_page', {
                    seconds: t,
                    page: window.location.pathname,
                });
            }
        }
    }, 5000);
}

// Auto-bind: data-track attribute'lu link/button'lara event ekle
function bindTracking() {
    document.querySelectorAll('[data-track]').forEach(el => {
        if (el.dataset.trackBound) return;
        el.dataset.trackBound = '1';

        el.addEventListener('click', () => {
            const eventName = el.dataset.track + '_click';
            // veya direkt name'i kullan: data-track="whatsapp_click"
            const finalName = el.dataset.track.includes('_') ? el.dataset.track : eventName;

            let payload = {};
            if (el.dataset.trackPayload) {
                try { payload = JSON.parse(el.dataset.trackPayload); }
                catch (e) { /* invalid JSON, geç */ }
            }

            window.ArsiTracking.trackEvent(finalName, payload);
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    bindTracking();
    initScrollDepthTracking();
    initTimeOnPageTracking();

    // Sayfa görüntüleme otomatik fire — yalnızca server-side log
    // (Meta Pixel kendi PageView'unu init scripte zaten fire etti, dedup için)
    if (window.axios) {
        window.axios.post(API_ENDPOINT, {
            event_name: 'page_view',
            page: window.location.pathname,
            referrer: document.referrer,
        }).catch(() => { /* sessizce geç */ });
    }
});

// Alpine init sonrası dinamik elementler için yeniden bind (optional)
document.addEventListener('alpine:initialized', bindTracking);
