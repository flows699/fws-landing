@props(['hero'])

<section class="relative flex min-h-[520px] items-center overflow-hidden bg-ink lg:h-[780px]">
    @if (filled($hero->image_path))
        {{-- Dekoratív háttér, a jelentést a H1 hordozza. LCP elem, ezért nem lazy. --}}
        <img
            src="{{ Storage::url($hero->image_path) }}"
            alt=""
            fetchpriority="high"
            class="absolute inset-0 h-full w-full object-cover"
        >
    @endif

    {{-- Mobilon fentről lefelé sötétít, különben a szöveg alatti világos képrészen olvashatatlan. --}}
    <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(22,22,26,0.55)_0%,rgba(22,22,26,0.8)_100%)] lg:bg-[linear-gradient(90deg,rgba(22,22,26,0.78)_0%,rgba(22,22,26,0.38)_55%,rgba(22,22,26,0.15)_100%)]"></div>

    <div class="relative mx-auto w-full max-w-container px-5 py-20 sm:px-8 lg:px-10 lg:py-0">
        <div class="max-w-[803px]">
            <h1 class="font-display text-[36px] leading-[1.02] font-bold tracking-[-0.02em] text-white sm:text-[44px] lg:text-[54px] xl:text-[66px]">
                {{ $hero->title }}
            </h1>

            @if (filled($hero->subtitle))
                <p class="mt-6 max-w-[528px] font-sans text-[18.5px] leading-[29.6px] text-white/78">
                    {{ $hero->subtitle }}
                </p>
            @endif

            @if (filled($hero->cta_primary_label) || filled($hero->cta_secondary_label))
                <div class="mt-10 flex flex-wrap gap-[14px]">
                    @if (filled($hero->cta_primary_label))
                        <x-btn variant="light" :href="$hero->cta_primary_url ?? '#'">{{ $hero->cta_primary_label }}</x-btn>
                    @endif

                    @if (filled($hero->cta_secondary_label))
                        <x-btn variant="outline" :href="$hero->cta_secondary_url ?? '#'">{{ $hero->cta_secondary_label }}</x-btn>
                    @endif
                </div>
            @endif
        </div>
    </div>
</section>
