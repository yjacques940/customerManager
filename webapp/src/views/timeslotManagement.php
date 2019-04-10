<?php
$titre = 'AppointmentCreation';
ob_start(); ?>

<link href='addons/fullcalendar-4.0.2/core/main.css' rel='stylesheet' />
<link href='addons/fullcalendar-4.0.2/daygrid/main.css' rel='stylesheet' />
<link href='addons/fullcalendar-4.0.2/timegrid/main.css' rel='stylesheet' />
<link href='addons/fullcalendar-4.0.2/bootstrap/main.css' rel='stylesheet' />

<script src='addons/fullcalendar-4.0.2/core/main.min.js'></script>
<script src='addons/fullcalendar-4.0.2/core/locales/fr.js'></script>
<script src='addons/fullcalendar-4.0.2/daygrid/main.min.js'></script>
<script src='addons/fullcalendar-4.0.2/timegrid/main.min.js'></script>
<script src='addons/fullcalendar-4.0.2/bootstrap/main.min.js'></script>
<script src='addons/fullcalendar-4.0.2/interaction/main.min.js'></script>

<script>
  var locale = '<?php echo $_SESSION['locale']; ?>';
  var timeslot = null;

  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [ 'dayGrid', 'timeGrid', 'bootstrap', 'interaction' ],
        locale: '<?php echo $_SESSION['locale']; ?>',
        themeSystem: 'bootstrap',
        defaultView: 'timeGridWeek',
        nowIndicator: true,
        weekends: false,
        selectable: true,
        unselectAuto: false,
        minTime: '8:00',
        maxTime: '22:00',
        select: function(info) {
            var startDatetime = info.start;
            var endDatetime = info.end;
            if (info.allDay)
                endDatetime.setDate(endDatetime.getDate() - 1);
            var start = {};
            var end = {};
            var dateOptions = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};
            var timeOptions = {hour: '2-digit', minute: '2-digit'};
            start.date = startDatetime.toLocaleDateString(locale + '-ca', dateOptions);
            end.date = endDatetime.toLocaleDateString(locale + '-ca', dateOptions);
            start.time = (info.allDay) ? null : ' ' + startDatetime.toLocaleTimeString('it-IT', timeOptions);
            end.time = (info.allDay) ? null : ' ' + endDatetime.toLocaleTimeString('it-IT', timeOptions);
            timeslot = {
                allDay: (info.allDay),
                startDate: start.date,
                startTime: start.time,
                endDate: end.date,
                endTime: end.time
            };
        },
        unselect: function(info) {
            timeslot = null;
        },
        customButtons: {
            add_event: {
                text: '<?php echo localize("Timeslot-Add") ?>',
                click: function() {
                    var at = " <?php echo localize("Timeslot-At") ?> "
                    var from = "<?php echo localize("Timeslot-From") ?> ";
                    var le = "<?php echo localize("Timeslot-Le") ?> ";
                    var to = "<?php echo localize("Timeslot-To") ?> ";
                    if (timeslot !== null) {
                        if (timeslot.startDate != timeslot.endDate)
                            infoString = from + timeslot.startDate + at + timeslot.startTime
                                + to + timeslot.endDate + at + timeslot.endTime;
                        else
                            if (timeslot.startTime === null)
                                infoString = le + timeslot.startDate;
                            else
                                infoString = le + timeslot.startDate + ' '
                                    + from + timeslot.startTime + at + timeslot.endTime;
                        Swal.fire({
                            title: "<strong>Nouvelle plage horaire</strong>",
                            html: infoString +
                                  '<input type="datetime-local" min="<?php echo date('Y-m-d'); ?>" id="appointmentDate" class="form-control">',
                            focusConfirm: false,
                            preConfirm: () => {
                                return [
                                    alert(document.getElementById('appointmentDate').value)
                                ]
                            }
                        })
                    } else {
                        alert('No selection');
                    }
                }
            }
        },
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'add_event'
        }
    });

    calendar.render();
  });

</script>

<div id="calendar" class="container py-lg-5 py-md-4 py-sm-4 py-3"></div>

<?php
require('OnClick.html');
$contenu = ob_get_clean();
require 'gabarit.php'; ?>
