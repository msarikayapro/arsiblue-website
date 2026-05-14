@extends('layouts.admin')

@section('title', 'Event Logları')
@section('page-title', 'Event Logları')

@section('topbar-right')
    <a href="{{ route('admin.events.export', request()->query()) }}"
       class="inline-flex items-center gap-2 bg-secondary text-on-secondary text-label-md px-4 py-2 rounded-lg hover:bg-secondary/90">
        <span class="material-symbols-outlined text-[18px]">file_download</span>
        CSV Export
    </a>
@endsection

@section('content')
    <div class="max-w-container-max-width mx-auto space-y-6">

        {{-- KPIs --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
            @foreach ([['Toplam', $kpis['total'], 'analytics'], ['PageView', $kpis['page_view'], 'visibility'], ['WhatsApp', $kpis['whatsapp'], 'sms'], ['Telefon', $kpis['phone'], 'call'], ['Form', $kpis['leads'], 'mail']] as [$label, $value, $icon])
                <div class="bg-surface-container-lowest rounded-xl p-4 ambient-shadow-lvl1 border border-outline-variant/30">
                    <div class="flex items-center gap-2 text-on-surface-variant text-xs mb-2">
                        <span class="material-symbols-outlined text-[16px]">{{ $icon }}</span>
                        {{ $label }}
                    </div>
                    <p class="text-2xl font-display-lg text-on-surface">{{ number_format($value, 0, ',', '.') }}</p>
                </div>
            @endforeach
        </div>

        {{-- Filter --}}
        <form method="GET" class="bg-surface-container-lowest rounded-xl p-4 ambient-shadow-lvl1 border border-outline-variant/30 flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs text-on-surface-variant mb-1">Event Tipi</label>
                <select name="event_name" class="px-3 py-2 rounded-lg bg-surface-container-low border-outline-variant text-sm">
                    <option value="">Tümü</option>
                    @foreach ($eventTypes as $t)
                        <option value="{{ $t }}" {{ request('event_name') === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-on-surface-variant mb-1">Şehir</label>
                <input name="city" value="{{ request('city') }}" class="px-3 py-2 rounded-lg bg-surface-container-low border-outline-variant text-sm">
            </div>
            <div>
                <label class="block text-xs text-on-surface-variant mb-1">Cihaz</label>
                <select name="device" class="px-3 py-2 rounded-lg bg-surface-container-low border-outline-variant text-sm">
                    <option value="">Tümü</option>
                    @foreach (['mobile', 'tablet', 'desktop'] as $d)
                        <option value="{{ $d }}" {{ request('device') === $d ? 'selected' : '' }}>{{ ucfirst($d) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-on-surface-variant mb-1">UTM Source</label>
                <input name="utm_source" value="{{ request('utm_source') }}" class="px-3 py-2 rounded-lg bg-surface-container-low border-outline-variant text-sm">
            </div>
            <div>
                <label class="block text-xs text-on-surface-variant mb-1">Başlangıç</label>
                <input type="date" name="from" value="{{ request('from') }}" class="px-3 py-2 rounded-lg bg-surface-container-low border-outline-variant text-sm">
            </div>
            <div>
                <label class="block text-xs text-on-surface-variant mb-1">Bitiş</label>
                <input type="date" name="to" value="{{ request('to') }}" class="px-3 py-2 rounded-lg bg-surface-container-low border-outline-variant text-sm">
            </div>
            <button type="submit" class="bg-primary text-on-primary text-label-md px-4 py-2 rounded-lg hover:bg-primary/90">Filtrele</button>
        </form>

        {{-- Table --}}
        <div class="bg-surface-container-lowest rounded-xl ambient-shadow-lvl1 border border-outline-variant/30 overflow-hidden">
            <table class="w-full text-body-md">
                <thead class="bg-surface-container-low text-label-md text-on-surface-variant">
                    <tr>
                        <th class="text-left px-6 py-3">Zaman</th>
                        <th class="text-left px-6 py-3">Event</th>
                        <th class="text-left px-6 py-3">Şehir</th>
                        <th class="text-left px-6 py-3">Cihaz</th>
                        <th class="text-left px-6 py-3">Source</th>
                        <th class="text-left px-6 py-3">Sayfa</th>
                        <th class="text-right px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($events as $event)
                        <tr class="border-t border-outline-variant/30 hover:bg-surface-container-low/50">
                            <td class="px-6 py-3 text-sm text-on-surface-variant whitespace-nowrap">{{ $event->created_at?->format('d.m.Y H:i') }}</td>
                            <td class="px-6 py-3"><span class="rounded-full bg-primary-container/20 text-primary px-2 py-0.5 text-xs">{{ $event->event_name }}</span></td>
                            <td class="px-6 py-3">{{ $event->city ?? '—' }}</td>
                            <td class="px-6 py-3 text-sm">{{ $event->device ?? '—' }}</td>
                            <td class="px-6 py-3 text-sm">{{ $event->utm_source ?? '—' }}</td>
                            <td class="px-6 py-3 text-sm truncate max-w-xs">{{ $event->current_page ?? '—' }}</td>
                            <td class="px-6 py-3 text-right">
                                <a href="{{ route('admin.events.show', $event) }}" class="text-primary text-label-md hover:underline">Detay →</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-12 text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl block mb-2">inbox</span>
                            Henüz event yok. Tracking Adım 11'de aktifleşecek.
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $events->links() }}</div>
    </div>
@endsection
