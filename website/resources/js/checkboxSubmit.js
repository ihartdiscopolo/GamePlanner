document.addEventListener('change', (e) => {
    if (e.target.type !== 'checkbox') return;
    const form = e.target.form;
    if (!form) return;
    fetch(form.action, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: new FormData(form)
    }).catch(err => console.error('Permission update failed:', err));
});