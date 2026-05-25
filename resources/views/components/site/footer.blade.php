<footer class="bg-surface-container-lowest border-t border-outline-variant pt-section-gap-mobile pb-24 md:pb-section-gap-mobile mt-section-gap-mobile">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-gutter px-margin-mobile md:px-gutter max-w-container-max-width mx-auto">

        {{-- Brand --}}
        <div class="space-y-4">
            <p class="text-headline-md font-bold text-primary">{{ setting('site_name', 'Arsi Blue Beach') }}</p>
            <p class="text-on-surface-variant text-body-md">
                {{ setting('site_tagline', 'Akdeniz\'in en güzel sahilinde, ailenizle huzurlu bir tatil.') }}
            </p>
            <div class="flex gap-3">
                @foreach ([
                    ['key' => 'instagram_url', 'icon' => 'photo_camera', 'label' => 'Instagram'],
                    ['key' => 'facebook_url', 'icon' => 'thumb_up', 'label' => 'Facebook'],
                    ['key' => 'tripadvisor_url', 'icon' => 'travel_explore', 'label' => 'Tripadvisor'],
                    ['key' => 'google_business_url', 'icon' => 'place', 'label' => 'Google'],
                ] as $social)
                    @if (setting($social['key']))
                        <a href="{{ setting($social['key']) }}" target="_blank" rel="noopener" title="{{ $social['label'] }}"
                           class="text-primary hover:opacity-70 transition">
                            <span class="material-symbols-outlined">{{ $social['icon'] }}</span>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Quick links --}}
        <div class="space-y-4">
            <h4 class="font-bold text-primary">Hızlı Linkler</h4>
            <ul class="space-y-2 text-label-md">
                <li><a href="{{ route('legal.about') }}" class="text-on-surface-variant hover:text-primary transition">Hakkımızda</a></li>
                @if (\Illuminate\Support\Facades\Route::has('rooms'))
                    <li><a href="{{ route('rooms') }}" class="text-on-surface-variant hover:text-primary transition">Odalarımız</a></li>
                @endif
                <li><a href="{{ route('landing.bayrama') }}" class="text-on-surface-variant hover:text-primary transition">Bayrama Özel</a></li>
                <li><a href="{{ route('legal.kvkk') }}" class="text-on-surface-variant hover:text-primary transition">KVKK</a></li>
                <li><a href="{{ route('legal.cerez') }}" class="text-on-surface-variant hover:text-primary transition">Çerez Politikası</a></li>
            </ul>
        </div>

        {{-- Contact --}}
        <div class="space-y-4">
            <h4 class="font-bold text-primary">İletişim</h4>
            <ul class="space-y-2 text-label-md text-on-surface-variant">
                @if (setting('address'))
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[18px] text-primary shrink-0 mt-0.5">location_on</span>
                        {{ setting('address') }}
                    </li>
                @endif
                @if (setting('phone_landline'))
                    <li class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-primary shrink-0">call</span>
                        <a href="{{ phoneLink(setting('phone_landline')) }}" data-track="phone" class="hover:text-primary">{{ setting('phone_landline') }}</a>
                    </li>
                @endif
                @if (setting('phone_whatsapp'))
                    <li class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-whatsapp shrink-0">sms</span>
                        <a href="{{ whatsappLink() }}" target="_blank" data-track="whatsapp" class="hover:text-primary">{{ setting('phone_whatsapp') }}</a>
                    </li>
                @endif
                @if (setting('email'))
                    <li class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-primary shrink-0">mail</span>
                        <a href="mailto:{{ setting('email') }}" data-track="email" class="hover:text-primary">{{ setting('email') }}</a>
                    </li>
                @endif
            </ul>
        </div>

        {{-- Acenta info --}}
        <div class="space-y-4">
            <h4 class="font-bold text-primary">Yetkili Acenta</h4>
            <div class="text-label-md text-on-surface-variant space-y-1">
                @if (setting('agency_name'))
                    <p class="font-semibold text-on-surface">{{ setting('agency_name') }}</p>
                @endif
                @if (setting('agency_tursab_number'))
                    <p>TÜRSAB No: {{ setting('agency_tursab_number') }}</p>
                @endif
                @if (setting('agency_tursab_pdf'))
                    <p><a href="{{ asset('storage/agency/'.setting('agency_tursab_pdf')) }}" target="_blank" class="inline-flex items-center gap-1 text-primary hover:underline">
                        <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
                        Belgemizi İndir
                    </a></p>
                @endif
            </div>
            <p class="text-xs text-on-surface-variant italic">
                Arsi Blue Beach Hotel'in yetkili acentası olarak hizmet veriyoruz. Online rezervasyon kabul etmiyoruz — telefon ve WhatsApp üzerinden ön ödemeli rezervasyon alıyoruz.
            </p>
        </div>
    </div>

    <div class="max-w-container-max-width mx-auto px-margin-mobile mt-12 pt-8 border-t border-outline-variant/30 text-center text-on-surface-variant text-sm">
        © {{ date('Y') }} {{ setting('site_name', 'Arsi Blue Beach') }}. Tüm hakları saklıdır.
        @if (setting('agency_name'))
            · {{ setting('agency_name') }}
        @endif
        · <a href="{{ route('admin.login') }}" class="hover:text-primary transition">Yönetim Paneli</a>
    </div>
</footer>
