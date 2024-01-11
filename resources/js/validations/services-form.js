import { initValidation } from '~resources/js/commons/validate';

// Form Data
const form = {
    'name': ['required', 'string'],
    'duration': ['required', 'time:H:i:s'],
    'is_available': ['required', 'boolean'],
};

const errorMessages = {
    'name': {
        'required': 'The service name is required',
        'string': 'The service name must be a string',
    },
    'duration': {
        'required': 'The service is required',
        'time': 'Please insert a valid time format',
    },
    'is_available': {
        'required': 'The availability is required',
        'boolean': 'The availability must be a boolean value'
    },
};

initValidation(form, errorMessages);