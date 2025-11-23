// Get basic elements
const deleteForms = document.querySelectorAll('.delete-form');
const modalElem = document.querySelector('.app-modal');

// Get modal elements
const modalTitleElem = modalElem?.querySelector('.app-modal-title');
const modalBodyElem = modalElem?.querySelector('.app-modal-body');
const modalSubmitBtn = modalElem?.querySelector('.app-modal-submit');

// Get translations from data attributes
const deleteTitle = modalElem?.dataset.deleteTitle || 'Delete';
const deleteConfirmTemplate = modalElem?.dataset.deleteConfirm || 'Are you sure you want to delete ":name"?';

// Logic
deleteForms.forEach(form => {

    // Get project name
    const modalName = form.dataset.modalName || '';

    form.addEventListener('submit', e => {
        e.preventDefault();

        if (modalElem) {
            // Set modal data with translations
            modalTitleElem.innerText = deleteTitle;
            modalBodyElem.innerText = deleteConfirmTemplate.replace(':name', modalName);

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