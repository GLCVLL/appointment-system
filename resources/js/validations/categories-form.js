import { initValidation } from '~resources/js/commons/validate';

// Form Data
const form = {
    'name': ['required', 'max:255'],

};

const errorMessages = {
    'name': {
        'required': 'The category name is required',
        'max': 'The category name may not be greater than 255 characters',
    }
};

initValidation(form, errorMessages);