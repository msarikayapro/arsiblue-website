@extends('layouts.admin')

@section('title', 'Sayfa İçeriği · '.$page->title)
@section('page-title', $page->title.' — Bölümler')

@php
    $previewUrl = $page->slug === 'home' ? url('/') : url('/'.$page->slug);

    // Section_key → human label mapping (yumuşak başlıklar için)
    $labels = [
        'hero_title' => 'Hero Başlık',
        'hero_subtitle' => 'Hero Alt Başlık',
        'hero_image' => 'Hero Görseli',
        'trust_strip' => 'Güven Şeridi (5 madde)',
        'about_title' => 'Hakkımızda Başlık',
        'about_text' => 'Hakkımızda Metni',
        'why_us' => 'Neden Biz? (kart listesi)',
        'final_cta_title' => 'Final CTA Başlık',
        'final_cta_subtitle' => 'Final CTA Alt Metin',
        'benefits_list' => 'Paket İçeriği',
        'cta_text' => 'CTA Metin',
        'package_includes' => 'Paket Dahil Olanlar',
        'family_features' => 'Aile Özellikleri',
        'safety_text' => 'Güvenlik Metni',
        'intro_text' => 'Tanıtım Metni',
        'facilities' => 'Tesisler Listesi',
    ];

    $iconByType = [
        'text' => 'short_text',
        'html' => 'description',
        'image' => 'image',
        'json' => 'data_array',
    ];
@endphp

@section('topbar-left')
    <a href="{{ route('admin.pages.index') }}" class="p-2 hover:bg-surface-container rounded-full text-on-surface-variant" title="Geri">
        <span class="material-symbols-outlined">arrow_back</span>
    </a>
    <div>
        <h2 class="font-headline-md text-on-surface">{{ $page->title }} — Bölümler</h2>
        <p class="text-xs text-on-surface-variant">Son güncelleme: {{ $page->updated_at->diffForHumans() }}</p>
    </div>
@endsection

@section('topbar-right')
    <div x-data="{ status: 'idle', savedAt: null }"
         x-init="window.addEventListener('section-saving', () => status = 'saving');
                 window.addEventListener('section-saved', e => { status = 'saved'; savedAt = e.detail.saved_at });
                 window.addEventListener('section-failed', () => status = 'error');"
         class="text-label-md mr-2">
        <span x-show="status === 'idle'" class="text-on-surface-variant">Otomatik kayıt aktif</span>
        <span x-show="status === 'saving'" class="text-tertiary flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px] animate-spin">refresh</span>
            Kaydediliyor...
        </span>
        <span x-show="status === 'saved'" x-cloak class="text-secondary flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px]">check_circle</span>
            Kaydedildi · <span x-text="savedAt"></span>
        </span>
        <span x-show="status === 'error'" x-cloak class="text-error flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px]">error</span>
            Kayıt hatası
        </span>
    </div>
    <a href="{{ $previewUrl }}" target="_blank"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-outline text-primary text-label-md hover:bg-primary-fixed transition">
        <span class="material-symbols-outlined text-[18px]">open_in_new</span>
        Önizle
    </a>
@endsection

@section('content')
    <div class="flex gap-6 -mx-gutter -my-4 min-h-[calc(100vh-8rem)]">

        {{-- ===== Sol: Sayfa Yapısı ===== --}}
        <aside class="w-72 shrink-0 bg-surface-container-low border-r border-outline-variant py-6 px-4 overflow-y-auto sticky top-16 max-h-[calc(100vh-4rem)]">
            <h3 class="text-xs uppercase tracking-wider font-semibold text-on-surface-variant mb-4 px-2">Sayfa Yapısı</h3>

            @if ($page->contents->isEmpty())
                <p class="text-body-md text-on-surface-variant px-2">
                    Bu sayfa için henüz tanımlı bölüm yok. Seeder çalıştırın veya CLI'dan ekleyin.
                </p>
            @else
                <ul class="space-y-2">
                    @foreach ($page->contents as $section)
                        <li>
                            <a href="#section-{{ $section->id }}"
                               class="group flex items-center gap-3 px-3 py-2.5 rounded-xl border border-outline-variant
                                      bg-surface-container-lowest hover:border-primary-fixed transition-all">
                                <span class="material-symbols-outlined text-on-surface-variant text-[20px]">
                                    {{ $iconByType[$section->content_type] ?? 'edit' }}
                                </span>
                                <span class="flex-1 text-sm text-on-surface truncate">
                                    {{ $labels[$section->section_key] ?? Str::headline($section->section_key) }}
                                </span>
                                <span class="text-[10px] uppercase tracking-wider text-on-surface-variant">
                                    {{ $section->content_type }}
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif

            <div class="mt-6 p-4 bg-primary-fixed rounded-2xl border border-primary-container">
                <p class="text-on-primary-fixed-variant text-xs font-semibold mb-1">İPUCU</p>
                <p class="text-on-primary-fixed-variant text-xs leading-relaxed">
                    Değişiklikler otomatik olarak 1.5 saniye içinde kaydedilir. JSON alanlarında format hatası olursa kayıt durur.
                </p>
            </div>
        </aside>

        {{-- ===== Orta: Section editörleri ===== --}}
        <section class="flex-1 max-w-3xl mx-auto py-6 space-y-6">

            @foreach ($page->contents as $section)
                @include('admin.pages.partials.section-editor', ['section' => $section, 'labels' => $labels, 'iconByType' => $iconByType, 'page' => $page])
            @endforeach

            <div class="text-center text-xs text-on-surface-variant pt-4">
                Bölüm eklemek/silmek için CLI ya da seeder kullanın. Drag-drop reorder Adım 6+ ile gelecek.
            </div>
        </section>
    </div>
@endsection
