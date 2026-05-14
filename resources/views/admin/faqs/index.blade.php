@extends('layouts.admin')

@section('title', 'SSS')
@section('page-title', 'Sıkça Sorulan Sorular')

@section('topbar-right')
    <a href="{{ route('admin.faqs.create') }}" class="inline-flex items-center gap-2 bg-primary text-on-primary text-label-md px-4 py-2 rounded-lg hover:bg-primary/90">
        <span class="material-symbols-outlined text-[18px]">add</span>
        Yeni SSS
    </a>
@endsection

@section('content')
    <div class="max-w-container-max-width mx-auto space-y-6">
        @if ($faqs->isEmpty())
            <div class="bg-surface-container-lowest rounded-xl p-12 text-center">
                <span class="material-symbols-outlined text-5xl text-on-surface-variant block mb-3">help</span>
                <p class="text-body-md text-on-surface-variant">Henüz SSS yok.</p>
            </div>
        @else
            @foreach ($faqs->groupBy('category') as $category => $items)
                <div class="bg-surface-container-lowest rounded-xl ambient-shadow-lvl1 border border-outline-variant/30 overflow-hidden">
                    <div class="px-6 py-4 bg-surface-container-low border-b border-outline-variant">
                        <h3 class="text-label-md text-on-surface-variant uppercase tracking-wider">{{ ucfirst($category) }} ({{ $items->count() }})</h3>
                    </div>
                    <ul class="divide-y divide-outline-variant/30">
                        @foreach ($items as $faq)
                            <li class="px-6 py-4 flex items-start gap-4">
                                <div class="flex-1">
                                    <p class="font-semibold text-on-surface">{{ $faq->question }}</p>
                                    <p class="text-sm text-on-surface-variant mt-1 line-clamp-2">{{ $faq->answer }}</p>
                                    <div class="flex items-center gap-3 mt-2 text-xs text-on-surface-variant">
                                        @if ($faq->visible_pages)
                                            <span><span class="material-symbols-outlined text-[14px] align-middle">filter_alt</span> {{ count($faq->visible_pages) }} sayfada</span>
                                        @else
                                            <span>Tüm sayfalarda</span>
                                        @endif
                                        @if (! $faq->is_active)
                                            <span class="text-error">· Pasif</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex gap-2 shrink-0">
                                    <a href="{{ route('admin.faqs.edit', $faq) }}" class="inline-flex items-center gap-1 text-primary text-label-md hover:underline">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>
                                    <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" onsubmit="return confirm('Sil?');">
                                        @csrf @method('DELETE')
                                        <button class="text-error p-1 hover:bg-error-container/20 rounded">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        @endif
    </div>
@endsection
