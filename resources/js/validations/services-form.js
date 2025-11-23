import { initValidation } from '~resources/js/commons/validate';

// Form Data
const form = {
    'name': ['required', 'string'],
    'category_id': ['required', 'integer'],
    'duration': ['required', 'time:H:i:s'],
    'price': ['required', 'decimal:0,2'],
    'is_available': ['required', 'boolean'],
};

const errorMessages = {
    'name': {
        'required': 'The service name is required',
        'string': 'The service name must be a string',
    },
    'category_id': {
        'required': 'The category is required',
        'integer': 'The category must be a valid selection',
    },
    'duration': {
        'required': 'The service is required',
        'time': 'Please insert a valid time format',
    },
    'price': {
        'required': 'The price is required',
        'decimal': 'Please insert a valid price',
    },
    'is_available': {
        'required': 'The availability is required',
        'boolean': 'The availability must be a boolean value'
    },
};

initValidation(form, errorMessages);