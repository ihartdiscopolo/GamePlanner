document.addEventListener('DOMContentLoaded', () => {
    const dropdownHeads = document.querySelectorAll('.drop-head');
    dropdownHeads.forEach(head => {
        head.addEventListener('click', () => {
            const parentDropdown = head.closest('.dropdown');
            
            const body = parentDropdown.querySelector('.drop-body');
            const arrow = parentDropdown.querySelector('.drop-arrow');
            
            const isOpen = body.classList.toggle('open');
            arrow.classList.toggle('open', isOpen);
        });
    });
});