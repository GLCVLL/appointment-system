import { initValidation } from '~resources/js/commons/validate';

// Form Data
const form = {
    'day': ['required', 'string'],
    'opening_time': ['required', 'time:H:i:s'],
    'closing_time': ['required', 'time:H:i:s'],
    'break_start': ['time:H:i:s'],
    'break_end': ['time:H:i:s'],
};

const errorMessages = {
    'day': {
        'required': 'The day is required',
        'string': 'The day must be a string'
    },
    'opening_time': {
        'required': 'The opening time is required',
        'time': 'Insert a valide time'
    },
    'closing_time': {
        'required': 'The closing time is required',
        'time': 'Insert a valide time'
    },
    'break_start': {
        'time': 'Insert a valide time'
    },
    'break_end': {
        'time': 'Insert a valide time'
    },

};

initValidation(form, errorMessages);