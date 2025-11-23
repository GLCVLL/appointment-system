import { initValidation } from '~resources/js/commons/validate';

// Form Data
const form = {
    'date': ['required', 'date'],
};

// Error messages are loaded from form data-validation-messages attribute
initValidation(form);