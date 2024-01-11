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
}


// Get Form elements
const formElem = document.getElementById('validation-form');
let validationElements = [];
for (const key in form) {
    validationElements.push(...Array.from(document.querySelectorAll(`[name^="${key}"]`)));
}

let formErrors = {};


// Logic
formElem.addEventListener('submit', e => {
    e.preventDefault();

    // Validation
    validate();

    // Check Errors
    if (Object.keys(formErrors).length) {

        // Show errors
        for (const key in formErrors) {

            const elem = document.getElementById(key);
            const errorElem = document.getElementById(`${key}-error`);

            elem.classList.add('is-invalid');
            errorElem.innerText = formErrors[key];
        }

    } else {
        // Submit
        e.currentTarget.submit();
    }
});

const validate = () => {

    // Reset
    formErrors = {};


    for (const key in form) {

        const elem = document.getElementById(key);
        const errorElem = document.getElementById(`${key}-error`);

        // Reset
        elem.classList.remove('is-invalid');
        errorElem.innerText = '';


        // Validation
        form[key].forEach(ruleStr => {

            const ruleElems = ruleStr.split(':');
            const rule = ruleElems[0];
            const param = ruleElems.length > 1 ? ruleElems[1] : '';

            if (!formErrors[key]) {

                switch (rule) {

                    case 'required':
                        if (elem.type === undefined) {
                            const isChecked = elem.querySelector(':checked');
                            if (!isChecked) formErrors[key] = errorMessages[key]['required'];
                        } else {
                            if (!elem.value) formErrors[key] = errorMessages[key]['required'];
                        }
                        break;

                    case 'string':
                        if (typeof elem.value !== 'string') formErrors[key] = errorMessages[key]['string'];
                        break;

                    case 'date':
                        if (!(/^\d{4}-\d{2}-\d{2}$/.test(elem.value))) formErrors[key] = errorMessages[key]['date'];
                        break;

                    case 'time':
                        if (!(/^([01]\d|2[0-3]):[0-5]\d$/.test(elem.value))) formErrors[key] = errorMessages[key]['time'];
                        break;

                    case 'after':
                        const otherElem = document.getElementById(param);
                        if (otherElem.value >= elem.value) formErrors[key] = errorMessages[key]['after'];
                        break;

                    default:
                        break;
                }
            }

        });

    }

}