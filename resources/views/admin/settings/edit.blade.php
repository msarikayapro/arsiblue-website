@extends('layouts.admin')

@section('title', 'Genel Ayarlar')
@section('page-title', 'Genel Ayarlar')

@section('content')
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="max-w-4xl mx-auto space-y-6">
        @csrf @method('PUT')

        <x-admin.section-card icon="badge" title="Site Kimliği">
            <div class="space-y-4">
                <div>
                    <label class="block text-label-md text-on-surface mb-2">Site Adı</label>
                    <input name="site_name" type="text" maxlength="120"
                           value="{{ old('site_name', setting('site_name')) }}"
                           class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                </div>
                <div>
                    <label class="block text-label-md text-on-surface mb-2">Tagline</label>
                    <input name="site_tagline" type="text" maxlength="255"
                           value="{{ old('site_tagline', setting('site_tagline')) }}"
                           class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                </div>
            </div>
        </x-admin.section-card>

        <x-admin.section-card icon="image" title="Logo & Favicon" variant="secondary">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-label-md text-on-surface mb-2">Logo</label>
                    @if (setting('site_logo'))
                        <img src="{{ asset('storage/uploads/'.setting('site_logo')) }}" alt="" class="mb-2 h-16">
                    @endif
                    <input type="file" name="site_logo" accept="image/*"
                           class="block w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary-container/10 file:text-primary file:cursor-pointer">
                </div>
                <div>
                    <label class="block text-label-md text-on-surface mb-2">Favicon</label>
                    @if (setting('site_favicon'))
                        <img src="{{ asset('storage/uploads/'.setting('site_favicon')) }}" alt="" class="mb-2 h-8">
                    @endif
                    <input type="file" name="site_favicon" accept="image/*,.ico"
                           class="block w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary-container/10 file:text-primary file:cursor-pointer">
                </div>
            </div>
        </x-admin.section-card>

        <x-admin.section-card icon="construction" title="Bakım Modu" variant="tertiary">
            <div class="space-y-4">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input name="maintenance_mode" type="checkbox" value="1"
                           {{ setting('maintenance_mode') ? 'checked' : '' }}
                           class="rounded border-outline-variant text-primary focus:ring-primary w-4 h-4">
                    <span class="text-label-md text-on-surface">Bakım modu aktif (frontend kapalı)</span>
                </label>
                <div>
                    <label class="block text-label-md text-on-surface mb-2">Bakım Mesajı</label>
                    <textarea name="maintenance_message" rows="3"
                              class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0">{{ old('maintenance_message', setting('maintenance_message')) }}</textarea>
                </div>
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
