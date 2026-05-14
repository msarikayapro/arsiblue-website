# Arsi Blue Beach Hotel — Site Projesi

Alanya'daki Arsi Blue Beach 4 yıldızlı aile otelinin tanıtım ve **lead toplama** sitesi.
Yetkili acenta tarafından işletiliyor — online rezervasyon yok, lead → WhatsApp/telefon kapatma.

**Stack:** Laravel 13 · Tailwind 3 · Alpine.js 3 · MySQL 8 · Vite 5

---

## Lokal Kurulum

```bash
# 1. Bağımlılıklar
composer install
npm install

# 2. .env yapılandır
cp .env.example .env
php artisan key:generate

# DB ayarlarını gir (varsayılan Laragon: localhost / root / boş şifre)
# .env'de DB_DATABASE=arsi_blue_beach satırını kontrol et

# 3. Veritabanı
mysql -u root -e "CREATE DATABASE arsi_blue_beach CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate --seed

# 4. Asset build
npm run build

# 5. Dev server
php artisan serve     # ya da Laragon ile arsi-blue-beach.test
```

**Default admin:** `admin@arsibluebeach.com` / `.env` içindeki `ADMIN_INITIAL_PASSWORD`

---

## Mimari

```
app/
├── Http/Controllers/
│   ├── Site/         # Frontend (Home, Landing, Page, Gallery, Contact, Lead, Legal, Sitemap)
│   ├── Admin/        # 14 admin controller — Pages/Campaigns/Tracking/Leads/...
│   └── Api/          # TrackingController (event log + CAPI)
├── Models/           # 10 Eloquent model (Admin, Setting, Page, PageContent, Campaign, Room, ...)
├── Services/
│   ├── SettingService     # cache'li setting() helper'ının arkasındaki servis
│   ├── PageCache          # frontend query cache'i
│   ├── MetaCapiService    # Meta Conversions API server-side gönderim
│   ├── SchemaOrgService   # JSON-LD üretici (Hotel, FAQPage, ...)
│   └── ImageService       # resize + WebP pipeline (Intervention v3)
└── View/Composers/
    └── GlobalDataComposer # site/* view'larına $seo + $schemaType inject

resources/views/
├── layouts/          # site, admin
├── components/site/  # 11 reusable Blade component
├── components/admin/ # section-card vb.
├── site/             # frontend sayfalar (home, landing/, legal/, gallery, ...)
├── admin/            # 13 modül edit/index view'ları
└── emails/           # new-lead bildirim template

stitch-designs/       # Stitch'ten indirilmiş ham tasarımlar — referans, deploy edilmez
├── _design-system/   # Mediterranean Serenity tasarım sistemi (DESIGN.md)
├── _screenshots/     # Stitch'in oluşturduğu ekran görüntüleri
├── site/             # frontend HTML'leri
├── admin/            # admin HTML'leri
└── _alternatifler/   # kullanılmayan alternatif varyantlar
```

---

## Deploy (cPanel + Litespeed)

### İlk kurulum

1. **DB oluştur:** cPanel → MySQL Database Wizard → `arsibluebeach_db` (veya istediğin ad)
2. **`.env` hazırla:** Local'den `.env.example` kopyala, üzerine üret:
   - `APP_KEY=` için `php artisan key:generate --show` lokalde çalıştır, çıktıyı yapıştır
   - DB bilgileri
   - `SYSTEM_UPDATE_SECRET=` için `bin2hex(random_bytes(24))` çalıştırıp uzun random string üret
   - `ADMIN_INITIAL_PASSWORD` (sadece ilk seed için; sonra admin panelden değiştir)
3. **Public path workaround:** cPanel'in `public_html`'i Laravel'in `public/`'üne yönlendirmesi gerek.
   En basit yöntem (`public_html/.htaccess`):
   ```apache
   <IfModule mod_rewrite.c>
       RewriteEngine On
       RewriteRule ^(.*)$ public/$1 [L]
   </IfModule>
   ```
   Veya `public/index.php`'yi `public_html/`'e kopyala, içindeki path'leri `/home/user/arsi-blue-beach/...` olarak güncelle.

4. **Storage klasörü:** `storage:link` çoğu cPanel'de çalışmaz. `public/storage/uploads/{gallery,rooms,campaigns,pages}` ve `public/storage/agency/` zaten repo'da var (boş `.gitkeep`'lerle).

### GitHub Actions ile Otomatik Deploy

`.github/workflows/deploy.yml` hazır. GitHub repo Settings → Secrets and variables → Actions:

| Secret | Açıklama |
|---|---|
| `FTP_SERVER` | cPanel FTP host (örn: `ftp.arsibluebeach.com`) |
| `FTP_USERNAME` | cPanel kullanıcı adı |
| `FTP_PASSWORD` | cPanel şifresi |
| `FTP_SERVER_DIR` | Genelde `/public_html/` |
| `APP_KEY` | Lokal'den `base64:...` çıktısı |
| `APP_URL` | `https://arsibluebeach.com` |
| `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | cPanel MySQL bilgileri |
| `MAIL_HOST`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS` | SMTP bilgileri |
| `ADMIN_NOTIFICATION_EMAIL` | Yeni lead bildirimi alacak adres |
| `ADMIN_INITIAL_PASSWORD` | İlk seed admin şifresi |
| `SYSTEM_UPDATE_SECRET` | Migration tetikleme URL'inin gizli kısmı |

**Push → main = otomatik deploy.**

### Migration Tetikleme

`php artisan` cPanel SSH'da çalışmıyorsa, **gizli URL** ile tetiklenir:

```
https://arsibluebeach.com/admin/system-update-{SECRET}
```

- Önce `/admin/login` ile giriş yap (sadece authenticated admin erişebilir)
- URL'i ziyaret et → JSON response: migrate + config:cache + view:cache + route:cache + Cache::flush

---

## Admin Paneli

`https://arsibluebeach.com/admin/login`

13 modül:
1. **Panel** — KPI'lar, aktif kampanya, son event'ler, tracking sağlığı
2. **Sayfa İçerikleri** — section-bazlı editör (text/html/image/json), auto-save 1.5s
3. **Kampanyalar** — CRUD, auto-save 3s, hero görsel, included_items repeatable
4. **Odalar** — CRUD, 10 feature key
5. **Galeri** — bulk upload (kategori bazlı), drag-drop reorder endpoint hazır
6. **SSS** — CRUD, kategori, visible_pages multi-select
7. **Tracking & Pixel** — Meta/Google/TikTok 3-tab, encrypted token kayıt
8. **SEO Meta** — per-page edit, canlı Google SERP preview, char counter
9. **Event Logları** — filter + KPI + CSV export
10. **Lead'ler** — list + detail + status workflow (yeni→aranıldı→sonuçlandı→iptal) + WhatsApp/tel quick action
11. **İletişim Bilgileri** — telefon/adres/sosyal medya
12. **Acenta Bilgileri** — yetkili acenta hukuki bilgiler + TÜRSAB PDF
13. **Genel Ayarlar** — site adı, logo, favicon, bakım modu

---

## Tracking

**Frontend:** `data-track="whatsapp|phone|campaign_click"` attribute'lu element click'lerinde
otomatik fire. Tracking pipeline:
1. Client-side: `fbq('track', mapped_event, {}, { eventID })` + `gtag('event', name)`
2. Server-side: `POST /api/track-event` → EventLog + Meta CAPI gönderim

**Admin'de:**
- Pixel ID + CAPI Token (encrypted) kaydedilir
- Event Mapping tablosu hangi action'ın hangi Meta event'ine maplendiğini belirler
- Test Event butonu CAPI bağlantısını kontrol eder

**Kırmızı çizgiler (spec § 16) — KESINLIKLE UYULMASI GEREKEN copy kuralları:**
- ❌ Snack bar / yürüme mesafesi / metrekare / ön ödeme yüzdesi
- ❌ "Resmi otel sitesi" iddiası
- ✅ "Müsaitliğe göre deniz manzaralı" / "Yetkili acenta" / "Sadece ön ödeme"

---

## Geliştirme

```bash
# Test
php artisan test

# Watch mode (Vite + queue + logs + serve)
composer run dev

# Yalnızca asset rebuild
npm run build

# Cache temizle
php artisan optimize:clear
```

---

## Stitch Tasarım Akışı

`stitch-designs/` Stitch'ten indirilmiş referans HTML'leri içerir. Mediterranean Serenity tasarım
sistemi `_design-system/DESIGN.md`'de tanımlı (renk tokenları, Plus Jakarta Sans, spacing).
Tüm Tailwind tokenları `tailwind.config.js`'e import edildi — yeni Stitch HTML alıp Blade'e
çevirmek için class'lar zaten tanımlı.

---

## Lisans

MIT
