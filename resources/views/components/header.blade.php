@php
    $links = [
        ['label' => 'Munkáink', 'href' => '#munkaink'],
        ['label' => 'Stúdió', 'href' => '#studio'],
        ['label' => 'Folyamat', 'href' => '#folyamat'],
    ];
@endphp

{{-- A designból nem derül ki, de az áttetsző háttér + blur csak ragadó fejlécnél értelmes. --}}
<header
    x-data="{ open: false }"
    @keydown.escape.window="open = false"
    class="sticky top-0 z-50 border-b border-line bg-[rgba(255,255,255,0.86)] backdrop-blur-[5px]"
>
    <div class="mx-auto flex h-[74px] w-full max-w-container items-center justify-between px-5 sm:px-8 lg:px-10">
        <a href="{{ route('landing') }}" class="font-display text-[21px] leading-[33.6px] font-bold tracking-[-0.21px] text-ink">
            FÉM<span class="text-accent">.</span>
        </a>

        <nav aria-label="Fő navigáció" class="hidden lg:flex lg:items-center lg:gap-[34px]">
            @foreach ($links as $link)
                <a href="{{ $link['href'] }}" class="font-mono text-[12px] leading-[19.2px] tracking-[1.2px] text-body transition-colors hover:text-ink">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="flex items-center gap-4">
            {{-- A wrapperen múlik a rejtés: a btn saját inline-flex osztálya később generálódik, mint a hidden, így azt felülírná. --}}
            <div class="hidden sm:block">
                <x-btn variant="dark" @click="$dispatch('open-contact')">Kapcsolat</x-btn>
            </div>

            {{-- Két külön svg, mert a <template x-if> az svg-n belül foreign contentbe kerül és nem működik. --}}
            <button
                type="button"
                @click="open = ! open"
                :aria-expanded="open ? 'true' : 'false'"
                :aria-label="open ? 'Navigáció bezárása' : 'Navigáció megnyitása'"
                aria-controls="mobile-nav"
                class="flex h-[43px] w-[43px] items-center justify-center border border-line text-ink lg:hidden"
            >
                <svg x-show="! open" class="h-4 w-4" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.25" aria-hidden="true">
                    <path d="M1 4h14M1 8h14M1 12h14" />
                </svg>
                <svg x-show="open" x-cloak class="h-4 w-4" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.25" aria-hidden="true">
                    <path d="M3 3l10 10M13 3L3 13" />
                </svg>
            </button>
        </div>
    </div>

    <nav
        id="mobile-nav"
        x-show="open"
        x-cloak
        @click.outside="open = false"
        aria-label="Mobil navigáció"
        class="border-t border-line bg-white lg:hidden"
    >
        <div class="mx-auto flex w-full max-w-container flex-col px-5 py-4 sm:px-8">
            @foreach ($links as $link)
                <a href="{{ $link['href'] }}" @click="open = false" class="py-3 font-mono text-[12px] leading-[19.2px] tracking-[1.2px] text-body">
                    {{ $link['label'] }}
                </a>
            @endforeach
            <x-btn variant="dark" class="mt-2 sm:hidden" @click="open = false; $dispatch('open-contact')">Kapcsolat</x-btn>
        </div>
    </nav>
</header>
