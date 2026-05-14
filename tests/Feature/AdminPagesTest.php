<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_pages_index_renders_for_authenticated_admin(): void
    {
        $admin = Admin::first();
        $this->assertNotNull($admin, 'Seeder admin yok — php artisan db:seed çalıştırın.');

        $response = $this->actingAs($admin)->get('/admin/pages');

        $response->assertOk();
        $response->assertSee('Sayfa İçerikleri');
        $response->assertSee('Düzenle');
        $response->assertSee('Ana Sayfa');
        $response->assertSee('Bayrama Özel');
    }

    public function test_pages_edit_renders_section_editor(): void
    {
        $admin = Admin::first();

        $response = $this->actingAs($admin)->get('/admin/pages/home');

        $response->assertOk();
        $response->assertSee('hero_title');
        $response->assertSee('sectionEditor(', false);
        $response->assertSee('Önizle');
        $response->assertSee('Otomatik kayıt aktif');
    }

    public function test_section_update_saves_value_and_returns_json(): void
    {
        $admin = Admin::first();

        $response = $this->actingAs($admin)->postJson(
            "/admin/pages/home/sections/1",
            ['value' => 'Test başlık ' . now()->timestamp]
        );

        $response->assertOk()->assertJson(['ok' => true]);
        $response->assertJsonStructure(['ok', 'saved_at']);
    }

    public function test_section_update_rejects_invalid_json(): void
    {
        $admin = Admin::first();

        // 'trust_strip' is JSON type. Bul ID'sini.
        $jsonSection = \App\Models\PageContent::where('content_type', 'json')->first();
        $this->assertNotNull($jsonSection);

        $response = $this->actingAs($admin)->postJson(
            "/admin/pages/home/sections/{$jsonSection->id}",
            ['value' => 'not-valid-json {{{']
        );

        $response->assertStatus(422);
        $response->assertJson(['ok' => false]);
    }

    public function test_unauthenticated_request_is_redirected_to_login(): void
    {
        $response = $this->get('/admin/pages');

        $response->assertRedirect(route('admin.login'));
    }
}
