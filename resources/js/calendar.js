import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';

/**
 * Powers resources/views/livewire/member/calendar-manager.blade.php.
 *
 * FullCalendar renders inside a wire:ignore'd element so Livewire never
 * touches its DOM; all data flows through the Livewire component's PHP
 * methods via Alpine's $wire, and the component dispatches a
 * "calendar-refresh" browser event after any create/update/delete so this
 * instance knows to refetch.
 */
function calendarApp() {
    return {
        calendar: null,
        title: '',
        viewType: 'dayGridMonth',
        hiddenCalendars: [],
        miniCursor: new Date(new Date().getFullYear(), new Date().getMonth(), 1),
        miniSelected: new Date(),
        miniWeeks: [],

        init($wire) {
            try {
                this.hiddenCalendars = JSON.parse(localStorage.getItem('wla-calendar-hidden-calendars') || '[]');
            } catch (e) {
                this.hiddenCalendars = [];
            }

            this.buildMiniMonth();

            this.calendar = new Calendar(this.$refs.calendarEl, {
                plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
                initialView: 'dayGridMonth',
                headerToolbar: false,
                height: 'auto',
                firstDay: 0,
                nowIndicator: true,
                selectable: true,
                selectMirror: true,
                editable: true,
                dayMaxEvents: true,
                navLinks: false,
                eventTimeFormat: { hour: 'numeric', minute: '2-digit', meridiem: 'short' },

                events: (info, successCallback, failureCallback) => {
                    $wire.call('getEvents', info.startStr, info.endStr)
                        .then((events) => successCallback(this.filterHidden(events)))
                        .catch(failureCallback);
                },

                dateClick: (info) => {
                    $wire.call('openCreate', info.dateStr, null, info.allDay);
                },

                select: (info) => {
                    $wire.call('openCreate', info.startStr, info.endStr, info.allDay);
                    this.calendar.unselect();
                },

                eventClick: (info) => {
                    $wire.call('openEdit', parseInt(info.event.id, 10));
                },

                eventDrop: (info) => {
                    $wire.call('updateEventTimes', parseInt(info.event.id, 10), info.event.startStr, info.event.endStr || null, info.event.allDay);
                },

                eventResize: (info) => {
                    $wire.call('updateEventTimes', parseInt(info.event.id, 10), info.event.startStr, info.event.endStr || null, info.event.allDay);
                },

                datesSet: (info) => {
                    this.title = info.view.title;
                    this.viewType = info.view.type;
                    this.miniCursor = new Date(info.view.currentStart.getFullYear(), info.view.currentStart.getMonth(), 1);
                    this.buildMiniMonth();
                },
            });

            this.calendar.render();

            window.addEventListener('calendar-refresh', () => this.calendar.refetchEvents());
        },

        filterHidden(events) {
            if (! this.hiddenCalendars.length) {
                return events;
            }

            return events.filter((e) => ! this.hiddenCalendars.includes(e.extendedProps?.calendarId));
        },

        toggleCalendar(id) {
            const idx = this.hiddenCalendars.indexOf(id);

            if (idx === -1) {
                this.hiddenCalendars.push(id);
            } else {
                this.hiddenCalendars.splice(idx, 1);
            }

            localStorage.setItem('wla-calendar-hidden-calendars', JSON.stringify(this.hiddenCalendars));
            this.calendar.refetchEvents();
        },

        today() {
            this.calendar.today();
        },

        prev() {
            this.calendar.prev();
        },

        next() {
            this.calendar.next();
        },

        changeView(view) {
            this.calendar.changeView(view);
        },

        // --- Mini month picker (Google Calendar-style left sidebar widget) ---

        buildMiniMonth() {
            const year = this.miniCursor.getFullYear();
            const month = this.miniCursor.getMonth();
            const firstOfMonth = new Date(year, month, 1);
            const startOffset = firstOfMonth.getDay();
            const gridStart = new Date(year, month, 1 - startOffset);

            const weeks = [];
            let cursor = new Date(gridStart);

            for (let w = 0; w < 6; w++) {
                const days = [];
                for (let d = 0; d < 7; d++) {
                    days.push({
                        date: new Date(cursor),
                        inMonth: cursor.getMonth() === month,
                        isToday: this.isSameDay(cursor, new Date()),
                    });
                    cursor.setDate(cursor.getDate() + 1);
                }
                weeks.push(days);
            }

            this.miniWeeks = weeks;
        },

        miniPrev() {
            this.miniCursor = new Date(this.miniCursor.getFullYear(), this.miniCursor.getMonth() - 1, 1);
            this.buildMiniMonth();
        },

        miniNext() {
            this.miniCursor = new Date(this.miniCursor.getFullYear(), this.miniCursor.getMonth() + 1, 1);
            this.buildMiniMonth();
        },

        miniMonthLabel() {
            return this.miniCursor.toLocaleDateString(undefined, { month: 'long', year: 'numeric' });
        },

        jumpTo(date) {
            this.calendar.gotoDate(date);
        },

        isSameDay(a, b) {
            return a.getFullYear() === b.getFullYear() && a.getMonth() === b.getMonth() && a.getDate() === b.getDate();
        },
    };
}

document.addEventListener('alpine:init', () => {
    window.Alpine.data('calendarApp', calendarApp);
});
