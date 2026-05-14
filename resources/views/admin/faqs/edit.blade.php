@extends('layouts.admin')

@section('title', $isNew ? 'Yeni SSS' : 'SSS Düzenle')
@section('page-title', $isNew ? 'Yeni SSS' : 'SSS Düzenle')

@section('topbar-left')
    <a href="{{ route('admin.faqs.index') }}" class="p-2 hover:bg-surface-container rounded-full text-on-surface-variant">
        <span class="material-symbols-outlined">arrow_back</span>
    </a>
    <h2 class="font-headline-md text-on-surface">{{ $isNew ? 'Yeni SSS' : 'SSS Düzenle' }}</h2>
@endsection

@section('content')
    <form action="{{ $isNew ? route('admin.faqs.store') : route('admin.faqs.update', $faq) }}" method="POST" class="max-w-3xl mx-auto space-y-6">
        @csrf
        @if (! $isNew) @method('PUT') @endif

        <x-admin.section-card icon="quiz" title="Soru & Cevap">
            <div class="space-y-4">
                <div>
                    <label class="block text-label-md text-on-surface mb-2">Soru *</label>
                    <input name="question" type="text" maxlength="500" required
                           value="{{ old('question', $faq->question) }}"
                           class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                    @error('question')<p class="text-xs text-error mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-label-md text-on-surface mb-2">Cevap *</label>
                    <textarea name="answer" rows="6" required
                              class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0">{{ old('answer', $faq->answer) }}</textarea>
                    @error('answer')<p class="text-xs text-error mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </x-admin.section-card>

        <x-admin.section-card icon="settings" title="Yayın & Görünürlük" variant="secondary">
            <div class="space-y-4">
                <div>
                    <label class="block text-label-md text-on-surface mb-2">Kategori</label>
                    <select name="category" class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                        @foreach (\App\Http\Controllers\Admin\FaqController::CATEGORIES as $cat)
                            <option value="{{ $cat }}" {{ ($faq->category ?? 'genel') === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-label-md text-on-surface mb-2">Görüneceği sayfalar (boş = tüm sayfalar)</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                        @foreach ($pageSlugs as $slug => $title)
                            <label class="flex items-center gap-2 cursor-pointer text-body-md">
                                <input type="checkbox" name="visible_pages[]" value="{{ $slug }}"
                                       {{ in_array($slug, (array) old('visible_pages', $faq->visible_pages ?? []), true) ? 'checked' : '' }}
                                       class="rounded border-outline-variant text-primary focus:ring-primary w-4 h-4">
                                {{ $title }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-label-md text-on-surface mb-2">Sıra</label>
                        <input name="sort_order" type="number" min="0"
                               value="{{ old('sort_order', $faq->sort_order) }}"
                               class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                    </div>
                    <div class="flex items-end">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input name="is_active" type="checkbox" value="1"
                                   {{ old('is_active', $faq->is_active) ? 'checked' : '' }}
                                   class="rounded border-outline-variant text-primary focus:ring-primary w-4 h-4">
                            <span class="text-label-md text-on-surface">Aktif</span>
                        </label>
                    </div>
                </div>
            </div>
        </x-admin.section-card>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.faqs.index') }}" class="px-6 py-3 rounded-lg border border-outline text-on-surface-variant hover:bg-surface-container">İptal</a>
            <button type="submit" class="inline-flex items-center gap-2 bg-primary text-on-primary text-label-md px-6 py-3 rounded-lg hover:bg-primary/90 shadow-sm min-h-[48px]">
                <span class="material-symbols-outlined text-[20px]">save</span>
                Kaydet
            </button>
        </div>
    </form>
@endsection
