@extends('layouts.admin')

@section('title', 'İletişim Bilgileri')
@section('page-title', 'İletişim Bilgileri')

@section('content')
    <form action="{{ route('admin.contact.update') }}" method="POST" class="max-w-4xl mx-auto space-y-6">
        @csrf @method('PUT')

        <x-admin.section-card icon="call" title="Telefon Numaraları">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-label-md text-on-surface mb-2">Sabit Hat</label>
                    <input name="phone_landline" type="text" maxlength="30"
                           value="{{ old('phone_landline', setting('phone_landline')) }}"
                           class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                </div>
                <div>
                    <label class="block text-label-md text-on-surface mb-2">WhatsApp</label>
                    <input name="phone_whatsapp" type="text" maxlength="30"
                           value="{{ old('phone_whatsapp', setting('phone_whatsapp')) }}"
                           class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                    <p class="text-xs text-on-surface-variant mt-1">
                        Önizleme: <a href="{{ whatsappLink() }}" target="_blank" class="text-primary hover:underline">{{ whatsappLink() }}</a>
                    </p>
                </div>
                <div>
                    <label class="block text-label-md text-on-surface mb-2">GSM</label>
                    <input name="phone_gsm" type="text" maxlength="30"
                           value="{{ old('phone_gsm', setting('phone_gsm')) }}"
                           class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                </div>
            </div>
        </x-admin.section-card>

        <x-admin.section-card icon="location_on" title="Adres & Konum" variant="secondary">
            <div class="space-y-4">
                <div>
                    <label class="block text-label-md text-on-surface mb-2">Adres</label>
                    <textarea name="address" rows="3"
                              class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0">{{ old('address', setting('address')) }}</textarea>
                </div>
                <div>
                    <label class="block text-label-md text-on-surface mb-2">Google Maps Embed URL</label>
                    <input name="google_maps_embed" type="text"
                           value="{{ old('google_maps_embed', setting('google_maps_embed')) }}"
                           placeholder="https://www.google.com/maps/embed?pb=..."
                           class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 font-mono text-sm min-h-[48px]">
                    <p class="text-xs text-on-surface-variant mt-1">Google Maps → "Paylaş" → "Harita yerleştir" → src kısmındaki URL.</p>
                </div>
                <div>
                    <label class="block text-label-md text-on-surface mb-2">İletişim Saatleri</label>
                    <input name="working_hours" type="text" maxlength="200"
                           value="{{ old('working_hours', setting('working_hours')) }}"
                           class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                </div>
                <div>
                    <label class="block text-label-md text-on-surface mb-2">E-posta</label>
                    <input name="email" type="email" maxlength="120"
                           value="{{ old('email', setting('email')) }}"
                           class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                </div>
            </div>
        </x-admin.section-card>

        <x-admin.section-card icon="share" title="Sosyal Medya" variant="tertiary">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach (['instagram_url' => 'Instagram', 'facebook_url' => 'Facebook', 'tiktok_url' => 'TikTok', 'tripadvisor_url' => 'Tripadvisor', 'google_business_url' => 'Google Business'] as $key => $label)
                    <div>
                        <label class="block text-label-md text-on-surface mb-2">{{ $label }} URL</label>
                        <input name="{{ $key }}" type="url"
                               value="{{ old($key, setting($key)) }}"
                               class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                    </div>
                @endforeach
            </div>
        </x-admin.section-card>

        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center gap-2 bg-primary text-on-primary text-label-md px-6 py-3 rounded-lg hover:bg-primary/90 shadow-sm min-h-[48px]">
                <span class="material-symbols-outlined text-[20px]">save</span>
                Kaydet
            </button>
        </div>
    </form>
@endsection
