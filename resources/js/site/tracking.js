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
        lead_form_submit: 'Lead',
        campaign_click: 'InitiateCheckout',
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
