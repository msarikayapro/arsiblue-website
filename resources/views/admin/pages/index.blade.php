@extends('layouts.admin')

@section('title', 'Sayfa İçerikleri')
@section('page-title', 'Sayfa İçerikleri')

@section('content')
    <div class="max-w-container-max-width mx-auto space-y-6">

        <div class="bg-surface-container-lowest rounded-xl p-6 ambient-shadow-lvl1 border border-outline-variant/30">
            <p class="text-body-md text-on-surface-variant">
                Aşağıda site genelindeki tüm dinamik sayfalar listelenmiştir. Her sayfanın bölümlerini ayrı ayrı düzenleyebilir,
                metin, görsel ve liste içeriklerini güncelleyebilirsiniz.
            </p>
        </div>

        <div class="bg-surface-container-lowest rounded-xl ambient-shadow-lvl1 border border-outline-variant/30 overflow-hidden">
            <table class="w-full text-body-md">
                <thead class="bg-surface-container-low text-label-md text-on-surface-variant">
                    <tr>
                        <th class="text-left px-6 py-4">Sayfa Adı</th>
                        <th class="text-left px-6 py-4">Slug</th>
                        <th class="text-left px-6 py-4">Şablon</th>
                        <th class="text-center px-6 py-4">Durum</th>
                        <th class="text-center px-6 py-4">Bölüm</th>
                        <th class="text-left px-6 py-4">Son Güncelleme</th>
                        <th class="text-right px-6 py-4">İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pages as $page)
                        <tr class="border-t border-outline-variant/30 hover:bg-surface-container-low/50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-on-surface">{{ $page->title }}</td>
                            <td class="px-6 py-4 text-on-surface-variant">
                                <code class="text-xs bg-surface-container px-2 py-1 rounded">/{{ $page->slug === 'home' ? '' : $page->slug }}</code>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs uppercase tracking-wider text-on-surface-variant">{{ $page->template }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($page->is_active)
                                    <span class="inline-flex items-center gap-1 text-secondary text-label-md">
                                        <span class="w-2 h-2 rounded-full bg-secondary"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-on-surface-variant text-label-md">
                                        <span class="w-2 h-2 rounded-full bg-outline-variant"></span>
                                        Pasif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center text-on-surface-variant">{{ $page->contents_count }}</td>
                            <td class="px-6 py-4 text-on-surface-variant text-sm">{{ $page->updated_at->diffForHumans() }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.pages.edit', $page->slug) }}"
                                   class="inline-flex items-center gap-2 bg-primary text-on-primary text-label-md px-4 py-2 rounded-lg hover:bg-primary/90 transition">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                    Düzenle
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection
