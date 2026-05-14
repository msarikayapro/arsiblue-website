<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function __construct(private SettingService $settings)
    {
    }

    public function edit(): View
    {
        return view('admin.settings.edit');
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'site_name' => ['nullable', 'string', 'max:120'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'maintenance_mode' => ['nullable', 'boolean'],
            'maintenance_message' => ['nullable', 'string', 'max:500'],
        ]);

        $this->settings->set('site_name', $data['site_name'] ?? '', 'text', 'general');
        $this->settings->set('site_tagline', $data['site_tagline'] ?? '', 'text', 'general');
        $this->settings->set('maintenance_mode', $request->boolean('maintenance_mode'), 'boolean', 'general');
        $this->settings->set('maintenance_message', $data['maintenance_message'] ?? '', 'text', 'general');

        if ($request->hasFile('site_logo')) {
            $request->validate(['site_logo' => 'image|max:2048']);
            $f = $request->file('site_logo');
            $name = 'logo-'.time().'.'.$f->extension();
            $f->move(public_path('storage/uploads'), $name);
            $this->settings->set('site_logo', $name, 'file', 'general');
        }

        if ($request->hasFile('site_favicon')) {
            $request->validate(['site_favicon' => 'image|max:512']);
            $f = $request->file('site_favicon');
            $name = 'favicon-'.time().'.'.$f->extension();
            $f->move(public_path('storage/uploads'), $name);
            $this->settings->set('site_favicon', $name, 'file', 'general');
        }

        return back()->with('success', 'Genel ayarlar kaydedildi.');
    }
}
