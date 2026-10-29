(() => {
    const form = document.getElementById('productForm');
    if (!form) return;
    const MAX_IMAGE_BYTES = 2 * 1024 * 1024;
    const IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    const fileInput = form.querySelector('#image');
    const saveBtn = form.querySelector('.btn-save');
    const clearErrors = () => form.querySelectorAll('.error').forEach(el => el.remove());
    function showFieldError(name, message) {
        const field = form.elements[name];
        const group = field && field.closest('.form-group');
        if (!group) return;
        const span = document.createElement('span');
        span.className = 'error';
        span.textContent = message;
        group.appendChild(span);
    }
    function imageError() {
        const file = fileInput.files[0];
        if (!file) return null;
        if (!IMAGE_TYPES.includes(file.type)) return 'Image must be a JPG, PNG, GIF or WEBP file.';
        if (file.size > MAX_IMAGE_BYTES) return 'Image must be at most 2 MB.';
        return null;
    }
    let preview = null;
    fileInput.addEventListener('change', () => {
        form.querySelectorAll('#image ~ .error').forEach(el => el.remove());
        if (preview) { URL.revokeObjectURL(preview.src); preview.remove(); preview = null; }
        const error = imageError();
        if (error) { showFieldError('image', error); return; }
        const file = fileInput.files[0];
        if (!file) return;
        preview = document.createElement('img');
        preview.className = 'current-image';
        preview.alt = 'Selected image preview';
        preview.src = URL.createObjectURL(file);
        fileInput.after(preview);
    });

    form.addEventListener('submit', async e => {
        e.preventDefault();
        clearErrors();

        const error = imageError();
        if (error) {
            showFieldError('image', error);
            return;
        }

        saveBtn.disabled = true;
        const label = saveBtn.textContent;
        saveBtn.textContent = 'Saving…';
        try {
            const res = await App.request(form.action, { method: 'POST', body: new FormData(form) });
            const data = await res.json();
            if (data.ok) {
                window.location.href = data.redirect; 
                return;
            }
            Object.entries(data.errors || {}).forEach(([name, msg]) => showFieldError(name, msg));
            App.showFlash(data.message || 'Something went wrong. Please try again.', 'error');
        } catch (err) {
            App.showFlash('Something went wrong. Please try again.', 'error');
        }
        saveBtn.disabled = false;
        saveBtn.textContent = label;
    });
})();
