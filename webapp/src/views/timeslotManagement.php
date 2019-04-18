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
<script src='addons/jquery.ui.touch-punch.min.js'></script>

<script>
  var locale = '<?php echo $_SESSION['locale']; ?>';
  var timeslot = null;

  document.addEventListener('DOMContentLoaded', function() {
    var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        plugins: [ 'dayGrid', 'timeGrid', 'bootstrap', 'interaction' ],
        locale: locale,
        themeSystem: 'bootstrap',
        defaultView: 'timeGridWeek',
        nowIndicator: true,
        weekends: false,
        selectable: true,
        unselectAuto: false,
        minTime: '8:00',
        maxTime: '22:00',
        eventClick: function(info) { showTimeslotDetails(info.event) },
        unselect: function(info) { timeslot = null },
        select: function(info) {
            Swal.close();
            if ((info.start.getDate() != info.end.getDate() && !info.allDay)
                || (info.start.getDate() != (info.end.getDate() - 1) && info.allDay))
            {
                calendar.unselect();
                showErrorSelectionWithinDay();
            } else {
                addSelectionInArray(info);
            }
        },
        customButtons: {
            block_event: {
                text: 'Rendre indisponible',
                click: function() {
                    (timeslot !== null)
                        ? ajaxAddNewTimeslot({"isAvailable": false, "isPublic": false, "notes": ''})
                        : showErrorNoSelection();
                }
            },
            add_event: {
                text: '<?php echo localize("Timeslot-Add") ?>',
                click: function() { (timeslot !== null) ? showConfirmNewTimeslot() : showErrorNoSelection(); }
            }
        },
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'block_event add_event'
        }
    });

    function addSelectionInArray(info){
        var dateOptions = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'};
        var timeOptions = {hour: '2-digit', minute: '2-digit'};
        var startDatetime = info.start;
        var endDatetime = info.end;
        timeslot = {
            allDay: (info.allDay),
            startDatetime: startDatetime.toLocaleString('it-IT'),
            startDateStr: startDatetime.toLocaleDateString(locale + '-ca', dateOptions),
            startTimeStr: (info.allDay) ? null : ' ' + startDatetime.toLocaleTimeString('it-IT', timeOptions),
            endDatetime: endDatetime.toLocaleString('it-IT'),
            endDateStr: endDatetime.toLocaleDateString(locale + '-ca', dateOptions),
            endTimeStr: (info.allDay) ? null : ' ' + endDatetime.toLocaleTimeString('it-IT', timeOptions)
        };
    }

    function addTimeSlotToCalendar(timeSlot) {
        calendar.addEvent({
            id: timeSlot.id,
            title: (timeSlot.notes) ? timeSlot.notes : "Aucune note",
            backgroundColor: (timeSlot.isPublic) ? '#0a0' : (timeSlot.isAvailable) ? '' : '#a00',
            start: timeSlot.startDateTime,
            end: timeSlot.endDateTime
        });
    }

    function ajaxAddNewTimeslot(form) {
        showToastCurrentlySaving();
        var startDatetime = timeslot.startDatetime;
        var endDatetime = timeslot.endDatetime;
        $.ajax({
            url: '?action=ajaxAddNewTimeslot',
            type: 'POST',
            data: {
                startDatetime: startDatetime,
                endDatetime: endDatetime,
                notes: form.notes.replace(/"/g, "''"),
                isPublic: form.isPublic,
                isAvailable: form.isAvailable
            }
        }).done(function(content) {
            if (content) {
                if (isJsonString(content)) {
                    var response = JSON.parse(content);
                    calendar.addEvent({
                        id: response.id,
                        title: (response.notes != '') ? response.notes : "Aucune note",
                        backgroundColor: (response.isPublic) ? '#0a0' : (response.isAvailable) ? '' : '#a00',
                        start: response.startDateTime,
                        end: response.endDateTime
                    });
                    calendar.unselect();
                    showToastSavingSuccess();
                } else Swal.fire('Erreur', content, 'error');
            }
            else Swal.fire('Erreur', content, 'error');
        }).fail(function() { showErrorAjax() });
    }

    function ajaxDeleteTimeslot(event) {
        showToastCurrentlySaving();
        $.ajax({
            url: '?action=ajaxDeleteTimeslot',
            type: 'POST',
            data: { "idTimeslot": event.id }
        }).done(function(content) {
            if (content) {
                if (content == 'success') {
                    calendar.getEventById(event.id).remove();
                    showToastSavingSuccess();
                } else Swal.fire('Erreur', content, 'error');
            }
            else Swal.fire('Erreur', content, 'error');
        }).fail(function() { showErrorAjax() });
    }

    function ajaxGetTimeSlots() {
        showToastLoading();
        $.getJSON('?action=ajaxGetTimeslots', { get_param: 'value' }, function(timeSlots) {
            $.each(timeSlots, function(index, timeSlot) { addTimeSlotToCalendar(timeSlot) });
        }).done(function() {
            calendar.render();
            Swal.close();
        });
    }

    function ajaxUpdateTimeslot(event, notes) {
        showToastCurrentlySaving();
        $.ajax({
            url: '?action=ajaxUpdateTimeslot',
            type: 'POST',
            data: {
                idTimeslot: event.id,
                notes: notes
            }
        }).done(function(response) {
            if (response == 'success') {
                showToastSavingSuccess();
                calendar.unselect();
                event.remove();
                calendar.addEvent({
                    id: event.id,
                    title: notes,
                    start: event.start,
                    end: event.end
                });
                showTimeslotDetails(calendar.getEventById(event.id));
            } else Swal.fire('Erreur', response, 'error');
        }).fail(function() { showErrorAjax() });
    }

    function isJsonString(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    }

    function showConfirmNewTimeslot() {
        var at = " <?php echo localize("Timeslot-At") ?> ";
        var from = "<?php echo localize("Timeslot-From") ?> ";
        var le = "<?php echo localize("Timeslot-Le") ?> ";
        var to = " <?php echo localize("Timeslot-To") ?> ";
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
            title: "Nouvelle plage horaire",
            html:
                'Souhaitez-vous créer une plage horaire</br><em>' + infoString + '</em> ?' +
                '<input id="newTimeslotNotes" class="swal2-input" placeholder="Notes (optionnel)">' +
                '<label for="newTimeslotIsPublic"><p>Créer une plage horaire publique</p></label>' +
                '<input id="newTimeslotIsPublic" type="checkbox" name="isPublic" value="true">',
            showCancelButton: true,
            confirmButtonText: 'Créer',
            preConfirm: () => {
                return [
                    document.getElementById('newTimeslotIsPublic').checked,
                    document.getElementById('newTimeslotNotes').value
                ]
            }
        }).then((result) => {
            if (!result.dismiss)
                ajaxAddNewTimeslot({
                    "isAvailable": true,
                    "isPublic": result.value[0],
                    "notes": result.value[1]
                });
        });
    }

    function showConfirmDeleteTimeslot(event) {
        var dateOptions = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'};
        Swal.fire({
            title: "Suppression plage horaire",
            html: 'Souhaitez-vous vraiment supprimer cette plage horaire?</br><em>Le '
                + event.start.toLocaleDateString(locale + '-ca', dateOptions) + '</em>',
            showCancelButton: true,
            confirmButtonText: 'Confirmer la suppression',
            confirmButtonColor: '#d33',
            type: "warning"
        }).then((result) => {
            if (result.value)
                ajaxDeleteTimeslot(event);
            else if (result.dismiss != 'backdrop')
                showTimeslotEditor(event);
        });
    }

    function showErrorAjax() {
        Swal.fire("Erreur", "Une erreur c'est produite lors de l'envoi de la requête", "error");
    }

    function showErrorConnection() {
        Swal.fire("Error", "Aucune réponse reçue. Veuillez réessayer plus tard...", "warning");
    }

    function showErrorNoSelection() {
        Swal.fire({
            position: 'top',
            type: 'info',
            toast: true,
            showConfirmButton: false,
            text: 'Veuillez sélectionner une plage horaire.'
        });
    }

    function showErrorSelectionWithinDay() {
        Swal.fire({
            position: 'top',
            type: 'warning',
            toast: true,
            showConfirmButton: false,
            text: 'La plage horaire doit être contenu dans la même journée.'
        });
    }

    function showTimeslotDetails(event) {
        var dateOptions = {weekday: 'short', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'};
        var notes = (event.title != 'Aucune note') ? event.title : 'Notes';
        Swal.fire({
            title: event.start.toLocaleDateString(locale + '-ca', dateOptions),
            text: (event.title != 'Aucune note') ? 'Notes: ' + event.title : 'Aucune note',
            showCancelButton: true,
            cancelButtonText: "Fermer",
            confirmButtonText: "Modifier",
            confirmButtonColor: '#d93',
        }).then((result) => { if (result.value) showTimeslotEditor(event) });
    }

    function showTimeslotEditor(event) {
        var dateOptions = {weekday: 'short', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'};
        var notes = (event.title != 'Aucune note') ? event.title : 'Notes';
        Swal.fire({
            title: event.start.toLocaleDateString(locale + '-ca', dateOptions),
            html: 'Mode édition'
                + '<br/><input class="swal2-input" type="text" id="notes" placeholder="'
                + notes
                + '"></input><br/><button id="timeslotUpdate" class="swal2-confirm swal2-styled" '
                + 'style="background-color: rgb(51, 153, 51)">Enregistrer</button>',
            showCancelButton: true,
            cancelButtonText: "Fermer",
            confirmButtonText: "Supprimer",
            confirmButtonColor: '#d33',
            onBeforeOpen: () => {
                const content = Swal.getContent();
                const $ = content.querySelector.bind(content);
                const timeslotUpdate = $('#timeslotUpdate');
                timeslotUpdate.addEventListener('click', () => {
                    ajaxUpdateTimeslot(event, $("#notes").value);
                });
            }
        }).then((result) => {
            if (result.value)
                showConfirmDeleteTimeslot(event)
            else if (result.dismiss != 'backdrop')
                showTimeslotDetails(event);
            });
    }

    function showToastCurrentlySaving() {
        Swal.fire({
            title: 'Enregistrement en cours...',
            timer: 7500,
            toast: true,
            position: 'top',
            onBeforeOpen: () => { Swal.showLoading() },
            onClose: () => { showErrorConnection() }
        });
    }

    function showToastLoading() {
        Swal.fire({
            title: 'Chargement en cours...',
            toast: true,
            position: 'top',
            onBeforeOpen: () => { Swal.showLoading() }
        });
    }

    function showToastSavingSuccess() {
        Swal.fire({
            text: 'Enregistrement effectué avec succès!',
            toast: true,
            type: 'success',
            timer: 1750,
            position: 'top',
            toast: true,
            showConfirmButton: false
        });
    }

    ajaxGetTimeSlots();
  });

</script>

<div id="calendar" class="container py-lg-5 py-md-4 py-sm-4 py-3"></div>

<?php
require('OnClick.html');
$contenu = ob_get_clean();
require 'gabarit.php'; ?>
