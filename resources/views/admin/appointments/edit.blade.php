@extends('layouts.app')

@section('title', ' - Edit Appointments')

@section('content')
    <section class="container-fluid py-4">

        {{-- Header --}}
        <header class="mb-3">

            {{-- Title --}}
            <div class="d-flex align-items-center gap-3">

                {{-- Back --}}
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-light">
                    <i class="fa-solid fa-chevron-left fa-xl"></i>
                </a>

                {{-- Title --}}
                <h2 class="mb-0">Edit Appointments</h2>

            </div>

        </header>

        <hr class="mb-4">
        {{-- Form --}}
        @include('includes.appointment.form')

    </section>
@endsection

@section('scripts')
    @vite(['resources/js/validations/appointment-form'])

    <script>
        // FUNCTIONS
        const setWorkingHours = () => {

            const currentDate = new Date(new Date().setHours(0, 0, 0, 0));

            // Check Public Holidays
            const selectedDate = new Date(new Date(dateInput.value).setHours(0, 0, 0, 0));
            let isPublicHoliday = false;

            closedDays.forEach(closedDay => {

                const closedDayDate = new Date(closedDay.date);

                if (selectedDate.getMonth() === closedDayDate.getMonth() &&
                    selectedDate.getDate() === closedDayDate.getDate()) {
                    isPublicHoliday = true;
                }
            });

            // Check if disabled
            if (!dateInput.value || selectedDate.getTime() < currentDate.getTime() || isPublicHoliday) {
                startTimeInput.disabled = true;
                startTimeInput.selectedIndex = 0;
                endTimeInput.disabled = true;
                endTimeInput.selectedIndex = 0;

            } else {


                const dayOfWeek = dayOfWeeks[new Date(dateInput.value).getDay()];
                const currentOpeningHour = openingHours.find(({
                    day
                }) => day === dayOfWeek);


                if (currentOpeningHour) {

                    let timeArray = createTimeArray(currentOpeningHour.opening_time, currentOpeningHour
                        .closing_time, 30, currentOpeningHour.break_start, currentOpeningHour.break_end);

                    // Populate start time input
                    let options = '<option value = "" > -- -- </option>';
                    timeArray.forEach(time => {
                        const isSelected = time.value === currentStartTime;
                        options +=
                            `<option ${isSelected ? 'selected': ''} value="${time.value}">${time.text}</option>`;
                    });
                    startTimeInput.innerHTML = options;
                    startTimeInput.disabled = false;


                    // Populate end time input
                    options = '<option value = "" > -- -- </option>';
                    timeArray.forEach(time => {
                        const isSelected = time.value === currentEndTime;
                        options +=
                            `<option ${isSelected ? 'selected': ''} value="${time.value}">${time.text}</option>`;
                    });
                    endTimeInput.innerHTML = options;
                    endTimeInput.disabled = false;

                } else {
                    startTimeInput.selectedIndex = 0;
                    startTimeInput.disabled = true;
                    endTimeInput.selectedIndex = 0;
                    endTimeInput.disabled = true;
                }

            }
        }

        const createTimeArray = (openingTime, closingTime, intervalMinutes, breakStart = null, breakEnd = null) => {

            let openingTimeArray = openingTime.split(':');
            let closingTimeArray = closingTime.split(':');
            let timeArray = [];

            let currentTime = new Date();
            currentTime.setHours(openingTimeArray[0], openingTimeArray[1], openingTimeArray[2]);

            let endTime = new Date();
            endTime.setHours(closingTimeArray[0], closingTimeArray[1], closingTimeArray[2]);


            // Set Break time
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

            while (currentTime <= endTime) {
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

        // INIT
        // Get DOM Elems
        const dateInput = document.getElementById('date');
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');

        // Get Generic Data
        const dayOfWeeks = @json(config('data.day_of_week'));
        const closedDays = @json($closedDays);
        const openingHours = @json($openingHours);

        // Get Current Times
        const currentStartTime = @json(old('start_time', $appointment->getDate('start_time', 'H:i')));
        const currentEndTime = @json(old('end_time', $appointment->getDate('end_time', 'H:i')));

        // LOGIC
        setWorkingHours();
        dateInput.addEventListener('change', setWorkingHours);
    </script>
@endsection
