<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_sitemap_xml_returns_valid_urlset(): void
    {
        $response = $this->get('/sitemap.xml');
        $response->assertOk();
        $this->assertStringContainsString('application/xml', $response->headers->get('Content-Type'));
        $response->assertSee('<?xml version="1.0"', false);
        $response->assertSee('<urlset', false);
        $response->assertSee('/bayrama-ozel', false);
        $response->assertSee('/balayi-paketi', false);
    }

    public function test_robots_txt_disallows_admin_and_api(): void
    {
        $response = $this->get('/robots.txt');
        $response->assertOk();
        $response->assertSeeText('Disallow: /admin');
        $response->assertSeeText('Disallow: /api');
        $response->assertSeeText('Sitemap:');
    }

    public function test_home_renders_hotel_schema_and_seo_meta(): void
    {
        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('"@type":"Hotel"', false);
        $response->assertSee('Arsi Blue Beach', false);
        $response->assertSee('og:title', false);
        $response->assertSee('canonical', false);
        $response->assertSee('Arsi Blue Beach Hotel | Alanya Aile Oteli', false);
    }

    public function test_landing_renders_faq_schema_when_faqs_exist(): void
    {
        $response = $this->get('/bayrama-ozel');
        $response->assertOk();
        $response->assertSee('"@type":"BreadcrumbList"', false);
    }

    public function test_contact_renders_local_business_schema(): void
    {
        $response = $this->get('/iletisim');
        $response->assertOk();
        $response->assertSee('"@type":"LocalBusiness"', false);
    }
}
