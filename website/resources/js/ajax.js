document.addEventListener('submit', async (e) => {
    const form = e.target;

    if (form.tagName !== 'FORM' || form.hasAttribute('data-no-ajax')) return;

    e.preventDefault();

    let data;
    try {
        const res = await fetch(form.action, {
            method: form.method || 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: new FormData(form)
        });
        data = await res.json();
    } catch (err) {
        console.error('Form submission failed:', err);
        showAlert(form, 'Something went wrong. Please try again.', 'danger');
        return;
    }

    if (data.success) {
        if (data.redirect) {
            window.location.href = data.redirect;
            return;
        }
        // otherwise just show the success alert and stay on the page
        showAlert(form, data.message, data.type || 'success');
        return;
    }

    showAlert(form, data.message, data.type || 'danger');
});

function showAlert(form, message, type) {
    let box = form.querySelector('.alert');
    if (!box) {
        box = document.createElement('div');
        form.prepend(box);
    }

    box.className = `alert alert-${type}`;
    box.textContent = message;

    const closeBtn = document.createElement('button');
    closeBtn.className = 'alert-close';
    closeBtn.setAttribute('aria-label', 'Close');
    closeBtn.innerHTML = '&times;';
    closeBtn.addEventListener('click', () => box.remove());

    box.appendChild(closeBtn);
}