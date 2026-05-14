@props([
    'icon' => 'edit',
    'title' => '',
    'variant' => 'primary', // primary|secondary|tertiary
])

<section {{ $attributes->merge(['class' => 'bg-surface-container-lowest rounded-3xl p-6 md:p-8 ambient-shadow-lvl1 border border-outline-variant/30']) }}>
    @if ($title)
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-outline-variant/50">
            @switch($variant)
                @case('secondary')
                    <div class="w-10 h-10 rounded-full bg-secondary-container/20 text-secondary flex items-center justify-center">
                        <span class="material-symbols-outlined">{{ $icon }}</span>
                    </div>
                    @break
                @case('tertiary')
                    <div class="w-10 h-10 rounded-full bg-tertiary-container/10 text-tertiary flex items-center justify-center">
                        <span class="material-symbols-outlined">{{ $icon }}</span>
                    </div>
                    @break
                @default
                    <div class="w-10 h-10 rounded-full bg-primary-container/10 text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined">{{ $icon }}</span>
                    </div>
            @endswitch
            <h3 class="font-headline-md text-headline-md text-on-surface">{{ $title }}</h3>
        </div>
    @endif

    {{ $slot }}
</section>
