// Get basic elements
const toggleMissedForms = document.querySelectorAll('.toggle-missed-form');
const modalElem = document.querySelector('.app-modal');

// Get modal elements
const modalTitleElem = modalElem?.querySelector('.app-modal-title');
const modalBodyElem = modalElem?.querySelector('.app-modal-body');

// Get translations from data attributes
const markMissedTitle = modalElem?.dataset.markMissedTitle || 'Mark as Missed';
const unmarkMissedTitle = modalElem?.dataset.unmarkMissedTitle || 'Unmark as Missed';
const markMissedConfirmTemplate = modalElem?.dataset.markMissedConfirm || 'Are you sure you want to mark ":name" as missed?';
const unmarkMissedConfirmTemplate = modalElem?.dataset.unmarkMissedConfirm || 'Are you sure you want to unmark ":name" as missed?';

// Logic
toggleMissedForms.forEach(form => {

    // Get appointment name
    const modalName = form.dataset.modalName || '';
    const isMissed = form.dataset.isMissed === 'true';

    form.addEventListener('submit', e => {
        e.preventDefault();

        if (modalElem) {
            // Get submit button (fresh each time to avoid listener issues)
            const modalSubmitBtn = modalElem.querySelector('.app-modal-submit');
            
            // Set modal data with translations based on current state
            if (isMissed) {
                modalTitleElem.innerText = unmarkMissedTitle;
                modalBodyElem.innerText = unmarkMissedConfirmTemplate.replace(':name', modalName);
            } else {
                modalTitleElem.innerText = markMissedTitle;
                modalBodyElem.innerText = markMissedConfirmTemplate.replace(':name', modalName);
            }

            // Show modal
            modalElem.classList.add('is-open');

            // Remove previous listeners by cloning and replacing
            const newSubmitBtn = modalSubmitBtn.cloneNode(true);
            modalSubmitBtn.parentNode.replaceChild(newSubmitBtn, modalSubmitBtn);

            // Submit on click
            newSubmitBtn.addEventListener('click', () => { 
                form.submit(); 
            });
        } else {
            // Direct submit
            form.submit();
        }
    });
})

