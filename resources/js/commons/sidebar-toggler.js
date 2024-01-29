// Get Elements
const sidebarToggler = document.getElementById('sidebar-toggler');
const sidebarElem = document.querySelector('.app-sidebar');

if (sidebarToggler && sidebarElem) {

    //*** FUNCTIONS ***//
    const changeTogglerIcon = () => {
        if (sidebarElem.classList.contains('is-open')) {
            sidebarTogglerIcon.classList.remove('fa-bars');
            sidebarTogglerIcon.classList.add('fa-close');
        } else {
            sidebarTogglerIcon.classList.add('fa-bars');
            sidebarTogglerIcon.classList.remove('fa-close');
        }
    }


    //*** DATA ***//
    // Get icon
    const sidebarTogglerIcon = sidebarToggler.querySelector('i');


    //*** EVENTS ***//
    // Toggle logic
    sidebarToggler.addEventListener('click', () => {

        sidebarElem.classList.toggle('is-open');

        // Change toggler icon
        changeTogglerIcon();

    });


    // Click outside
    document.addEventListener('click', (e) => {
        if (!sidebarElem.contains(e.target) && !sidebarToggler.contains(e.target)) sidebarElem.classList.remove('is-open');

        // Change toggler icon
        changeTogglerIcon();
    });
}
