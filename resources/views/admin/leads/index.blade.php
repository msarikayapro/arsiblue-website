@extends('layouts.admin')

@section('title', 'Lead\'ler')
@section('page-title', 'Lead\'ler')

@section('content')
    <div class="max-w-container-max-width mx-auto space-y-6">

        {{-- Status pills --}}
        <div class="flex flex-wrap gap-2">
            @foreach (['' => ['Tümü', $counts['all']], 'yeni' => ['Yeni', $counts['yeni']], 'aranildi' => ['Arandı', $counts['aranildi']], 'sonuclandi' => ['Sonuçlandı', $counts['sonuclandi']], 'iptal' => ['İptal', $counts['iptal']]] as $key => [$label, $count])
                <a href="{{ route('admin.leads.index', $key ? ['status' => $key] : []) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-label-md border transition
                          {{ request('status') == $key
                                ? 'bg-primary text-on-primary border-primary'
                                : 'bg-surface-container-lowest text-on-surface border-outline-variant hover:border-primary' }}">
                    {{ $label }}
                    <span class="text-xs opacity-80">{{ $count }}</span>
                </a>
            @endforeach
        </div>

        {{-- Filter bar --}}
        <form method="GET" class="bg-surface-container-lowest rounded-xl p-4 ambient-shadow-lvl1 border border-outline-variant/30 flex flex-wrap gap-3 items-end">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <div>
                <label class="block text-xs text-on-surface-variant mb-1">Landing</label>
                <input name="landing" value="{{ request('landing') }}" placeholder="bayrama-ozel"
                       class="px-3 py-2 rounded-lg bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 text-sm">
            </div>
            <div>
                <label class="block text-xs text-on-surface-variant mb-1">Tarih (Başlangıç)</label>
                <input type="date" name="from" value="{{ request('from') }}"
                       class="px-3 py-2 rounded-lg bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 text-sm">
            </div>
            <div>
                <label class="block text-xs text-on-surface-variant mb-1">Tarih (Bitiş)</label>
                <input type="date" name="to" value="{{ request('to') }}"
                       class="px-3 py-2 rounded-lg bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 text-sm">
            </div>
            <button type="submit" class="bg-primary text-on-primary text-label-md px-4 py-2 rounded-lg hover:bg-primary/90">Filtrele</button>
        </form>

        {{-- List --}}
        <div class="bg-surface-container-lowest rounded-xl ambient-shadow-lvl1 border border-outline-variant/30 overflow-hidden">
            <table class="w-full text-body-md">
                <thead class="bg-surface-container-low text-label-md text-on-surface-variant">
                    <tr>
                        <th class="text-left px-6 py-4">Ad</th>
                        <th class="text-left px-6 py-4">Telefon</th>
                        <th class="text-left px-6 py-4">Mesaj</th>
                        <th class="text-left px-6 py-4">Landing</th>
                        <th class="text-center px-6 py-4">Durum</th>
                        <th class="text-left px-6 py-4">Geliş</th>
                        <th class="text-right px-6 py-4">İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leads as $lead)
                        @php
                            $colors = [
                                'yeni' => ['bg-primary-container/30', 'text-primary'],
                                'aranildi' => ['bg-tertiary-container/30', 'text-tertiary'],
                                'sonuclandi' => ['bg-secondary-container/30', 'text-secondary'],
                                'iptal' => ['bg-surface-container', 'text-on-surface-variant'],
                            ];
                            [$bg, $text] = $colors[$lead->status] ?? $colors['yeni'];
                        @endphp
                        <tr class="border-t border-outline-variant/30 hover:bg-surface-container-low/50">
                            <td class="px-6 py-4 font-semibold">{{ $lead->name }}</td>
                            <td class="px-6 py-4 text-on-surface-variant">{{ $lead->phone }}</td>
                            <td class="px-6 py-4 text-on-surface-variant truncate max-w-xs">{{ Str::limit($lead->message, 60) }}</td>
                            <td class="px-6 py-4 text-xs text-on-surface-variant">{{ $lead->landing_page }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-2 py-1 rounded-full text-xs {{ $bg }} {{ $text }}">{{ ucfirst($lead->status) }}</span>
                            </td>
                            <td class="px-6 py-4 text-on-surface-variant text-sm">{{ $lead->createdAtFormatted() }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.leads.show', $lead) }}"
                                   class="inline-flex items-center gap-1 text-primary text-label-md hover:underline">
                                    Detay <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-on-surface-variant">
                                <span class="material-symbols-outlined text-4xl block mb-2">inbox</span>
                                Henüz lead yok.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $leads->links() }}</div>
    </div>
@endsection
