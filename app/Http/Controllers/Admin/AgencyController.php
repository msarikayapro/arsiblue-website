<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AgencyController extends Controller
{
    public function __construct(private SettingService $settings)
    {
    }

    public function edit(): View
    {
        return view('admin.agency.edit');
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'agency_name' => ['nullable', 'string', 'max:200'],
            'agency_tax_number' => ['nullable', 'string', 'max:30'],
            'agency_tax_office' => ['nullable', 'string', 'max:120'],
            'agency_mersis' => ['nullable', 'string', 'max:30'],
            'agency_trade_registry' => ['nullable', 'string', 'max:60'],
            'agency_tursab_number' => ['nullable', 'string', 'max:30'],
            'agency_kep' => ['nullable', 'email', 'max:120'],
            'agency_email' => ['nullable', 'email', 'max:120'],
            'agency_authorized_person' => ['nullable', 'string', 'max:120'],
            'agency_kvkk_responsible' => ['nullable', 'string', 'max:120'],
        ]);

        foreach ($data as $key => $value) {
            $this->settings->set($key, $value ?? '', 'text', 'agency');
        }

        return back()->with('success', 'Acenta bilgileri kaydedildi.');
    }

    public function uploadTursab(Request $request): RedirectResponse
    {
        $request->validate([
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        $filename = 'tursab-'.time().'-'.Str::random(6).'.pdf';
        $request->file('pdf')->move(public_path('storage/agency'), $filename);

        $this->settings->set('agency_tursab_pdf', $filename, 'file', 'agency');

        return back()->with('success', 'TÜRSAB belgesi yüklendi.');
    }
}
