@extends('layouts.admin')

@section('title', 'Odalar')
@section('page-title', 'Odalar')

@section('topbar-right')
    <a href="{{ route('admin.rooms.create') }}" class="inline-flex items-center gap-2 bg-primary text-on-primary text-label-md px-4 py-2 rounded-lg hover:bg-primary/90">
        <span class="material-symbols-outlined text-[18px]">add</span>
        Yeni Oda
    </a>
@endsection

@section('content')
    <div class="max-w-container-max-width mx-auto">
        @if ($rooms->isEmpty())
            <div class="bg-surface-container-lowest rounded-xl p-12 text-center">
                <span class="material-symbols-outlined text-5xl text-on-surface-variant block mb-3">bed</span>
                <p class="text-body-md text-on-surface-variant">Henüz oda yok.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($rooms as $room)
                    <div class="bg-surface-container-lowest rounded-xl p-5 ambient-shadow-lvl1 border border-outline-variant/30">
                        @if ($room->main_image)
                            <img src="{{ asset('storage/uploads/rooms/'.$room->main_image) }}" alt="" class="w-full h-40 object-cover rounded-lg mb-4">
                        @else
                            <div class="w-full h-40 bg-surface-container rounded-lg flex items-center justify-center text-on-surface-variant mb-4">
                                <span class="material-symbols-outlined text-4xl">bed</span>
                            </div>
                        @endif
                        <h3 class="font-headline-md text-on-surface">{{ $room->name }}</h3>
                        <p class="text-sm text-on-surface-variant mt-1">{{ $room->short_description }}</p>
                        <div class="flex items-center justify-between mt-4">
                            <span class="text-xs {{ $room->is_active ? 'text-secondary' : 'text-on-surface-variant' }}">
                                <span class="w-2 h-2 inline-block rounded-full {{ $room->is_active ? 'bg-secondary' : 'bg-outline-variant' }}"></span>
                                {{ $room->is_active ? 'Aktif' : 'Pasif' }}
                            </span>
                            <div class="flex gap-1">
                                <a href="{{ route('admin.rooms.edit', $room) }}" class="text-primary p-2 hover:bg-primary-container/20 rounded">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </a>
                                <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('Sil?');" class="inline-block">
                                    @csrf @method('DELETE')
                                    <button class="text-error p-2 hover:bg-error-container/20 rounded">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
