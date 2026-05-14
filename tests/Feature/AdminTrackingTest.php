<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class AdminTrackingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_tracking_index_renders_three_tabs(): void
    {
        $admin = Admin::first();
        $response = $this->actingAs($admin)->get('/admin/tracking');

        $response->assertOk();
        $response->assertSeeText('Meta (Facebook & Instagram)');
        $response->assertSeeText('Google');
        $response->assertSeeText('TikTok');
        $response->assertSeeText('Event Mapping');
        $response->assertSeeText('whatsapp_click');
    }

    public function test_meta_pixel_id_validation_rejects_non_numeric(): void
    {
        $admin = Admin::first();

        $response = $this->actingAs($admin)->put('/admin/tracking/meta', [
            'meta_pixel_id' => 'abc123', // not 15-16 digits
        ]);

        $response->assertSessionHasErrors('meta_pixel_id');
    }

    public function test_meta_settings_save_and_capi_token_is_encrypted_in_db(): void
    {
        $admin = Admin::first();

        $this->actingAs($admin)->put('/admin/tracking/meta', [
            'meta_pixel_id' => '1234567890123456',
            'meta_pixel_active' => '1',
            'meta_capi_token' => 'EAAB-test-token',
            'meta_capi_test_code' => 'TEST123',
            'meta_capi_active' => '1',
        ]);

        // Sanitized roundtrip via setting() helper (decrypts)
        $this->assertSame('1234567890123456', setting('meta_pixel_id'));
        $this->assertTrue((bool) setting('meta_pixel_active'));
        $this->assertSame('EAAB-test-token', setting('meta_capi_token'));

        // Raw DB value is encrypted
        $raw = \App\Models\Setting::where('key', 'meta_capi_token')->first()->value;
        $this->assertNotSame('EAAB-test-token', $raw, 'CAPI token DB\'de plain saklanmış!');
        $this->assertSame('EAAB-test-token', Crypt::decryptString($raw));
    }

    public function test_google_validation_format(): void
    {
        $admin = Admin::first();

        $response = $this->actingAs($admin)->put('/admin/tracking/google', [
            'gtm_container_id' => 'INVALID',
        ]);
        $response->assertSessionHasErrors('gtm_container_id');

        $response = $this->actingAs($admin)->put('/admin/tracking/google', [
            'gtm_container_id' => 'GTM-ABC123',
            'ga4_measurement_id' => 'G-ABCDEF1234',
        ]);
        $response->assertSessionHasNoErrors();
        $this->assertSame('GTM-ABC123', setting('gtm_container_id'));
    }

    public function test_health_endpoint_returns_all_integrations(): void
    {
        $admin = Admin::first();
        $response = $this->actingAs($admin)->getJson('/admin/tracking/health');

        $response->assertOk();
        $response->assertJsonStructure([
            'meta_pixel' => ['configured', 'active', 'last_event'],
            'meta_capi', 'gtm', 'ga4', 'google_ads', 'tiktok',
        ]);
    }

    public function test_test_capi_returns_422_when_settings_missing(): void
    {
        $admin = Admin::first();
        $response = $this->actingAs($admin)->postJson('/admin/tracking/test-capi');

        $response->assertStatus(422);
        $response->assertJson(['ok' => false]);
    }

    public function test_event_mapping_saved_via_meta_form(): void
    {
        $admin = Admin::first();

        $this->actingAs($admin)->put('/admin/tracking/meta', [
            'meta_pixel_id' => '1234567890123456',
            'event_mapping' => [
                'whatsapp_click' => ['meta' => 'Contact', 'active' => '1'],
                'phone_click' => ['meta' => 'Lead', 'active' => '1'],
            ],
        ]);

        $mapping = setting('event_mapping');
        $this->assertSame('Contact', $mapping['whatsapp_click']['meta']);
        $this->assertTrue($mapping['whatsapp_click']['active']);
    }
}
