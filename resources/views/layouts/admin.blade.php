<!DOCTYPE html>
<html lang="tr" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Yönetim Paneli') · {{ setting('site_name', 'Arsi Blue Beach') }}</title>

    <link rel="icon" href="{{ setting('site_favicon') ? asset('storage/uploads/'.setting('site_favicon')) : asset('favicon.ico') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-on-background font-body-md text-body-md antialiased min-h-screen flex">

    {{-- ========== SIDEBAR ========== --}}
    <nav class="h-screen w-64 fixed left-0 top-0 bg-surface-container shadow-sm flex flex-col border-r border-outline-variant z-20">
        <div class="p-6">
            <h1 class="font-display-lg text-headline-md text-primary leading-tight">
                {{ setting('site_name', 'Arsi Blue Beach') }}
            </h1>
            <p class="text-label-md text-on-surface-variant mt-1">Yönetim Paneli</p>
        </div>

        <ul class="flex-1 overflow-y-auto px-2 pb-4 space-y-1">
            @php
                // [route name | '#', icon, label, url path key for active matching]
                $items = [
                    ['admin.dashboard', 'dashboard', 'Panel', null],
                    ['admin.pages.index', 'edit_document', 'Sayfa İçerikleri', 'pages'],
                    ['admin.campaigns.index', 'local_offer', 'Kampanyalar', 'campaigns'],
                    ['admin.rooms.index', 'bed', 'Odalar', 'rooms'],
                    ['admin.gallery.index', 'photo_library', 'Galeri', 'gallery'],
                    ['admin.faqs.index', 'help', 'SSS', 'faqs'],
                    ['admin.tracking.index', 'monitoring', 'Tracking & Pixel', 'tracking'],
                    ['admin.seo.index', 'travel_explore', 'SEO', 'seo'],
                    ['admin.events.index', 'event_note', 'Event Logları', 'events'],
                    ['admin.leads.index', 'group', 'Lead\'ler', 'leads'],
                    ['admin.contact.edit', 'call', 'İletişim Bilgileri', 'contact'],
                    ['admin.agency.edit', 'business', 'Acenta Bilgileri', 'agency'],
                    ['admin.settings.edit', 'settings', 'Genel Ayarlar', 'settings'],
                ];
            @endphp
            @foreach ($items as [$route, $icon, $label, $key])
                @php
                    $active = ($route === 'admin.dashboard' && request()->routeIs('admin.dashboard'))
                        || ($key && str_contains(request()->path(), 'admin/'.$key));
                    $href = $route !== '#' && \Illuminate\Support\Facades\Route::has($route)
                        ? route($route)
                        : '#';
                @endphp
                <li>
                    <a href="{{ $href }}"
                       class="flex items-center gap-3 rounded-lg px-4 py-3 text-label-md transition-colors
                              {{ $active
                                    ? 'bg-secondary-container text-on-secondary-container font-semibold'
                                    : 'text-on-surface-variant hover:bg-surface-container-high' }}">
                        <span class="material-symbols-outlined text-[20px]">{{ $icon }}</span>
                        {{ $label }}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="p-4 border-t border-outline-variant">
            <div class="flex items-center gap-3 px-2 py-2">
                <div class="w-10 h-10 rounded-full bg-primary-container/10 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">person</span>
                </div>
                <div class="flex flex-col flex-1 min-w-0">
                    <span class="text-label-md text-on-surface truncate">{{ auth()->user()?->name ?? 'Yönetici' }}</span>
                    <span class="text-xs text-on-surface-variant truncate">{{ auth()->user()?->email }}</span>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST" class="shrink-0">
                    @csrf
                    <button type="submit"
                            title="Çıkış"
                            class="text-on-surface-variant hover:text-error rounded-full p-2 transition-colors">
                        <span class="material-symbols-outlined text-[20px]">logout</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    {{-- ========== MAIN ========== --}}
    <div class="flex-1 ml-64 flex flex-col min-h-screen">

        {{-- Topbar --}}
        <header class="bg-surface h-16 border-b border-outline-variant flex justify-between items-center px-gutter sticky top-0 z-10">
            <div class="flex items-center gap-4">
                @hasSection('topbar-left')
                    @yield('topbar-left')
                @else
                    <h2 class="font-headline-md text-headline-md text-on-surface">@yield('page-title', 'Panel')</h2>
                @endif
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ url('/') }}" target="_blank"
                   class="text-on-surface-variant hover:bg-surface-container-low rounded-full p-2 transition"
                   title="Siteyi görüntüle">
                    <span class="material-symbols-outlined">open_in_new</span>
                </a>
                @yield('topbar-right')
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 p-gutter w-full pb-section-gap-mobile md:pb-section-gap-desktop">
            @if (session('success'))
                <div class="mb-4 rounded-lg bg-secondary-container/30 border border-secondary/30 text-on-secondary-container px-4 py-3 text-body-md">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-lg bg-error-container border border-error/30 text-on-error-container px-4 py-3 text-body-md">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
