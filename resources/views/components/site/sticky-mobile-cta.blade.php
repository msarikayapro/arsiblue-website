{{-- Mobile bottom CTA bar --}}
<nav class="fixed bottom-0 w-full z-40 md:hidden bg-surface-container-lowest border-t border-outline-variant shadow-[0_-4px_12px_rgba(0,0,0,0.04)]">
    <div class="grid grid-cols-3 h-16 max-w-container-max-width mx-auto">
        <a href="{{ phoneLink(setting('phone_landline', setting('phone_gsm'))) }}" data-track="phone"
           class="flex flex-col items-center justify-center text-on-surface-variant active:scale-95 transition">
            <span class="material-symbols-outlined">call</span>
            <span class="text-xs font-semibold">Ara</span>
        </a>
        <a href="{{ whatsappLink('Arsi Blue Beach hakkında bilgi almak istiyorum.') }}" target="_blank" data-track="whatsapp"
           class="flex flex-col items-center justify-center bg-whatsapp text-white active:scale-95 transition">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">sms</span>
            <span class="text-xs font-semibold">WhatsApp</span>
        </a>
        <a href="#bilgi-al"
           class="flex flex-col items-center justify-center text-on-surface-variant active:scale-95 transition">
            <span class="material-symbols-outlined">edit_note</span>
            <span class="text-xs font-semibold">Bilgi Al</span>
        </a>
    </div>
</nav>

{{-- Desktop floating WhatsApp button --}}
<a href="{{ whatsappLink('Arsi Blue Beach hakkında bilgi almak istiyorum.') }}" target="_blank" data-track="whatsapp"
   class="fixed bottom-8 right-8 z-40 hidden md:flex items-center gap-2 bg-whatsapp text-white px-5 py-4 rounded-full shadow-2xl hover:scale-105 transition-transform group">
    <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">sms</span>
    <span class="max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300 font-bold whitespace-nowrap">WhatsApp'tan yazın</span>
</a>
