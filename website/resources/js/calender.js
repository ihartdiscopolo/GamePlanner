document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const addForm = document.getElementById('calendar-add-form');
    const eventModal = document.getElementById('calendar-event-modal');

    if (!calendarEl) {
        return;
    }

    const loadFullCalendar = () => {
        if (window.FullCalendar) {
            initCalendar();
            return;
        }

        const script = document.createElement('script');
        script.src = '/js/fullcalendar.min.js';   // local path
        script.onload = initCalendar;
        document.head.appendChild(script);
        script.onerror = function() {
            alert('Unable to load calendar library.');
        };
    };

    const initCalendar = () => {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: '/calender/events',
            ...(addForm ? {
                dateClick: function(info) {
                    document.getElementById('event-date').value = info.dateStr;
                    document.getElementById('event-title').focus();
                    document.getElementById('calendar-create-modal').classList.add('show');
                }
            } : {}),
            ...(eventModal ? {
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    const event = info.event;
                    document.getElementById('calendar-event-title').textContent = event.title;
                    document.getElementById('calendar-event-date').textContent = event.extendedProps.eventDate || event.startStr;
                    document.getElementById('calendar-event-time').textContent = event.extendedProps.eventTime || 'No time';
                    document.getElementById('calendar-event-assigned').textContent = event.extendedProps.assignedName || 'All members';
                    eventModal.classList.add('show');
                }
            } : {})
        });

        calendar.render();
    };

    if (addForm) {
        addForm.addEventListener('submit', function(event) {
            const title = addForm.querySelector('[name="title"]').value.trim();
            const date = addForm.querySelector('[name="eventDate"]').value.trim();

            if (!title || !date) {
                event.preventDefault();
                alert('Event name and date are required.');
            }
        });
    }

    loadFullCalendar();
});
