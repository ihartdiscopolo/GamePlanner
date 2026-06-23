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
        window.location.href = data.redirect || '/';
        return;
    }

    showAlert(form, data.message, data.type || 'danger');
});

function showAlert(form, message, type) {
    let box = form.querySelector('.alert'); // ✅ scoped to this form only
    if (!box) {
        box = document.createElement('div');
        form.prepend(box); // insert inside the form, not the shared parent
    }
    box.className = `alert alert-${type}`;
    box.textContent = message;
}