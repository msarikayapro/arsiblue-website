<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\EventLog;
use App\Models\Lead;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();
        $weekAgo = $today->copy()->subDays(6);

        $kpis = [
            'visits_today' => EventLog::today()->byEvent('page_view')->count(),
            'whatsapp_today' => EventLog::today()->byEvent('whatsapp_click')->count(),
            'phone_today' => EventLog::today()->byEvent('phone_click')->count(),
            'forms_today' => EventLog::today()->byEvent('lead_form_submit')->count(),
        ];

        // 7 günlük sparkline (basit dizi)
        $sparkline = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = $today->copy()->subDays($i);
            $sparkline[] = [
                'date' => $day->format('d.m'),
                'visits' => EventLog::whereDate('created_at', $day)->byEvent('page_view')->count(),
                'whatsapp' => EventLog::whereDate('created_at', $day)->byEvent('whatsapp_click')->count(),
                'phone' => EventLog::whereDate('created_at', $day)->byEvent('phone_click')->count(),
                'forms' => EventLog::whereDate('created_at', $day)->byEvent('lead_form_submit')->count(),
            ];
        }

        $activeCampaign = Campaign::active()->orderBy('sort_order')->first();

        $recentEvents = EventLog::orderByDesc('created_at')->limit(10)->get();

        $newLeadsCount = Lead::where('status', 'yeni')->count();

        // Tracking sağlığı (admin/tracking modülü — Adım 7)
        $tracking = [
            'meta_pixel' => filled(setting('meta_pixel_id')) && setting('meta_pixel_active'),
            'meta_capi' => filled(setting('meta_capi_token')) && setting('meta_capi_active'),
            'gtm' => filled(setting('gtm_container_id')),
            'ga4' => filled(setting('ga4_measurement_id')),
        ];

        return view('admin.dashboard', compact(
            'kpis', 'sparkline', 'activeCampaign', 'recentEvents', 'newLeadsCount', 'tracking'
        ));
    }
}
