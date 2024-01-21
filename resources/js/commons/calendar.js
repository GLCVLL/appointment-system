import { Calendar } from 'fullcalendar';
import { formatDate, getTimePeriod } from '~resources/js/utils/';


/*** FUNCTIONS ***/

/**
 * Initialize Fullcalendar
 */
const initCalendar = () => {

    const calendar = new Calendar(calendarEl, {
        height: 400,
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
    // console.log(info);

    // Check business hours
    if (info.jsEvent.target.classList.contains('fc-non-business')) return;

    // Check passed day
    if (info.date.getTime() < new Date().getTime()) return;


    if (modalElem) {

        // Data
        const date = info.date;
        const interval = 30; // In minutes

        // Get selected date values
        const dateStr = formatDate(date, 'Y-m-d');
        const startTimeStr = formatDate(date, 'H:i');
        const endTimeStr = formatDate(new Date(date.getTime() + interval * 60000), 'H:i');

        // Set form date input
        dateInput.value = dateStr;

        // Set form business hours selects options and values
        setBusinessHoursOptions();
        startTimeInput.value = startTimeStr;
        endTimeInput.value = endTimeStr;

        // Show modal
        modalElem.classList.add('is-open');
    }
}


// Edit appointment
const editAppointment = info => {

    let eventObj = info.event;

    // Check Holidays
    if (eventObj._def.extendedProps.holiday) {
        return false;
    }

    console.log(eventObj._def.extendedProps.services);
}


/**
 * Set Busines Hours select options
 * 
 * @returns {void}
 */
const setBusinessHoursOptions = () => {

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


    // Create time array
    const { endTime, breakStart, breakEnd } = selectedBusinessHours;
    const timeArray = getTimePeriod(startTime, endTime, interval, breakStart, breakEnd);
    const options = setTimeOptions(timeArray);


    // Populate selects
    startTimeInput.innerHTML += options;
    endTimeInput.innerHTML += options;
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
                start: i + holiday.substring(4) + 'T00:00:00',
                end: i + holiday.substring(4) + 'T23:99:99',
                holiday: true,
            }
        });

        holidays.push(...yearHolidays);
    }

    return holidays;
}


/*** DATA ***/
// Get DOM Elems
const calendarEl = document.getElementById('calendar');
const modalElem = document.getElementById('create-modal');
const dateInput = document.getElementById('date');
const startTimeInput = document.getElementById('start_time');
const endTimeInput = document.getElementById('end_time');

// Vars
let appointments, businessHours, holidays;


/*** LOGIC ***/
if (calendarEl) {

    // Get calendar data
    appointments = JSON.parse(calendarEl.dataset.events);
    const businessHoursData = JSON.parse(calendarEl.dataset.openingHours);
    const holidaysData = JSON.parse(calendarEl.dataset.holidays);

    // Format data
    businessHours = formatBusinessHours(businessHoursData);
    holidays = formatHolidaysEvents(holidaysData);

    // Add Events
    document.addEventListener('DOMContentLoaded', initCalendar);
    dateInput.addEventListener('change', setBusinessHoursOptions);
}
