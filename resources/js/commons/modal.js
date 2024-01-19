// Get modal
const modalElems = document.querySelectorAll('.app-modal');

modalElems.forEach(modalElem => {
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
});