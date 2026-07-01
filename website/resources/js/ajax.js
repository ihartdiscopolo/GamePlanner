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

        // Remove a parent element if the form requests it
        const selector = form.dataset.removeOnSuccess;
        if (selector) {
            const target = form.closest(selector);
            if (target) target.remove();
            return;
        }

        // Hide or show elements if the form requests it
        const toggleSelector = form.dataset.toggleOnSuccess;
        if (toggleSelector) {
            toggleSelector.split(',').forEach(sel => {
                sel = sel.trim();
                document.querySelectorAll(sel).forEach(target => {
                    target.hidden = !target.hidden;
                });
            });
        }

        showAlert(form, data.message, data.type || 'success');
        return;
    }

    showAlert(form, data.message, data.type || 'danger');
});

function showAlert(form, message, type) {
    if (!message || !message.trim()) return;

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