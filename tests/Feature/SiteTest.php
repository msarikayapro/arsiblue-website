<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[DataProvider('publicRoutes')]
    public function test_public_pages_render(string $url, string $expectedSubstring): void
    {
        $response = $this->get($url);
        $response->assertOk();
        $response->assertSeeText($expectedSubstring);
    }

    public static function publicRoutes(): array
    {
        return [
            'home' => ['/', 'Akdeniz'],
            'bayrama-ozel' => ['/bayrama-ozel', 'Bayrama'],
            'balayi-paketi' => ['/balayi-paketi', 'Balayı'],
            'aile-oteli' => ['/aile-oteli', 'Aile'],
            'odalar' => ['/odalar', 'Konforlu'],
            'tesisler' => ['/tesisler', 'Tesisler'],
            'galeri' => ['/galeri', 'Galeri'],
            'iletisim' => ['/iletisim', 'Bize Ulaşın'],
            'kvkk' => ['/kvkk', 'KVKK'],
            'cerez' => ['/cerez-politikasi', 'Çerez'],
            'hakkimizda' => ['/hakkimizda', 'Hakkımızda'],
        ];
    }

    public function test_lead_form_creates_lead_and_event(): void
    {
        $response = $this->postJson('/bilgi-al', [
            'name' => 'Test Misafir',
            'phone' => '0 555 123 45 67',
            'message' => 'Bilgi istiyorum.',
        ]);

        $response->assertOk()->assertJson(['ok' => true]);

        $lead = \App\Models\Lead::where('name', 'Test Misafir')->first();
        $this->assertNotNull($lead);
        $this->assertSame('yeni', $lead->status);
        $this->assertNotNull($lead->event_log_id);

        $event = \App\Models\EventLog::find($lead->event_log_id);
        $this->assertSame('lead_form_submit', $event->event_name);
    }

    public function test_lead_form_honeypot_silently_succeeds_without_creating_lead(): void
    {
        $count = \App\Models\Lead::count();

        $response = $this->postJson('/bilgi-al', [
            'name' => 'Bot',
            'phone' => '+9999',
            'website' => 'spammer.com', // honeypot tetiklendi
        ]);

        $response->assertOk();
        $this->assertSame($count, \App\Models\Lead::count(), 'Honeypot lead\'i kabul etti — yanlış!');
    }

    public function test_lead_form_validates_required_fields(): void
    {
        $response = $this->postJson('/bilgi-al', ['phone' => '5551234567']);
        $response->assertStatus(422)->assertJsonValidationErrors('name');

        $response = $this->postJson('/bilgi-al', ['name' => 'A', 'phone' => 'abc']);
        $response->assertStatus(422)->assertJsonValidationErrors('phone');
    }

    public function test_meta_tags_present_in_home(): void
    {
        $response = $this->get('/');
        $response->assertSee('<title>', false);
        $response->assertSee('og:title', false);
        $response->assertSee('canonical', false);
        $response->assertSee('schema.org', false);
    }
}
