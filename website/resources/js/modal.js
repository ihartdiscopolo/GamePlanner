document.addEventListener('DOMContentLoaded', function() {
    const openButtons = document.querySelectorAll('.btn-open-modal');
    const closeButtons = document.querySelectorAll('.btn-close');

    // Loop through all "Open" buttons
    openButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            
            // Get the specific modal ID from the data-target attribute
            const targetModalId = this.getAttribute('data-target');
            const modal = document.getElementById(targetModalId);
            
            if (modal) {
                modal.classList.add('show');
            }
        });
    });

    // Loop through all "Close" buttons
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Finds the closest parent element with the class '.modal' and closes it
            const modal = this.closest('.modal');
            if (modal) {
                modal.classList.remove('show');
            }
        });
    });

    // Close the modal if the user clicks outside the modal content box
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.classList.remove('show');
        }
    });

    // Close the modal if the user presses escape
    window.addEventListener('keyup', function(event) {
        if (event.key === "Escape") {
            const openModal = document.querySelector('.modal.show');
            if (openModal) {
                openModal.classList.remove('show');
            }
        }
    });
});