
window.App = (() => {
    let flashTimer;
    function request(url, options = {}) {
        return fetch(url, {
            ...options,
            headers: { 'X-Requested-With': 'fetch', ...(options.headers || {}) },
        });
    }
    function showFlash(message, type = 'success') {
        const el = document.getElementById('flash');
        if (!el) return;
        el.textContent = message;
        el.className = 'flash ' + type;
        el.hidden = false;
        clearTimeout(flashTimer);
        if (type === 'success') {
            flashTimer = setTimeout(() => { el.hidden = true; }, 5000);
        }
    }
    return { request, showFlash };
})();
