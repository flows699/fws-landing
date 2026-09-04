@php
    $menu = [
        ['label' => 'Munkáink', 'href' => '#munkaink'],
        ['label' => 'Stúdió', 'href' => '#studio'],
        ['label' => 'Folyamat', 'href' => '#folyamat'],
        ['label' => 'Kapcsolat', 'href' => '#kapcsolat'],
    ];
@endphp

<footer id="kapcsolat" class="bg-ink pt-20 pb-[30px]">
    <div class="mx-auto w-full max-w-container px-5 sm:px-8 lg:px-10">
        <div class="grid gap-12 pb-14 md:grid-cols-[1.7fr_1fr_1fr]">
            <div>
                <p class="font-display text-[24px] leading-[38.4px] font-bold tracking-[-0.24px] text-white">
                    FÉM<span class="text-accent">.</span>
                </p>
                <p class="mt-[18px] max-w-[308px] font-sans text-[14.5px] leading-[23.2px] text-footer-text">
                    Ipari formatervező stúdió Budapesten.
                </p>
            </div>

            <div>
                <h2 class="font-mono text-[11px] leading-[17.6px] font-medium tracking-[1.54px] text-footer-label">Menü</h2>
                <ul class="mt-5 space-y-[14.6px]">
                    @foreach ($menu as $item)
                        <li>
                            <a href="{{ $item['href'] }}" class="font-sans text-[14.5px] leading-[23.2px] text-footer-strong transition-colors hover:text-white">
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h2 class="font-mono text-[11px] leading-[17.6px] font-medium tracking-[1.54px] text-footer-label">Kapcsolat</h2>
                <address class="mt-5 space-y-[1.5px] font-sans text-[14.5px] leading-[23.2px] text-footer-strong not-italic">
                    <p>1061 Budapest Fém utca 99.</p>
                    <p><a href="mailto:studio@fem.hu" class="transition-colors hover:text-white">studio@fem.hu</a></p>
                    <p><a href="tel:+3612345678" class="transition-colors hover:text-white">+36 1 234 5678</a></p>
                </address>
            </div>
        </div>

        <div class="flex flex-col gap-4 border-t border-white/12 pt-[26px] sm:flex-row sm:items-center sm:justify-between">
            <p class="font-mono text-[11.5px] leading-[18.4px] tracking-[1.61px] text-footer-label">
                &copy; {{ now()->year }} FÉM Stúdió — Minden jog fenntartva
            </p>
            <div class="flex gap-4 font-mono text-[11.5px] leading-[18.4px] tracking-[1.61px] text-footer-muted">
                <a href="#" class="transition-colors hover:text-white">Adatvédelem</a>
                <a href="#" class="transition-colors hover:text-white">Impresszum</a>
            </div>
        </div>
    </div>
</footer>
