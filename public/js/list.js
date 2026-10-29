
(() => {
    const list = document.getElementById('product-list');
    const search = document.getElementById('search');
    const clear = document.getElementById('clearSearch');
    if (!list || !search) return;

    let timer;
    let controller;

    const buildUrl = (page, term) => {
        const params = new URLSearchParams();
        if (page > 1) params.set('page', page);
        if (term) params.set('search', term);
        const qs = params.toString();
        return 'index.php' + (qs ? '?' + qs : '');
    };
    async function load(url, mode = 'push') {
        controller?.abort();
        controller = new AbortController();
        list.setAttribute('aria-busy', 'true');
        try {
            const res = await App.request(url, { signal: controller.signal });
            const html = await res.text();
            if (!res.ok) throw new Error('load failed');
            list.innerHTML = html;
            const state = list.querySelector('.list-state');
            const page = Number(state.dataset.page);
            const term = state.dataset.search;
            const finalUrl = buildUrl(page, term);
            if (mode === 'push') history.pushState(null, '', finalUrl);
            else if (mode === 'replace') history.replaceState(null, '', finalUrl);
            document.querySelector('#deleteForm [name=page]').value = page;
            document.querySelector('#deleteForm [name=search]').value = term;
            clear.hidden = term === '';
        } catch (err) {
            if (err.name !== 'AbortError') {
                App.showFlash('Could not load the products. Please try again.', 'error');
            }
        } finally {
            list.removeAttribute('aria-busy');
        }
    }
    search.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(() => load(buildUrl(1, search.value.trim()), 'replace'), 300);
    });
    search.form.addEventListener('submit', e => {
        e.preventDefault();
        clearTimeout(timer);
        load(buildUrl(1, search.value.trim()), 'push');
    });
    clear.addEventListener('click', e => {
        e.preventDefault();
        search.value = '';
        load(buildUrl(1, ''), 'push');
        search.focus();
    });
    list.addEventListener('click', e => {
        const link = e.target.closest('.pagination a');
        if (!link) return;
        e.preventDefault();
        load('index.php' + new URL(link.href).search, 'push');
    });
    window.addEventListener('popstate', () => {
        search.value = new URLSearchParams(location.search).get('search') || '';
        load('index.php' + location.search, 'none');
    });

    App.reloadList = () => load('index.php' + location.search, 'replace');
})();
