<?php
    include "includes/session.php";
    include "includes/header.php";
?>
<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/topbar.php'; ?>
<style>
  /* Tooltip styling */
.tooltip {
    position: absolute;
    top: -20px;
    left: 0;
    background-color: rgba(0, 0, 0, 0.75);
    color: white;
    padding: 5px;
    font-size: 12px;
    border-radius: 3px;
    visibility: hidden;
    z-index: 1000;
}

/* Show tooltip when event is hovered */
.fc-event:hover .tooltip {
    visibility: visible;
}

/* Styling for icons in event titles */
.event-icon {
    font-size: 1.2em;
    margin-right: 8px;
}

</style>
<!-- FullCalendar CSS -->
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' />

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>My Schedule</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">My Schedule</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-body">
                <div id='calendar'></div>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>

<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: function(fetchInfo, successCallback, failureCallback) {
            // Fetch both schedule and clock in/out events
            Promise.all([
                fetch('fetch_schedule.php'),
                fetch('fetch_clock_in_out.php')
            ])
            .then(responses => Promise.all(responses.map(res => res.json())))
            .then(([scheduleData, clockInOutData]) => {
                console.log("Fetched Schedule Events:", scheduleData);
                console.log("Fetched Clock-in/Clock-out Events:", clockInOutData);

                // Merge both data into one array
                const events = [
                    ...scheduleData.map(event => ({ ...event, type: 'Scheduled' })),
                    ...clockInOutData.map(event => ({ ...event, type: 'Clocked' }))
                ];

                successCallback(events);
            })
            .catch(error => {
                console.error("Error fetching events:", error);
                failureCallback(error);
            });
        },
        editable: false,
        selectable: true,
        eventDidMount: function(info) {
            var start = new Date(info.event.start);
            var end = new Date(info.event.end);

            // Format start and end time as '8:00 AM - 5:00 PM'
            var options = { hour: '2-digit', minute: '2-digit', hour12: true };
            var formattedStart = start.toLocaleString('en-US', options);
            var formattedEnd = end.toLocaleString('en-US', options);

            var formattedTime = formattedStart + ' - ' + formattedEnd;

            var titleElement = info.el.querySelector('.fc-event-title');
            if (titleElement) {
                titleElement.textContent = formattedTime;
            }

            var eventElement = info.el.querySelector('.fc-event');
            if (eventElement) {
                // Style based on event type
                if (info.event.extendedProps.type === 'Scheduled') {
                    eventElement.style.backgroundColor = '#28a745';  // Green for scheduled events
                    eventElement.style.borderColor = '#1e7e34';  // Darker green border
                    titleElement.textContent = 'Scheduled: ' + formattedTime;
                } else if (info.event.extendedProps.type === 'Clocked') {
                    eventElement.style.backgroundColor = '#ffc107';  // Yellow for clock-in/out events
                    eventElement.style.borderColor = '#e0a800';  // Darker yellow border
                    titleElement.textContent = 'Clocked: ' + formattedTime;
                }

                eventElement.style.color = 'white'; // White text for better contrast
            }

            // Add icons to events
            var icon = document.createElement('span');
            icon.classList.add('event-icon');
            if (info.event.extendedProps.type === 'Scheduled') {
                icon.textContent = '🕒';  // Clock emoji for scheduled events
            } else if (info.event.extendedProps.type === 'Clocked') {
                icon.textContent = '⏰';  // Alarm clock emoji for clock-in/out events
            }
            titleElement.prepend(icon);

            // Add tooltips for clarity
            var tooltip = document.createElement('div');
            tooltip.classList.add('tooltip');
            tooltip.textContent = info.event.extendedProps.type === 'Scheduled' ? 'Scheduled Event' : 'Clock In/Out Event';
            info.el.appendChild(tooltip);
        }
    });

    calendar.render();
});


</script>
</body>
</html>
