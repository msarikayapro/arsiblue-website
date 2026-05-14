@php
    $sectionUrl = route('admin.pages.section.update', [$page->slug, $section->id]);
    $uploadUrl = route('admin.pages.upload-image', $page->slug);
    $label = $labels[$section->section_key] ?? Str::headline($section->section_key);
    $value = (string) $section->content;

    if ($section->content_type === 'json' && $value !== '') {
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $value = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
    }
@endphp

<div id="section-{{ $section->id }}"
     x-data="sectionEditor({
        url: @js($sectionUrl),
        uploadUrl: @js($uploadUrl),
        type: @js($section->content_type),
        initial: @js($value)
     })"
     class="bg-surface-container-lowest rounded-3xl p-8 ambient-shadow-lvl1 border border-outline-variant">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-primary-container/10 flex items-center justify-center text-primary">
                <span class="material-symbols-outlined">{{ $iconByType[$section->content_type] ?? 'edit' }}</span>
            </div>
            <div>
                <h3 class="font-headline-md text-on-surface">{{ $label }}</h3>
                <p class="text-xs text-on-surface-variant">
                    <code>{{ $section->section_key }}</code> ·
                    <span class="uppercase tracking-wider">{{ $section->content_type }}</span>
                </p>
            </div>
        </div>
        <div x-cloak x-show="dirty" class="text-xs text-tertiary">değişti</div>
    </div>

    {{-- Body — content_type bazlı --}}
    @switch($section->content_type)

        @case('text')
            @if (str_contains($section->section_key, 'subtitle') || str_contains($section->section_key, 'subtext'))
                <textarea x-model="value"
                          @input.debounce.1500ms="save()"
                          rows="3"
                          class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant
                                 focus:border-primary focus:ring-0 text-on-surface text-body-md transition-all"></textarea>
            @else
                <input x-model="value"
                       @input.debounce.1500ms="save()"
                       type="text"
                       class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant
                              focus:border-primary focus:ring-0 text-on-surface text-body-md transition-all">
            @endif
            @break

        @case('html')
            <div class="border border-outline-variant rounded-2xl overflow-hidden bg-surface-container-low">
                <div class="px-4 py-2 bg-surface-container-high border-b border-outline-variant text-xs text-on-surface-variant">
                    HTML kabul edilir: <code>&lt;p&gt;</code>, <code>&lt;strong&gt;</code>, <code>&lt;a href&gt;</code>, <code>&lt;ul&gt;&lt;li&gt;</code>
                </div>
                <textarea x-model="value"
                          @input.debounce.1500ms="save()"
                          rows="8"
                          class="w-full p-6 bg-surface-container-low border-0 focus:ring-0
                                 text-body-md text-on-surface font-mono text-sm leading-relaxed resize-y"></textarea>
            </div>
            @break

        @case('image')
            <div class="space-y-4">
                <div class="relative group aspect-video rounded-2xl overflow-hidden border-2 border-dashed border-outline-variant
                            bg-surface-container flex flex-col items-center justify-center">
                    <template x-if="value">
                        <img :src="imageUrl(value)" alt="" class="absolute inset-0 w-full h-full object-cover">
                    </template>
                    <template x-if="!value">
                        <div class="text-center text-on-surface-variant py-12">
                            <span class="material-symbols-outlined text-4xl block mb-2">add_photo_alternate</span>
                            <p class="text-body-md">Görsel yükleyin (JPG, PNG, WebP — max 4MB)</p>
                        </div>
                    </template>
                    <label class="absolute inset-0 cursor-pointer
                                  bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity
                                  flex flex-col items-center justify-center text-white">
                        <span class="material-symbols-outlined text-3xl mb-2">photo_camera</span>
                        <span class="text-label-md">Yeni görsel seç</span>
                        <input type="file" accept="image/*" class="hidden" @change="uploadImage($event)">
                    </label>
                </div>
                <div class="flex items-center gap-3 text-xs text-on-surface-variant">
                    <span x-show="value" class="truncate" x-text="value"></span>
                    <button x-show="value"
                            @click="value = ''; save()"
                            type="button"
                            class="text-error hover:underline">
                        Görseli kaldır
                    </button>
                </div>
                <p class="text-[11px] text-on-surface-variant italic">Önerilen boyut: 1920×1080 px (hero için).</p>
            </div>
            @break

        @case('json')
            <div class="border border-outline-variant rounded-2xl overflow-hidden bg-surface-container-low">
                <div class="px-4 py-2 bg-surface-container-high border-b border-outline-variant text-xs text-on-surface-variant flex items-center justify-between">
                    <span>Düzenlenebilir JSON · format hatası olursa kayıt yapılmaz</span>
                    <button @click="prettify()" type="button"
                            class="text-primary hover:underline text-xs">Yeniden formatla</button>
                </div>
                <textarea x-model="value"
                          @input.debounce.1500ms="save()"
                          rows="14"
                          spellcheck="false"
                          class="w-full p-6 bg-surface-container-low border-0 focus:ring-0
                                 font-mono text-sm text-on-surface leading-relaxed resize-y"></textarea>
                <div x-show="error" x-cloak
                     class="px-4 py-2 bg-error-container/30 border-t border-error/30 text-error text-xs">
                    <span x-text="error"></span>
                </div>
            </div>
            @break

        @default
            <div class="rounded-xl bg-error-container/20 border border-error/30 text-on-error-container p-4 text-body-md">
                Bilinmeyen content_type: <code>{{ $section->content_type }}</code> — destek eklenmedi.
            </div>
    @endswitch

</div>
