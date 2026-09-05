@props(['projects'])

{{-- A ragadó fejléc alá csúszna a szekciócím, ha a horgony pontosan a tetejére ugrana. --}}
<section id="munkaink" class="scroll-mt-[74px] py-16 lg:py-section">
    <div class="mx-auto w-full max-w-container px-5 sm:px-8 lg:px-10">
        <h2 class="font-display text-[28px] leading-[1.02] font-bold tracking-[-0.02em] text-ink sm:text-[34px] lg:text-[38px] xl:text-[44px]">
            Munkáink
        </h2>

        <div class="mt-8 grid gap-[30px] border-t border-line pt-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach ($projects as $project)
                <x-project-card :project="$project" />
            @endforeach
        </div>
    </div>
</section>
