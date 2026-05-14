<!DOCTYPE html>
<html lang="tr" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Giriş Yap · {{ setting('site_name', 'Arsi Blue Beach') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-background text-on-background font-body-md text-body-md antialiased flex items-center justify-center px-margin-mobile py-12">

    <div class="w-full max-w-md">
        {{-- Logo / brand --}}
        <div class="text-center mb-8">
            <h1 class="font-display-lg text-headline-lg text-primary">{{ setting('site_name', 'Arsi Blue Beach') }}</h1>
            <p class="text-body-md text-on-surface-variant mt-2">Yönetim Paneli</p>
        </div>

        {{-- Login card --}}
        <div class="bg-surface-container-lowest rounded-xl ambient-shadow-lvl1 border border-outline-variant/30 p-8">
            <h2 class="font-headline-md text-headline-md text-on-surface mb-6">Giriş Yap</h2>

            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-error-container border border-error/30 text-on-error-container px-4 py-3 text-body-md">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.attempt') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-label-md text-on-surface mb-2">E-posta</label>
                    <input type="email" name="email" id="email"
                           value="{{ old('email') }}"
                           required autofocus
                           class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3
                                  focus:ring-2 focus:ring-primary focus:border-primary
                                  text-body-md text-on-surface min-h-[48px]">
                </div>

                <div>
                    <label for="password" class="block text-label-md text-on-surface mb-2">Şifre</label>
                    <input type="password" name="password" id="password"
                           required
                           class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3
                                  focus:ring-2 focus:ring-primary focus:border-primary
                                  text-body-md text-on-surface min-h-[48px]">
                </div>

                <label class="flex items-center gap-2 text-label-md text-on-surface-variant">
                    <input type="checkbox" name="remember" value="1"
                           class="rounded border-outline-variant text-primary focus:ring-primary w-4 h-4">
                    Beni hatırla
                </label>

                <button type="submit"
                        class="w-full bg-primary text-on-primary text-label-md font-semibold rounded-lg py-3 px-6
                               hover:bg-primary/90 transition-colors shadow-sm min-h-[48px]
                               flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">login</span>
                    Giriş Yap
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-on-surface-variant mt-6">
            © {{ date('Y') }} {{ setting('site_name', 'Arsi Blue Beach') }} · Yetkili Acenta
        </p>
    </div>

</body>
</html>
