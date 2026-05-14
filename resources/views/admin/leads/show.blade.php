@extends('layouts.admin')

@section('title', 'Lead: '.$lead->name)
@section('page-title', $lead->name)

@section('topbar-left')
    <a href="{{ route('admin.leads.index') }}" class="p-2 hover:bg-surface-container rounded-full text-on-surface-variant" title="Geri">
        <span class="material-symbols-outlined">arrow_back</span>
    </a>
    <div>
        <h2 class="font-headline-md text-on-surface">{{ $lead->name }}</h2>
        <p class="text-xs text-on-surface-variant">{{ $lead->createdAtFormatted() }}</p>
    </div>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Sol — Lead bilgileri --}}
        <div class="lg:col-span-2 space-y-6">

            <x-admin.section-card icon="person" title="Lead Bilgisi">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-body-md">
                    <div>
                        <dt class="text-label-md text-on-surface-variant">Ad Soyad</dt>
                        <dd class="text-on-surface">{{ $lead->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-label-md text-on-surface-variant">Telefon</dt>
                        <dd class="text-on-surface font-mono">{{ $lead->phone }}</dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-label-md text-on-surface-variant">Mesaj</dt>
                        <dd class="text-on-surface whitespace-pre-wrap">{{ $lead->message ?: '—' }}</dd>
                    </div>
                </dl>
            </x-admin.section-card>

            <x-admin.section-card icon="campaign" title="Kaynak & UTM" variant="secondary">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-body-md">
                    <div><dt class="text-label-md text-on-surface-variant">Landing</dt><dd class="text-on-surface">{{ $lead->landing_page ?: '—' }}</dd></div>
                    <div><dt class="text-label-md text-on-surface-variant">UTM Source</dt><dd class="text-on-surface">{{ $lead->utm_source ?: '—' }}</dd></div>
                    <div><dt class="text-label-md text-on-surface-variant">UTM Medium</dt><dd class="text-on-surface">{{ $lead->utm_medium ?: '—' }}</dd></div>
                    <div><dt class="text-label-md text-on-surface-variant">UTM Campaign</dt><dd class="text-on-surface">{{ $lead->utm_campaign ?: '—' }}</dd></div>
                    <div><dt class="text-label-md text-on-surface-variant">IP</dt><dd class="text-on-surface font-mono text-sm">{{ $lead->user_ip ?: '—' }}</dd></div>
                    <div class="md:col-span-2"><dt class="text-label-md text-on-surface-variant">User Agent</dt><dd class="text-xs text-on-surface-variant truncate">{{ $lead->user_agent ?: '—' }}</dd></div>
                </dl>
            </x-admin.section-card>

            <x-admin.section-card icon="sticky_note_2" title="Notlar" variant="tertiary">
                <form action="{{ route('admin.leads.notes', $lead) }}" method="POST" class="space-y-3">
                    @csrf @method('PUT')
                    <textarea name="notes" rows="5" placeholder="Konuşma notları, takip bilgileri..."
                              class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0">{{ $lead->notes }}</textarea>
                    <button type="submit" class="bg-primary text-on-primary text-label-md px-4 py-2 rounded-lg hover:bg-primary/90">
                        Notu Kaydet
                    </button>
                </form>
            </x-admin.section-card>

        </div>

        {{-- Sağ — Aksiyonlar --}}
        <div class="space-y-4">
            <div class="bg-surface-container-lowest rounded-xl p-6 ambient-shadow-lvl1 border border-outline-variant/30 space-y-3">
                <h3 class="font-headline-md text-on-surface">Hızlı Aksiyonlar</h3>

                <a href="{{ 'https://wa.me/'.preg_replace('/[^0-9]/', '', $lead->phone) }}" target="_blank"
                   class="block w-full bg-whatsapp text-white px-4 py-3 rounded-lg text-center font-semibold hover:opacity-90">
                    <span class="material-symbols-outlined align-middle">sms</span>
                    WhatsApp'tan Yaz
                </a>
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $lead->phone) }}"
                   class="block w-full bg-primary text-on-primary px-4 py-3 rounded-lg text-center font-semibold hover:bg-primary/90">
                    <span class="material-symbols-outlined align-middle">call</span>
                    Telefon Et
                </a>
            </div>

            <form action="{{ route('admin.leads.status', $lead) }}" method="POST"
                  class="bg-surface-container-lowest rounded-xl p-6 ambient-shadow-lvl1 border border-outline-variant/30 space-y-3">
                @csrf @method('PUT')
                <h3 class="font-headline-md text-on-surface">Durum Güncelle</h3>
                <select name="status" class="w-full px-4 py-3 rounded-lg bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0">
                    @foreach (\App\Models\Lead::STATUSES as $status)
                        <option value="{{ $status }}" {{ $lead->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="w-full bg-primary text-on-primary text-label-md px-4 py-2 rounded-lg hover:bg-primary/90">Kaydet</button>
            </form>

            <form action="{{ route('admin.leads.destroy', $lead) }}" method="POST"
                  onsubmit="return confirm('Bu lead\'i silmek istediğinden emin misin?');"
                  class="bg-surface-container-lowest rounded-xl p-6 ambient-shadow-lvl1 border border-outline-variant/30">
                @csrf @method('DELETE')
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 text-error border border-error/30 px-4 py-2 rounded-lg hover:bg-error-container/20">
                    <span class="material-symbols-outlined text-[18px]">delete</span> Lead'i Sil
                </button>
            </form>
        </div>

    </div>
@endsection
