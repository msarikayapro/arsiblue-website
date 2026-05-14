@extends('layouts.admin')

@section('title', 'Kampanyalar')
@section('page-title', 'Kampanyalar')

@section('topbar-right')
    <a href="{{ route('admin.campaigns.create') }}"
       class="inline-flex items-center gap-2 bg-primary text-on-primary text-label-md px-4 py-2 rounded-lg hover:bg-primary/90 transition shadow-sm">
        <span class="material-symbols-outlined text-[18px]">add</span>
        Yeni Kampanya
    </a>
@endsection

@section('content')
    <div class="max-w-container-max-width mx-auto space-y-6">

        @if ($campaigns->isEmpty())
            <div class="bg-surface-container-lowest rounded-xl p-12 ambient-shadow-lvl1 border border-outline-variant/30 text-center">
                <span class="material-symbols-outlined text-5xl text-on-surface-variant block mb-3">local_offer</span>
                <p class="text-body-md text-on-surface-variant">Henüz kampanya yok.</p>
                <a href="{{ route('admin.campaigns.create') }}"
                   class="inline-flex items-center gap-2 mt-4 bg-primary text-on-primary text-label-md px-4 py-2 rounded-lg hover:bg-primary/90">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    İlk kampanyayı oluştur
                </a>
            </div>
        @else
            <div class="bg-surface-container-lowest rounded-xl ambient-shadow-lvl1 border border-outline-variant/30 overflow-hidden">
                <table class="w-full text-body-md">
                    <thead class="bg-surface-container-low text-label-md text-on-surface-variant">
                        <tr>
                            <th class="text-left px-6 py-4">Kampanya</th>
                            <th class="text-right px-6 py-4">Fiyat</th>
                            <th class="text-center px-6 py-4">Oda</th>
                            <th class="text-left px-6 py-4">Bitiş</th>
                            <th class="text-center px-6 py-4">Durum</th>
                            <th class="text-right px-6 py-4">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($campaigns as $campaign)
                            <tr x-data="{ active: {{ $campaign->is_active ? 'true' : 'false' }} }"
                                class="border-t border-outline-variant/30 hover:bg-surface-container-low/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-on-surface">{{ $campaign->title }}</p>
                                    @if ($campaign->subtitle)
                                        <p class="text-xs text-on-surface-variant mt-1">{{ $campaign->subtitle }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    @if ($campaign->old_price)
                                        <p class="text-xs line-through text-on-surface-variant">{{ $campaign->oldPriceFormatted() }}</p>
                                    @endif
                                    <p class="font-semibold text-primary">{{ $campaign->priceFormatted() }}</p>
                                </td>
                                <td class="px-6 py-4 text-center text-on-surface-variant">
                                    {{ $campaign->rooms_left ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-on-surface-variant text-sm">
                                    {{ $campaign->valid_until?->translatedFormat('d M Y') ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button @click="$nextTick(async () => {
                                                const res = await window.axios.post('{{ route('admin.campaigns.toggle-active', $campaign) }}');
                                                if (res.data?.ok) active = res.data.is_active;
                                            })"
                                            type="button"
                                            class="inline-flex items-center gap-2 text-label-md transition"
                                            :class="active ? 'text-secondary' : 'text-on-surface-variant'">
                                        <span class="w-2 h-2 rounded-full"
                                              :class="active ? 'bg-secondary' : 'bg-outline-variant'"></span>
                                        <span x-text="active ? 'Aktif' : 'Pasif'"></span>
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap space-x-2">
                                    <a href="{{ route('admin.campaigns.edit', $campaign) }}"
                                       class="inline-flex items-center gap-2 bg-primary text-on-primary text-label-md px-3 py-2 rounded-lg hover:bg-primary/90">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                        Düzenle
                                    </a>
                                    <form action="{{ route('admin.campaigns.destroy', $campaign) }}" method="POST" class="inline-block"
                                          onsubmit="return confirm('Bu kampanyayı silmek istediğinden emin misin?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Sil"
                                                class="inline-flex items-center text-error p-2 rounded-lg hover:bg-error-container/20">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>
@endsection
