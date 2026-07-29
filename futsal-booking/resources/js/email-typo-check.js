/**
 * Email Typo Detection using Levenshtein Distance
 * 
 * This module provides client-side email domain typo detection
 * to help users correct common email domain mistakes.
 */

// Popular email domains whitelist
const POPULAR_DOMAINS = [
    'gmail.com',
    'yahoo.com',
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
function checkEmailTypo(email, threshold = 2) {
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
}

/**
 * Create suggestion UI element
 * @param {object} suggestion - Suggestion object from checkEmailTypo
 * @param {Function} onAccept - Callback when user accepts suggestion
 * @param {Function} onDismiss - Callback when user dismisses suggestion
 * @returns {HTMLElement} - Suggestion UI element
 */
function createSuggestionUI(suggestion, onAccept, onDismiss) {
    const container = document.createElement('div');
    container.className = 'email-typo-suggestion';
    container.style.cssText = `
        margin-top: 6px;
        padding: 8px 12px;
        background: #fffbeb;
        border: 1px solid #fde68a;
        color: #92400e;
        border-radius: 8px;
        font-size: 0.78rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        animation: slideDown 0.3s ease-out;
    `;
    
    const message = document.createElement('span');
    message.innerHTML = `💡 Apakah maksud Anda <strong style="text-decoration: underline;">${suggestion.suggested}</strong>?`;
    
    const buttons = document.createElement('div');
    buttons.style.cssText = 'display: flex; gap: 4px; flex-shrink: 0;';
    
    const acceptBtn = document.createElement('button');
    acceptBtn.textContent = 'Ganti';
    acceptBtn.type = 'button';
    acceptBtn.style.cssText = `
        background: #d97706;
        color: #fff;
        border: none;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 0.72rem;
        font-weight: 700;
        cursor: pointer;
    `;
    acceptBtn.onclick = () => {
        onAccept(suggestion.suggested);
        container.remove();
    };
    
    const dismissBtn = document.createElement('button');
    dismissBtn.textContent = 'Abaikan';
    dismissBtn.type = 'button';
    dismissBtn.style.cssText = `
        background: transparent;
        color: #92400e;
        border: none;
        padding: 3px 6px;
        font-size: 0.72rem;
        font-weight: 600;
        cursor: pointer;
    `;
    dismissBtn.onclick = () => {
        onDismiss();
        container.remove();
    };
    
    buttons.appendChild(acceptBtn);
    buttons.appendChild(dismissBtn);
    container.appendChild(message);
    container.appendChild(buttons);
    
    // Add animation keyframes if not exists
    if (!document.getElementById('email-typo-styles')) {
        const style = document.createElement('style');
        style.id = 'email-typo-styles';
        style.textContent = `
            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    return container;
}

/**
 * Setup email typo detection for an input field
 * @param {HTMLInputElement} inputElement - Email input element
 * @param {object} options - Configuration options
 */
function setupEmailTypoCheck(inputElement, options = {}) {
    const {
        threshold = 2,
        debounceMs = 500,
        onAccept = null,
        onDismiss = null
    } = options;
    
    let debounceTimer = null;
    let currentSuggestionElement = null;
    
    const checkAndShowSuggestion = () => {
        const email = inputElement.value.trim();
        
        // Remove existing suggestion
        if (currentSuggestionElement) {
            currentSuggestionElement.remove();
            currentSuggestionElement = null;
        }
        
        if (!email || !email.includes('@')) {
            return;
        }
        
        const suggestion = checkEmailTypo(email, threshold);
        
        if (suggestion) {
            currentSuggestionElement = createSuggestionUI(
                suggestion,
                (correctedEmail) => {
                    inputElement.value = correctedEmail;
                    // Trigger change event for any listeners
                    inputElement.dispatchEvent(new Event('input', { bubbles: true }));
                    inputElement.dispatchEvent(new Event('change', { bubbles: true }));
                    if (onAccept) onAccept(correctedEmail);
                },
                () => {
                    if (onDismiss) onDismiss();
                }
            );
            
            // Insert suggestion after input field
            inputElement.parentNode.insertBefore(
                currentSuggestionElement,
                inputElement.nextSibling
            );
        }
    };
    
    // Debounced check on input
    inputElement.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(checkAndShowSuggestion, debounceMs);
    });
    
    // Check on blur (when user leaves the field)
    inputElement.addEventListener('blur', () => {
        clearTimeout(debounceTimer);
        checkAndShowSuggestion();
    });
    
    // Clean up suggestion when user starts typing again
    inputElement.addEventListener('focus', () => {
        if (currentSuggestionElement) {
            currentSuggestionElement.remove();
            currentSuggestionElement = null;
        }
    });
}

// Export functions for global use
window.EmailTypoCheck = {
    checkEmailTypo,
    setupEmailTypoCheck,
    POPULAR_DOMAINS
};

// Auto-initialize if data attribute is present
document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('[data-email-typo-check]');
    inputs.forEach(input => {
        const threshold = parseInt(input.dataset.typoThreshold) || 2;
        setupEmailTypoCheck(input, { threshold });
    });
});
