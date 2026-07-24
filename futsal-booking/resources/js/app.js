

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

/**
 * Mencegah klik dua kali dan memberi feedback saat form sedang dikirim.
 * Tambahkan data-no-loading pada form yang tidak boleh memakai perilaku ini.
 */
document.addEventListener('submit', (event) => {
    const form = event.target;

    if (!(form instanceof HTMLFormElement) || form.dataset.noLoading !== undefined || !form.checkValidity()) {
        return;
    }

    const submitButton = event.submitter ?? form.querySelector('button[type="submit"]');

    if (!(submitButton instanceof HTMLButtonElement) || submitButton.disabled || submitButton.dataset.loading === 'true') {
        return;
    }

    submitButton.dataset.loading = 'true';
    submitButton.dataset.originalLabel = submitButton.innerHTML;
    submitButton.innerHTML = submitButton.dataset.loadingText ?? 'Memproses...';
    submitButton.classList.add('is-loading');
    submitButton.disabled = true;
    submitButton.setAttribute('aria-busy', 'true');
});

// Browser dapat mengembalikan halaman dari cache saat tombol Back ditekan.
window.addEventListener('pageshow', () => {
    document.querySelectorAll('button[data-loading="true"]').forEach((button) => {
        button.innerHTML = button.dataset.originalLabel ?? button.innerHTML;
        button.classList.remove('is-loading');
        button.disabled = false;
        button.removeAttribute('aria-busy');
        delete button.dataset.loading;
    });
});
