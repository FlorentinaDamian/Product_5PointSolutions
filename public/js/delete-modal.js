(() => {
    const modal = document.getElementById('deleteModal');
    const cancel = document.getElementById('modalCancel');
    const form = document.getElementById('deleteForm');
    if (!modal || !cancel || !form) return;

    let opener = null;
    const close = () => {
        modal.hidden = true;
        if (opener && document.contains(opener)) opener.focus();
        opener = null;
    };
    document.addEventListener('click', e => {
        const btn = e.target.closest('.js-delete');
        if (!btn) return;
        opener = btn;
        document.getElementById('modalId').value = btn.dataset.id;
        document.getElementById('modalName').textContent = btn.dataset.name;
        modal.hidden = false;
        cancel.focus();
    });
    cancel.addEventListener('click', close);
    modal.addEventListener('click', e => { if (e.target === modal) close(); });
    document.addEventListener('keydown', e => {
        if (modal.hidden) return;
        if (e.key === 'Escape') {
            close();
        } else if (e.key === 'Tab') {
            const items = [...modal.querySelectorAll('button:not([disabled])')];
            const first = items[0];
            const last = items[items.length - 1];
            if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
            else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
        }
    });
    form.addEventListener('submit', async e => {
        e.preventDefault();
        const submit = form.querySelector('button[type=submit]');
        submit.disabled = true;
        try {
            const res = await App.request(form.action, { method: 'POST', body: new FormData(form) });
            const data = await res.json();
            opener = null; 
            close();
            App.showFlash(data.message, data.ok ? 'success' : 'error');
            if (App.reloadList) await App.reloadList();
        } catch (err) {
            close();
            App.showFlash('Something went wrong. Please try again.', 'error');
        } finally {
            submit.disabled = false;
        }
    });
})();
