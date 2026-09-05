{{-- Natív <dialog>: a fókuszcsapdát, az ESC-et és a backdropot a böngésző adja. --}}
<dialog
    x-data="contactForm"
    x-ref="dialog"
    @open-contact.window="open()"
    @click.self="close()"
    @close="onClose()"
    aria-labelledby="contact-modal-title"
    class="m-auto w-[calc(100%-2rem)] max-w-[520px] border border-line bg-white p-0 text-ink backdrop:bg-[rgba(22,22,26,0.6)]"
>
    <div class="max-h-[calc(100dvh-4rem)] overflow-y-auto p-6 sm:p-10">
        <div class="flex items-start justify-between gap-6">
            <h2 id="contact-modal-title" class="font-display text-[28px] leading-[1.06] font-bold tracking-[-0.02em]">
                Beszéljünk a projektedről
            </h2>

            <button
                type="button"
                @click="close()"
                aria-label="Ablak bezárása"
                class="-mt-1 -mr-1 flex h-8 w-8 shrink-0 items-center justify-center text-body transition-colors hover:text-ink"
            >
                <svg class="h-4 w-4" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.25" aria-hidden="true">
                    <path d="M3 3l10 10M13 3L3 13" />
                </svg>
            </button>
        </div>

        <form
            x-ref="form"
            x-show="! sent"
            action="{{ route('contact.store') }}"
            method="post"
            @submit.prevent="submit()"
            @input="clearError($event.target.name)"
            class="mt-8 space-y-6"
            novalidate
        >
            <div>
                <label for="contact-name" class="block font-mono text-[11px] leading-[17.6px] font-medium tracking-[1.54px] text-muted">Név</label>
                <input
                    id="contact-name"
                    x-ref="name"
                    type="text"
                    name="name"
                    autocomplete="name"
                    class="mt-2 w-full border border-line px-4 py-3 font-sans text-[14.5px] leading-[23.2px] text-ink outline-none transition-colors focus:border-accent"
                >
                <p x-show="errors.name" x-text="errors.name?.[0]" class="mt-2 font-mono text-[11px] leading-[17.6px] text-danger"></p>
            </div>

            <div>
                <label for="contact-email" class="block font-mono text-[11px] leading-[17.6px] font-medium tracking-[1.54px] text-muted">E-mail cím</label>
                <input
                    id="contact-email"
                    type="email"
                    name="email"
                    autocomplete="email"
                    class="mt-2 w-full border border-line px-4 py-3 font-sans text-[14.5px] leading-[23.2px] text-ink outline-none transition-colors focus:border-accent"
                >
                <p x-show="errors.email" x-text="errors.email?.[0]" class="mt-2 font-mono text-[11px] leading-[17.6px] text-danger"></p>
            </div>

            <div>
                <label for="contact-message" class="block font-mono text-[11px] leading-[17.6px] font-medium tracking-[1.54px] text-muted">Üzenet</label>
                <textarea
                    id="contact-message"
                    name="message"
                    rows="5"
                    class="mt-2 w-full resize-y border border-line px-4 py-3 font-sans text-[14.5px] leading-[23.2px] text-ink outline-none transition-colors focus:border-accent"
                ></textarea>
                <p x-show="errors.message" x-text="errors.message?.[0]" class="mt-2 font-mono text-[11px] leading-[17.6px] text-danger"></p>
            </div>

            {{-- Honeypot: rejtett mező, csak a botok töltik ki. --}}
            <div class="hidden" aria-hidden="true">
                <label for="contact-website">Weboldal</label>
                <input id="contact-website" type="text" name="website" tabindex="-1" autocomplete="off">
            </div>

            <p x-show="generalError" x-text="generalError" class="border border-danger px-4 py-3 font-mono text-[11px] leading-[17.6px] text-danger"></p>

            <x-btn variant="dark" type="submit" ::disabled="sending" class="w-full disabled:opacity-60 sm:w-auto">
                <span x-text="sending ? 'Küldés…' : 'Üzenet küldése'">Üzenet küldése</span>
            </x-btn>
        </form>

        <div x-show="sent" class="mt-8">
            <p x-text="successMessage" class="font-sans text-[14.5px] leading-[23.2px] text-body"></p>

            <x-btn variant="dark" @click="close()" class="mt-8 w-full sm:w-auto">Bezárás</x-btn>
        </div>
    </div>
</dialog>
