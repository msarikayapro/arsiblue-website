<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EventLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = EventLog::query();

        if ($e = $request->query('event_name')) {
            $query->where('event_name', $e);
        }
        if ($city = $request->query('city')) {
            $query->where('city', 'like', "%{$city}%");
        }
        if ($device = $request->query('device')) {
            $query->where('device', $device);
        }
        if ($source = $request->query('utm_source')) {
            $query->where('utm_source', $source);
        }
        if ($from = $request->query('from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->query('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $events = $query->latest()->paginate(50)->withQueryString();

        $kpis = [
            'total' => $query->count(),
            'page_view' => (clone $query)->where('event_name', 'page_view')->count(),
            'whatsapp' => (clone $query)->where('event_name', 'whatsapp_click')->count(),
            'phone' => (clone $query)->where('event_name', 'phone_click')->count(),
            'leads' => (clone $query)->where('event_name', 'lead_form_submit')->count(),
        ];

        $eventTypes = EventLog::distinct()->pluck('event_name')->filter()->sort()->values();

        return view('admin.events.index', compact('events', 'kpis', 'eventTypes'));
    }

    public function show(EventLog $event): View
    {
        return view('admin.events.show', compact('event'));
    }

    public function export(Request $request): StreamedResponse
    {
        $headers = ['Content-Type' => 'text/csv; charset=UTF-8'];
        $filename = 'events-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($request) {
            $out = fopen('php://output', 'w');
            // BOM for Excel UTF-8 detection
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['ID', 'Tarih', 'Event', 'Sayfa', 'Şehir', 'Cihaz', 'UTM Source', 'UTM Campaign']);

            EventLog::query()
                ->when($request->query('event_name'), fn ($q, $v) => $q->where('event_name', $v))
                ->when($request->query('from'), fn ($q, $v) => $q->whereDate('created_at', '>=', $v))
                ->when($request->query('to'), fn ($q, $v) => $q->whereDate('created_at', '<=', $v))
                ->orderByDesc('created_at')
                ->chunk(500, function ($chunk) use ($out) {
                    foreach ($chunk as $e) {
                        fputcsv($out, [
                            $e->id,
                            $e->created_at?->format('Y-m-d H:i:s'),
                            $e->event_name,
                            $e->current_page,
                            $e->city,
                            $e->device,
                            $e->utm_source,
                            $e->utm_campaign,
                        ]);
                    }
                });

            fclose($out);
        }, $filename, $headers);
    }
}
