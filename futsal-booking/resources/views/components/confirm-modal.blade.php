<div x-data="confirmModalStore()" x-show="open" x-cloak @keydown.escape.window="cancel()"
     class="fixed inset-0 z-[9999] flex items-center justify-center px-4">
    <div x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="cancel()"
         class="absolute inset-0 bg-black/60"></div>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="relative w-full max-w-sm rounded-2xl border border-neutral-800 bg-neutral-900 p-6 shadow-2xl shadow-black/50">
        <h3 class="mb-2 text-base font-bold text-white">Konfirmasi</h3>
        <p x-text="message" class="mb-6 text-sm leading-relaxed text-neutral-400"></p>

        <div class="flex justify-end gap-2.5">
            <button type="button" @click="cancel()" class="rounded-xl border border-neutral-700 px-4 py-2.5 text-sm font-semibold text-neutral-200 transition hover:bg-neutral-800">
                Batal
            </button>
            <button type="button" @click="confirm()" class="rounded-xl bg-amber-400 px-4 py-2.5 text-sm font-bold text-neutral-950 transition hover:bg-amber-300">
                Ya, Lanjut
            </button>
        </div>
    </div>
</div>

<script>
    function confirmModalStore() {
        return {
            open: false,
            message: '',
            resolve: null,
            init() {
                window.appConfirm = (message) => {
                    this.message = message;
                    this.open = true;
                    return new Promise((resolve) => { this.resolve = resolve; });
                };
            },
            confirm() {
                this.open = false;
                this.resolve?.(true);
                this.resolve = null;
            },
            cancel() {
                this.open = false;
                this.resolve?.(false);
                this.resolve = null;
            },
        };
    }

    document.addEventListener('submit', (event) => {
        const form = event.target;
        const message = form.dataset.confirmMessage;
        if (!message || form.dataset.confirmed === 'true') return;

        event.preventDefault();
        window.appConfirm(message).then((ok) => {
            if (!ok) return;
            form.dataset.confirmed = 'true';
            form.requestSubmit();
        });
    });
</script>
