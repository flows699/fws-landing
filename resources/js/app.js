import Alpine from 'alpinejs';

const genericError = 'Nem sikerült elküldeni az üzenetet. Kérjük, próbáld újra.';

Alpine.data('contactForm', () => ({
    sending: false,
    sent: false,
    errors: {},
    generalError: '',
    successMessage: '',

    open() {
        this.$refs.dialog.showModal();
        this.$nextTick(() => this.$refs.name.focus());
    },

    close() {
        this.$refs.dialog.close();
    },

    /** A <dialog> ESC-re és backdrop kattintásra is ezen az eseményen keresztül zár. */
    onClose() {
        this.$refs.form.reset();
        this.sending = false;
        this.sent = false;
        this.errors = {};
        this.generalError = '';
    },

    async submit() {
        this.sending = true;
        this.errors = {};
        this.generalError = '';

        try {
            const response = await fetch(this.$refs.form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(Object.fromEntries(new FormData(this.$refs.form))),
            });

            if (response.status === 201) {
                const payload = await response.json();

                this.successMessage = payload.message;
                this.sent = true;

                return;
            }

            if (response.status === 422) {
                const payload = await response.json();

                this.errors = payload.errors ?? {};

                return;
            }

            this.generalError = response.status === 429
                ? 'Túl sok próbálkozás, várj egy percet.'
                : genericError;
        } catch {
            this.generalError = genericError;
        } finally {
            this.sending = false;
        }
    },
}));

window.Alpine = Alpine;

Alpine.start();
