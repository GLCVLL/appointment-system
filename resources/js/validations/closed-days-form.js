import { initValidation } from '~resources/js/commons/validate';

// Form Data
const form = {
    'date': ['required', 'date'],

};

const errorMessages = {

    'date': {
        'required': 'The date is required',
        'date': 'Insert a valide date'
    },

};

initValidation(form, errorMessages);