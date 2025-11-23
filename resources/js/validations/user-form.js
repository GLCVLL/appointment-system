import { initValidation } from '~resources/js/commons/validate';

// Form Data
const form = {
    'name': ['required', 'string', 'max:255'],
    'email': ['required', 'email', 'max:255'],
    'password': ['required', 'string', 'min:8'],
    'phone_number': ['nullable', 'string'],
};

// Error messages are loaded from form data-validation-messages attribute
initValidation(form);