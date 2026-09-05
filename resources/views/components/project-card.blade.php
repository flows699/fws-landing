@props(['project'])

<article>
    <div class="aspect-[4/3] w-full overflow-hidden bg-surface">
        <img
            src="{{ Storage::url($project->cover_path) }}"
            alt="{{ $project->title }}"
            loading="lazy"
            class="h-full w-full object-cover transition-opacity hover:opacity-90"
        >
    </div>

    <time datetime="{{ $project->published_at->toDateString() }}" class="mt-[18px] block font-mono text-[11px] leading-[17.6px] tracking-[0.66px] text-muted">
        {{ $project->published_at->format('Y.m.d') }}
    </time>

    <h3 class="mt-[29px] font-display text-[18.5px] leading-[22.94px] font-medium tracking-[-0.185px] text-ink">
        {{ $project->title }}
    </h3>
</article>
