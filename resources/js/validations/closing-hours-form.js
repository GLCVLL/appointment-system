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
    const dateInput = document.getElementById('date');
    const startTimeSelect = document.getElementById('start_time');
    const endTimeSelect = document.getElementById('end_time');

    if (!dateInput || !startTimeSelect || !endTimeSelect) {
        return;
    }

    // Store original selected values
    let originalStartTime = startTimeSelect.value;
    let originalEndTime = endTimeSelect.value;

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
            // Reset to default times if no date selected
            updateTimeSelects([]);
            return;
        }

        // Show loading state
        startTimeSelect.disabled = true;
        endTimeSelect.disabled = true;

        // Get the times URL from form data attribute
        const formElem = document.getElementById('validation-form');
        const timesUrl = formElem?.dataset.timesUrl || '/admin/closing-hours/times/date';
        
        // Fetch available times for the selected date
        fetch(`${timesUrl}?date=${selectedDate}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(times => {
                if (times.length === 0) {
                    // No opening hours for this day
                    startTimeSelect.innerHTML = '<option value="">----</option>';
                    endTimeSelect.innerHTML = '<option value="">----</option>';
                    alert('No opening hours found for this day. Please select another date.');
                } else {
                    updateTimeSelects(times);
                }
            })
            .catch(error => {
                console.error('Error fetching times:', error);
                alert('Error loading available times. Please try again.');
            })
            .finally(() => {
                startTimeSelect.disabled = false;
                endTimeSelect.disabled = false;
            });
    });

    // If date is already set on page load, trigger the change event
    if (dateInput.value) {
        dateInput.dispatchEvent(new Event('change'));
    }
});
