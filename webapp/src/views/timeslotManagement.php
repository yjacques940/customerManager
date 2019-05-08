<?php
if (!userHasPermission('Timeslots-Read') || !userHasPermission('Timeslots-Write')) error(403);
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
    var dateTimeOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    var dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    var timeOptions = { hour: '2-digit', minute: '2-digit' };
    var locale = '<?php echo $_SESSION['locale']; ?>';
    var currentSelection = null;
    var timeslotsInfoArray = {};
    var colors = {
        'unavailable': 'rgba(170,0,0,.75)',
        'available': 'rgba(0,85,153,.75)',
        'public': 'rgba(0,170,0,.75)',
        'reserved': 'rgba(0,153,221,.75)'
    };

    function setLegendColors() {
        $('#legend-color-unavailable').css("background-color", colors.unavailable);
        $('#legend-color-available').css("background-color", colors.available);
        $('#legend-color-reserved').css("background-color", colors.reserved);
        $('#legend-color-public').css("background-color", colors.public);
    }

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
            unselect: function(info) { currentSelection = null },
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
                        (currentSelection !== null)
                            ? ajaxAddNewTimeslot({ "isAvailable": false, "isPublic": false, "notes": '' })
                            : showErrorNoSelection();
                    }
                },
                add_event: {
                    text: '<?php echo localize("Timeslot-Add") ?>',
                    click: function() {
                        (currentSelection !== null)
                            ? showConfirmNewTimeslot()
                            : showErrorNoSelection();
                    }
                }
            },
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'block_event add_event'
            }
        });

        function addSelectionInArray(info){
            var startDatetime = info.start;
            var endDatetime = info.end;
            currentSelection = {
                allDay: (info.allDay),
                startDatetime: startDatetime,
                startDateStr: startDatetime.toLocaleDateString(locale + '-ca', dateOptions),
                startTimeStr: (info.allDay) ? null : ' ' + startDatetime.toLocaleTimeString('it-IT', timeOptions),
                endDatetime: endDatetime,
                endDateStr: endDatetime.toLocaleDateString(locale + '-ca', dateOptions),
                endTimeStr: (info.allDay) ? null : ' ' + endDatetime.toLocaleTimeString('it-IT', timeOptions)
            };
        }

        function addTimeslotInfoInArray(timeslotInfo) {
            timeslotsInfoArray[timeslotInfo.idTimeSlot] = {
                idAppointment: timeslotInfo.idAppointment,
                customerInfo: timeslotInfo.customerInfo
            };
        }

        function addTimeslotToCalendar(timeslot) {
            if (timeslotsInfoArray[timeslot.id]) {
                var text = timeslotsInfoArray[timeslot.id].customerInfo.fullName + '\n';
                var backgroundColor = colors.reserved;
            } else {
                var text = "";
                var backgroundColor = (timeslot.isPublic)
                    ? colors.public
                    : (timeslot.isAvailable) ? colors.available : colors.unavailable;
            }
            text += (timeslot.notes) ? timeslot.notes : "Aucune note";
            calendar.addEvent({
                id: timeslot.id,
                title: text,
                borderColor: "rgba(0,0,0,.2)",
                backgroundColor: backgroundColor,
                start: timeslot.startDateTime.toLocaleString('it-IT'),
                end: timeslot.endDateTime.toLocaleString('it-IT')
            });
        }

        function ajaxAddNewTimeslot(form) {
            showToastCurrentlySaving();
            $.ajax({
                url: '?action=ajaxAddNewTimeslot',
                type: 'POST',
                data: {
                    startDatetime: currentSelection.startDatetime.toLocaleString('it-IT'),
                    endDatetime: currentSelection.endDatetime.toLocaleString('it-IT'),
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
                            title: (response.notes) ? response.notes : "Aucune note",
                            backgroundColor: (response.isPublic)
                                ? colors.public
                                : (response.isAvailable) ? colors.available : colors.unavailable,
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
                data: { idTimeslot: event.id }
            }).done(function(content) {
                if (content) {
                    if (content == 'success') {
                        calendar.getEventById(event.id).remove();
                        showToastSavingSuccess();
                    } else if (isJsonString(content)) {
                        showErrorAppointmentExists(content);
                    } else Swal.fire('Erreur', content, 'error');
                } else Swal.fire('Erreur', content, 'error');
            }).fail(function() { showErrorAjax() });
        }

        function ajaxGetTimeslots() {
            showToastLoading();
            $.getJSON('?action=ajaxGetTimeslots', { get_param: 'value' }, function(data) {
                $.each(data.timeslotsInfo, function(index, timeslotInfo) { addTimeslotInfoInArray(timeslotInfo) });
                $.each(data.timeslots, function(index, timeslot) { addTimeslotToCalendar(timeslot) });
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
                        title: (notes != '') ? notes : "Aucune note",
                        backgroundColor: event.backgroundColor,
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
            if (currentSelection.allDay)
            {
                var endDatetime = new Date(currentSelection.endDatetime.getTime());
                endDatetime.setDate(endDatetime.getDate() - 1);
                if (currentSelection.startDatetime.getTime() == endDatetime.getTime())
                {
                    infoString = le + currentSelection.startDateStr;
                } else {
                    infoString = from + currentSelection.startDateStr
                        + to + endDatetime.toLocaleDateString(locale + '-ca', dateOptions);
                }
            } else {
                if (currentSelection.startDateStr == currentSelection.endDateStr)
                {
                    infoString = le + currentSelection.startDateStr + ' '
                        + from + currentSelection.startTimeStr + at + currentSelection.endTimeStr;
                } else {
                    infoString = from + currentSelection.startDateStr + at + currentSelection.startTimeStr
                        + to + currentSelection.endDateStr + at + currentSelection.endTimeStr;
                }
            }
            Swal.fire({
                title: "Nouvelle plage horaire",
                html:
                    'Souhaitez-vous créer une plage horaire</br><em>' + infoString + '</em> ?' +
                    '<input id="newTimeslotNotes" class="swal2-input" placeholder="Notes (optionnel)">' +
                    '<label for="newTimeslotIsPublic"><p>Créer une plage horaire publique </p> </label>' +
                    '<input id="newTimeslotIsPublic" type="checkbox" name="isPublic" value="true" ' +
                    'style="height: 16px; width: 24px; vertical-align: middle">',
                confirmButtonText: 'Créer',
                showCancelButton: true,
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
            Swal.fire({
                title: "Suppression plage horaire",
                html: 'Souhaitez-vous vraiment supprimer cette plage horaire?</br><em>Le '
                    + event.start.toLocaleDateString(locale + '-ca', dateTimeOptions) + '</em>',
                type: "warning",
                showCancelButton: true,
                confirmButtonText: 'Confirmer la suppression',
                confirmButtonColor: '#d33'
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

        function showErrorAppointmentExists(content) {
            var response = JSON.parse(content);
            var data = response.data;
            if (data.customer.email != '')
                var emailMessage = "<br/>Courriel: <a href='mailto:"
                    + data.customer.email + "'>" + data.customer.email + "</a>";
            else
                var emailMessage = '';

            var message = "Vous devez annuler le rendez-vous avant de supprimer cette plage horaire.<br/>"
                + "<br/>Informations sur le rendez-vous:"
                + "<br/>Rendez-vous réservé le "
                + new Date(data.appointment.createdOn).toLocaleDateString(locale + '-ca', dateOptions)
                + "<br/>Client: " + data.customer.fullName + emailMessage;

            Swal.fire({
                title: response.errorMessage,
                html: message,
                type: "warning"
            });
        }

        function showErrorNoSelection() {
            Swal.fire({
                text: 'Veuillez sélectionner une plage horaire.',
                type: 'info',
                toast: true,
                position: 'top',
                showConfirmButton: false
            });
        }

        function showErrorSelectionWithinDay() {
            Swal.fire({
                text: 'La plage horaire doit être contenue dans la même journée.',
                type: 'warning',
                toast: true,
                position: 'top',
                showConfirmButton: false
            });
        }

        function showTimeslotDetails(timeslot) {
            var notes = "";
            if (timeslotsInfoArray[timeslot.id] != null) {
                notes += timeslotsInfoArray[timeslot.id].customerInfo.fullName;
            }
            notes += (timeslot.title != 'Aucune note') ? 'Notes de la plage horaire: ' + timeslot.title : 'Aucune note';
            Swal.fire({
                title: timeslot.start.toLocaleDateString(locale + '-ca', dateTimeOptions),
                text: notes,
                showCancelButton: true,
                cancelButtonText: "Fermer",
                confirmButtonText: "Modifier",
                confirmButtonColor: '#d93',
            }).then((result) => { if (result.value) showTimeslotEditor(timeslot) });
        }

        function showTimeslotEditor(timeslot) {
            var notes = (timeslot.title != 'Aucune note') ? timeslot.title : 'Notes';
            Swal.fire({
                title: timeslot.start.toLocaleDateString(locale + '-ca', dateTimeOptions),
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
                        ajaxUpdateTimeslot(timeslot, $("#notes").value);
                    });
                }
            }).then((result) => {
                if (result.value)
                    showConfirmDeleteTimeslot(timeslot)
                else if (result.dismiss != 'backdrop')
                    showTimeslotDetails(timeslot);
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
                timer: 1750,
                toast: true,
                type: 'success',
                position: 'top',
                showConfirmButton: false
            });
        }

        ajaxGetTimeslots();
        setLegendColors();
    });

</script>
<style>
.legend [id*='legend-color-'] {
    height: 25px;
    width: 25px;
    background-color: grey;
    border: 1px solid rgba(0,0,0,.2);
}
</style>

<h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize('PageTitle-TimeslotManagement') ?></h3>
<p class="text-center">Légende:</p>
<div class="legend d-flex flex-wrap justify-content-around">
    <div class="d-flex flex-nowrap m-1">
        <div id="legend-color-unavailable"></div>
        <div class="ml-1 mr-auto">Indisponible privée</div>
    </div>
    <div class="d-flex flex-nowrap m-1">
        <div id="legend-color-available"></div>
        <div class="ml-1 mr-auto">Disponible privée</div>
    </div>
    <div class="d-flex flex-nowrap m-1">
        <div id="legend-color-public"></div>
        <div class="ml-1 mr-auto">Disponible publique</div>
    </div>
    <div class="d-flex flex-nowrap m-1">
        <div id="legend-color-reserved"></div>
        <div class="ml-1 mr-auto">Réservée</div>
    </div>
</div>
<div id="calendar" class="container py-lg-5 py-md-4 py-sm-4 py-3"></div>

<?php
require('OnClick.html');
$contenu = ob_get_clean();
require 'gabarit.php'; ?>
