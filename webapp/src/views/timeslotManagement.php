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

  var data = {
      allDay: false,
      startDate: "",
      endDate: "",
      startTime: "",
      endTime: ""
  };

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
        minTime: '8:00',
        maxTime: '22:00',
        select: function(info) {
            var startDatetime = info.startStr.split('T');
            var endDatetime = info.startStr.split('T');
            var start = {};
            var end = {};
            start.date = startDatetime[0];
            end.date = endDatetime[0];
            if (!info.allDay) {
                start.time = startDatetime[1].split('-')[0];
                end.time = endDatetime[1].split('-')[0];
            }
            data = {
                allDay: (info.allDay),
                startDate: start.date,
                endDate: end.date,
                startTime: start.time,
                endTime: end.time
            };
        },
        customButtons: {
            add_event: {
                text: 'Add',
                click: function() {
                    Swal.fire({
                        title: data['allDay'].toString(),
                        html: '<input type="datetime-local" min="<?php echo date('Y-m-d'); ?>" id="appointmentDate" class="form-control">',
                        focusConfirm: false,
                        preConfirm: () => {
                            return [
                                alert(document.getElementById('appointmentDate').value)
                            ]
                        }
                    })
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
