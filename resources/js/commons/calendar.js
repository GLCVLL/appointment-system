import { Calendar } from 'fullcalendar';
import { formatDate, getTimePeriod } from '~resources/js/utils/';


/*** FUNCTIONS ***/

/**
 * Initialize Fullcalendar
 */
const initCalendar = () => {

    const calendar = new Calendar(calendarEl, {
        height: 600,
        headerToolbar: {
            left: "prev,today,next",
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        views: {
            timeGridWeek: {
                dayHeaderFormat: {
                    weekday: 'short',
                    day: '2-digit'
                }
            },
        },
        slotLabelFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },
        initialView: 'timeGridWeek',
        slotMinTime: '8:00:00',
        slotMaxTime: '19:00:00',
        allDaySlot: false,
        businessHours,
        eventSources: [{
            id: 1,
            backgroundColor: '#6571ff88',
            events: appointments
        },
        {
            id: 2,
            backgroundColor: '#ff336644',
            events: holidays
        }],
        dateClick: createAppointment,
        eventClick: editAppointment,
    });

    calendar.render();
}


// Create appointment
const createAppointment = info => {

    // Check business hours
    if (info.jsEvent.target.classList.contains('fc-non-business')) return;

    // Check passed day
    if (info.date.getTime() < new Date().getTime()) return;


    if (modalElem) {

        resetForm();

        // Data
        const date = info.date;
        resourceId = '';

        // Get selected date values
        const dateStr = formatDate(date, 'Y-m-d');
        const startTimeStr = formatDate(date, 'H:i');

        // Set form date input
        dateInput.value = dateStr;

        // Set form business hours selects options and values
        setBusinessHoursOptions();
        startTimeInput.value = startTimeStr;
        endTimeInput.selectedIndex = startTimeInput.selectedIndex + 1 === endTimeInput.options.length ?
            startTimeInput.selectedIndex :
            startTimeInput.selectedIndex + 1;

        // Set form action
        formElem.action = baseFormAction;
        methodInput.value = '';

        // Hide delete btn
        deleteBtn.classList.add('d-none');

        // Show modal
        modalElem.querySelector('.app-modal-title span').innerText = 'Create Appointment';
        modalElem.classList.add('is-open');
    }
}


// Edit appointment
const editAppointment = info => {

    // Check Holidays
    if (info.event._def.extendedProps.holiday) return false;

    if (modalElem) {

        // Data
        const currentAppointment = info.event._def.extendedProps.data;
        const servicesIds = currentAppointment.services.map(({ id }) => id);
        resourceId = currentAppointment.id;

        // Set main inputs
        userInput.value = currentAppointment.user_id;
        dateInput.value = currentAppointment.date;
        notesInput.value = currentAppointment.notes;
        servicesInputs.forEach(serviceInput => {
            serviceInput.checked = servicesIds.includes(parseInt(serviceInput.value))
        });


        // Set form business hours selects options and other inputs
        setBusinessHoursOptions(currentAppointment.id);
        startTimeInput.value = formatDate(new Date(info.event.start), 'H:i');
        endTimeInput.value = formatDate(new Date(info.event.end), 'H:i');

        // Set form action
        formElem.action = `${baseFormAction}/${currentAppointment.id}`;
        methodInput.value = 'PUT';

        // Show delete btn
        deleteBtn.classList.remove('d-none');

        // Show modal
        modalElem.querySelector('.app-modal-title span').innerText = 'Edit Appointment';
        modalElem.classList.add('is-open');
    }
}


// Delete appointment
const deleteAppointment = () => {

    modalDeletTitleElem.innerText = 'Delete Appointment';
    modalDeletBodyElem.innerText = `Are you sure you want to delete this appointment?`;
    modalDeleteElem.classList.add('is-open');

    modalDeletSubmitBtn.addEventListener('click', () => {
        formElem.action = `${baseFormAction}/${resourceId}`;
        methodInput.value = 'DELETE';

        formElem.submit();
    });

}


/**
 * Set Business hours select options
 * 
 * @param {string} currentAppointmentId - id of current appointment
 * @returns {void}
 */
const setBusinessHoursOptions = (currentAppointmentId = null) => {

    // Reset time selects
    startTimeInput.innerHTML = '<option value="" > -- -- </option>';
    endTimeInput.innerHTML = '<option value="" > -- -- </option>';


    // Get data
    const currentDate = new Date();
    const selectedDate = new Date(dateInput.value);
    const interval = 30;


    // Check holidays
    if (holidays.some(({ start }) => start.split('T')[0] === dateInput.value)) return;


    // Check passed day
    if (formatDate(selectedDate, 'Y-m-d') < formatDate(currentDate, 'Y-m-d')) return;


    // Check Business Hours
    const selectedDayOfWeek = selectedDate.getDay();
    const selectedBusinessHours = businessHours.find(businessDay => businessDay.daysOfWeek.includes(selectedDayOfWeek));
    if (!selectedBusinessHours) return;


    // Correct today business start time
    let { startTime } = selectedBusinessHours;
    if (formatDate(selectedDate, 'Y-m-d') === formatDate(currentDate, 'Y-m-d') &&
        startTime < formatDate(currentDate, 'H:i:s')) {

        // Approximate current time minutes to interval multiplier
        const roundedTime = new Date(currentDate);
        roundedTime.setMinutes(Math.ceil(currentDate.getMinutes() / interval) * interval);
        roundedTime.setSeconds(0);// reset seconds

        // Correct business start time
        startTime = formatDate(roundedTime, 'H:i:s');
    }


    // Create base time array
    const { endTime, breakStart, breakEnd } = selectedBusinessHours;
    const timeArray = getTimePeriod(startTime, endTime, interval, breakStart, breakEnd);


    // Remove taken slots
    const selectedDateAppointments = appointments.filter(({ data }) => data.date === dateInput.value);
    let startTimeArray = [...timeArray];
    let endTimeArray = [...timeArray];
    selectedDateAppointments.forEach(({ data }) => {

        if (!currentAppointmentId || data.id != currentAppointmentId) {

            // Include end time
            startTimeArray = startTimeArray.filter(({ value }) => {
                const selectedTimeFormatted = value + ':00';
                return selectedTimeFormatted < data.start_time || selectedTimeFormatted >= data.end_time;
            });

            // Include start time
            endTimeArray = endTimeArray.filter(({ value }) => {
                const selectedTimeFormatted = value + ':00';
                return selectedTimeFormatted <= data.start_time || selectedTimeFormatted > data.end_time;
            });
        }

    });

    // Create options
    const startOptions = setTimeOptions(startTimeArray);
    const endOptions = setTimeOptions(endTimeArray);


    // Populate selects
    startTimeInput.innerHTML += startOptions;
    endTimeInput.innerHTML += endOptions;
}


/**
 * Set select options based on an array of time objects
 * 
 * @param {{
 * text: string,
 * value: string,
 * }[]} timeArray 
 * @param {string} selectedTime - a time string
 * @returns {string} - return a string of options
 */
const setTimeOptions = (timeArray, selectedTime = null) => timeArray.map(time =>
    `<option ${time.value === selectedTime ? 'selected' : ''} value="${time.value}">${time.text}</option>`
).join('');


/**
 * Format Business hours to fullcalendar format
 * 
 * @param {{
 *  day: string,
 *  opening_time: string,
 *  closing_time: string,
 *  break_start: string,
 *  break_end: string,
 * }[]} data - OpeningHours object
 * @returns {{
 * daysOfWeek: number[],
 * startTime: string,
 * endTime: string,
 * breakStart: string,
 * breakEnd: string,
 * }[]} - Calendar formatted business hours
 */
const formatBusinessHours = (data) => {

    // Days of week
    const daysOfWeekList = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    return data.map(businessDay => {
        return {
            daysOfWeek: [daysOfWeekList.indexOf(businessDay.day)],
            startTime: businessDay.opening_time,
            endTime: businessDay.closing_time,
            breakStart: businessDay.break_start,
            breakEnd: businessDay.break_end,
        }
    });
}


/**
 * Format holidays to fullcalendar format considering previous and next year
 * 
 * @param {string[]} data - Array of date strings [Y-m-d]
 * @returns {{
 * title: string,
 * start: string,
 * end: string,
 * holiday: boolean,
 * }[]} - Calendar formatted holidays
*/
const formatHolidaysEvents = (data) => {

    const holidays = [];
    const curYear = new Date().getFullYear();

    // Add past, current and next year holidays
    for (let i = curYear - 1; i <= curYear + 1; i++) {

        const yearHolidays = data.map(holiday => {
            return {
                title: 'Holiday',
                start: i + holiday.substring(4) + 'T00:00:01',
                end: i + holiday.substring(4) + 'T23:59:59',
                holiday: true,
            }
        });

        holidays.push(...yearHolidays);
    }
    console.log(holidays);
    return holidays;
}

/**
 * Reset all modal inputs
 */
const resetForm = () => {
    userInput.selectedIndex = 0;
    servicesInputs.forEach(serviceInput => { serviceInput.checked = false });
    dateInput.value = '';
    startTimeInput.selectedIndex = 0;
    endTimeInput.selectedIndex = 0;
    notesInput.value = '';
}


/*** DATA ***/
// Get Calenda Elem
const calendarEl = document.getElementById('calendar');

// Get modal form Elems
const modalElem = document.getElementById('modal-form');
const modalHasError = document.querySelector('.app-modal.has-error');
const deleteBtn = document.getElementById('delete-btn');

// Get form Elems
const formElem = document.getElementById('validation-form');
const methodInput = document.getElementById('method');
const userInput = document.getElementById('user_id');
const servicesInputs = document.querySelectorAll('[id^="service-"]');
const dateInput = document.getElementById('date');
const startTimeInput = document.getElementById('start_time');
const endTimeInput = document.getElementById('end_time');
const notesInput = document.getElementById('notes');

// Get modal delete Elems
const modalDeleteElem = document.querySelector('.app-modal:not([id])');
const modalDeletTitleElem = modalDeleteElem.querySelector('.app-modal-title');
const modalDeletBodyElem = modalDeleteElem.querySelector('.app-modal-body');
const modalDeletSubmitBtn = modalDeleteElem.querySelector('.app-modal-submit');

// Vars
const baseFormAction = formElem.action;
let resourceId = formElem.dataset.resourceId;
let appointments, businessHours, holidays;


/*** LOGIC ***/
if (calendarEl) {

    // Get calendar data
    appointments = JSON.parse(calendarEl.dataset.events);
    const businessHoursData = JSON.parse(calendarEl.dataset.openingHours);
    const holidaysData = JSON.parse(calendarEl.dataset.holidays);
    console.log(holidaysData);

    // Format data
    businessHours = formatBusinessHours(businessHoursData);
    holidays = formatHolidaysEvents(holidaysData);


    // Add Events
    document.addEventListener('DOMContentLoaded', initCalendar);
    dateInput.addEventListener('change', setBusinessHoursOptions);
    deleteBtn.addEventListener('click', deleteAppointment);


    // Show modal if there are errors
    if (modalElem && (modalElem.querySelectorAll('.error-message').length || modalHasError)) {

        let modalTitle = 'Create Appointment';

        // Get old values
        const oldStartTime = startTimeInput.value;
        const oldEndTime = endTimeInput.value;

        // Create
        if (!resourceId) {

            // Set form business hours selects options and values
            setBusinessHoursOptions();
            startTimeInput.value = oldStartTime;
            endTimeInput.value = oldEndTime;

            // Hide delete btn
            deleteBtn.classList.add('d-none');

        }
        // Edit
        else {

            // Set form business hours selects options and other inputs
            setBusinessHoursOptions(resourceId);
            startTimeInput.value = oldStartTime;
            endTimeInput.value = oldEndTime;

            // Set form action
            formElem.action = `${baseFormAction}/${resourceId}`;
            methodInput.value = 'PUT';

            modalTitle = 'Edit Appointment';

            // Show delete btn
            deleteBtn.classList.remove('d-none');
        }

        // Show modal
        modalElem.querySelector('.app-modal-title span').innerText = modalTitle;
        modalElem.classList.add('is-open');
    }
}
