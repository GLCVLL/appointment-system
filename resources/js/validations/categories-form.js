import { initValidation } from '~resources/js/commons/validate';

// Form Data
const form = {
    'name': ['required', 'max:255'],
};

// Error messages are loaded from form data-validation-messages attribute
initValidation(form);