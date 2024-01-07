// Get modal
const modalElem = document.querySelector('.app-modal');

if (modalElem) {
    // Get modal elements
    const modalContentElem = modalElem.querySelector('.app-modal-content');
    const modalCloseElems = modalElem.querySelectorAll('[data-close]');

    // Check click outside
    modalElem.addEventListener('click', e => {
        if (!modalContentElem.contains(e.target)) modalElem.classList.remove('is-open');
    });

    // Check close button
    modalCloseElems.forEach(el => {
        el.addEventListener('click', () => {
            modalElem.classList.remove('is-open');
        });
    });
}