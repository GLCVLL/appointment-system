@extends('layouts.app')

@section('title', ' - Appointments')

@section('content')
    <section class="py-4 px-md-4">

        <div class="container-fluid">

            {{-- Header --}}
            <header class="d-flex justify-content-between align-items-center mb-4">

                {{-- Title --}}
                <h2 class="mb-0">Calendar</h2>

                {{-- Add Category --}}
                <a href="{{ route('admin.appointments.create') }}" class="btn btn-success btn-circle">
                    <i class="fas fa-plus fa-lg"></i>
                </a>
            </header>

            {{-- Calendar --}}
            <div id="calendar"></div>

        </div>
    </section>
@endsection

@section('scripts')
    @vite(['resources/js/commons/modal-delete.js'])

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let calendarEl = document.getElementById('calendar');
            let calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: "prev,today,next",
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
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
                eventColor: '#6571ff88',
                events: @json($events),
                dateClick: function(info) {
                    console.log(info);
                },
            });
            calendar.render();
        });
    </script>
@endsection
