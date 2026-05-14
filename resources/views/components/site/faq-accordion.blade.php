@props(['faqs'])

<div x-data="{ open: null }" class="space-y-3 max-w-3xl mx-auto">
    @foreach ($faqs as $i => $faq)
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 overflow-hidden">
            <button @click="open = open === {{ $i }} ? null : {{ $i }}" type="button"
                    class="w-full text-left px-6 py-4 flex items-center justify-between gap-4 hover:bg-surface-container-low transition">
                <span class="font-semibold text-on-surface text-body-lg">{{ $faq->question }}</span>
                <span class="material-symbols-outlined text-primary transition-transform"
                      :class="open === {{ $i }} ? 'rotate-180' : ''">expand_more</span>
            </button>
            <div x-show="open === {{ $i }}" x-cloak
                 x-transition:enter="transition-all duration-200 ease-out"
                 x-transition:enter-start="opacity-0 max-h-0"
                 x-transition:enter-end="opacity-100 max-h-[500px]"
                 class="overflow-hidden">
                <div class="px-6 pb-5 text-body-md text-on-surface-variant leading-relaxed">
                    {!! nl2br(e($faq->answer)) !!}
                </div>
            </div>
        </div>
    @endforeach
</div>
