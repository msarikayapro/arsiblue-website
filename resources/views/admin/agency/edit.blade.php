@extends('layouts.admin')

@section('title', 'Acenta Bilgileri')
@section('page-title', 'Acenta Bilgileri')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">

        <div class="bg-tertiary-container/20 border border-tertiary/30 rounded-xl p-4 text-on-tertiary-fixed">
            <div class="flex gap-3">
                <span class="material-symbols-outlined text-tertiary">info</span>
                <div class="text-body-md">
                    <p class="font-semibold mb-1">Bu bilgiler hukuki yükümlülükler için zorunludur.</p>
                    <p class="text-sm">KVKK aydınlatma metni, hakkımızda sayfası ve footer'da otomatik olarak kullanılır.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.agency.update') }}" method="POST" class="space-y-6">
            @csrf @method('PUT')

            <x-admin.section-card icon="business" title="Acenta Kimliği">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ([
                        'agency_name' => 'Ticari Adı',
                        'agency_tax_number' => 'Vergi Numarası',
                        'agency_tax_office' => 'Vergi Dairesi',
                        'agency_mersis' => 'MERSİS Numarası',
                        'agency_trade_registry' => 'Ticaret Sicil No',
                        'agency_tursab_number' => 'TÜRSAB Belge No',
                    ] as $key => $label)
                        <div>
                            <label class="block text-label-md text-on-surface mb-2">{{ $label }}</label>
                            <input name="{{ $key }}" type="text" maxlength="200"
                                   value="{{ old($key, setting($key)) }}"
                                   class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                        </div>
                    @endforeach
                </div>
            </x-admin.section-card>

            <x-admin.section-card icon="alternate_email" title="İletişim & Sorumlu" variant="secondary">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ([
                        'agency_kep' => 'KEP Adresi',
                        'agency_email' => 'E-posta',
                        'agency_authorized_person' => 'Yetkili Kişi',
                        'agency_kvkk_responsible' => 'KVKK Veri Sorumlusu',
                    ] as $key => $label)
                        <div>
                            <label class="block text-label-md text-on-surface mb-2">{{ $label }}</label>
                            <input name="{{ $key }}" type="text" maxlength="200"
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

        <x-admin.section-card icon="picture_as_pdf" title="TÜRSAB Belgesi (PDF)" variant="tertiary">
            <form action="{{ route('admin.agency.tursab-upload') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                @if (setting('agency_tursab_pdf'))
                    <p class="text-body-md text-on-surface-variant">
                        Mevcut belge:
                        <a href="{{ asset('storage/agency/'.setting('agency_tursab_pdf')) }}" target="_blank" class="text-primary hover:underline">
                            {{ setting('agency_tursab_pdf') }}
                        </a>
                    </p>
                @endif
                <input type="file" name="pdf" accept="application/pdf" required
                       class="block w-full text-sm text-on-surface-variant file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary-container/10 file:text-primary file:cursor-pointer">
                <button type="submit" class="inline-flex items-center gap-2 bg-secondary text-on-secondary text-label-md px-4 py-2 rounded-lg hover:bg-secondary/90">
                    <span class="material-symbols-outlined text-[18px]">upload</span>
                    Yükle (max 5MB)
                </button>
            </form>
        </x-admin.section-card>

    </div>
@endsection
