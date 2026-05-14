@extends('layouts.admin')

@section('title', 'Galeri')
@section('page-title', 'Galeri')

@section('content')
    <div class="max-w-container-max-width mx-auto space-y-6">

        {{-- Bulk upload --}}
        <x-admin.section-card icon="cloud_upload" title="Toplu Görsel Yükle">
            <form action="{{ route('admin.gallery.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-label-md text-on-surface mb-2">Kategori *</label>
                        <select name="category" required class="w-full px-4 py-3 rounded-xl bg-surface-container-low border-outline-variant focus:border-primary focus:ring-0 min-h-[48px]">
                            @foreach (\App\Http\Controllers\Admin\GalleryController::CATEGORIES as $cat)
                                <option value="{{ $cat }}">{{ ucfirst(str_replace('_', ' ', $cat)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-label-md text-on-surface mb-2">Görseller (max 6MB her biri)</label>
                        <input type="file" name="images[]" accept="image/*" multiple required
                               class="block w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary-container/10 file:text-primary file:cursor-pointer">
                    </div>
                </div>
                <button type="submit" class="inline-flex items-center gap-2 bg-primary text-on-primary text-label-md px-6 py-3 rounded-lg hover:bg-primary/90 min-h-[48px]">
                    <span class="material-symbols-outlined text-[20px]">upload</span>
                    Yükle
                </button>
            </form>
        </x-admin.section-card>

        {{-- Category filter --}}
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.gallery.index') }}"
               class="px-3 py-1.5 rounded-full text-label-md border {{ ! request('category') ? 'bg-primary text-on-primary border-primary' : 'bg-surface-container-lowest text-on-surface border-outline-variant' }}">
                Tümü ({{ array_sum($counts) }})
            </a>
            @foreach (\App\Http\Controllers\Admin\GalleryController::CATEGORIES as $cat)
                <a href="{{ route('admin.gallery.index', ['category' => $cat]) }}"
                   class="px-3 py-1.5 rounded-full text-label-md border {{ request('category') === $cat ? 'bg-primary text-on-primary border-primary' : 'bg-surface-container-lowest text-on-surface border-outline-variant' }}">
                    {{ ucfirst(str_replace('_', ' ', $cat)) }} ({{ $counts[$cat] ?? 0 }})
                </a>
            @endforeach
        </div>

        {{-- Grid --}}
        @if ($items->isEmpty())
            <div class="bg-surface-container-lowest rounded-xl p-12 text-center">
                <span class="material-symbols-outlined text-5xl text-on-surface-variant block mb-3">photo_library</span>
                <p class="text-body-md text-on-surface-variant">Bu kategoride görsel yok.</p>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                @foreach ($items as $item)
                    <div class="relative group aspect-square rounded-2xl overflow-hidden">
                        <img src="{{ asset('storage/uploads/gallery/'.$item->image_path) }}" alt="{{ $item->alt_text }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-2">
                            <p class="text-xs text-white truncate">{{ $item->category }}</p>
                            <form action="{{ route('admin.gallery.destroy', $item) }}" method="POST" onsubmit="return confirm('Sil?');" class="absolute top-2 right-2">
                                @csrf @method('DELETE')
                                <button class="p-1.5 bg-white/90 rounded-full text-error hover:bg-white">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            <div>{{ $items->links() }}</div>
        @endif

    </div>
@endsection
