<?php
$titre = 'AppointmentCreation';
ob_start(); ?>

<link href='addons/fullcalendar-4.0.2/core/main.css' rel='stylesheet' />
<link href='addons/fullcalendar-4.0.2/daygrid/main.css' rel='stylesheet' />
<link href='addons/fullcalendar-4.0.2/timegrid/main.css' rel='stylesheet' />
<link href='addons/fullcalendar-4.0.2/bootstrap/main.css' rel='stylesheet' />

<script src='addons/fullcalendar-4.0.2/core/main.min.js'></script>
<script src='addons/fullcalendar-4.0.2/moment/main.min.js'></script>
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
        selectConstraint: {
            start: '00:01',
            end: '23:59',
        },
        select: function(info) {
            if ((info.start.getDate() != info.end.getDate() && !info.allDay)
                || (info.start.getDate() != (info.end.getDate() - 1) && info.allDay)) {
                calendar.unselect();
                Swal.fire({
                    position: 'bottom-end',
                    type: 'warning',
                    title: 'Sélection invalide',
                    text: 'Veuillez sélectionner une plage horaire qui débute et se termine dans la même journée.',
                    backdrop: `
                        rgba(1,1,0,0.11)
                    `
                })
            } else {
                var startDatetime = info.start;
                var endDatetime = info.end;
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
                    startDatetime: startDatetime.toLocaleString('it-IT'),
                    startDateStr: start.date,
                    startTimeStr: start.time,
                    endDatetime: endDatetime.toLocaleString('it-IT'),
                    endDateStr: end.date,
                    endTimeStr: end.time
                };
            }
        },
        unselect: function(info) {
            timeslot = null;
        },
        customButtons: {
            block_event: {
                text: 'Rendre indisponible',
                click: function() {
                    if (timeslot !== null) {
                        addNewEvent(false, false);
                    } else {
                        alert('No selection');
                    }
                }
            },
            add_event: {
                text: '<?php echo localize("Timeslot-Add") ?>',
                click: function() {
                    var at = " <?php echo localize("Timeslot-At") ?> "
                    var from = "<?php echo localize("Timeslot-From") ?> ";
                    var le = "<?php echo localize("Timeslot-Le") ?> ";
                    var to = " <?php echo localize("Timeslot-To") ?> ";
                    if (timeslot !== null) {
                        if (timeslot.startDateStr != timeslot.endDateStr)
                            if (timeslot.allDay)
                                infoString = from + timeslot.startDateStr + to + timeslot.endDateStr;
                            else
                                infoString = from + timeslot.startDateStr + at + timeslot.startTimeStr
                                    + to + timeslot.endDateStr + at + timeslot.endTimeStr;
                        else
                            if (timeslot.allDay)
                                infoString = le + timeslot.startDateStr;
                            else
                                infoString = le + timeslot.startDateStr + ' '
                                    + from + timeslot.startTimeStr + at + timeslot.endTimeStr;
                        Swal.fire({
                            title: "<strong>Nouvelle plage horaire</strong>",
                            html: 'Souhaitez-vous créer une plage horaire</br><em>' + infoString + '</em> ?',
                            input: 'checkbox',
                            inputPlaceholder: 'Créer une plage horaire publique',
                            showCancelButton: true,
                            confirmButtonText: 'Créer'
                        }).then((result) => {
                            if (typeof result.value === 'number')
                                addNewEvent((result.value) ? true : false, true);
                        });
                    } else {
                        alert('No selection');
                    }
                }
            }
        },
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'block_event add_event'
        }
    });

    getTimeSlots();
    function getTimeSlots() {
        $.getJSON('?action=ajaxGetTimeSlots', { get_param: 'value' }, function(timeSlots) {
            $.each(timeSlots, function(index, timeSlot) {
                addTimeSlotToCalendar(timeSlot);
            });
            calendar.render();
        });
    }

    function addTimeSlotToCalendar(timeSlot) {
        calendar.addEvent({
            id: timeSlot.id,
            title: 'TimeSlot #' + timeSlot.id,
            backgroundColor: (timeSlot.isPublic) ? '#0a0' : (timeSlot.isAvailable) ? '' : '#a00',
            start: timeSlot.startDateTime,
            end: timeSlot.endDateTime
        });
    }

    function addNewEvent(isPublic, isAvailable) {
        Swal.fire({
            title: 'Enregistrement en cours...',
            timer: 7500,
            onBeforeOpen: () => { Swal.showLoading() },
            allowOutsideClick: () => !Swal.isLoading(),
            onClose: () => {
                Swal.fire(
                    'Error',
                    'Aucune réponse reçue. Veuillez réessayer plus tard...',
                    'warning'
                )
            }
        });
        var startDatetime = timeslot.startDatetime;
        var endDatetime = timeslot.endDatetime;
        $.ajax({
            url: '?action=ajaxAddNewTimeslot',
            type: 'POST',
            data: {
                startDatetime: startDatetime,
                endDatetime: endDatetime,
                isPublic: isPublic,
                isAvailable: isAvailable
            }
        }).done(function(response){
            if (response == 'success') {
                Swal.fire({
                    text: 'Enregistrement effectué avec succès!',
                    type: 'success',
                    timer: 1750,
                    showConfirmButton: false
                });
                //2019-04-30T09:00:00
                //alert(startDatetime.toDateString('Y-m-d'));
                calendar.addEvent({
                    id: 0,
                    title: 'New TimeSlot',
                    start: startDatetime,
                    end: endDatetime
                });
                calendar.unselect();
            }
            else Swal.fire('Erreur', response, 'error');
        }).fail(function(){
            Swal.fire(
                'Erreur',
                "Une erreur c'est produite lors de l'envoi de la requête",
                'error'
            );
        });
    }
  });

</script>

<div id="calendar" class="container py-lg-5 py-md-4 py-sm-4 py-3"></div>

<?php
require('OnClick.html');
$contenu = ob_get_clean();
require 'gabarit.php'; ?>
