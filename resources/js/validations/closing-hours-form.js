import { initValidation } from '~resources/js/commons/validate';

// Form Data
const form = {
    'date': ['required', 'date'],
    'start_time': ['required', 'time:H:i'],
    'end_time': ['required', 'time:H:i'],
};

// Error messages are loaded from form data-validation-messages attribute
initValidation(form);

// Handle dynamic time updates based on selected date
document.addEventListener('DOMContentLoaded', function() {
    const formElem = document.getElementById('validation-form');
    const dateInput = document.getElementById('date');
    const startTimeSelect = document.getElementById('start_time');
    const endTimeSelect = document.getElementById('end_time');

    if (!formElem || !dateInput || !startTimeSelect || !endTimeSelect) {
        return;
    }

    // Get opening hours from data attribute
    const openingHoursData = formElem.dataset.openingHours;
    if (!openingHoursData) {
        return;
    }

    const openingHours = JSON.parse(openingHoursData);

    // Store original selected values
    let originalStartTime = startTimeSelect.value;
    let originalEndTime = endTimeSelect.value;

    // Function to get day of week from date string (YYYY-MM-DD)
    function getDayOfWeek(dateString) {
        const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const date = new Date(dateString);
        return days[date.getDay()];
    }

    // Function to generate time array from opening and closing times
    function generateTimeArray(openingTime, closingTime) {
        const timeArray = [];
        const start = new Date(`2000-01-01T${openingTime}`);
        const end = new Date(`2000-01-01T${closingTime}`);
        const step = 30; // 30 minutes intervals

        let currentTime = new Date(start);
        while (currentTime < end) {
            const hours = currentTime.getHours().toString().padStart(2, '0');
            const minutes = currentTime.getMinutes().toString().padStart(2, '0');
            timeArray.push({
                value: `${hours}:${minutes}`,
                text: `${hours}:${minutes}`
            });
            currentTime.setMinutes(currentTime.getMinutes() + step);
        }

        return timeArray;
    }

    // Function to update time selects
    function updateTimeSelects(times) {
        // Clear existing options except the first one
        startTimeSelect.innerHTML = '<option value="">----</option>';
        endTimeSelect.innerHTML = '<option value="">----</option>';

        // Add new time options
        times.forEach(time => {
            const startOption = document.createElement('option');
            startOption.value = time.value;
            startOption.textContent = time.text;
            if (time.value === originalStartTime) {
                startOption.selected = true;
            }
            startTimeSelect.appendChild(startOption);

            const endOption = document.createElement('option');
            endOption.value = time.value;
            endOption.textContent = time.text;
            if (time.value === originalEndTime) {
                endOption.selected = true;
            }
            endTimeSelect.appendChild(endOption);
        });

        // If original values don't exist in new options, reset
        if (originalStartTime && !Array.from(startTimeSelect.options).some(opt => opt.value === originalStartTime)) {
            startTimeSelect.value = '';
            originalStartTime = '';
        }
        if (originalEndTime && !Array.from(endTimeSelect.options).some(opt => opt.value === originalEndTime)) {
            endTimeSelect.value = '';
            originalEndTime = '';
        }
    }

    // Handle date change
    dateInput.addEventListener('change', function() {
        const selectedDate = dateInput.value;

        if (!selectedDate) {
            // Reset to empty if no date selected
            updateTimeSelects([]);
            return;
        }

        // Get day of week from selected date
        const dayOfWeek = getDayOfWeek(selectedDate);

        // Find opening hours for this day
        const openingHour = openingHours.find(oh => oh.day === dayOfWeek);

        if (!openingHour) {
            // No opening hours for this day - leave selects empty
            updateTimeSelects([]);
            return;
        }

        // Generate time array from opening hours
        const times = generateTimeArray(openingHour.opening_time, openingHour.closing_time);
        updateTimeSelects(times);
    });

    // If date is already set on page load, trigger the change event
    if (dateInput.value) {
        dateInput.dispatchEvent(new Event('change'));
    }
});
