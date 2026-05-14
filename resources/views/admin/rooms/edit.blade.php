@extends('layouts.admin')

@section('title', $isNew ? 'Yeni Oda' : $room->name)
@section('page-title', $isNew ? 'Yeni Oda' : $room->name)

@section('topbar-left')
    <a href="{{ route('admin.rooms.index') }}" class="p-2 hover:bg-surface-container rounded-full text-on-surface-variant">
        <span class="material-symbols-outlined">arrow_back</span>
    </a>
    <h2 class="font-headline-md text-on-surface">{{ $isNew ? 'Yeni Oda' : $room->name }}</h2>
@endsection

@section('content')
    <form action="{{ $isNew ? route('admin.rooms.store') : route('admin.rooms.update', $room) }}" method="POST" class="max-w-3xl mx-auto space-y-6">
        @csrf
        @if (! $isNew) @method('PUT') @endif

        <x-admin.section-card icon="info" title="Temel Bilgiler">
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-label-md text-on-surface mb-2">Oda Adı *</label>
                        <input name="name" type="text" required maxlength="120"
                               value="{{ old('name', $room->name) }}"
                               class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                    </div>
                    <div>
                        <label class="block text-label-md text-on-surface mb-2">Slug (URL) *</label>
                        <input name="slug" type="text" required maxlength="80"
                               value="{{ old('slug', $room->slug) }}"
                               class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 font-mono text-sm min-h-[48px]">
                    </div>
                </div>
                <div>
                    <label class="block text-label-md text-on-surface mb-2">Kısa Açıklama</label>
                    <input name="short_description" type="text" maxlength="255"
                           value="{{ old('short_description', $room->short_description) }}"
                           class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                </div>
                <div>
                    <label class="block text-label-md text-on-surface mb-2">Detaylı Açıklama (HTML)</label>
                    <textarea name="long_description" rows="6"
                              class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 font-mono text-sm">{{ old('long_description', $room->long_description) }}</textarea>
                </div>
                <div>
                    <label class="block text-label-md text-on-surface mb-2">Müsaitlik Notu</label>
                    <input name="availability_note" type="text" maxlength="200"
                           value="{{ old('availability_note', $room->availability_note ?? 'Müsaitliğe göre') }}"
                           class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                </div>
            </div>
        </x-admin.section-card>

        <x-admin.section-card icon="checklist" title="Özellikler" variant="secondary">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                @foreach ($features as $key => $label)
                    <label class="flex items-center gap-2 cursor-pointer text-body-md">
                        <input type="checkbox" name="features[]" value="{{ $key }}"
                               {{ in_array($key, (array) old('features', $room->features ?? []), true) ? 'checked' : '' }}
                               class="rounded border-outline-variant text-primary focus:ring-primary w-4 h-4">
                        {{ $label }}
                    </label>
                @endforeach
            </div>
        </x-admin.section-card>

        <x-admin.section-card icon="public" title="Yayın" variant="tertiary">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-label-md text-on-surface mb-2">Sıra</label>
                    <input name="sort_order" type="number" min="0"
                           value="{{ old('sort_order', $room->sort_order) }}"
                           class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                </div>
                <div class="flex items-end">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input name="is_active" type="checkbox" value="1"
                               {{ old('is_active', $room->is_active ?? true) ? 'checked' : '' }}
                               class="rounded border-outline-variant text-primary focus:ring-primary w-4 h-4">
                        <span class="text-label-md text-on-surface">Aktif</span>
                    </label>
                </div>
            </div>
        </x-admin.section-card>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.rooms.index') }}" class="px-6 py-3 rounded-lg border border-outline text-on-surface-variant hover:bg-surface-container">İptal</a>
            <button type="submit" class="inline-flex items-center gap-2 bg-primary text-on-primary text-label-md px-6 py-3 rounded-lg hover:bg-primary/90 shadow-sm min-h-[48px]">
                <span class="material-symbols-outlined text-[20px]">save</span>
                Kaydet
            </button>
        </div>
    </form>
@endsection
