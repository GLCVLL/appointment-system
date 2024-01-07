// Get basic elements
const deleteForms = document.querySelectorAll('.delete-form');
const modalElem = document.querySelector('.app-modal');

// Get modal elements
const modalTitleElem = modalElem?.querySelector('.app-modal-title');
const modalBodyElem = modalElem?.querySelector('.app-modal-body');
const modalSubmitBtn = modalElem?.querySelector('.app-modal-submit');

// Logic
deleteForms.forEach(form => {

    // Get project name
    const modalName = form.dataset.modalName || '';

    form.addEventListener('submit', e => {
        e.preventDefault();

        if (modalElem) {
            // Set modal data
            modalTitleElem.innerText = 'Delete';
            modalBodyElem.innerText = `Are you you want to delete "${modalName}"?`;

            // Show modal
            modalElem.classList.add('is-open');

            // Submit on click
            modalSubmitBtn.addEventListener('click', () => { form.submit() })
        } else {
            // Direct submit
            form.submit();
        }
    });
})