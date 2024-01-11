import { initValidation } from '~resources/js/commons/validate';

// Form Data
const form = {
    'user_id': ['required'],
    'services': ['required'],
    'date': ['required', 'date'],
    'start_time': ['required', 'time'],
    'end_time': ['required', 'time', 'after:start_time'],
    'notes': ['nullable', 'string'],
};

const errorMessages = {
    'user_id': {
        'required': 'The client is required'
    },
    'services': {
        'required': 'The service is required'
    },
    'start_time': {
        'required': 'The start Time is required',
        'time': 'Insert a valide time'
    },
    'end_time': {
        'required': 'The end Time is required',
        'time': 'Insert a valide time',
        'after': 'The end time must be greater than start time'
    },
    'date': {
        'required': 'The date is required',
        'date': 'Insert a valide date'
    },
    'notes': {
        'string': 'The notes must be a string'
    },
};

initValidation(form, errorMessages);