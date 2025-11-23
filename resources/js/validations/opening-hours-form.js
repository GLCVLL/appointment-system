import { initValidation } from '~resources/js/commons/validate';

// Form Data
const form = {
    'day': ['required', 'string'],
    'opening_time': ['required', 'time:H:i:s'],
    'closing_time': ['required', 'time:H:i:s'],
    'break_start': ['time:H:i:s'],
    'break_end': ['time:H:i:s'],
};

// Error messages are loaded from form data-validation-messages attribute
initValidation(form);