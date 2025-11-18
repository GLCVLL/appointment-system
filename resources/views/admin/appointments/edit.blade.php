@extends('layouts.app')

@section('title', ' - Edit Appointments')

@section('content')
<section class="p-4">

    <div class="container-fluid">

        {{-- Header --}}
        <header class="mb-4">

            {{-- Title --}}
            <div class="d-flex align-items-center gap-3">

                {{-- Back --}}
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline-light">
                    <i class="fa-solid fa-chevron-left fa-xl"></i>
                </a>

                {{-- Title --}}
                <h2 class="mb-0">Edit Appointments</h2>

            </div>

        </header>

        {{-- Form --}}
        @include('includes.appointment.form')

    </div>
</section>
@endsection

@section('scripts')
@vite(['resources/js/validations/appointment-form'])

<script>
    // FUNCTIONS
    const setWorkingHours = () => {

        // Get data
        const currentDate = new Date(new Date().setHours(0, 0, 0, 0));
        const selectedDate = new Date(new Date(dateInput.value).setHours(0, 0, 0, 0));
        const dayOfWeek = dayOfWeeks[new Date(dateInput.value).getDay()];
        const currentOpeningHours = openingHours.find(({
            day
        }) => day === dayOfWeek);


        /*** CHECK PUBLIC HOLIDAYS ***/
        let isPublicHoliday = isPubliHoliday(selectedDate, closedDays);


        /*** CHECK PASSED DAYS ***/
        let isDatePassed = selectedDate.getTime() < currentDate.getTime();


        // Disable Time inputs
        if (!dateInput.value || isDatePassed || isPublicHoliday || !currentOpeningHours) {
            startTimeInput.disabled = true;
            startTimeInput.selectedIndex = 0;
            endTimeInput.disabled = true;
            endTimeInput.value = '';
            calculateEndTime();

        } else {

            // Time Array Data
            let openingTime = currentOpeningHours.opening_time;
            let closingTime = currentOpeningHours.closing_time;
            let breakStart = currentOpeningHours.break_start;
            let breakEnd = currentOpeningHours.break_end;
            let interval = 30;

            /*** CHECK TODAY DATE AVAILABLE TIMES ***/
            // Correct opening time with today available time
            if (selectedDate.getTime() === currentDate.getTime() && openingTime < getTimeString(new Date())) {

                const now = new Date();

                const minutes = now.getMinutes();
                let roundedHours = now.getHours();
                let roundedMinutes = Math.ceil((minutes / interval)) * interval;
                if (roundedMinutes >= 60) {
                    roundedHours = (roundedHours + 1) % 24;
                    roundedMinutes = 0;
                }

                openingTime =
                    `${String(roundedHours).padStart(2, '0')}:${String(roundedMinutes).padStart(2, '0')}:00`;

            }

            // Create Time Array
            let timeArray = createTimeArray(openingTime, closingTime, interval, breakStart, breakEnd);



            /*** CHECK FREE SLOTS ***/
            // Get appointments of selected date 
            const selectedDateAppointments = appointments.filter(({
                date
            }) => date === dateInput.value);

            // Remove times already taken by other appointments
            selectedDateAppointments.forEach(appointment => {

                // Check if is the current appointment
                if (appointment.id !== currentAppointmentId) {

                    timeArray = timeArray.filter(({
                        value
                    }) => {
                        const timeFormatted = value + ':00';
                        return timeFormatted < appointment.start_time || timeFormatted >=
                            appointment
                            .end_time
                    });
                }

            });


            // Populate start time input
            let options = '<option value="" > -- -- </option>';
            timeArray.forEach(time => {
                const isSelected = time.value === currentStartTime;
                options +=
                    `<option ${isSelected ? 'selected': ''} value="${time.value}">${time.text}</option>`;
            });
            startTimeInput.innerHTML = options;
            startTimeInput.disabled = false;

            // End time is calculated dynamically, keep it disabled
            endTimeInput.disabled = true;
            calculateEndTime();

        }
    }

    const calculateEndTime = () => {
        // Reset end time
        endTimeInput.innerHTML = '<option value="">----</option>';
        endTimeInput.value = '';

        // Check if start time and services are selected
        if (!startTimeInput.value) {
            return;
        }

        const selectedServices = Array.from(document.querySelectorAll('input[name="services[]"]:checked'))
            .map(checkbox => parseInt(checkbox.value));

        if (selectedServices.length === 0) {
            return;
        }

        // Calculate total duration in minutes
        let totalMinutes = 0;
        selectedServices.forEach(serviceId => {
            const service = servicesData.find(s => s.id === serviceId);
            if (service && service.duration) {
                // Convert duration from "HH:MM:SS" to minutes
                const [hours, minutes, seconds] = service.duration.split(':').map(Number);
                totalMinutes += hours * 60 + minutes;
            }
        });

        if (totalMinutes === 0) {
            return;
        }

        // Calculate end time
        const [startHours, startMinutes] = startTimeInput.value.split(':').map(Number);
        const startDate = new Date();
        startDate.setHours(startHours, startMinutes, 0, 0);

        const endDate = new Date(startDate.getTime() + totalMinutes * 60000);
        const endTimeString = `${String(endDate.getHours()).padStart(2, '0')}:${String(endDate.getMinutes()).padStart(2, '0')}`;

        // Add option to select and set value (for display only, field is disabled)
        endTimeInput.innerHTML = `<option value="${endTimeString}" selected>${endTimeString}</option>`;
        endTimeInput.value = endTimeString;
    }


    const createTimeArray = (openingTime, closingTime, intervalMinutes, breakStart = null, breakEnd = null) => {

        // Data
        let openingTimeArray = openingTime.split(':');
        let closingTimeArray = closingTime.split(':');
        let timeArray = [];

        // Current Time Setup
        let currentTime = new Date();
        currentTime.setHours(openingTimeArray[0], openingTimeArray[1], openingTimeArray[2]);

        // End Time Setup
        let endTime = new Date();
        endTime.setHours(closingTimeArray[0], closingTimeArray[1], closingTimeArray[2]);


        // Break Time Setup
        let breakStartTime = null;
        let breakEndTime = null;
        if (breakStart && breakEnd) {

            let breakStartArray = breakStart.split(':');
            let breakEndArray = breakEnd.split(':');

            breakStartTime = new Date();
            breakStartTime.setHours(breakStartArray[0], breakStartArray[1], breakStartArray[2]);

            breakEndTime = new Date();
            breakEndTime.setHours(breakEndArray[0], breakEndArray[1], breakEndArray[2]);
        }

        // Create time intervals
        while (currentTime <= endTime) {

            // Break Time Check
            if (breakStartTime && breakEndTime && currentTime < breakEndTime && currentTime > breakStartTime) {
                currentTime = breakEndTime;
            } else {

                timeArray.push({
                    value: currentTime.toTimeString().substring(0, 5), // "HH:MM"
                    text: currentTime.toTimeString().substring(0, 5) // "HH:MM"
                });

                currentTime.setMinutes(currentTime.getMinutes() + intervalMinutes);
            }
        }

        return timeArray;
    }

    const getTimeString = (date) => {

        // Format time members
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        const seconds = date.getSeconds().toString().padStart(2, '0');

        // return formatted time
        return `${hours}:${minutes}:${seconds}`;
    }

    const isPubliHoliday = (date, holidays) => {

        // Flag
        let found = false;

        holidays.forEach(holiday => {

            const day = new Date(holiday);

            // Check only Month and Day
            if (date.getMonth() === day.getMonth() &&
                date.getDate() === day.getDate()) {
                found = true;
            }
        });

        return found;
    }


    // INIT
    // Get DOM Elems
    const dateInput = document.getElementById('date');
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');

    // Get Generic Data
    const appointments = @json($appointments);
    const dayOfWeeks = @json(config('data.day_of_week'));
    const closedDays = @json($closedDays);
    const openingHours = @json($openingHours);
    const servicesData = @json($services->map->only(['id', 'duration'])->values());

    // Get Current Times
    const currentAppointmentId = @json($appointment->id);
    const currentStartTime = @json(old('start_time', $appointment->getDate('start_time', 'H:i')));

    // LOGIC
    setWorkingHours();
    dateInput.addEventListener('change', setWorkingHours);
    startTimeInput.addEventListener('change', calculateEndTime);

    // Listen to service checkboxes changes
    document.querySelectorAll('input[name="services[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', calculateEndTime);
    });
</script>
@endsection