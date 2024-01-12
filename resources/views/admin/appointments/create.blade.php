@extends('layouts.app')

@section('title', ' - Add Appointment')

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
                <h2 class="mb-0">Add Appointment</h2>

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
        // Get DOM Elems
        const dateInput = document.getElementById('date');
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');

        // Get Generic Data
        const dayOfWeeks = @json(config('data.day_of_week'));
        const openingHours = @json($openingHours);


        // Check if disabled
        if (!dateInput.value) {
            startTimeInput.disabled = true;
            startTimeInput.selectedIndex = 0;
            endTimeInput.disabled = true;
            endTimeInput.selectedIndex = 0;
        }

        dateInput.addEventListener('change', () => {

            // Check if disabled
            if (!dateInput.value) {
                startTimeInput.disabled = true;
                endTimeInput.disabled = true;
            } else {


                const dayOfWeek = dayOfWeeks[new Date(dateInput.value).getDay()];
                const currentOpeningHour = openingHours.find(({
                    day
                }) => day === dayOfWeek);


                if (currentOpeningHour) {

                    let timeArray = createTimeArray(currentOpeningHour.opening_time, currentOpeningHour
                        .closing_time, 30);

                    let options = '<option value = "" > -- -- </option>';
                    timeArray.forEach(time => {
                        options += `<option value="${time.value}">${time.text}</option>`;
                    });

                    startTimeInput.innerHTML = options;
                    startTimeInput.disabled = false;
                    endTimeInput.innerHTML = options;
                    endTimeInput.disabled = false;

                } else {
                    startTimeInput.selectedIndex = 0;
                    startTimeInput.disabled = true;
                    endTimeInput.selectedIndex = 0;
                    endTimeInput.disabled = true;
                }

            }
        });


        function createTimeArray(openingTime, closingTime, intervalMinutes) {

            let openingTimeArray = openingTime.split(':');
            let closingTimeArray = closingTime.split(':');
            let timeArray = [];
            let currentTime = new Date();
            currentTime.setHours(openingTimeArray[0], openingTimeArray[1], openingTimeArray[2]);

            let endTime = new Date();
            endTime.setHours(closingTimeArray[0], closingTimeArray[1], closingTimeArray[2]);

            while (currentTime <= endTime) {
                timeArray.push({
                    value: currentTime.toTimeString().substring(0, 5), // "HH:MM"
                    text: currentTime.toTimeString().substring(0, 5) // "HH:MM"
                });

                currentTime.setMinutes(currentTime.getMinutes() + intervalMinutes);
            }

            return timeArray;
        }
    </script>
@endsection
