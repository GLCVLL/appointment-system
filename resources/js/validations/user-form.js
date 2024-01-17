import { initValidation } from '~resources/js/commons/validate';

// Form Data
const form = {
    'name': ['required', 'string', 'max:255'],
    'email': ['required', 'email', 'max:255'],
    'password': ['required', 'string', 'min:8'],
    'phone_number': ['nullable', 'string'],
};

const errorMessages = {
    'name': {
        'required': 'The name is required',
        'string': 'The name must be a string',
        'max': 'The name may not be greater than 255 characters',
    },
    'email': {
        'required': 'The email is required',
        'email': 'Please insert a valid email',
        'max': 'The email may not be greater than 255 characters',
    },
    'password': {
        'required': 'The password is required',
        'string': 'The password must be a string',
        'min': 'The password must be at least 8 characters',
    },
    'phone_number': {
        'string': 'The phone number must be a correct number',
    },
};

initValidation(form, errorMessages);