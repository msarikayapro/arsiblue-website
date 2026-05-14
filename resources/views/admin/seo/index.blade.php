@extends('layouts.admin')

@section('title', 'SEO Meta')
@section('page-title', 'SEO Meta')

@section('content')
    <div class="max-w-container-max-width mx-auto">
        <div class="bg-surface-container-lowest rounded-xl ambient-shadow-lvl1 border border-outline-variant/30 overflow-hidden">
            <table class="w-full text-body-md">
                <thead class="bg-surface-container-low text-label-md text-on-surface-variant">
                    <tr>
                        <th class="text-left px-6 py-4">Sayfa</th>
                        <th class="text-left px-6 py-4">Title</th>
                        <th class="text-left px-6 py-4">Description</th>
                        <th class="text-center px-6 py-4">Robots</th>
                        <th class="text-right px-6 py-4">İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pages as $page)
                        <tr class="border-t border-outline-variant/30 hover:bg-surface-container-low/50">
                            <td class="px-6 py-4">
                                <p class="font-semibold">{{ $page->title }}</p>
                                <code class="text-xs text-on-surface-variant">/{{ $page->slug }}</code>
                            </td>
                            <td class="px-6 py-4 text-sm text-on-surface-variant max-w-xs truncate">
                                {{ $page->seo?->title ?: '— eksik —' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-on-surface-variant max-w-md truncate">
                                {{ $page->seo?->description ?: '— eksik —' }}
                            </td>
                            <td class="px-6 py-4 text-center text-xs">
                                <code>{{ $page->seo?->robots ?? 'index,follow' }}</code>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.seo.edit', $page->slug) }}"
                                   class="inline-flex items-center gap-1 bg-primary text-on-primary text-label-md px-3 py-2 rounded-lg hover:bg-primary/90">
                                    <span class="material-symbols-outlined text-[18px]">edit</span> Düzenle
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
