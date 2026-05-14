<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Faq;
use App\Models\Gallery;
use App\Models\Lead;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminModulesTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = Admin::first();
    }

    #[DataProvider('routesProvider')]
    public function test_admin_module_routes_render(string $route): void
    {
        $response = $this->actingAs($this->admin)->get($route);
        $response->assertOk();
    }

    public static function routesProvider(): array
    {
        return [
            'rooms index' => ['/admin/rooms'],
            'rooms create' => ['/admin/rooms/create'],
            'gallery' => ['/admin/gallery'],
            'faqs' => ['/admin/faqs'],
            'faqs create' => ['/admin/faqs/create'],
            'seo' => ['/admin/seo'],
            'seo edit home' => ['/admin/seo/home'],
            'events' => ['/admin/events'],
            'leads' => ['/admin/leads'],
            'contact' => ['/admin/contact'],
            'agency' => ['/admin/agency'],
            'settings' => ['/admin/settings'],
        ];
    }

    public function test_contact_settings_save(): void
    {
        $this->actingAs($this->admin)->put('/admin/contact', [
            'phone_landline' => '0 242 555 11 22',
            'phone_whatsapp' => '0 555 111 22 33',
            'email' => 'test@arsibluebeach.com',
        ]);

        $this->assertSame('0 242 555 11 22', setting('phone_landline'));
        $this->assertSame('test@arsibluebeach.com', setting('email'));
    }

    public function test_faq_create_and_destroy(): void
    {
        $this->actingAs($this->admin)->post('/admin/faqs', [
            'question' => 'Test sorusu?',
            'answer' => 'Test cevabı.',
            'category' => 'genel',
            'is_active' => '1',
        ]);

        $faq = Faq::where('question', 'Test sorusu?')->first();
        $this->assertNotNull($faq);

        $this->actingAs($this->admin)->delete("/admin/faqs/{$faq->id}");
        $this->assertNull(Faq::find($faq->id));
    }

    public function test_room_crud(): void
    {
        $this->actingAs($this->admin)->post('/admin/rooms', [
            'name' => 'Suite Test',
            'slug' => 'suite-test',
            'short_description' => 'Lüks',
            'features' => ['ac', 'wifi', 'sea_view'],
            'is_active' => '1',
        ]);

        $room = Room::where('slug', 'suite-test')->first();
        $this->assertNotNull($room);
        $this->assertSame(['ac', 'wifi', 'sea_view'], $room->features);
    }

    public function test_lead_status_update(): void
    {
        $lead = Lead::create([
            'name' => 'Ahmet', 'phone' => '05551234567', 'message' => 'test',
            'status' => 'yeni',
        ]);

        $this->actingAs($this->admin)->put("/admin/leads/{$lead->id}/status", ['status' => 'aranildi']);

        $this->assertSame('aranildi', $lead->fresh()->status);
    }

    public function test_seo_save_with_invalid_json_schema_fails(): void
    {
        $response = $this->actingAs($this->admin)->put('/admin/seo/home', [
            'title' => 'Yeni Title',
            'description' => 'desc',
            'schema_json' => 'not-valid {{{',
        ]);

        $response->assertSessionHasErrors('schema_json');
    }

    public function test_gallery_bulk_upload(): void
    {
        $this->actingAs($this->admin)->post('/admin/gallery/upload', [
            'category' => 'havuz',
            'images' => [
                UploadedFile::fake()->image('test1.jpg'),
                UploadedFile::fake()->image('test2.jpg'),
            ],
        ]);

        $this->assertSame(2, Gallery::where('category', 'havuz')->count());
    }
}
