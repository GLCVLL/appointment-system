import { initValidation } from '~resources/js/commons/validate';

// Form Data
const form = {
    'name': ['required', 'string'],
    'category_id': ['required', 'integer'],
    'duration': ['required', 'time:H:i:s'],
    'price': ['required', 'decimal:0,2'],
    'is_available': ['required', 'boolean'],
};

// Error messages are loaded from form data-validation-messages attribute
initValidation(form);
