@extends('layouts.admin')

@section('title', $isNew ? 'Yeni Kampanya' : 'Kampanya: '.$campaign->title)
@section('page-title', $isNew ? 'Yeni Kampanya' : $campaign->title)

@php
    $action = $isNew ? route('admin.campaigns.store') : route('admin.campaigns.update', $campaign);
    $uploadUrl = $isNew ? null : route('admin.campaigns.upload-image', $campaign);
@endphp

@section('topbar-left')
    <a href="{{ route('admin.campaigns.index') }}" class="p-2 hover:bg-surface-container rounded-full text-on-surface-variant" title="Geri">
        <span class="material-symbols-outlined">arrow_back</span>
    </a>
    <div>
        <h2 class="font-headline-md text-on-surface">{{ $isNew ? 'Yeni Kampanya' : $campaign->title }}</h2>
        @unless ($isNew)
            <p class="text-xs text-on-surface-variant">Son güncelleme: {{ $campaign->updated_at->diffForHumans() }}</p>
        @endunless
    </div>
@endsection

@section('topbar-right')
    @unless ($isNew)
        <div x-data="{ status: 'idle', savedAt: null }"
             x-init="window.addEventListener('campaign-saving', () => status = 'saving');
                     window.addEventListener('campaign-saved', e => { status = 'saved'; savedAt = e.detail.saved_at });
                     window.addEventListener('campaign-failed', () => status = 'error');"
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
    @endunless
@endsection

@section('content')
    <div class="max-w-4xl mx-auto"
         x-data="campaignForm({
            url: @js($action),
            isNew: @js($isNew),
            uploadUrl: @js($uploadUrl),
            data: @js([
                'title' => $campaign->title,
                'subtitle' => $campaign->subtitle,
                'label_text' => $campaign->label_text,
                'old_price' => $campaign->old_price,
                'new_price' => $campaign->new_price,
                'currency' => $campaign->currency ?? 'TL',
                'nights' => $campaign->nights,
                'adults' => $campaign->adults,
                'children' => $campaign->children,
                'child_age_limit' => $campaign->child_age_limit,
                'rooms_left' => $campaign->rooms_left,
                'urgency_text' => $campaign->urgency_text,
                'countdown_enabled' => (bool) $campaign->countdown_enabled,
                'valid_until' => $campaign->valid_until?->format('Y-m-d\TH:i'),
                'description' => $campaign->description,
                'included_items' => $campaign->included_items ?? [],
                'hero_image' => $campaign->hero_image,
                'gallery_images' => $campaign->gallery_images ?? [],
                'is_active' => (bool) $campaign->is_active,
                'show_on_homepage' => (bool) $campaign->show_on_homepage,
                'visible_landings' => $campaign->visible_landings ?? [],
                'sort_order' => $campaign->sort_order ?? 0,
            ])
         })"
         @input.debounce.3000ms="autoSave()"
         @change.debounce.3000ms="autoSave()">

        <form @submit.prevent="manualSave()" class="space-y-6">
            @csrf

            {{-- 1) Temel bilgiler --}}
            <x-admin.section-card icon="title" title="Temel Bilgiler">
                <div class="space-y-4">
                    <div>
                        <label class="block text-label-md text-on-surface mb-2">Başlık *</label>
                        <input x-model="data.title" type="text" required
                               class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 text-body-md min-h-[48px]">
                    </div>
                    <div>
                        <label class="block text-label-md text-on-surface mb-2">Alt Başlık</label>
                        <input x-model="data.subtitle" type="text"
                               class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 text-body-md min-h-[48px]">
                    </div>
                    <div>
                        <label class="block text-label-md text-on-surface mb-2">Etiket (örn: "Bayrama Özel")</label>
                        <input x-model="data.label_text" type="text" maxlength="50"
                               class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 text-body-md min-h-[48px]">
                    </div>
                </div>
            </x-admin.section-card>

            {{-- 2) Fiyatlandırma --}}
            <x-admin.section-card icon="payments" title="Fiyatlandırma" variant="secondary">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-label-md text-on-surface mb-2">Eski Fiyat (TL)</label>
                        <input x-model.number="data.old_price" type="number" min="0"
                               class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                    </div>
                    <div>
                        <label class="block text-label-md text-on-surface mb-2">Yeni Fiyat (TL)</label>
                        <input x-model.number="data.new_price" type="number" min="0"
                               class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                    </div>
                    <div>
                        <label class="block text-label-md text-on-surface mb-2">Para Birimi</label>
                        <input x-model="data.currency" type="text" maxlength="5"
                               class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                    </div>
                </div>
            </x-admin.section-card>

            {{-- 3) Konaklama detayı --}}
            <x-admin.section-card icon="hotel" title="Konaklama Detayı" variant="tertiary">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-label-md text-on-surface mb-2">Gece</label>
                        <input x-model.number="data.nights" type="number" min="1" max="60"
                               class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                    </div>
                    <div>
                        <label class="block text-label-md text-on-surface mb-2">Yetişkin</label>
                        <input x-model.number="data.adults" type="number" min="1" max="20"
                               class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                    </div>
                    <div>
                        <label class="block text-label-md text-on-surface mb-2">Çocuk</label>
                        <input x-model.number="data.children" type="number" min="0" max="20"
                               class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                    </div>
                    <div>
                        <label class="block text-label-md text-on-surface mb-2">Yaş Limiti</label>
                        <input x-model.number="data.child_age_limit" type="number" min="0" max="18"
                               class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                    </div>
                </div>
            </x-admin.section-card>

            {{-- 4) Aciliyet --}}
            <x-admin.section-card icon="alarm" title="Aciliyet & Geri Sayım">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-label-md text-on-surface mb-2">Kalan Oda</label>
                            <input x-model.number="data.rooms_left" type="number" min="0"
                                   class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                        </div>
                        <div>
                            <label class="block text-label-md text-on-surface mb-2">Aciliyet Metni</label>
                            <input x-model="data.urgency_text" type="text" maxlength="120"
                                   placeholder="Son 12 oda"
                                   class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                        </div>
                    </div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input x-model="data.countdown_enabled" type="checkbox" name="countdown_enabled"
                               class="rounded border-outline-variant text-primary focus:ring-primary w-4 h-4">
                        <span class="text-label-md text-on-surface">Geri sayım aktif</span>
                    </label>
                    <div x-show="data.countdown_enabled" x-cloak>
                        <label class="block text-label-md text-on-surface mb-2">Bitiş Tarihi & Saati</label>
                        <input x-model="data.valid_until" type="datetime-local"
                               class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                    </div>
                </div>
            </x-admin.section-card>

            {{-- 5) İçerik --}}
            <x-admin.section-card icon="format_list_bulleted" title="İçerik & Dahil Olanlar">
                <div class="space-y-4">
                    <div>
                        <label class="block text-label-md text-on-surface mb-2">Açıklama</label>
                        <textarea x-model="data.description" rows="3"
                                  class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 text-body-md"></textarea>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-label-md text-on-surface">Dahil Olanlar</label>
                            <button type="button" @click="data.included_items.push('')"
                                    class="inline-flex items-center gap-1 text-primary text-label-md hover:underline">
                                <span class="material-symbols-outlined text-[18px]">add</span>
                                Madde ekle
                            </button>
                        </div>
                        <template x-for="(item, idx) in data.included_items" :key="idx">
                            <div class="flex items-center gap-2 mb-2">
                                <input x-model="data.included_items[idx]" type="text" maxlength="200"
                                       class="flex-1 px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 text-body-md min-h-[48px]">
                                <button type="button" @click="data.included_items.splice(idx, 1)"
                                        class="p-2 text-error hover:bg-error-container/20 rounded-full" title="Bu maddeyi sil">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </div>
                        </template>
                        <p x-show="data.included_items.length === 0" class="text-xs text-on-surface-variant italic">Henüz madde yok. "Madde ekle" ile başlayın.</p>
                    </div>
                </div>
            </x-admin.section-card>

            {{-- 6) Görseller --}}
            <x-admin.section-card icon="image" title="Görseller">
                @if ($isNew)
                    <p class="text-body-md text-on-surface-variant italic">
                        Görsel yükleme için önce kampanyayı oluşturun ("Oluştur" butonu).
                    </p>
                @else
                    <div class="space-y-4">
                        <div>
                            <label class="block text-label-md text-on-surface mb-2">Hero Görseli</label>
                            <div class="relative group aspect-video rounded-2xl overflow-hidden border-2 border-dashed border-outline-variant bg-surface-container flex flex-col items-center justify-center">
                                <template x-if="data.hero_image">
                                    <img :src="imageUrl(data.hero_image)" alt="" class="absolute inset-0 w-full h-full object-cover">
                                </template>
                                <template x-if="!data.hero_image">
                                    <div class="text-center text-on-surface-variant py-12">
                                        <span class="material-symbols-outlined text-4xl block mb-2">add_photo_alternate</span>
                                        <p class="text-body-md">Hero görseli yükle</p>
                                    </div>
                                </template>
                                <label class="absolute inset-0 cursor-pointer bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white">
                                    <span class="material-symbols-outlined text-3xl mb-2">photo_camera</span>
                                    <span class="text-label-md">Yeni görsel</span>
                                    <input type="file" accept="image/*" class="hidden" @change="uploadImage($event, 'hero')">
                                </label>
                            </div>
                        </div>
                    </div>
                @endif
            </x-admin.section-card>

            {{-- 7) Yayın --}}
            <x-admin.section-card icon="public" title="Yayın Ayarları" variant="secondary">
                <div class="space-y-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input x-model="data.is_active" type="checkbox" name="is_active"
                               class="rounded border-outline-variant text-primary focus:ring-primary w-4 h-4">
                        <span class="text-label-md text-on-surface">Kampanya aktif</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input x-model="data.show_on_homepage" type="checkbox" name="show_on_homepage"
                               class="rounded border-outline-variant text-primary focus:ring-primary w-4 h-4">
                        <span class="text-label-md text-on-surface">Ana sayfada göster</span>
                    </label>
                    <div>
                        <p class="text-label-md text-on-surface mb-2">Hangi landing sayfalarında görünsün?</p>
                        <div class="space-y-2">
                            @foreach ($landings as $slug => $name)
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" :checked="data.visible_landings.includes(@js($slug))"
                                           @change="toggleLanding(@js($slug))"
                                           class="rounded border-outline-variant text-primary focus:ring-primary w-4 h-4">
                                    <span class="text-body-md text-on-surface">{{ $name }} <code class="text-xs text-on-surface-variant">/{{ $slug }}</code></span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </x-admin.section-card>

            {{-- Bottom action bar --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.campaigns.index') }}" class="px-6 py-3 rounded-lg border border-outline text-on-surface-variant hover:bg-surface-container">
                    İptal
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-primary text-on-primary text-label-md px-6 py-3 rounded-lg hover:bg-primary/90 transition shadow-sm min-h-[48px]">
                    <span class="material-symbols-outlined text-[20px]">save</span>
                    {{ $isNew ? 'Oluştur' : 'Şimdi Kaydet' }}
                </button>
            </div>
        </form>

    </div>
@endsection
