import { initValidation } from '~resources/js/commons/validate';

// Form Data
const form = {
    'user_id': ['required'],
    'services': ['required'],
    'date': ['required', 'date', 'after_or_equal'],
    'start_time': ['required', 'time', 'after_date_time:date'],
    'notes': ['nullable', 'string'],
};

// Error messages are loaded from form data-validation-messages attribute
initValidation(form);