
export const initValidation = (form, errorMessages) => {
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

                const paramCharPos = ruleStr.indexOf(':');

                let rule = '';
                let param = '';

                if (paramCharPos > 0) {
                    rule = ruleStr.substring(0, paramCharPos);
                    param = ruleStr.substring(paramCharPos + 1);
                } else {
                    rule = ruleStr;
                }


                if (!formErrors[key]) {

                    switch (rule) {

                        case 'required':
                            // If type is undefined than is a div containing checkboxes
                            if (elem.type === undefined) {
                                const isChecked = elem.querySelector(':checked');
                                if (!isChecked) formErrors[key] = errorMessages[key][rule];
                            } else {
                                if (!elem.value) formErrors[key] = errorMessages[key][rule];
                            }
                            break;

                        case 'string':
                            if (typeof elem.value !== 'string') formErrors[key] = errorMessages[key][rule];
                            break;

                        case 'max':
                            if (elem.value.length > parseInt(param)) formErrors[key] = errorMessages[key][rule];
                            break;

                        case 'boolean':
                            const validBooleanValues = [true, false, 1, 0, "1", "0"];
                            if (!validBooleanValues.includes(elem.value)) formErrors[key] = errorMessages[key][rule];
                            break;

                        // Format Y-m-d
                        case 'date':
                            if (!(/^\d{4}-\d{2}-\d{2}$/.test(elem.value))) formErrors[key] = errorMessages[key][rule];
                            break;

                        // Format H:i:s
                        case 'time':

                            if (param === 'H:i:s') {
                                if (!(/^(?:[01]\d|2[0-3]):[0-5]\d:[0-5]\d$/.test(elem.value))) formErrors[key] = errorMessages[key]['time'];
                            } else {
                                if (!(/^([01]\d|2[0-3]):[0-5]\d$/.test(elem.value))) formErrors[key] = errorMessages[key][rule];
                            }
                            break;

                        case 'after':
                            const otherElem = document.getElementById(param);
                            if (otherElem.value >= elem.value) formErrors[key] = errorMessages[key][rule];
                            break;

                        case 'after_or_equal':
                            if (isAfterOrEqual(elem.value)) formErrors[key] = errorMessages[key][rule];
                            break;

                        case 'after_date_time':
                            const dateElem = document.getElementById(param);
                            if (!isAfterDateTime(elem.value, new Date(dateElem.value))) formErrors[key] = errorMessages[key][rule];
                            break;

                        default:
                            break;
                    }
                }

            });

        }

    }

    // Validation Rules
    const isAfterOrEqual = (value) => {

        // Get current date
        const currentDate = new Date();

        // Create current date string [Y-m-d]
        const currentYear = currentDate.getFullYear();
        const currentMonth = currentDate.getMonth() + 1;
        const currentDay = currentDate.getDate();
        const currentDateStr = `${currentYear}-${currentMonth.toString().padStart(2, '0')}-${currentDay.toString().padStart(2, '0')}`;

        if (value < currentDateStr) return false;
        return true;
    }

    const isAfterDateTime = (value, date) => {

        // Get current date
        const currentDate = new Date();

        // Create current date string [Y-m-d]
        const currentYear = currentDate.getFullYear();
        const currentMonth = currentDate.getMonth() + 1;
        const currentDay = currentDate.getDate();
        const currentDateStr = `${currentYear}-${currentMonth.toString().padStart(2, '0')}-${currentDay.toString().padStart(2, '0')}`;

        // Create current time string [H:i]
        const currentHours = currentDate.getHours();
        const currentMinutes = currentDate.getMinutes();
        const currentTimeStr = `${currentHours.toString().padStart(2, '0')}:${currentMinutes.toString().padStart(2, '0')}`;

        // Create date string [Y-m-d]
        const dateYear = date.getFullYear();
        const dateMonth = date.getMonth() + 1;
        const dateDay = date.getDate();
        const dateStr = `${dateYear}-${dateMonth.toString().padStart(2, '0')}-${dateDay.toString().padStart(2, '0')}`;

        // Check date and time
        if (dateStr === currentDateStr && value < currentTimeStr) return false;
        return true;
    }
}