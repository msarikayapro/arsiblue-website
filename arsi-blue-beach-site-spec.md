# ARSİ BLUE BEACH — SİTE PROJESİ SPEC

> **Bu dosya Claude Code için master spec'tir.** Projeyi sıfırdan ayağa kaldırmak için gereken her şeyi içerir. Sırasıyla oku, sırasıyla uygula. Sorularını sorgulamadan varsayım yapma — belirsizse dur ve sor.

---

## 0. PROJE ÖZETİ

**Proje:** Alanya'daki Arsi Blue Beach 4 yıldızlı aile otelinin tanıtım ve **lead toplama** sitesi. Site, oteli satan yetkili seyahat acentası tarafından işletiliyor. Online rezervasyon YOK — site, ziyaretçiyi WhatsApp/telefon araması veya "Bilgi Al" formuyla lead'e dönüştürür, satış telefon/WhatsApp üzerinden kapatılır.

**Ana hedef:** Meta + Google reklamlarından gelen trafiği nitelikli lead'e çevirmek. Sitede ölçülen ana dönüşümler:
1. WhatsApp butonu tıklama
2. Telefon numarası tıklama
3. "Bilgi Al" form gönderimi

**Stratejik konum:** Site, rakip acentaların "tek yetkili biziz" yalanına karşı **şeffaflık + profesyonellik + hızlı yanıt** ile rekabet eder. "Resmi otel sitesi" gibi davranmaz — yetkili acenta olduğunu açıkça söyler.

---

## 1. TEKNİK STACK

### Backend
- **PHP:** 8.2 veya 8.3 (8.2+ kesinlikle)
- **Framework:** Laravel 11
- **Veritabanı:** MySQL 8.0 veya MariaDB 10.6+
- **Composer paketleri (zorunlu):**
  - `laravel/framework: ^11.0`
  - `intervention/image: ^3.0` (görsel resize için)
  - `spatie/laravel-sitemap` (sitemap.xml üretimi için)
  - `guzzlehttp/guzzle` (CAPI HTTP istekleri için — Laravel'de gelir)

### Frontend
- **CSS:** Tailwind CSS 3.x (utility-first)
- **JS:** Alpine.js 3.x (CDN, hafif etkileşim için)
- **Build tool:** Vite (Laravel default)
- **İkonlar:** Heroicons (outline) — Blade component olarak (`<x-heroicon-o-...>`)
  - Paket: `blade-ui-kit/blade-heroicons`
- **Fontlar:** Inter (Google Fonts, self-hosted)

### **Admin paneli için hazır paket YOK** — Filament, Backpack, Nova kullanılmayacak. Tamamen custom Tailwind + Blade.

### Hosting Kısıtları (KRİTİK)
- **cPanel paylaşımlı hosting** (Linux + Litespeed/Apache)
- `public_html` ana dizin
- **SSH erişimi sınırlı** — `php artisan` komutları her zaman çalışmaz
- **`php artisan storage:link` çalışmıyor** — workaround: `public/storage` klasörüne direkt yazılır
- Deploy: **GitHub Actions → FTP/SFTP**
- Migration'lar **gizli web rotası** ile tetiklenir (detay aşağıda)

---

## 2. PROJE YAPISI

Standart Laravel 11 yapısı + özel klasörler:

```
arsi-blue-beach/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Site/                    # Frontend controller'ları
│   │   │   │   ├── HomeController.php
│   │   │   │   ├── LandingController.php
│   │   │   │   ├── GalleryController.php
│   │   │   │   ├── ContactController.php
│   │   │   │   ├── LeadController.php
│   │   │   │   └── LegalController.php
│   │   │   └── Admin/                   # Admin controller'ları
│   │   │       ├── AuthController.php
│   │   │       ├── DashboardController.php
│   │   │       ├── PageController.php
│   │   │       ├── CampaignController.php
│   │   │       ├── RoomController.php
│   │   │       ├── GalleryController.php
│   │   │       ├── FaqController.php
│   │   │       ├── TrackingController.php
│   │   │       ├── SeoController.php
│   │   │       ├── EventLogController.php
│   │   │       ├── LeadController.php
│   │   │       ├── ContactController.php
│   │   │       ├── AgencyController.php
│   │   │       └── SettingsController.php
│   │   ├── Middleware/
│   │   │   ├── AdminAuth.php
│   │   │   └── RateLimitLeadForm.php
│   │   └── Requests/
│   │       └── (form request validation sınıfları)
│   ├── Models/
│   │   ├── Setting.php
│   │   ├── Campaign.php
│   │   ├── Page.php
│   │   ├── PageContent.php
│   │   ├── Room.php
│   │   ├── Gallery.php
│   │   ├── Faq.php
│   │   ├── SeoMeta.php
│   │   ├── EventLog.php
│   │   ├── Lead.php
│   │   ├── ContactInfo.php
│   │   ├── AgencyInfo.php
│   │   └── Admin.php
│   ├── Services/
│   │   ├── MetaCapiService.php          # Server-side CAPI gönderimi
│   │   ├── EventTrackingService.php     # Genel event yönetimi
│   │   ├── SettingService.php           # Settings cache yönetimi
│   │   └── SchemaOrgService.php         # JSON-LD üretimi
│   └── View/
│       └── Composers/
│           └── GlobalDataComposer.php   # Tüm view'lara settings inject eder
├── database/
│   └── migrations/
├── public/
│   ├── storage/                         # Manuel oluşturulacak, storage:link yerine
│   │   ├── uploads/
│   │   │   ├── gallery/
│   │   │   ├── rooms/
│   │   │   ├── campaigns/
│   │   │   └── pages/
│   │   └── agency/                      # TÜRSAB belgesi vb. PDF'ler
│   └── (Laravel'in normal public assets'leri)
├── resources/
│   ├── css/
│   │   └── app.css                      # Tailwind import + custom CSS
│   ├── js/
│   │   ├── app.js                       # Alpine.js + tracking utils
│   │   ├── site/                        # Frontend JS modülleri
│   │   └── admin/                       # Admin JS modülleri
│   └── views/
│       ├── layouts/
│       │   ├── site.blade.php           # Frontend ana layout
│       │   └── admin.blade.php          # Admin ana layout
│       ├── components/                  # Blade component'leri
│       │   ├── site/
│       │   │   ├── header.blade.php
│       │   │   ├── footer.blade.php
│       │   │   ├── sticky-cta.blade.php
│       │   │   ├── campaign-card.blade.php
│       │   │   ├── room-card.blade.php
│       │   │   ├── faq-accordion.blade.php
│       │   │   ├── lead-form.blade.php
│       │   │   ├── whatsapp-button.blade.php
│       │   │   ├── tracking-pixel.blade.php
│       │   │   └── schema-org.blade.php
│       │   └── admin/
│       │       ├── sidebar.blade.php
│       │       ├── topbar.blade.php
│       │       ├── kpi-card.blade.php
│       │       ├── badge.blade.php
│       │       ├── toggle.blade.php
│       │       ├── form-input.blade.php
│       │       ├── file-upload.blade.php
│       │       └── empty-state.blade.php
│       ├── site/                        # Frontend sayfaları
│       │   ├── home.blade.php
│       │   ├── landing/
│       │   │   ├── bayrama-ozel.blade.php
│       │   │   ├── balayi-paketi.blade.php
│       │   │   └── aile-oteli.blade.php
│       │   ├── gallery.blade.php
│       │   ├── contact.blade.php
│       │   └── legal/
│       │       ├── kvkk.blade.php
│       │       ├── cerez.blade.php
│       │       └── hakkimizda.blade.php
│       ├── admin/                       # Admin sayfaları
│       │   ├── auth/
│       │   │   └── login.blade.php
│       │   ├── dashboard.blade.php
│       │   ├── pages/
│       │   ├── campaigns/
│       │   ├── rooms/
│       │   ├── gallery/
│       │   ├── faqs/
│       │   ├── tracking/
│       │   ├── seo/
│       │   ├── events/
│       │   ├── leads/
│       │   ├── contact/
│       │   ├── agency/
│       │   └── settings/
│       └── emails/
│           └── new-lead.blade.php       # Yeni lead bildirimi
├── routes/
│   ├── web.php                          # Frontend route'ları
│   ├── admin.php                        # Admin route'ları (web.php'den require edilir)
│   └── api.php                          # CAPI ve event tracking endpoint'leri
├── tailwind.config.js
├── vite.config.js
└── .env.example
```

---

## 3. VERİTABANI ŞEMASI

Aşağıdaki migration'lar sırasıyla oluşturulacak. Her tablo için tam migration kodu üret.

### 3.1. `admins`
Tek kullanıcı için ama scalable.
```
id, name, email, email_verified_at, password, remember_token, created_at, updated_at
```

### 3.2. `settings`
Key-value yapısı. Tüm global ayarlar burada. **Cache'lenmeli.**
```
id, key (unique, indexed), value (text/longtext), type (text/number/boolean/json/file), 
group (general/tracking/contact/agency/seo), created_at, updated_at
```

Default kayıtlar (seeder'da):
- `site_name`, `site_tagline`, `site_logo`, `site_favicon`
- `meta_pixel_id`, `meta_capi_token`, `meta_capi_test_code`
- `gtm_container_id`, `ga4_measurement_id`
- `google_ads_conversion_id`, `google_ads_conversion_label`
- `google_search_console_verification`
- `tiktok_pixel_id`
- `phone_landline`, `phone_whatsapp`, `phone_gsm`
- `email`, `address`, `google_maps_embed`
- `instagram_url`, `facebook_url`, `tripadvisor_url`, `google_business_url`
- `maintenance_mode`, `maintenance_message`

### 3.3. `campaigns`
```
id, title, subtitle, label_text, 
old_price, new_price, currency (default 'TL'),
nights, adults, children, child_age_limit,
rooms_left, urgency_text, 
countdown_enabled (bool), valid_until (datetime),
description (text), 
included_items (json — array of strings),
hero_image, gallery_images (json),
is_active (bool), show_on_homepage (bool),
visible_landings (json — array of landing slugs),
sort_order, created_at, updated_at
```

### 3.4. `pages`
Frontend'deki tüm dinamik sayfalar.
```
id, slug (unique, indexed), title, 
template (home/landing/legal/custom),
is_active (bool), sort_order,
created_at, updated_at
```

Seed: home, bayrama-ozel, balayi-paketi, aile-oteli, galeri, iletisim, kvkk, cerez, hakkimizda

### 3.5. `page_contents`
Her sayfanın bölümleri. Esnek yapı.
```
id, page_id (fk), section_key (string, indexed), 
content_type (text/html/image/json),
content (longtext), 
sort_order, is_active (bool),
created_at, updated_at
```

Örnek section_key'ler:
- home: `hero_title`, `hero_subtitle`, `hero_image`, `about_text`, `why_us_items`, vb.
- landing: `hero_title`, `hero_subtitle`, `benefits_list`, `cta_text`, vb.

### 3.6. `rooms`
```
id, name, slug, short_description, long_description (html),
features (json — array of feature keys: bed_double, ac, tv, wifi, vb.),
availability_note (default "Müsaitliğe göre"),
main_image, images (json),
is_active (bool), sort_order, 
created_at, updated_at
```

### 3.7. `gallery`
```
id, category (havuz/plaj/oda/yemek/animasyon/dis_mekan),
image_path, thumbnail_path,
alt_text, 
sort_order, is_active (bool),
created_at, updated_at
```

### 3.8. `faqs`
```
id, question, answer (text), category (genel/oda/odeme/aile),
visible_pages (json — array of page slugs, null = all pages),
sort_order, is_active (bool),
created_at, updated_at
```

### 3.9. `seo_metas`
Her sayfa için meta tag'ler.
```
id, page_slug (unique, indexed),
title (max 60), description (max 160), keywords,
og_title, og_description, og_image,
canonical_url, robots (default 'index,follow'),
schema_json (text — özel JSON-LD ekleme için),
created_at, updated_at
```

### 3.10. `event_logs`
Site'deki tüm önemli event'ler.
```
id, event_name (lead/contact/whatsapp_click/phone_click/page_view/form_submit),
session_id, user_ip, 
country, city, region,
device (mobile/tablet/desktop), browser, os,
referrer, utm_source, utm_medium, utm_campaign, utm_content, utm_term,
landing_page, current_page,
payload (json — event-spesifik veri),
fb_pixel_sent (bool), fb_capi_sent (bool), 
ga4_sent (bool), gads_sent (bool),
event_id (uuid — Meta deduplication için),
created_at
```

### 3.11. `leads`
"Bilgi Al" formu gönderileri.
```
id, name, phone, message (text),
landing_page, utm_source, utm_medium, utm_campaign,
user_ip, user_agent,
status (yeni/aranildi/sonuclandi/iptal),
notes (text — admin notu),
event_log_id (fk, nullable),
created_at, updated_at
```

### 3.12. `email_queue`
Yeni lead geldiğinde admin'e mail için (Laravel queue).
```
Standart Laravel jobs/failed_jobs tabloları
```

### Indeksler ve Performance Notları

- `event_logs.created_at` indexed (rapor için)
- `event_logs.event_name` indexed
- `leads.created_at` indexed
- `leads.status` indexed
- `settings.key` unique
- `pages.slug` unique
- `seo_metas.page_slug` unique

---

## 4. MODELLER VE İLİŞKİLER

**Önemli implementasyon notları:**

### Setting Model
```php
- Cache::remember ile her get işlemi cache'lenir (1 saat TTL)
- set() metodu cache'i invalidate eder
- Type-aware get(): boolean ise cast, json ise decode, vb.
- Helper: setting('meta_pixel_id') global fonksiyonu (app/helpers.php)
```

### Campaign Model
```php
- scope: active() — is_active=true and valid_until > now()
- scope: featuredOnHomepage() — show_on_homepage=true
- accessor: priceFormatted() — "16.250 TL" format (Turkish locale)
- accessor: oldPriceFormatted()
- accessor: countdownData() — array with days/hours/minutes/seconds remaining
- accessor: progressPercent() — rooms_left bazlı (10 oda = %100 başlangıç varsayımı)
```

### Page Model
```php
- relationship: hasMany(PageContent::class)
- helper: getContent('section_key') — direkt section çağırma
- scope: bySlug($slug)
```

### EventLog Model
```php
- static fire($eventName, $payload, $request) — event yaratıp tracking gönderir
- relationship: belongsTo(Lead::class) — sadece form submit'lerde
- scope: today(), thisWeek(), byEvent($name)
- scope: byCity($city), byDevice($device)
```

### Lead Model
```php
- relationship: belongsTo(EventLog::class)
- scope: byStatus($status)
- accessor: createdAtFormatted() — "5 dk önce" relative time
```

---

## 5. ROUTE'LAR

### 5.1. `routes/web.php`
```
GET  /                           → Site\HomeController@index             [home]
GET  /bayrama-ozel               → Site\LandingController@bayrama        [landing.bayrama]
GET  /balayi-paketi              → Site\LandingController@balayi         [landing.balayi]
GET  /aile-oteli                 → Site\LandingController@aile           [landing.aile]
GET  /galeri                     → Site\GalleryController@index          [gallery]
GET  /iletisim                   → Site\ContactController@index          [contact]
POST /bilgi-al                   → Site\LeadController@store             [lead.store]
GET  /kvkk                       → Site\LegalController@kvkk             [legal.kvkk]
GET  /cerez-politikasi           → Site\LegalController@cerez            [legal.cerez]
GET  /hakkimizda                 → Site\LegalController@about            [legal.about]
GET  /sitemap.xml                → Site\SitemapController@index          [sitemap]
GET  /robots.txt                 → Site\SitemapController@robots         [robots]

require __DIR__.'/admin.php';
```

### 5.2. `routes/admin.php`
```
// Login
GET  /admin/login                → Admin\AuthController@showLogin
POST /admin/login                → Admin\AuthController@login
POST /admin/logout               → Admin\AuthController@logout

// Korumalı (admin.auth middleware)
GET  /admin                      → Admin\DashboardController@index       [admin.dashboard]
GET  /admin/dashboard            → Admin\DashboardController@index

// Sayfa İçerikleri
GET  /admin/pages                → Admin\PageController@index
GET  /admin/pages/{slug}         → Admin\PageController@edit
PUT  /admin/pages/{slug}         → Admin\PageController@update
POST /admin/pages/upload-image   → Admin\PageController@uploadImage

// Kampanyalar
GET  /admin/campaigns            → Admin\CampaignController@index
GET  /admin/campaigns/create     → Admin\CampaignController@create
POST /admin/campaigns            → Admin\CampaignController@store
GET  /admin/campaigns/{id}/edit  → Admin\CampaignController@edit
PUT  /admin/campaigns/{id}       → Admin\CampaignController@update
DELETE /admin/campaigns/{id}     → Admin\CampaignController@destroy
POST /admin/campaigns/{id}/toggle → Admin\CampaignController@toggleActive

// Odalar
GET  /admin/rooms                → Admin\RoomController@index
... (standart CRUD)

// Galeri
GET  /admin/gallery              → Admin\GalleryController@index
POST /admin/gallery/upload       → Admin\GalleryController@upload (bulk)
PUT  /admin/gallery/{id}         → Admin\GalleryController@update
DELETE /admin/gallery/{id}       → Admin\GalleryController@destroy
POST /admin/gallery/reorder      → Admin\GalleryController@reorder

// SSS
GET  /admin/faqs                 → Admin\FaqController@index
... (standart CRUD + reorder)

// Tracking & Pixels (KRİTİK MODÜL)
GET  /admin/tracking             → Admin\TrackingController@index
PUT  /admin/tracking/meta        → Admin\TrackingController@updateMeta
PUT  /admin/tracking/google      → Admin\TrackingController@updateGoogle
PUT  /admin/tracking/tiktok      → Admin\TrackingController@updateTiktok
POST /admin/tracking/test-capi   → Admin\TrackingController@testCapi
GET  /admin/tracking/health      → Admin\TrackingController@health (JSON)

// SEO
GET  /admin/seo                  → Admin\SeoController@index
GET  /admin/seo/{slug}           → Admin\SeoController@edit
PUT  /admin/seo/{slug}           → Admin\SeoController@update

// Event Logları
GET  /admin/events               → Admin\EventLogController@index
GET  /admin/events/{id}          → Admin\EventLogController@show (modal/AJAX)
GET  /admin/events/export        → Admin\EventLogController@export (CSV)

// Lead'ler
GET  /admin/leads                → Admin\LeadController@index
GET  /admin/leads/{id}           → Admin\LeadController@show
PUT  /admin/leads/{id}/status    → Admin\LeadController@updateStatus
PUT  /admin/leads/{id}/notes     → Admin\LeadController@updateNotes
DELETE /admin/leads/{id}         → Admin\LeadController@destroy

// İletişim
GET  /admin/contact              → Admin\ContactController@edit
PUT  /admin/contact              → Admin\ContactController@update

// Acenta Bilgileri
GET  /admin/agency               → Admin\AgencyController@edit
PUT  /admin/agency               → Admin\AgencyController@update
POST /admin/agency/tursab-upload → Admin\AgencyController@uploadTursab

// Genel Ayarlar
GET  /admin/settings             → Admin\SettingsController@index
PUT  /admin/settings             → Admin\SettingsController@update

// Sistem
GET  /admin/system-update-{secret} → Admin\SystemController@migrate  // Gizli rotada — env'den okunur
```

### 5.3. `routes/api.php`
```
POST /api/track-event            → ApiController@trackEvent
  - Frontend'den event tracking için
  - Server-side CAPI gönderir
  - event_logs tablosuna yazar
  - Rate limit: 60 req/min per IP

POST /api/lead-submit            → ApiController@submitLead
  - "Bilgi Al" form submit
  - Honeypot kontrolü
  - Validation
  - Lead + EventLog yarat
  - CAPI Lead event gönder
  - Admin'e mail gönder (queue)
```

---

## 6. FRONTEND — SAYFALAR

> **KRITIK:** Tüm copy, "kırmızı çizgiler" listesine uyacak. Yanlış ifadeler **asla** yazılmayacak:
> - ❌ "Snack bar" — bahsedilmeyecek
> - ❌ "Yürüme mesafesi" — yerine "yakın mesafede"
> - ❌ Metrekare bilgisi — yerine "konforlu odalar"
> - ❌ "Deniz manzaralı oda" kesin vaat — yerine "müsaitliğe göre deniz manzaralı odalar"
> - ❌ Ön ödeme yüzdesi — sadece "ön ödemeli rezervasyon"
> - ❌ Çocuk kulübü, oda servisi — yok, bahsedilmeyecek
> - ❌ "Resmi site" iddiası — yetkili acentayız, açık konumlanma

### 6.1. Ana Sayfa (`/`)

**Layout sırası:**
1. Hero (full-screen, kampanya floating card ile)
2. Güven şeridi (5 ikon: denize sıfır, her şey dahil, aile oteli, ön ödeme, 3 havuz)
3. Hakkımızda (otel tanıtımı, 4 mini badge kart)
4. Kampanya bölümü (büyük, ~36.000 → 16.250 TL, geri sayım, içerik listesi)
5. Odalar (3 oda kartı)
6. Tesis & Eğlence (4 kart masonry — aqua park, çocuk havuzu, kapalı havuz, plaj)
7. Özel anlar (balayı paketi tanıtımı)
8. Konum & Çevre (Google Maps + yakın yerler listesi)
9. **Neden bizden rezervasyon?** (acenta avantajları — 6 kart)
10. SSS accordion
11. Final CTA (büyük lacivert bölüm, 3 iletişim kartı)
12. Footer

**Tüm içerikler `page_contents` tablosundan dinamik olarak çekilecek.** Hardcoded copy YOK. İlk seeder, default içerikleri yükleyecek.

### 6.2. Landing — `/bayrama-ozel`

Kısa, odaklı, tek dönüşüm hedefi:
1. Hero (kampanya devasa kart)
2. Geri sayım
3. Paket dahil olanlar listesi
4. Otel mini tanıtım (4 mini kart)
5. CTA bölümü (WhatsApp + telefon + bilgi al formu)
6. Hızlı SSS (5 soru)
7. Footer

### 6.3. Landing — `/balayi-paketi`

Romantik tema, çift odaklı:
1. Hero (romantik görsel, "Balayınızı Akdeniz'de Taçlandırın")
2. Paket içeriği (oda süsleme + meyve + şarap)
3. Romantik bölüm (deniz manzarası — "müsaitliğe göre")
4. Çevre (marina, restoran — yakın mesafede)
5. Bilgi Al formu
6. Footer

### 6.4. Landing — `/aile-oteli`

Aile odaklı:
1. Hero (çocuklu aile havuzda)
2. Aile özellikleri (çocuk havuzu, aqua park, animasyon, açık büfe)
3. Güvenlik vurgusu (pozitif framing — %100 aile oteli)
4. Aile odası seçenekleri
5. Aile SSS
6. CTA + Footer

### 6.5. Galeri (`/galeri`)

Kategori tab'ları + lightbox. JS: vanilla veya Alpine.js ile basit lightbox.

### 6.6. İletişim (`/iletisim`)

- 3 telefon kartı (büyük, tıklanabilir)
- Adres + Google Maps embed
- Çalışma saatleri (varsa)
- Bilgi Al formu
- TÜRSAB belgesi (PDF link)

### 6.7. Hukuki Sayfalar

- `/kvkk` — Acenta'nın veri sorumlusu olduğu metin (placeholder şablon)
- `/cerez-politikasi` — Cookie kullanımı + admin'den eklenen pixel'lere uygun açıklama
- `/hakkimizda` — Acenta tanıtımı (placeholder)

---

## 7. ADMIN PANEL

> **KULLANICI ÖNCELİĞİ:** İçerik düzenleme kolaylığı + Meta/Google tracking kurulum kolaylığı. Diğer modüller minimal kalabilir.

### 7.1. Dashboard

**Layout:**
- Hoş geldin bandı (gradient)
- 4 KPI kartı (bugün ziyaret, WhatsApp, telefon, form submit — son 7 gün sparkline trendi)
- Aktif kampanya özet kartı (geri sayım + oda durumu + hızlı düzenle butonu)
- Hızlı aksiyon paneli (6 büyük buton: kampanya fiyat, oda sayısı, foto yükle, pixel ID, SSS ekle, iletişim)
- Son 10 event (canlı yenilenir, AJAX 30 sn'de bir)
- Tracking sağlık durumu kartı (Pixel/CAPI/GA4/GTM yeşil mi)

**Routes:** `/admin` ve `/admin/dashboard` her ikisi de buraya gider.

### 7.2. Sayfa İçerikleri (KULLANICININ ÖNCELİĞİ)

**Liste sayfası:** Tüm pages tablosu. Her satır: sayfa adı, slug, durum, son güncelleme, [Düzenle] butonu.

**Düzenleme sayfası (`/admin/pages/{slug}`):**

İçerik editörü **section bazlı** olacak. Her section_key bir form kartı.

**Layout:**
- Sol kolon (form): Page contents listesi, her section bir card
- Sağ kolon (önizleme): Iframe ile sayfa preview, mobile/desktop toggle

**Section tipleri:**
- **Text input:** Kısa metinler (başlık, alt başlık)
- **Textarea:** Orta uzunluk
- **Rich text editor:** Detaylı açıklamalar (TinyMCE veya Trix editör)
- **Image upload:** Tek görsel (drag-drop + preview + alt text)
- **Multi-image:** Birden fazla görsel (sıralanabilir grid)
- **JSON list:** Tekrarlanan öğeler (örn: "neden biz" 6 madde)
- **Checkbox/Toggle:** Boolean ayarlar
- **Color picker:** Section bazlı renk (opsiyonel)

**Auto-save:** 3 saniyede bir auto-save (AJAX), sağ üstte "Kaydedildi ✓" göstergesi.

**Bu modül site'nin "DNA"sı** — kullanıcı buraya çok zaman harcayacak, UX ekstra önemli.

### 7.3. Kampanyalar

**Liste:** Tablo görünümü, durum/fiyat/oda/bitiş kolonları.

**Düzenleme:** İki kolon layout (form sol, canlı önizleme sağ). Form kartları:
1. Temel bilgiler (başlık, alt başlık, etiket)
2. Fiyatlandırma (eski/yeni fiyat, para birimi)
3. Konaklama detayı (gece, yetişkin, çocuk, yaş limiti)
4. Aciliyet (kalan oda, aciliyet metni, geri sayım toggle, bitiş tarihi/saati)
5. İçerik listesi (drag-drop, repeatable)
6. Görseller (ana görsel + yedek galeri)
7. Yayın (aktif toggle, hangi landing'lerde gözüksün, ana sayfa toggle)

**Auto-save aktif.** Form pasif kalırsa 3 sn sonra otomatik kaydet.

### 7.4. Pixel & Tag Yönetimi (KULLANICININ ÖNCELİĞİ)

**Tab navigation üstte:** Meta | Google | TikTok

**Meta Tab:**

**Card 1 — Meta Pixel:**
- Pixel ID input (validation: sadece rakam, 15-16 hane)
- Test Event Code input
- Durum badge'i (yeşil/kırmızı, son event zamanı)
- "Pixel Helper'ı Aç" buton (Chrome ext linki)

**Card 2 — Conversions API:**
- Access Token input (password tipi, göster/gizle toggle, **DB'de hash'lenmiş tutulmaz** — encrypted cast kullan)
- Test Event Code input (Pixel'den ayrı olabilir)
- Aktif toggle
- "Test Event Gönder" buton — Meta'ya gerçek test eventi gönderir (debug için)
- Son CAPI gönderim zamanı + durum

**Card 3 — Event Mapping (tablo):**
Site aksiyonu | Meta Event Adı | Aktif

Default mapping:
- WhatsApp tıklama → `Lead` ✅
- Telefon tıklama → `Contact` ✅
- Form submit → `Lead` ✅
- Kampanya kartı tık → `InitiateCheckout` ✅
- Sayfa görüntüleme → `PageView` ✅ (otomatik)

Her satırın event adı dropdown ile değiştirilebilir, aktif/pasif toggle'ı var.

**Google Tab:**

- **GTM Container ID** input (format: GTM-XXXXXXX, validation)
- **GA4 Measurement ID** input (format: G-XXXXXXXXXX)
- **Google Ads Conversion ID** input (format: AW-XXXXXXXXX)
- **Google Ads Conversion Label** input
- **Search Console Verification Meta Tag** input (sadece içerik kısmı, full tag değil)
- Her input'un durum badge'i

**TikTok Tab:**
- TikTok Pixel ID input
- TikTok Events API token (encrypted)
- Aktif toggle

**Üst Status Banner:**
Sayfa açılışında AJAX ile `/admin/tracking/health` çağrılır, sonuca göre yeşil veya kırmızı banner.

### 7.5. SEO & Meta

**Liste:** Tüm sayfalar (home, landingler, galeri, iletişim, vb.) — her satır: sayfa adı, title, description önizlemesi, durum.

**Düzenleme:**
- Title (input + karakter sayacı, max 60, sarı uyarı 55+, kırmızı 60+)
- Description (textarea + sayaç, max 160)
- Keywords (input — eski usul ama yine de)
- Canonical URL (input)
- Robots (select)
- OG Title (input)
- OG Description (textarea)
- OG Image (upload)
- Custom Schema JSON (textarea — advanced)

**Sağda Google sonuç sayfası preview** (Title + URL + Description nasıl gözükecek).

### 7.6. Event Logları

**Liste:**
- Üstte filtre bar (tarih aralığı, event tipi, şehir, cihaz, kaynak, arama)
- KPI özet (4 küçük kart — filtreye göre güncellenir)
- Ana tablo: Zaman, Event, Şehir, Cihaz, Kaynak, Sayfa, [Detay] buton
- Pagination (50/sayfa)
- "CSV Export" butonu

**Detay Modal:** Tıklayınca side drawer açılır, event'in tüm payload'u + tracking gönderim durumu.

**İkinci Tab — Görsel Analitik:**
- Günlük event sayısı line chart (son 30 gün) — Chart.js
- Event tipi dağılımı pie chart
- Saat-saat heatmap (7 gün × 24 saat)

**Üçüncü Tab — Coğrafi Harita:**
- Türkiye haritası (SVG)
- Şehirlerde nokta (büyüklük = event sayısı)
- Hedef şehirleri (Konya, Ankara, Gaziantep vb.) vurgulu

### 7.7. Lead'ler

**Liste:**
- Filtre: durum, tarih, landing
- Tablo: ad, telefon, mesaj (kısaltılmış), landing, durum badge, oluşturma, [Detay]
- Status renkleri: yeni (mavi), aranıldı (sarı), sonuçlandı (yeşil), iptal (gri)

**Detay sayfası:**
- Tüm form bilgileri
- UTM bilgileri
- Cihaz/şehir bilgileri
- Notlar (admin yazabilir, rich text)
- Status değiştirme (dropdown)
- WhatsApp'ta hızlıca aç buton (`wa.me/905XXXXXXXXX?text=...`)
- Telefondan ara buton (`tel:`)

### 7.8. İletişim Bilgileri

Tek sayfa, 3 kart:

**Telefon Kartı:**
- Sabit hat (input + format mask)
- WhatsApp (input + format mask + WhatsApp Business mı normal mi toggle)
- GSM (input + format mask)
- Önizleme: footer'da nasıl gözükecek
- WhatsApp link önizleme: `https://wa.me/...`

**Adres Kartı:**
- Tam adres (textarea)
- Google Maps embed URL (input + nasıl alınır tooltip)
- Lat/Lng (opsiyonel)

**Sosyal Medya Kartı:**
- Instagram URL
- Facebook URL
- TikTok URL (opsiyonel)
- Tripadvisor URL
- Google Business Profile URL

### 7.9. Acenta Bilgileri

Hukuki + KVKK için gerekli bilgiler (hepsi placeholder olarak başlayacak):

- Acenta Ticari Adı
- Vergi Numarası + Vergi Dairesi
- MERSİS Numarası
- Ticaret Sicil Numarası
- TÜRSAB Belge Numarası
- TÜRSAB Belgesi Upload (PDF)
- KEP Adresi
- E-mail
- Yetkili Kişi Adı
- KVKK Veri Sorumlusu Adı (opsiyonel ayrı)

**Bu bilgiler footer + KVKK + hakkımızda + iletişim sayfalarında otomatik kullanılır.**

### 7.10. Genel Ayarlar

- Site adı, tagline
- Logo upload
- Favicon upload
- Bakım modu (toggle + mesaj)
- Yedekleme (Phase 2 — şimdilik sadece UI)

---

## 8. TRACKING IMPLEMENTASYONU (KRİTİK BÖLÜM)

### 8.1. Frontend — Meta Pixel

**Blade component:** `resources/views/components/site/tracking-pixel.blade.php`

`<head>` içinde, settings tablosundan çekilen değerlerle:

```blade
@if(setting('meta_pixel_id'))
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '{{ setting('meta_pixel_id') }}');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id={{ setting('meta_pixel_id') }}&ev=PageView&noscript=1"/></noscript>
@endif
```

### 8.2. Frontend — GTM, GA4

GTM önceliklidir; varsa GA4 GTM içinden yönetilir. Yoksa direkt GA4 inject edilir.

```blade
@if(setting('gtm_container_id'))
{{-- GTM head + body --}}
@elseif(setting('ga4_measurement_id'))
{{-- GA4 direkt gtag --}}
@endif
```

### 8.3. Event Trigger — Frontend JS

`resources/js/site/tracking.js`:

```js
window.ArsiTracking = {
  trackEvent(eventName, payload = {}) {
    const eventId = this.generateUUID();
    
    // 1. Client-side (Meta Pixel)
    if (typeof fbq !== 'undefined') {
      fbq('track', this.mapToMetaEvent(eventName), payload, { eventID: eventId });
    }
    
    // 2. Google
    if (typeof gtag !== 'undefined') {
      gtag('event', eventName, payload);
    }
    
    // 3. Server-side (CAPI + log)
    fetch('/api/track-event', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({
        event_name: eventName,
        event_id: eventId,
        payload: payload,
        page: window.location.pathname,
        referrer: document.referrer
      })
    });
  },
  
  mapToMetaEvent(name) {
    const map = {
      'whatsapp_click': 'Lead',
      'phone_click': 'Contact',
      'lead_form_submit': 'Lead',
      'campaign_click': 'InitiateCheckout',
    };
    return map[name] || 'CustomEvent';
  },
  
  generateUUID() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, c => {
      const r = Math.random() * 16 | 0;
      return (c == 'x' ? r : (r & 0x3 | 0x8)).toString(16);
    });
  }
};

// Auto-bind: tüm WhatsApp/telefon linklerine event ekle
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-track="whatsapp"]').forEach(el => {
    el.addEventListener('click', () => ArsiTracking.trackEvent('whatsapp_click'));
  });
  document.querySelectorAll('[data-track="phone"]').forEach(el => {
    el.addEventListener('click', () => ArsiTracking.trackEvent('phone_click'));
  });
});
```

### 8.4. Backend — `MetaCapiService`

`app/Services/MetaCapiService.php`:

Tek metod: `send($eventName, $eventId, $payload, $userData)`.

İçeriği:
- Meta Conversions API endpoint: `https://graph.facebook.com/v18.0/{PIXEL_ID}/events`
- Bearer token: `setting('meta_capi_token')`
- Body: standart Meta CAPI format (event_name, event_time, event_id, user_data hash'lenmiş, custom_data)
- `user_data` içinde: `client_ip_address`, `client_user_agent`, `fbp` (cookie), `fbc` (cookie/UTM)
- SHA-256 hash: email, phone varsa (form lead'lerinde)
- Test mode: `test_event_code` setting varsa request'e ekle
- Response log: success/fail durumu `event_logs.fb_capi_sent` alanına

### 8.5. Backend — `/api/track-event` Endpoint

`app/Http/Controllers/Api/TrackingController.php`:

```
1. Rate limit kontrolü (60/min per IP)
2. CSRF validation (Laravel default)
3. Request validation (event_name required, payload optional)
4. EventLog::create() ile DB'ye yaz
5. setting() üzerinden hangi tracking'ler aktif kontrol et
6. MetaCapiService::send() çağır (event_logs ID ile)
7. GA4/Google Ads server-side gönderim (varsa)
8. Response: ['success' => true, 'event_id' => ...]
```

**Önemli:** Bu endpoint senkron çalışır ama CAPI gönderimi `dispatch()` ile job'a atılır — frontend'i bekletme.

### 8.6. "Bilgi Al" Form Submit

`POST /bilgi-al`:

```
1. Honeypot check (gizli field doluysa spam, sessiz reddet)
2. Rate limit (5 submit/saat per IP)
3. Validation:
   - name: required, min 2, max 100
   - phone: required, regex (TR formatı: +90 veya 0 ile başlayan, 10-11 hane)
   - message: nullable, max 500
4. Lead::create()
5. EventLog::fire('lead_form_submit', [...])
6. Lead'e event_log_id assign
7. Meta CAPI Lead event (phone hashlenmiş user_data ile)
8. GA4 + GAds conversion event
9. Email queue: yeni lead bildirimi admin'e
10. Response: success message + redirect (veya AJAX response)
```

---

## 9. SCHEMA.ORG (SEO için kritik)

`app/Services/SchemaOrgService.php`:

Her sayfaya uygun JSON-LD üret. Otomatik metodlar:

### Hotel Schema (ana sayfa)
```json
{
  "@context": "https://schema.org",
  "@type": "Hotel",
  "name": "Arsi Blue Beach Hotel",
  "alternateName": ["Arsi Blue Beach", "Arsi Otel Alanya", "Arsi Hotel"],
  "url": "https://[domain]",
  "logo": "[setting:site_logo]",
  "image": [galeri görselleri],
  "telephone": "[setting:phone_landline]",
  "address": {
    "@type": "PostalAddress",
    "addressLocality": "Alanya",
    "addressRegion": "Antalya",
    "addressCountry": "TR",
    "streetAddress": "[setting:address]"
  },
  "geo": { "@type": "GeoCoordinates", "latitude": ..., "longitude": ... },
  "starRating": { "@type": "Rating", "ratingValue": "4" },
  "priceRange": "₺₺",
  "amenityFeature": [...],
  "sameAs": [instagram, facebook, tripadvisor, google_business]
}
```

### Offer Schema (aktif kampanya için)
Hotel schema'ya `makesOffer` olarak eklenir.

### FAQPage Schema
SSS bölümü olan sayfalarda FAQPage schema otomatik üretilir.

### BreadcrumbList Schema
Alt sayfalarda otomatik üretilir.

### LocalBusiness Schema
İletişim sayfasında.

**Blade component:** `<x-site.schema-org :type="'Hotel'" :page="$page" />` her sayfanın head'inde.

---

## 10. GÜVENLİK

### Genel
- CSRF protection (Laravel default)
- XSS: Blade `{{ }}` otomatik escape
- SQL Injection: Eloquent + parameterized queries
- Mass assignment: `$fillable` her model'de tanımlı
- Rate limiting:
  - `/api/track-event`: 60/min per IP
  - `/bilgi-al`: 5/saat per IP
  - `/admin/login`: 5/dk per IP

### Admin Auth
- Laravel Breeze veya custom basit auth
- Tek kullanıcı yeterli ama scalable yapı
- Password hash: bcrypt
- Session: database driver (cPanel'de file driver da olabilir)
- Remember me: opsiyonel

### Sensitive Data
- Meta CAPI token: `encrypted` cast (Eloquent `$casts`)
- TikTok token: `encrypted` cast
- Settings'te tüm hassas key'ler encrypted

### Hidden Update Route
- URL: `/admin/system-update-{SECRET}` where SECRET = `.env`'den `SYSTEM_UPDATE_SECRET`
- Çalıştırınca: `php artisan migrate --force` programmatic
- Log'lanır
- Sadece GET (token URL'de)

### Honeypot
"Bilgi Al" formunda gizli field: `<input type="text" name="website" style="position:absolute;left:-9999px">`. Doluysa form sessiz reddedilir (200 OK döner ama lead kaydı yapılmaz).

---

## 11. PERFORMANCE

### Caching
- **Settings:** `Cache::remember('settings.all', 3600, ...)` — 1 saat
- **Sayfa içerikleri:** `Cache::remember('page.{slug}', 1800, ...)` — 30 dk
- **Aktif kampanya:** `Cache::remember('campaign.active', 600, ...)` — 10 dk (geri sayım için kısa)
- **Galeri:** `Cache::remember('gallery.all', 3600, ...)` — 1 saat
- **SSS:** 1 saat
- Admin'de update yapılınca cache invalidate (`Cache::forget()`)

### Image Optimization
- Upload'larda otomatik resize:
  - Hero: max 1920×1080
  - Kart görseli: max 800×600
  - Thumbnail: 300×200
- WebP convert (Intervention Image v3)
- Lazy loading (`loading="lazy"`)
- Responsive srcset (mobile/tablet/desktop versiyonları)

### CSS/JS
- Tailwind production build (purge aktif)
- JS modülleri: site/admin ayrı bundle
- Vite ile minify
- Litespeed cache (.htaccess kuralları)

### Database
- Indexler yukarıda belirtildi
- Eager loading (`with()`) N+1 önle
- Pagination her zaman

---

## 12. STITCH HTML ENTEGRASYONU

Kullanıcının Stitch'ten indirdiği HTML/CSS/JS dosyaları **`stitch-designs/`** klasörüne uploads edilecek (ZIP içinde).

### Stitch çıktısını Blade'e çevirme süreci:

1. **HTML'i incele:** Her sayfa için Stitch çıktısını oku.
2. **Tailwind class'larını koru:** Stitch zaten Tailwind kullanıyorsa direkt aktar.
3. **Dinamik kısımları değişkenle değiştir:** 
   - Statik metinleri `{{ $page->getContent('hero_title') }}` gibi yap
   - Görselleri `{{ asset('storage/' . $image) }}` ile değiştir
   - Linkleri `route()` veya `url()` ile değiştir
4. **Tekrarlanan blokları component'e çevir:** Kampanya kartı, oda kartı, FAQ item gibi parçalar Blade component yap.
5. **Form'ları Laravel'e bağla:** "Bilgi Al" formu CSRF token + `route('lead.store')` ile.
6. **JS'i Alpine.js'e çevir:** Stitch vanilla JS verdiyse Alpine direktiflerine dönüştür (`x-data`, `x-show`, `x-on:click` vb.)
7. **İkonları Heroicons component'e çevir:** Inline SVG'leri `<x-heroicon-o-... />` yap.

### Klasör Yapısı (Stitch entegrasyonu sonrası):
```
stitch-designs/          # Raw Stitch çıktıları (referans, deploy edilmiyor)
  site/
    home.html
    bayrama-ozel.html
    ...
  admin/
    dashboard.html
    campaigns-edit.html
    ...

resources/views/site/    # Blade'e çevrilmiş versiyonlar (deploy edilen)
  ...
```

---

## 13. DEPLOYMENT (cPanel/Litespeed)

### 13.1. public_html Workaround

cPanel'de Laravel'in `public/` klasörü direkt `public_html` olamaz. Çözüm:

**Option A (Önerilen):** Tüm proje `public_html` dışına, sadece public içeriği `public_html`'e symlink:
```
/home/user/
  arsi-blue-beach/        ← Proje burada
    app/
    config/
    ...
    public/               ← Asıl public
  public_html/            ← Sembol
    index.php → ../arsi-blue-beach/public/index.php (modified paths)
```

**Option B (cPanel daha sık):** `public_html/` içine yerleştir, `.htaccess` ile public/ subroot olmadığı için path düzeltme:

`/public_html/.htaccess`:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

Veya `public/index.php`'yi `public_html/` köküne kopyala ve path'leri düzelt.

**Claude Code'a not:** İkinci yöntemle başla, kullanıcı hosting'inde test edilecek.

### 13.2. Storage Link Workaround

`php artisan storage:link` çalışmadığı için **`public/storage/uploads/`** klasörü direkt fiziksel klasör olarak oluşturulacak. Dosya yükleme:

```php
// Yanlış (storage:link gerektirir):
$file->storeAs('uploads', $name, 'public');

// Doğru (direkt public/storage/uploads/):
$file->move(public_path('storage/uploads/gallery'), $name);
```

`config/filesystems.php`'ye custom disk eklenir:
```php
'public_uploads' => [
    'driver' => 'local',
    'root' => public_path('storage/uploads'),
    'url' => env('APP_URL').'/storage/uploads',
    'visibility' => 'public',
],
```

### 13.3. GitHub Actions Deploy

`.github/workflows/deploy.yml`:

```yaml
name: Deploy to cPanel
on:
  push:
    branches: [main]
jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
      - run: composer install --no-dev --optimize-autoloader
      - run: npm install
      - run: npm run build
      - name: FTP Deploy
        uses: SamKirkland/FTP-Deploy-Action@v4.3.4
        with:
          server: ${{ secrets.FTP_SERVER }}
          username: ${{ secrets.FTP_USERNAME }}
          password: ${{ secrets.FTP_PASSWORD }}
          local-dir: ./
          server-dir: /public_html/
          exclude: |
            **/.git*
            **/.git*/**
            **/node_modules/**
            **/tests/**
            .env
            .env.example
```

### 13.4. Gizli Migration Rotası

`.env`:
```
SYSTEM_UPDATE_SECRET=randomBase64String_32chars
```

Route:
```php
Route::get('/admin/system-update-' . env('SYSTEM_UPDATE_SECRET'), function () {
    Artisan::call('migrate', ['--force' => true]);
    Artisan::call('config:cache');
    Artisan::call('view:cache');
    Artisan::call('route:cache');
    return response()->json([
        'success' => true,
        'output' => Artisan::output(),
        'timestamp' => now()->toDateTimeString(),
    ]);
})->middleware('admin.auth');
```

**Sadece authenticated admin** erişebilir.

### 13.5. Environment Variables (.env.example)

```
APP_NAME="Arsi Blue Beach"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://arsibluebeach.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

CACHE_DRIVER=file
SESSION_DRIVER=database
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="${APP_NAME}"

ADMIN_NOTIFICATION_EMAIL=

SYSTEM_UPDATE_SECRET=

# Bu değerler admin panelinden güncellenir, .env'de zorunlu değil:
# Meta Pixel, CAPI vb. settings tablosunda
```

---

## 14. SEEDER'LAR

İlk kurulumda çalışacak seeder'lar:

### `AdminSeeder`
- Default admin: email `admin@arsibluebeach.com`, password env'den (`ADMIN_INITIAL_PASSWORD`)

### `SettingsSeeder`
Tüm default ayarlar (yukarıdaki settings listesi), placeholder değerlerle:
```php
['meta_pixel_id', '', 'text', 'tracking'],
['meta_capi_token', '', 'text', 'tracking'],
['phone_landline', '0 242 522 50 42', 'text', 'contact'],
['phone_whatsapp', '0 551 714 92 00', 'text', 'contact'],
['phone_gsm', '0 551 712 91 00', 'text', 'contact'],
// ...
```

### `PagesSeeder`
Tüm pages: home, bayrama-ozel, balayi-paketi, aile-oteli, galeri, iletisim, kvkk, cerez, hakkimizda

### `PageContentsSeeder`
Default içerikler tüm pages için (kırmızı çizgilere uygun copy ile).

### `CampaignsSeeder`
Default Bayrama Özel kampanyası:
```php
[
    'title' => 'Bayrama Özel',
    'old_price' => 36000,
    'new_price' => 16250,
    'nights' => 3,
    'adults' => 2,
    'children' => 1,
    'child_age_limit' => 12,
    'rooms_left' => 12,
    'urgency_text' => 'Son 12 oda',
    'valid_until' => '2026-05-31 23:59:59',
    'included_items' => [
        'Tüm yiyecek-içecek dahil (açık büfe)',
        'Sınırsız alkollü/alkolsüz içecek (09:00-22:00)',
        '3 havuz + aqua park dahil',
        'Her gün animasyon ve şovlar',
        'Otel şezlonglarımız ücretsiz',
    ],
    'is_active' => true,
],
```

### `RoomsSeeder`
3 oda: Standart, Aile, Deniz Manzaralı (müsaitliğe göre)

### `FaqsSeeder`
Master prompt'taki 10 SSS

### `SeoMetasSeeder`
Her sayfa için default title/description (brand keyword'lerle dolu)

---

## 15. ÇALIŞMA SIRASI (CLAUDE CODE İÇİN)

**Bu sıralamayı takip et. Her adımın sonunda commit at, sonra devam et.**

### Adım 1: Proje Kurulumu
1. `composer create-project laravel/laravel arsi-blue-beach`
2. `.env` yapılandır (DB, mail, vb.)
3. Tailwind + Vite kurulumu
4. Heroicons paketi: `composer require blade-ui-kit/blade-heroicons`
5. Intervention Image: `composer require intervention/image`
6. Spatie sitemap: `composer require spatie/laravel-sitemap`
7. `tailwind.config.js`: renk paletini ekle (kullanıcının site brief'inden)
8. `resources/css/app.css`: Tailwind imports + custom CSS
9. Inter font'u self-host

### Adım 2: Veritabanı
1. Tüm migration'ları yarat (yukarıda 3.1-3.12)
2. Modeller (yukarıda madde 4)
3. Seeder'lar (madde 14)
4. `php artisan migrate --seed` test et (local)

### Adım 3: Admin Auth
1. Login sayfası (Tailwind tasarım)
2. AuthController login/logout
3. `AdminAuth` middleware
4. Layout: `layouts/admin.blade.php` (sidebar + topbar)
5. Logout flow

### Adım 4: Admin — Dashboard
1. DashboardController
2. KPI hesaplama (event_logs'tan)
3. Aktif kampanya kartı
4. Son event listesi (AJAX 30sn refresh)
5. Tracking health check endpoint
6. Hızlı aksiyonlar UI

### Adım 5: Admin — Sayfa İçerikleri (Kullanıcının önceliği)
1. PageController + section bazlı edit
2. Rich text editor entegrasyonu (TinyMCE veya Trix)
3. Image upload (resize + WebP)
4. Auto-save AJAX
5. Sağ panelde iframe preview

### Adım 6: Admin — Kampanyalar
1. CRUD
2. Auto-save
3. Sağ panelde canlı önizleme
4. Geri sayım toggle/picker
5. Görsel upload

### Adım 7: Admin — Tracking & Pixel (Kullanıcının önceliği)
1. Meta tab (Pixel + CAPI + Event Mapping)
2. CAPI test endpoint
3. Google tab (GTM, GA4, GAds, Search Console)
4. TikTok tab
5. Health check banner
6. Settings'e kaydetme (encrypted casts)

### Adım 8: Admin — Diğer Modüller (Hızlı geçilebilir)
1. Galeri (bulk upload + kategori + drag-drop sıralama)
2. SSS (CRUD + drag-drop sıralama)
3. SEO Meta (sayfa bazlı düzenleme + Google preview)
4. Event Logları (liste + filtre + detay modal + grafik)
5. Lead'ler (liste + detay + status + WhatsApp/tel hızlı aç)
6. İletişim Bilgileri (3 kart, basit edit)
7. Acenta Bilgileri (form, placeholder field'larla)
8. Genel Ayarlar (logo, favicon, bakım modu)

### Adım 9: Frontend — Layout & Components
1. `layouts/site.blade.php`
2. Header component (sticky, mobil drawer)
3. Footer component
4. Sticky mobil bottom bar
5. Tracking pixel component (head'e inject)
6. Schema.org component
7. Lead form component (CSRF, validation, AJAX submit)
8. Campaign card component
9. Room card component
10. FAQ accordion component

### Adım 10: Frontend — Sayfalar
1. Ana sayfa (`/`) — Stitch HTML'ini Blade'e dönüştür
2. Bayrama özel landing
3. Balayı paketi landing
4. Aile oteli landing
5. Galeri (lightbox)
6. İletişim
7. KVKK / Çerez / Hakkımızda

### Adım 11: Tracking Implementation
1. Frontend `tracking.js` (auto-bind data attributes)
2. `/api/track-event` endpoint
3. MetaCapiService
4. Event mapping settings'ten
5. Sayfa görüntüleme otomatik fire
6. WhatsApp/telefon link click otomatik fire
7. Lead form submit otomatik fire
8. UTM capture (session'a save)

### Adım 12: Lead Form
1. Form component
2. Honeypot
3. Validation rules
4. LeadController store
5. Email queue (admin'e bildirim)
6. Success/error UX

### Adım 13: SEO
1. Schema.org JSON-LD her sayfada
2. Meta tag inject (settings tablosundan)
3. sitemap.xml route
4. robots.txt route
5. Canonical tag her sayfada
6. OG image fallback

### Adım 14: Performance
1. Cache implementation (settings, pages, campaigns)
2. Image optimization pipeline
3. Tailwind purge
4. Litespeed `.htaccess` rules
5. Lazy loading

### Adım 15: Deploy Hazırlığı
1. `public_html` path workaround
2. `public/storage/uploads/` klasör yapısı
3. GitHub Actions workflow
4. Gizli migration rotası
5. `.env.example` finalize
6. README.md (deploy talimatları)

### Adım 16: Final Test
1. Tüm sayfalar yükleniyor mu (frontend)
2. Tüm admin modülleri çalışıyor mu
3. Tracking event'leri firing mi (browser console + Meta Test Events tool)
4. Lead form submit + email + DB kayıt
5. Settings değişikliklerinin canlıya yansıması
6. Mobil görünüm tüm sayfalar
7. SEO meta tag'leri her sayfada doğru

---

## 16. KIRMIZI ÇİZGİLER (TEKRAR — UNUTMA)

Tüm copy üretiminde, seeder'larda, default içeriklerde:

| ❌ KULLANMA | ✅ KULLAN |
|---|---|
| Snack bar / snek bar | Bahsedilmeyecek |
| Yürüme mesafesi | "Yakın mesafede" |
| Metrekare (16-17 m² vb.) | "Konforlu odalar" |
| Deniz manzaralı oda (kesin) | "Müsaitliğe göre deniz manzaralı odalar" |
| Ön ödeme yüzdesi (%30, %50) | "Sadece ön ödeme" / "Ön ödemeli rezervasyon" |
| Çocuk kulübü, dadı | Bahsedilmeyecek |
| Oda servisi | Bahsedilmeyecek |
| Otel turu (kendi düzenlediği gibi) | "Tur yönlendirmeleri mevcut" |
| Animasyon saati | "Her gün animasyon" |
| "Resmi otel sitesi" | "Yetkili acenta" / "Acenta üzerinden rezervasyon" |
| "Tek yetkili biziz" | (kullanma — yalan olur) |

---

## 17. SORUMLULUK SINIRI

Claude Code şunları **yapmayacak** (kullanıcı verecek):

- Gerçek otel görselleri (placeholder kullan)
- Gerçek acenta bilgileri (placeholder)
- Gerçek Meta/Google ID'leri (admin'den girilecek)
- Domain ve hosting yapılandırması (kullanıcı yapacak)
- DNS ayarları
- SSL sertifikası
- E-mail SMTP credentials

Bunlar için **placeholder** kullan, `.env.example`'da örnek format ver.

---

## 18. EKLER

### A. Tailwind Config — Renk Paleti

```js
// tailwind.config.js
module.exports = {
  content: ['./resources/**/*.{blade.php,js}'],
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#E0F2FE',
          500: '#0077B6',
          600: '#0066A0',
          700: '#023E8A',
          900: '#0F172A',
        },
        accent: {
          yellow: '#FFB703',
          orange: '#FB8500',
          coral: '#FB8500',
        },
        sand: '#FAF3E0',
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
      },
      animation: {
        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
        'float': 'float 6s ease-in-out infinite',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}
```

### B. Önemli Helper Fonksiyonları (`app/helpers.php`)

```php
function setting($key, $default = null) {
    return app(\App\Services\SettingService::class)->get($key, $default);
}

function whatsappLink($message = '') {
    $number = preg_replace('/[^0-9]/', '', setting('phone_whatsapp'));
    if (!str_starts_with($number, '90')) $number = '90' . ltrim($number, '0');
    return 'https://wa.me/' . $number . ($message ? '?text=' . urlencode($message) : '');
}

function phoneLink($number) {
    return 'tel:' . preg_replace('/[^0-9+]/', '', $number);
}

function priceFormat($amount, $currency = 'TL') {
    return number_format($amount, 0, ',', '.') . ' ' . $currency;
}
```

`composer.json` autoload:
```json
"autoload": {
    "files": ["app/helpers.php"]
}
```

---

## SON

Bu spec dosyası **otoritedir**. Belirsizlik veya çakışma olursa bana sor, varsayım yapma. Stitch HTML'lerini Blade'e dönüştürürken **bu spec'in kuralları + Stitch'in tasarım kararları** birlikte uygulanır. Çelişki olursa **bu spec öncelikli** — kırmızı çizgiler ve tracking yapısı asla taviz verilemez.

İyi çalışmalar.
