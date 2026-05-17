@extends('layouts.admin')

@section('title', 'Galeri Kategorileri')
@section('page-title', 'Galeri Kategorileri')

@section('topbar-left')
    <a href="{{ route('admin.gallery.index') }}" class="p-2 hover:bg-surface-container rounded-full text-on-surface-variant" title="Galeri">
        <span class="material-symbols-outlined">arrow_back</span>
    </a>
    <h2 class="font-headline-md text-on-surface">Galeri Kategorileri</h2>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">

        @if (session('success'))
            <div class="bg-secondary-container/30 border border-secondary/30 text-on-secondary-container px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-error-container/30 border border-error/30 text-on-error-container px-4 py-3 rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        {{-- Yeni kategori ekleme --}}
        <x-admin.section-card icon="add_circle" title="Yeni Kategori">
            <form action="{{ route('admin.gallery.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div class="md:col-span-2">
                        <label class="block text-label-md text-on-surface mb-2">Kategori Adı *</label>
                        <input name="name" type="text" required maxlength="100" placeholder="Örn: Spa & Wellness"
                               class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                    </div>
                    <div>
                        <label class="block text-label-md text-on-surface mb-2">Slug (opsiyonel)</label>
                        <input name="slug" type="text" maxlength="50" pattern="[a-z0-9_-]+" placeholder="otomatik"
                               class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 font-mono text-sm min-h-[48px]">
                    </div>
                </div>
                <p class="text-xs text-on-surface-variant">
                    Slug boş bırakılırsa ad'dan otomatik üretilir. <strong>Oluşturulduktan sonra değiştirilemez.</strong>
                </p>
                <button type="submit" class="inline-flex items-center gap-2 bg-primary text-on-primary text-label-md px-6 py-3 rounded-lg hover:bg-primary/90 min-h-[48px]">
                    <span class="material-symbols-outlined text-[20px]">add</span>
                    Kategori Ekle
                </button>
            </form>
        </x-admin.section-card>

        {{-- Mevcut kategoriler --}}
        <x-admin.section-card icon="folder" title="Mevcut Kategoriler" variant="secondary">
            @if ($categories->isEmpty())
                <p class="text-body-md text-on-surface-variant py-4">Henüz kategori yok.</p>
            @else
                <div class="space-y-3">
                    @foreach ($categories as $cat)
                        @php $count = $counts[$cat->slug] ?? 0; @endphp
                        <form action="{{ route('admin.gallery.categories.update', $cat) }}" method="POST"
                              class="flex flex-col md:flex-row items-stretch md:items-center gap-3 p-4 bg-surface-container-low rounded-2xl border border-outline-variant/30">
                            @csrf @method('PUT')

                            <div class="flex-1 grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                                <div class="md:col-span-5">
                                    <label class="text-[11px] text-on-surface-variant uppercase tracking-wider">Ad</label>
                                    <input name="name" type="text" required maxlength="100"
                                           value="{{ $cat->name }}"
                                           class="w-full px-3 py-2 rounded-lg bg-surface-container-lowest border-outline-variant focus:border-primary focus:ring-0">
                                </div>
                                <div class="md:col-span-3">
                                    <label class="text-[11px] text-on-surface-variant uppercase tracking-wider">Slug (sabit)</label>
                                    <div class="px-3 py-2 rounded-lg bg-surface-container border border-outline-variant/30 font-mono text-sm text-on-surface-variant">
                                        {{ $cat->slug }}
                                    </div>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="text-[11px] text-on-surface-variant uppercase tracking-wider">Sıra</label>
                                    <input name="sort_order" type="number" min="0"
                                           value="{{ $cat->sort_order }}"
                                           class="w-full px-3 py-2 rounded-lg bg-surface-container-lowest border-outline-variant focus:border-primary focus:ring-0">
                                </div>
                                <div class="md:col-span-2 flex flex-col gap-1">
                                    <span class="text-[11px] text-on-surface-variant uppercase tracking-wider">Görsel</span>
                                    <span class="text-body-md text-on-surface font-semibold">{{ $count }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 md:flex-col md:items-end">
                                <label class="flex items-center gap-2 cursor-pointer text-label-md">
                                    <input type="checkbox" name="is_active" value="1"
                                           {{ $cat->is_active ? 'checked' : '' }}
                                           class="rounded border-outline-variant text-primary focus:ring-primary w-4 h-4">
                                    Aktif
                                </label>
                                <div class="flex gap-2">
                                    <button type="submit" class="p-2 rounded-lg bg-primary text-on-primary hover:bg-primary/90" title="Kaydet">
                                        <span class="material-symbols-outlined text-[20px]">save</span>
                                    </button>
                                </div>
                            </div>
                        </form>

                        @if ($count === 0)
                            <form action="{{ route('admin.gallery.categories.destroy', $cat) }}" method="POST"
                                  onsubmit="return confirm('{{ $cat->name }} kategorisini silmek istediğinize emin misiniz?');"
                                  class="-mt-2 pl-4">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-error text-xs hover:underline">
                                    {{ $cat->name }} kategorisini sil
                                </button>
                            </form>
                        @endif
                    @endforeach
                </div>
            @endif
        </x-admin.section-card>

        <div class="text-xs text-on-surface-variant px-2">
            <strong>İpucu:</strong> Silinemeyen kategorilerde görsel var. Önce galeriden o kategorinin görsellerini silin
            veya başka kategoriye taşıyın; sonra kategoriyi silebilirsiniz. Aktif olmayan kategoriler ne admin yükleme
            seçeneklerinde ne de public galeride görünür.
        </div>
    </div>
@endsection
