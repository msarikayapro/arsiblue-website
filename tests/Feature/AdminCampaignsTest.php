<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Campaign;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCampaignsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_campaigns_index_lists_seeded_campaigns(): void
    {
        $admin = Admin::first();
        $response = $this->actingAs($admin)->get('/admin/campaigns');

        $response->assertOk();
        $response->assertSee('Bayrama Özel');
        $response->assertSee('Erken Rezervasyon');
    }

    public function test_create_form_renders(): void
    {
        $admin = Admin::first();
        $response = $this->actingAs($admin)->get('/admin/campaigns/create');

        $response->assertOk();
        $response->assertSee('Yeni Kampanya');
        $response->assertSee('Temel Bilgiler');
        $response->assertSee('Fiyatlandırma');
    }

    public function test_store_creates_campaign_and_redirects_to_edit(): void
    {
        $admin = Admin::first();
        $response = $this->actingAs($admin)->post('/admin/campaigns', [
            'title' => 'Test Kampanya',
            'subtitle' => 'Test',
            'old_price' => 5000,
            'new_price' => 3500,
            'currency' => 'TL',
            'is_active' => '1',
        ]);

        $campaign = Campaign::where('title', 'Test Kampanya')->first();
        $this->assertNotNull($campaign);
        $this->assertSame(3500, $campaign->new_price);
        $this->assertTrue($campaign->is_active);

        $response->assertRedirect("/admin/campaigns/{$campaign->id}/edit");
    }

    public function test_update_via_json_returns_ok_with_saved_at(): void
    {
        $admin = Admin::first();
        $campaign = Campaign::first();

        $response = $this->actingAs($admin)
            ->putJson("/admin/campaigns/{$campaign->id}", [
                'title' => $campaign->title,
                'new_price' => 9999,
                'currency' => 'TL',
                'included_items' => ['Yeni madde 1', 'Yeni madde 2'],
            ]);

        $response->assertOk()->assertJson(['ok' => true]);

        $campaign->refresh();
        $this->assertSame(9999, $campaign->new_price);
        $this->assertSame(['Yeni madde 1', 'Yeni madde 2'], $campaign->included_items);
    }

    public function test_toggle_active_inverts_state(): void
    {
        $admin = Admin::first();
        $campaign = Campaign::where('is_active', true)->first();

        $response = $this->actingAs($admin)->post("/admin/campaigns/{$campaign->id}/toggle-active");

        $response->assertOk()->assertJson(['ok' => true, 'is_active' => false]);
        $this->assertFalse($campaign->fresh()->is_active);
    }

    public function test_destroy_removes_campaign(): void
    {
        $admin = Admin::first();
        $campaign = Campaign::first();
        $id = $campaign->id;

        $response = $this->actingAs($admin)->delete("/admin/campaigns/{$id}");

        $response->assertRedirect('/admin/campaigns');
        $this->assertNull(Campaign::find($id));
    }
}
