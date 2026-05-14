@props(['title' => 'Bilgi Al', 'subtitle' => 'Adınızı, telefonunuzu bırakın — en kısa sürede dönüş yapalım.'])

<div id="bilgi-al"
     x-data="{
        form: { name: '', phone: '', message: '', website: '' },
        sending: false,
        error: null,
        success: false,
        async submit() {
            if (this.sending) return;
            this.sending = true;
            this.error = null;
            try {
                const res = await window.axios.post('{{ route('lead.store') }}', this.form);
                this.success = true;
                this.form = { name: '', phone: '', message: '', website: '' };
                if (typeof fbq !== 'undefined') fbq('track', 'Lead');
                if (typeof gtag !== 'undefined') gtag('event', 'generate_lead');
            } catch (e) {
                this.error = e.response?.data?.message ?? 'Form gönderilemedi. Lütfen tekrar deneyin.';
                if (e.response?.data?.errors) {
                    this.error = Object.values(e.response.data.errors).flat().join(' ');
                }
            } finally {
                this.sending = false;
            }
        }
     }"
     class="bg-surface-container-lowest rounded-3xl p-6 md:p-8 ambient-shadow-lvl1 border border-outline-variant/30">

    <div class="text-center mb-6">
        <h3 class="font-headline-md text-headline-md text-primary">{{ $title }}</h3>
        @if ($subtitle)
            <p class="text-body-md text-on-surface-variant mt-2">{{ $subtitle }}</p>
        @endif
    </div>

    {{-- Success state --}}
    <div x-show="success" x-cloak class="text-center py-8 space-y-3">
        <div class="w-16 h-16 mx-auto rounded-full bg-secondary-container/50 flex items-center justify-center">
            <span class="material-symbols-outlined text-secondary text-4xl">check_circle</span>
        </div>
        <h4 class="font-headline-md text-on-surface">Teşekkür ederiz!</h4>
        <p class="text-body-md text-on-surface-variant">En kısa sürede sizi arayacağız. Acil sorularınız için WhatsApp:</p>
        <a href="{{ whatsappLink() }}" target="_blank" data-track="whatsapp"
           class="inline-flex items-center gap-2 bg-whatsapp text-white px-6 py-3 rounded-full font-semibold hover:scale-105 transition">
            <span class="material-symbols-outlined">sms</span>
            WhatsApp'tan Yaz
        </a>
    </div>

    {{-- Form --}}
    <form @submit.prevent="submit()" x-show="!success" class="space-y-4">

        {{-- Honeypot — bot-only field --}}
        <input type="text" x-model="form.website" name="website" tabindex="-1" autocomplete="off"
               style="position:absolute;left:-9999px;opacity:0;pointer-events:none;height:0">

        <div>
            <label class="block text-label-md text-on-surface mb-2">Adınız *</label>
            <input x-model="form.name" type="text" required maxlength="100"
                   class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 text-body-md min-h-[48px]">
        </div>

        <div>
            <label class="block text-label-md text-on-surface mb-2">Telefon *</label>
            <input x-model="form.phone" type="tel" required maxlength="20"
                   placeholder="0 5XX XXX XX XX"
                   class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 text-body-md min-h-[48px]">
        </div>

        <div>
            <label class="block text-label-md text-on-surface mb-2">Mesajınız (opsiyonel)</label>
            <textarea x-model="form.message" rows="3" maxlength="500"
                      placeholder="Tarih, kişi sayısı, beklentileriniz..."
                      class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 text-body-md"></textarea>
        </div>

        <div x-show="error" x-cloak class="rounded-lg bg-error-container border border-error/30 text-on-error-container px-4 py-3 text-sm">
            <span x-text="error"></span>
        </div>

        <button type="submit" :disabled="sending"
                class="w-full bg-primary text-on-primary text-label-md font-semibold py-4 rounded-xl hover:opacity-90 transition shadow-sm min-h-[48px] flex items-center justify-center gap-2 disabled:opacity-50">
            <template x-if="sending">
                <span class="material-symbols-outlined animate-spin">refresh</span>
            </template>
            <template x-if="!sending">
                <span class="material-symbols-outlined">send</span>
            </template>
            <span x-text="sending ? 'Gönderiliyor...' : 'Bilgi İste'"></span>
        </button>

        <p class="text-xs text-center text-on-surface-variant">
            Form göndererek <a href="{{ route('legal.kvkk') }}" class="text-primary hover:underline">KVKK Aydınlatma Metni</a>'ni kabul etmiş olursunuz.
        </p>
    </form>
</div>
