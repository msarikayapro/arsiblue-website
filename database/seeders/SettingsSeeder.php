<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // ----- general -----
            ['site_name', 'Arsi Blue Beach Hotel', 'text', 'general'],
            ['site_tagline', 'Alanya\'da denize sıfır 4 yıldızlı aile oteli', 'text', 'general'],
            ['site_logo', '', 'file', 'general'],
            ['site_favicon', '', 'file', 'general'],
            ['maintenance_mode', '0', 'boolean', 'general'],
            ['maintenance_message', 'Site geçici olarak bakımdadır. Bilgi için bize ulaşın.', 'text', 'general'],

            // ----- contact -----
            ['phone_landline', '0 242 522 50 42', 'text', 'contact'],
            ['phone_whatsapp', '0 551 714 92 00', 'text', 'contact'],
            ['phone_gsm', '0 551 712 91 00', 'text', 'contact'],
            ['email', 'info@arsibluebeach.com', 'text', 'contact'],
            ['address', 'Mahmutlar Mah., Alanya / Antalya', 'text', 'contact'],
            ['google_maps_embed', '', 'text', 'contact'],
            ['working_hours', '7/24 — WhatsApp ve telefon ile her saat ulaşılabilir', 'text', 'contact'],
            ['instagram_url', '', 'text', 'contact'],
            ['facebook_url', '', 'text', 'contact'],
            ['tiktok_url', '', 'text', 'contact'],
            ['tripadvisor_url', '', 'text', 'contact'],
            ['google_business_url', '', 'text', 'contact'],

            // ----- agency (yetkili acenta bilgileri — placeholder) -----
            ['agency_name', '', 'text', 'agency'],
            ['agency_tax_number', '', 'text', 'agency'],
            ['agency_tax_office', '', 'text', 'agency'],
            ['agency_mersis', '', 'text', 'agency'],
            ['agency_trade_registry', '', 'text', 'agency'],
            ['agency_tursab_number', '', 'text', 'agency'],
            ['agency_tursab_pdf', '', 'file', 'agency'],
            ['agency_kep', '', 'text', 'agency'],
            ['agency_email', '', 'text', 'agency'],
            ['agency_authorized_person', '', 'text', 'agency'],
            ['agency_kvkk_responsible', '', 'text', 'agency'],

            // ----- tracking -----
            ['meta_pixel_id', '', 'text', 'tracking'],
            ['meta_capi_token', '', 'text', 'tracking'], // ENCRYPTED on save
            ['meta_capi_test_code', '', 'text', 'tracking'],
            ['meta_pixel_active', '0', 'boolean', 'tracking'],
            ['meta_capi_active', '0', 'boolean', 'tracking'],
            ['gtm_container_id', '', 'text', 'tracking'],
            ['ga4_measurement_id', '', 'text', 'tracking'],
            ['google_ads_conversion_id', '', 'text', 'tracking'],
            ['google_ads_conversion_label', '', 'text', 'tracking'],
            ['google_search_console_verification', '', 'text', 'tracking'],
            ['tiktok_pixel_id', '', 'text', 'tracking'],
            ['tiktok_capi_token', '', 'text', 'tracking'], // ENCRYPTED
            ['tiktok_active', '0', 'boolean', 'tracking'],

            // Event mapping (JSON) — Frontend aksiyonları → Meta event adları
            ['event_mapping', json_encode([
                'whatsapp_click' => ['meta' => 'Lead', 'active' => true],
                'phone_click' => ['meta' => 'Contact', 'active' => true],
                'lead_form_submit' => ['meta' => 'Lead', 'active' => true],
                'campaign_click' => ['meta' => 'InitiateCheckout', 'active' => true],
                'page_view' => ['meta' => 'PageView', 'active' => true],
            ], JSON_UNESCAPED_UNICODE), 'json', 'tracking'],

            // ----- seo (global defaults) -----
            ['seo_default_title_suffix', ' | Arsi Blue Beach Hotel', 'text', 'seo'],
            ['seo_default_og_image', '', 'file', 'seo'],
            ['seo_custom_head_scripts', '', 'text', 'seo'],
            ['seo_custom_body_scripts', '', 'text', 'seo'],
        ];

        foreach ($defaults as [$key, $value, $type, $group]) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => $type, 'group' => $group]
            );
        }
    }
}
