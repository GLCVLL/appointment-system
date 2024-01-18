import { Calendar } from 'fullcalendar'

// Get Elem
const calendarEl = document.getElementById('calendar');

// Check if exist
if (calendarEl && calendarEl.dataset.events) {

    // On Content loaded
    document.addEventListener('DOMContentLoaded', function () {

        // parse events
        let events = JSON.parse(calendarEl.dataset.events);

        // Calendar config
        let calendar = new Calendar(calendarEl, {
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
            eventColor: '#6571ff88',
            events,
            dateClick: function (info) {
                console.log(info);
            },
        });

        calendar.render();
    });
}
