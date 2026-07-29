

import Alpine from 'alpinejs';
import '../css/tom-select-dark.css';
import './ketersediaan';

window.Alpine = Alpine;

// Popular email domains whitelist
const POPULAR_DOMAINS = [
    'gmail.com',
    'yahoo.com',
    'yahoo.co.id',
    'hotmail.com',
    'outlook.com',
    'live.com',
    'aol.com',
    'icloud.com',
    'protonmail.com',
    'mail.com',
    'yandex.com',
    'zoho.com',
    'fastmail.com',
    'gmx.com',
    'me.com',
    'msn.com',
    'comcast.net',
    'verizon.net',
    'att.net',
    'sbcglobal.net',
    'cox.net',
    'earthlink.net',
    'rocketmail.com',
    'ymail.com',
];

/**
 * Calculate Levenshtein distance between two strings
 * @param {string} a - First string
 * @param {string} b - Second string
 * @returns {number} - Edit distance
 */
function levenshteinDistance(a, b) {
    const matrix = [];
    
    // Initialize matrix
    for (let i = 0; i <= b.length; i++) {
        matrix[i] = [i];
    }
    
    for (let j = 0; j <= a.length; j++) {
        matrix[0][j] = j;
    }
    
    // Fill matrix
    for (let i = 1; i <= b.length; i++) {
        for (let j = 1; j <= a.length; j++) {
            if (b.charAt(i - 1) === a.charAt(j - 1)) {
                matrix[i][j] = matrix[i - 1][j - 1];
            } else {
                matrix[i][j] = Math.min(
                    matrix[i - 1][j - 1] + 1, // substitution
                    matrix[i][j - 1] + 1,     // insertion
                    matrix[i - 1][j] + 1      // deletion
                );
            }
        }
    }
    
    return matrix[b.length][a.length];
}

/**
 * Extract domain from email address
 * @param {string} email - Email address
 * @returns {string|null} - Domain or null if invalid
 */
function extractDomain(email) {
    if (!email || typeof email !== 'string') {
        return null;
    }
    
    const parts = email.split('@');
    if (parts.length !== 2) {
        return null;
    }
    
    return parts[1].toLowerCase().trim();
}

/**
 * Find closest matching domain from whitelist
 * @param {string} domain - Domain to check
 * @param {number} threshold - Maximum edit distance (default: 2)
 * @returns {object|null} - Suggestion object or null
 */
function findClosestDomain(domain, threshold = 2) {
    if (!domain) {
        return null;
    }
    
    let closest = null;
    let minDistance = Infinity;
    
    for (const popularDomain of POPULAR_DOMAINS) {
        const distance = levenshteinDistance(domain, popularDomain);
        
        if (distance < minDistance && distance <= threshold) {
            minDistance = distance;
            closest = {
                suggested: popularDomain,
                distance: distance
            };
        }
    }
    
    return closest;
}

/**
 * Check email for domain typo
 * @param {string} email - Email address to check
 * @param {number} threshold - Maximum edit distance (default: 2)
 * @returns {object|null} - Suggestion object or null
 */
window.checkEmailTypo = function(email, threshold = 2) {
    const domain = extractDomain(email);
    
    if (!domain) {
        return null;
    }
    
    // If domain is already in whitelist, no typo
    if (POPULAR_DOMAINS.includes(domain)) {
        return null;
    }
    
    const suggestion = findClosestDomain(domain, threshold);
    
    if (!suggestion) {
        return null;
    }
    
    // Extract local part of email
    const localPart = email.split('@')[0];
    
    return {
        original: email,
        suggested: `${localPart}@${suggestion.suggested}`,
        originalDomain: domain,
        suggestedDomain: suggestion.suggested,
        distance: suggestion.distance
    };
};

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
