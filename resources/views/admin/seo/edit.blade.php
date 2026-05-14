@extends('layouts.admin')

@section('title', 'SEO: '.$page->title)
@section('page-title', 'SEO: '.$page->title)

@section('topbar-left')
    <a href="{{ route('admin.seo.index') }}" class="p-2 hover:bg-surface-container rounded-full text-on-surface-variant">
        <span class="material-symbols-outlined">arrow_back</span>
    </a>
    <h2 class="font-headline-md text-on-surface">SEO: {{ $page->title }}</h2>
@endsection

@section('content')
    <form action="{{ route('admin.seo.update', $page->slug) }}" method="POST"
          x-data="{ title: @js($seo->title ?? ''), desc: @js($seo->description ?? ''), siteName: @js(setting('site_name', 'Arsi Blue Beach')) }"
          class="max-w-container-max-width mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf @method('PUT')

        <div class="lg:col-span-2 space-y-6">

            <x-admin.section-card icon="title" title="Temel Meta Tag'ler">
                <div class="space-y-4">
                    <div>
                        <label class="block text-label-md text-on-surface mb-2">Title (max 60 karakter)</label>
                        <input name="title" type="text" maxlength="70" x-model="title"
                               class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                        <p class="text-xs mt-1"
                           :class="title.length > 60 ? 'text-error' : (title.length > 55 ? 'text-tertiary' : 'text-on-surface-variant')">
                            <span x-text="title.length"></span> / 60 karakter
                        </p>
                    </div>
                    <div>
                        <label class="block text-label-md text-on-surface mb-2">Description (max 160 karakter)</label>
                        <textarea name="description" rows="3" maxlength="200" x-model="desc"
                                  class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0"></textarea>
                        <p class="text-xs mt-1"
                           :class="desc.length > 160 ? 'text-error' : (desc.length > 150 ? 'text-tertiary' : 'text-on-surface-variant')">
                            <span x-text="desc.length"></span> / 160 karakter
                        </p>
                    </div>
                    <div>
                        <label class="block text-label-md text-on-surface mb-2">Keywords</label>
                        <input name="keywords" type="text" maxlength="255"
                               value="{{ old('keywords', $seo->keywords) }}"
                               class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-label-md text-on-surface mb-2">Canonical URL</label>
                            <input name="canonical_url" type="url"
                                   value="{{ old('canonical_url', $seo->canonical_url) }}"
                                   class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                        </div>
                        <div>
                            <label class="block text-label-md text-on-surface mb-2">Robots</label>
                            <select name="robots" class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                                @foreach (['index,follow', 'noindex,follow', 'index,nofollow', 'noindex,nofollow'] as $r)
                                    <option value="{{ $r }}" {{ ($seo->robots ?? 'index,follow') === $r ? 'selected' : '' }}>{{ $r }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </x-admin.section-card>

            <x-admin.section-card icon="share" title="Open Graph (Sosyal Medya)" variant="secondary">
                <div class="space-y-4">
                    <div>
                        <label class="block text-label-md text-on-surface mb-2">OG Title</label>
                        <input name="og_title" type="text" maxlength="70"
                               value="{{ old('og_title', $seo->og_title) }}"
                               class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                    </div>
                    <div>
                        <label class="block text-label-md text-on-surface mb-2">OG Description</label>
                        <textarea name="og_description" rows="2" maxlength="200"
                                  class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0">{{ old('og_description', $seo->og_description) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-label-md text-on-surface mb-2">OG Image (filename veya URL)</label>
                        <input name="og_image" type="text" maxlength="255"
                               value="{{ old('og_image', $seo->og_image) }}"
                               placeholder="og-home.jpg veya https://..."
                               class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 font-mono text-sm min-h-[48px]">
                    </div>
                </div>
            </x-admin.section-card>

            <x-admin.section-card icon="data_object" title="Custom JSON-LD Schema (advanced)" variant="tertiary">
                <textarea name="schema_json" rows="10"
                          placeholder="{...JSON-LD schema.org objesi...}"
                          class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 font-mono text-sm">{{ old('schema_json', $seo->schema_json) }}</textarea>
                @error('schema_json')<p class="text-xs text-error mt-1">{{ $message }}</p>@enderror
                <p class="text-xs text-on-surface-variant mt-1">Otomatik schema'lara EK olarak gömülür. Boş bırakabilirsiniz.</p>
            </x-admin.section-card>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 bg-primary text-on-primary text-label-md px-6 py-3 rounded-lg hover:bg-primary/90 shadow-sm min-h-[48px]">
                    <span class="material-symbols-outlined text-[20px]">save</span>
                    Kaydet
                </button>
            </div>
        </div>

        {{-- Google preview --}}
        <div class="lg:col-span-1">
            <div class="sticky top-20">
                <x-admin.section-card icon="search" title="Google Sonuç Önizleme">
                    <div class="border border-outline-variant rounded-lg p-4 bg-white">
                        <p class="text-xs text-on-surface-variant truncate">arsibluebeach.com/{{ $page->slug === 'home' ? '' : $page->slug }}</p>
                        <p class="text-lg text-blue-700 font-medium mt-1 line-clamp-1" x-text="title || '(Title eksik)'"></p>
                        <p class="text-sm text-on-surface-variant mt-1 line-clamp-3" x-text="desc || '(Description eksik)'"></p>
                    </div>
                </x-admin.section-card>
            </div>
        </div>
    </form>
@endsection
