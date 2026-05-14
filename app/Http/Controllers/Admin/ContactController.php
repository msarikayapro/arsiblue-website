<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function __construct(private SettingService $settings)
    {
    }

    public function edit(): View
    {
        return view('admin.contact.edit');
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'phone_landline' => ['nullable', 'string', 'max:30'],
            'phone_whatsapp' => ['nullable', 'string', 'max:30'],
            'phone_gsm' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:120'],
            'address' => ['nullable', 'string', 'max:500'],
            'google_maps_embed' => ['nullable', 'string', 'max:2000'],
            'working_hours' => ['nullable', 'string', 'max:200'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'tiktok_url' => ['nullable', 'url', 'max:255'],
            'tripadvisor_url' => ['nullable', 'url', 'max:255'],
            'google_business_url' => ['nullable', 'url', 'max:255'],
        ]);

        foreach ($data as $key => $value) {
            $this->settings->set($key, $value ?? '', 'text', 'contact');
        }

        return back()->with('success', 'İletişim bilgileri kaydedildi.');
    }
}
