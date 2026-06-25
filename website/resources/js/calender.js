document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const addForm = document.getElementById('calendar-add-form');
    const eventModal = document.getElementById('calendar-event-modal');

    if (!calendarEl || !addForm || !eventModal) {
        return;
    }

    const loadFullCalendar = () => {
        if (window.FullCalendar) {
            initCalendar();
            return;
        }

        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css';
        document.head.appendChild(link);

        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js';
        script.onload = initCalendar;
        script.onerror = function() {
            alert('Unable to load calendar library.');
        };
        document.head.appendChild(script);
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
            dateClick: function(info) {
                document.getElementById('event-date').value = info.dateStr;
                document.getElementById('event-title').focus();
                document.getElementById('calendar-create-modal').classList.add('show');
            },
            eventClick: function(info) {
                info.jsEvent.preventDefault();
                const event = info.event;
                document.getElementById('calendar-event-title').textContent = event.title;
                document.getElementById('calendar-event-date').textContent = event.extendedProps.eventDate || event.startStr;
                document.getElementById('calendar-event-time').textContent = event.extendedProps.eventTime || 'No time';
                document.getElementById('calendar-event-assigned').textContent = event.extendedProps.assignedName || 'All members';
                eventModal.classList.add('show');
            }
        });

        calendar.render();
    };

    addForm.addEventListener('submit', function(event) {
        const title = addForm.querySelector('[name="title"]').value.trim();
        const date = addForm.querySelector('[name="eventDate"]').value.trim();

        if (!title || !date) {
            event.preventDefault();
            alert('Event name and date are required.');
        }
    });

    loadFullCalendar();
});
