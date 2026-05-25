@extends('layouts.admin')

@section('title', 'Tracking & Pixel')
@section('page-title', 'Tracking & Pixel')

@section('content')
    <div class="max-w-container-max-width mx-auto space-y-6"
         x-data="{
            tab: window.location.hash?.replace('#', '') || 'meta',
            health: {},
            overallOk: true,
            async loadHealth() {
                try {
                    const r = await window.axios.get('{{ route('admin.tracking.health') }}');
                    this.health = r.data;
                    this.overallOk = Object.values(r.data).every(v => v.configured && v.active);
                } catch (e) {
                    this.overallOk = false;
                }
            },
         }"
         x-init="loadHealth()">

        {{-- ============ Health Banner ============ --}}
        <div class="rounded-xl p-4 ambient-shadow-lvl1 border"
             :class="overallOk
                ? 'bg-secondary-container/30 border-secondary/30 text-on-secondary-container'
                : 'bg-tertiary-container/20 border-tertiary/30 text-tertiary'">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined">
                    <template x-if="overallOk">monitoring</template>
                    <template x-if="!overallOk">warning</template>
                </span>
                <div class="flex-1">
                    <p class="font-semibold text-body-md" x-show="overallOk">Tracking sağlıklı çalışıyor</p>
                    <p class="font-semibold text-body-md" x-show="!overallOk">Bazı entegrasyonlar henüz yapılandırılmadı</p>
                    <p class="text-xs mt-1">
                        <template x-for="[k, info] in Object.entries(health)">
                            <span class="inline-flex items-center gap-1 mr-3">
                                <span class="w-2 h-2 rounded-full"
                                      :class="info.configured && info.active ? 'bg-secondary' : 'bg-outline-variant'"></span>
                                <span x-text="k"></span>
                            </span>
                        </template>
                    </p>
                </div>
                <button @click="loadHealth()" class="text-label-md text-primary hover:underline" type="button">Yenile</button>
            </div>
        </div>

        {{-- ============ Tabs ============ --}}
        <div class="bg-surface-container-lowest rounded-xl ambient-shadow-lvl1 border border-outline-variant/30 overflow-hidden">
            <div class="flex border-b border-outline-variant">
                <button @click="tab = 'meta'" type="button"
                        class="flex-1 px-6 py-4 text-label-md font-semibold transition-colors flex items-center justify-center gap-2"
                        :class="tab === 'meta' ? 'bg-surface-container-lowest text-primary border-b-2 border-primary' : 'text-on-surface-variant hover:bg-surface-container-low'">
                    <span class="material-symbols-outlined text-[18px]">facebook</span>
                    Meta (Facebook & Instagram)
                </button>
                <button @click="tab = 'google'" type="button"
                        class="flex-1 px-6 py-4 text-label-md font-semibold transition-colors flex items-center justify-center gap-2"
                        :class="tab === 'google' ? 'bg-surface-container-lowest text-primary border-b-2 border-primary' : 'text-on-surface-variant hover:bg-surface-container-low'">
                    <span class="material-symbols-outlined text-[18px]">analytics</span>
                    Google
                </button>
                <button @click="tab = 'tiktok'" type="button"
                        class="flex-1 px-6 py-4 text-label-md font-semibold transition-colors flex items-center justify-center gap-2"
                        :class="tab === 'tiktok' ? 'bg-surface-container-lowest text-primary border-b-2 border-primary' : 'text-on-surface-variant hover:bg-surface-container-low'">
                    <span class="material-symbols-outlined text-[18px]">music_note</span>
                    TikTok
                </button>
                <button @click="tab = 'custom'" type="button"
                        class="flex-1 px-6 py-4 text-label-md font-semibold transition-colors flex items-center justify-center gap-2"
                        :class="tab === 'custom' ? 'bg-surface-container-lowest text-primary border-b-2 border-primary' : 'text-on-surface-variant hover:bg-surface-container-low'">
                    <span class="material-symbols-outlined text-[18px]">code</span>
                    Özel Kod
                </button>
            </div>

            {{-- ============ TAB: META ============ --}}
            <div x-show="tab === 'meta'" class="p-6 md:p-8 space-y-6" id="meta">
                <form action="{{ route('admin.tracking.meta') }}" method="POST" class="space-y-6">
                    @csrf @method('PUT')

                    <x-admin.section-card icon="tag" title="Meta Pixel">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-label-md text-on-surface mb-2">Pixel ID</label>
                                <input name="meta_pixel_id" type="text" inputmode="numeric" pattern="\d{15,16}"
                                       value="{{ old('meta_pixel_id', setting('meta_pixel_id')) }}"
                                       placeholder="15-16 haneli sayı"
                                       class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 font-mono text-body-md min-h-[48px]">
                                @error('meta_pixel_id')<p class="text-xs text-error mt-1">{{ $message }}</p>@enderror
                                <p class="text-xs text-on-surface-variant mt-1">
                                    Events Manager → Veri Kaynakları'ndan kopyalanır.
                                    <a href="https://chromewebstore.google.com/detail/meta-pixel-helper/fdgfkebogiimcoedlicjlajpkdmockpc" target="_blank"
                                       class="text-primary hover:underline">Pixel Helper Chrome eklentisi</a>
                                </p>
                            </div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input name="meta_pixel_active" type="checkbox" value="1"
                                       {{ setting('meta_pixel_active') ? 'checked' : '' }}
                                       class="rounded border-outline-variant text-primary focus:ring-primary w-4 h-4">
                                <span class="text-label-md text-on-surface">Pixel aktif (frontend'e inject edilsin)</span>
                            </label>
                        </div>
                    </x-admin.section-card>

                    <x-admin.section-card icon="api" title="Conversions API (CAPI)" variant="secondary">
                        <div class="space-y-4">
                            <div x-data="{ show: false }">
                                <label class="block text-label-md text-on-surface mb-2">
                                    Access Token
                                    <span class="text-xs text-on-surface-variant ml-2">(şifreli kayıt)</span>
                                </label>
                                <div class="relative">
                                    <input name="meta_capi_token" :type="show ? 'text' : 'password'"
                                           value=""
                                           placeholder="{{ setting('meta_capi_token') ? '•••• mevcut token korunuyor — değiştirmek için yeni değer yazın' : 'Meta Events Manager → Settings → Generate Access Token' }}"
                                           class="w-full px-4 py-3 pr-12 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 font-mono text-sm min-h-[48px]">
                                    <button @click="show = !show" type="button"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary p-1">
                                        <span class="material-symbols-outlined text-[20px]" x-text="show ? 'visibility_off' : 'visibility'"></span>
                                    </button>
                                </div>
                                <p class="text-xs text-on-surface-variant mt-1">Boş bırakırsanız mevcut token korunur.</p>
                            </div>

                            <div>
                                <label class="block text-label-md text-on-surface mb-2">Test Event Code</label>
                                <input name="meta_capi_test_code" type="text" maxlength="50"
                                       value="{{ old('meta_capi_test_code', setting('meta_capi_test_code')) }}"
                                       placeholder="TEST12345 — Events Manager'da debug için"
                                       class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 font-mono text-sm min-h-[48px]">
                            </div>

                            <label class="flex items-center gap-3 cursor-pointer">
                                <input name="meta_capi_active" type="checkbox" value="1"
                                       {{ setting('meta_capi_active') ? 'checked' : '' }}
                                       class="rounded border-outline-variant text-primary focus:ring-primary w-4 h-4">
                                <span class="text-label-md text-on-surface">CAPI aktif (server-side gönderim)</span>
                            </label>

                            <div class="pt-2"
                                 x-data="{ testing: false, result: null }">
                                <button type="button"
                                        @click="testing = true; result = null;
                                                window.axios.post('{{ route('admin.tracking.test-capi') }}')
                                                  .then(r => result = r.data)
                                                  .catch(e => result = { ok: false, message: e.response?.data?.message ?? 'Hata' })
                                                  .finally(() => testing = false)"
                                        class="inline-flex items-center gap-2 bg-secondary text-on-secondary text-label-md px-4 py-2 rounded-lg hover:bg-secondary/90 transition">
                                    <span class="material-symbols-outlined text-[18px]" :class="{ 'animate-spin': testing }">science</span>
                                    Test Event Gönder
                                </button>
                                <div x-show="result" x-cloak class="mt-3 p-3 rounded-lg text-body-md"
                                     :class="result?.ok ? 'bg-secondary-container/30 text-on-secondary-container' : 'bg-error-container text-on-error-container'">
                                    <p x-text="result?.message"></p>
                                </div>
                            </div>
                        </div>
                    </x-admin.section-card>

                    <x-admin.section-card icon="alt_route" title="Event Mapping" variant="tertiary">
                        <p class="text-sm text-on-surface-variant mb-4">
                            Site aksiyonlarınız Meta'ya hangi event adıyla gönderilecek?
                        </p>
                        <div class="overflow-x-auto">
                            <table class="w-full text-body-md">
                                <thead class="text-label-md text-on-surface-variant border-b border-outline-variant">
                                    <tr>
                                        <th class="text-left py-2">Site Aksiyonu</th>
                                        <th class="text-left py-2 px-4">Meta Event</th>
                                        <th class="text-center py-2">Aktif</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($eventMapping as $action => $cfg)
                                        <tr class="border-b border-outline-variant/30">
                                            <td class="py-3"><code class="text-sm">{{ $action }}</code></td>
                                            <td class="py-3 px-4">
                                                <select name="event_mapping[{{ $action }}][meta]"
                                                        class="px-3 py-2 rounded-lg bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 text-sm">
                                                    @foreach ($metaEvents as $e)
                                                        <option value="{{ $e }}" {{ ($cfg['meta'] ?? 'CustomEvent') === $e ? 'selected' : '' }}>{{ $e }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="py-3 text-center">
                                                <input name="event_mapping[{{ $action }}][active]" type="checkbox" value="1"
                                                       {{ ($cfg['active'] ?? false) ? 'checked' : '' }}
                                                       class="rounded border-outline-variant text-primary focus:ring-primary w-4 h-4">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </x-admin.section-card>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 bg-primary text-on-primary text-label-md px-6 py-3 rounded-lg hover:bg-primary/90 shadow-sm min-h-[48px]">
                            <span class="material-symbols-outlined text-[20px]">save</span>
                            Meta Ayarlarını Kaydet
                        </button>
                    </div>
                </form>
            </div>

            {{-- ============ TAB: GOOGLE ============ --}}
            <div x-show="tab === 'google'" x-cloak class="p-6 md:p-8 space-y-6" id="google">
                <form action="{{ route('admin.tracking.google') }}" method="POST" class="space-y-6">
                    @csrf @method('PUT')

                    <x-admin.section-card icon="dataset" title="Google Tag Manager">
                        <label class="block text-label-md text-on-surface mb-2">GTM Container ID</label>
                        <input name="gtm_container_id" type="text" placeholder="GTM-XXXXXXX"
                               value="{{ old('gtm_container_id', setting('gtm_container_id')) }}"
                               class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 font-mono text-body-md min-h-[48px]">
                        @error('gtm_container_id')<p class="text-xs text-error mt-1">{{ $message }}</p>@enderror
                        <p class="text-xs text-on-surface-variant mt-1">GTM kuruluysa GA4 burada yönetilir, ayrıca G- ID girmeniz gerekmez.</p>
                    </x-admin.section-card>

                    <x-admin.section-card icon="analytics" title="Google Analytics 4" variant="secondary">
                        <label class="block text-label-md text-on-surface mb-2">GA4 Measurement ID</label>
                        <input name="ga4_measurement_id" type="text" placeholder="G-XXXXXXXXXX"
                               value="{{ old('ga4_measurement_id', setting('ga4_measurement_id')) }}"
                               class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 font-mono text-body-md min-h-[48px]">
                        @error('ga4_measurement_id')<p class="text-xs text-error mt-1">{{ $message }}</p>@enderror
                    </x-admin.section-card>

                    <x-admin.section-card icon="ads_click" title="Google Ads Conversion" variant="tertiary">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-label-md text-on-surface mb-2">Conversion ID</label>
                                <input name="google_ads_conversion_id" type="text" placeholder="AW-XXXXXXXXX"
                                       value="{{ old('google_ads_conversion_id', setting('google_ads_conversion_id')) }}"
                                       class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 font-mono text-body-md min-h-[48px]">
                                @error('google_ads_conversion_id')<p class="text-xs text-error mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-label-md text-on-surface mb-2">Conversion Label</label>
                                <input name="google_ads_conversion_label" type="text" maxlength="120"
                                       value="{{ old('google_ads_conversion_label', setting('google_ads_conversion_label')) }}"
                                       class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 font-mono text-sm min-h-[48px]">
                            </div>
                        </div>
                    </x-admin.section-card>

                    <x-admin.section-card icon="verified" title="Search Console Verification">
                        <label class="block text-label-md text-on-surface mb-2">Meta Tag content değeri</label>
                        <input name="google_search_console_verification" type="text"
                               value="{{ old('google_search_console_verification', setting('google_search_console_verification')) }}"
                               placeholder="Sadece content kısmı, tam tag'i değil"
                               class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 font-mono text-sm min-h-[48px]">
                    </x-admin.section-card>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 bg-primary text-on-primary text-label-md px-6 py-3 rounded-lg hover:bg-primary/90 shadow-sm min-h-[48px]">
                            <span class="material-symbols-outlined text-[20px]">save</span>
                            Google Ayarlarını Kaydet
                        </button>
                    </div>
                </form>
            </div>

            {{-- ============ TAB: TIKTOK ============ --}}
            <div x-show="tab === 'tiktok'" x-cloak class="p-6 md:p-8 space-y-6" id="tiktok">
                <form action="{{ route('admin.tracking.tiktok') }}" method="POST" class="space-y-6">
                    @csrf @method('PUT')

                    <x-admin.section-card icon="music_note" title="TikTok Pixel & Events API">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-label-md text-on-surface mb-2">Pixel ID</label>
                                <input name="tiktok_pixel_id" type="text" maxlength="60"
                                       value="{{ old('tiktok_pixel_id', setting('tiktok_pixel_id')) }}"
                                       placeholder="C0XXXXXXXXXXXXXXXXXX"
                                       class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 font-mono text-body-md min-h-[48px]">
                            </div>

                            <div x-data="{ show: false }">
                                <label class="block text-label-md text-on-surface mb-2">
                                    Events API Token <span class="text-xs text-on-surface-variant ml-2">(şifreli kayıt)</span>
                                </label>
                                <div class="relative">
                                    <input name="tiktok_capi_token" :type="show ? 'text' : 'password'"
                                           value=""
                                           placeholder="{{ setting('tiktok_capi_token') ? '•••• mevcut token korunuyor' : 'TikTok Events Manager\'dan kopyala' }}"
                                           class="w-full px-4 py-3 pr-12 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 font-mono text-sm min-h-[48px]">
                                    <button @click="show = !show" type="button"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary p-1">
                                        <span class="material-symbols-outlined text-[20px]" x-text="show ? 'visibility_off' : 'visibility'"></span>
                                    </button>
                                </div>
                            </div>

                            <label class="flex items-center gap-3 cursor-pointer">
                                <input name="tiktok_active" type="checkbox" value="1"
                                       {{ setting('tiktok_active') ? 'checked' : '' }}
                                       class="rounded border-outline-variant text-primary focus:ring-primary w-4 h-4">
                                <span class="text-label-md text-on-surface">TikTok aktif</span>
                            </label>
                        </div>
                    </x-admin.section-card>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 bg-primary text-on-primary text-label-md px-6 py-3 rounded-lg hover:bg-primary/90 shadow-sm min-h-[48px]">
                            <span class="material-symbols-outlined text-[20px]">save</span>
                            TikTok Ayarlarını Kaydet
                        </button>
                    </div>
                </form>
            </div>

            {{-- ============ TAB: ÖZEL KOD ============ --}}
            <div x-show="tab === 'custom'" x-cloak class="p-6 md:p-8 space-y-6" id="custom">
                <form action="{{ route('admin.tracking.custom-code') }}" method="POST" class="space-y-6">
                    @csrf @method('PUT')

                    <div class="rounded-xl p-4 bg-tertiary-container/20 border border-tertiary/30 text-tertiary text-body-md flex items-start gap-3">
                        <span class="material-symbols-outlined text-[20px] mt-0.5">warning</span>
                        <div>
                            <p class="font-semibold mb-1">Dikkat — bu alanlara yapıştırdığınız kod aynen siteye eklenir.</p>
                            <p class="text-xs">Yalnızca güvendiğiniz kaynaklardan gelen kodları (Google Tag Manager, Hotjar, chat widget vb.) yapıştırın. Hatalı script siteyi bozabilir.</p>
                        </div>
                    </div>

                    <x-admin.section-card icon="data_object" title="<head> içine eklenecek kod">
                        <p class="text-xs text-on-surface-variant mb-3">
                            Sayfa <code>&lt;/head&gt;</code> kapanışından hemen önce yer alır. Genelde analitik script'leri, doğrulama meta tag'leri, preconnect link'leri buraya gider.
                        </p>
                        <textarea name="seo_custom_head_scripts" rows="10" spellcheck="false"
                                  placeholder="Örn: Google Tag Manager script'i, Hotjar, custom meta tag..."
                                  class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 font-mono text-sm leading-relaxed">{{ old('seo_custom_head_scripts', setting('seo_custom_head_scripts')) }}</textarea>
                        @error('seo_custom_head_scripts')<p class="text-xs text-error mt-1">{{ $message }}</p>@enderror
                    </x-admin.section-card>

                    <x-admin.section-card icon="code_blocks" title="<body> içine eklenecek kod" variant="secondary">
                        <p class="text-xs text-on-surface-variant mb-3">
                            Sayfa <code>&lt;/body&gt;</code> kapanışından hemen önce yer alır. Chat widget'ları, GTM noscript fallback'i veya footer'a yakın yüklenmesi gereken script'ler için.
                        </p>
                        <textarea name="seo_custom_body_scripts" rows="10" spellcheck="false"
                                  placeholder="Örn: chat widget, noscript GTM iframe, footer'da yüklenecek script..."
                                  class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 font-mono text-sm leading-relaxed">{{ old('seo_custom_body_scripts', setting('seo_custom_body_scripts')) }}</textarea>
                        @error('seo_custom_body_scripts')<p class="text-xs text-error mt-1">{{ $message }}</p>@enderror
                    </x-admin.section-card>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 bg-primary text-on-primary text-label-md px-6 py-3 rounded-lg hover:bg-primary/90 shadow-sm min-h-[48px]">
                            <span class="material-symbols-outlined text-[20px]">save</span>
                            Özel Kodları Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection
