<?php
if (!userHasPermission('TimeSlots-Read') || !userHasPermission('TimeSlots-Write')) error(403);
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
    var timeSlotsInfoArray = {};
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
            eventClick: function(info) { showTimeSlotDetails(info.event) },
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
                            ? ajaxAddNewTimeSlot({ "isAvailable": false, "isPublic": false, "notes": '' })
                            : showErrorNoSelection();
                    }
                },
                add_event: {
                    text: '<?php echo localize("TimeSlot-Add") ?>',
                    click: function() {
                        (currentSelection !== null)
                            ? showConfirmNewTimeSlot()
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

        function addTimeSlotInfoInArray(timeSlotInfo) {
            timeSlotsInfoArray[timeSlotInfo.idTimeSlot] = {
                idAppointment: timeSlotInfo.idAppointment,
                notesTimeSlot: timeSlotInfo.notesTimeSlot,
                customerInfo: timeSlotInfo.customerInfo
            };
        }

        function addTimeSlotToCalendar(timeSlot) {
            if (timeSlotsInfoArray[timeSlot.id]) {
                var text = timeSlotsInfoArray[timeSlot.id].customerInfo.fullName + '\n';
                var backgroundColor = colors.reserved;
            } else {
                var text = "";
                var backgroundColor = (timeSlot.isPublic)
                    ? colors.public
                    : (timeSlot.isAvailable) ? colors.available : colors.unavailable;
            }
            text += (timeSlot.notes) ? timeSlot.notes : "Aucune note";
            calendar.addEvent({
                id: timeSlot.id,
                title: text,
                description: "test",
                borderColor: "rgba(0,0,0,.2)",
                backgroundColor: backgroundColor,
                start: timeSlot.startDateTime.toLocaleString('it-IT'),
                end: timeSlot.endDateTime.toLocaleString('it-IT')
            });
        }

        function ajaxAddNewTimeSlot(form) {
            showToastCurrentlySaving();
            $.ajax({
                url: '?action=ajaxAddNewTimeSlot',
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

        function ajaxDeleteTimeSlot(event) {
            showToastCurrentlySaving();
            $.ajax({
                url: '?action=ajaxDeleteTimeSlot',
                type: 'POST',
                data: { idTimeSlot: event.id }
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

        function ajaxGetTimeSlots() {
            showToastLoading();
            $.getJSON('?action=ajaxGetTimeSlots', { get_param: 'value' }, function(data) {
                $.each(data.timeSlotsInfo, function(index, timeSlotInfo) { addTimeSlotInfoInArray(timeSlotInfo) });
                $.each(data.timeSlots, function(index, timeSlot) { addTimeSlotToCalendar(timeSlot) });
            }).done(function() {
                calendar.render();
                Swal.close();
            });
        }

        function ajaxUpdateTimeSlot(timeSlot, notes) {
            showToastCurrentlySaving();
            $.ajax({
                url: '?action=ajaxUpdateTimeSlot',
                type: 'POST',
                data: {
                    idTimeSlot: timeSlot.id,
                    notes: notes
                }
            }).done(function(response) {
                if (response == 'success') {
                    showToastSavingSuccess();
                    calendar.unselect();
                    timeSlot.remove();
                    var eventTitle = (timeSlotsInfoArray[timeSlot.id] !== undefined)
                        ? timeSlotsInfoArray[timeSlot.id].customerInfo.fullName + '\n' : '';
                    if (notes != '') {
                        timeSlotsInfoArray[timeSlot.id].notesTimeSlot = notes;
                        eventTitle += notes;
                    }
                    else {
                        timeSlotsInfoArray[timeSlot.id].notesTimeSlot = null;
                        eventTitle += "Aucune note";
                    }
                    calendar.addEvent({
                        id: timeSlot.id,
                        title: eventTitle,
                        backgroundColor: timeSlot.backgroundColor,
                        start: timeSlot.start,
                        end: timeSlot.end
                    });
                    showTimeSlotDetails(calendar.getEventById(timeSlot.id));
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

        function showConfirmNewTimeSlot() {
            var at = " <?php echo localize("TimeSlot-At") ?> ";
            var from = "<?php echo localize("TimeSlot-From") ?> ";
            var le = "<?php echo localize("TimeSlot-Le") ?> ";
            var to = " <?php echo localize("TimeSlot-To") ?> ";
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
                    '<input id="newTimeSlotNotes" class="swal2-input" placeholder="Notes (optionnel)">' +
                    '<label for="newTimeSlotIsPublic"><p>Créer une plage horaire publique </p> </label>' +
                    '<input id="newTimeSlotIsPublic" type="checkbox" name="isPublic" value="true" ' +
                    'style="height: 16px; width: 24px; vertical-align: middle">',
                confirmButtonText: 'Créer',
                showCancelButton: true,
                preConfirm: () => {
                    return [
                        document.getElementById('newTimeSlotIsPublic').checked,
                        document.getElementById('newTimeSlotNotes').value
                    ]
                }
            }).then((result) => {
                if (!result.dismiss)
                    ajaxAddNewTimeSlot({
                        "isAvailable": true,
                        "isPublic": result.value[0],
                        "notes": result.value[1]
                    });
            });
        }

        function showConfirmDeleteTimeSlot(timeSlot) {
            Swal.fire({
                title: "Suppression plage horaire",
                html: 'Souhaitez-vous vraiment supprimer cette plage horaire?</br><em>Le '
                    + timeSlot.start.toLocaleDateString(locale + '-ca', dateTimeOptions) + '</em>',
                type: "warning",
                showCancelButton: true,
                confirmButtonText: 'Confirmer la suppression',
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (result.value)
                    ajaxDeleteTimeSlot(timeSlot);
                else if (result.dismiss != 'backdrop')
                    showTimeSlotDetails(timeSlot);
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
                + "<br/>Client: <a href='?action=showCustomerInfo&customerId=" + data.customer.id + "'>"
                + data.customer.fullName + "</a>" + emailMessage
                + "<br/><a class='btn btn-info pl-4 pr-4' target='_blank' "
                + "href='?action=showAppointmentDetails&appointmentId=" + data.appointment.id + "'/>"
                + "Consulter le rendez-vous"
                + " <i class='fa fa-external-link' aria-hidden='true'></i></a>";

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

        function showTimeSlotDetails(timeSlot) {
            if (timeSlotsInfoArray[timeSlot.id] !== undefined) {
                var data = timeSlotsInfoArray[timeSlot.id];
                var notes = "<h5 class='mt-3 font-weight-underlined'><u>"
                    + "Notes sur plage horaire"
                    + ":</u></h5>";
                notes += (data.notesTimeSlot !== null) ? data.notesTimeSlot : 'Aucune note';
                notes += "<h5 class='mt-3 font-weight-bold'><u>"
                    + "Réservation au nom de"
                    + ":</u></h5><a target='_blank' href='?action=showCustomerInfo&customerId="
                    + data.customerInfo.id + "'>" + data.customerInfo.fullName
                    + " <i class='fa fa-external-link' aria-hidden='true'></i></a><h5 class='mt-3 font-weight-bold'><u>"
                    + "Pour rejoindre"
                    + ":</u></h5>";
                if (data.customerInfo.email !== null) {
                    notes += "<b class='mt-3 font-weight-bold'>"
                        + "Courriel:"
                        + "</b> <a href='mailto:" + data.customerInfo.email + "'>"
                        + data.customerInfo.email + "</a><br/>";
                }
                $.each(data.customerInfo.phoneNumbers, function(index, phoneNumber) {
                    notes += "<b class='font-weight-bold'>" + phoneNumber.phoneType + ":</b> " + phoneNumber.phone;
                    notes += (phoneNumber.extension !== null) ? " Ext. " + phoneNumber.extension + "<br/>" : "<br/>";
                });
                notes += "<br/><a class='btn btn-info pl-4 pr-4' target='_blank' "
                    + "href='?action=showAppointmentDetails&appointmentId=" + data.idAppointment + "'/>"
                    + "Consulter le rendez-vous"
                    + " <i class='fa fa-external-link' aria-hidden='true'></i></a>";
            }
            else {
                var notes = "<h5 class='m-1 font-weight-underlined'><u>"
                    + "Notes sur plage horaire"
                    + ":</u></h5>" + timeSlot.title;
            }
            notes += "<br/><button class='btn m-1 mt-3 pl-4 pr-4' style='background-color: #D90; color: #FFF'"
                + " id='timeSlotEdit'>"
                + "Modifier la plage horaire"
                + " <i class='fa fa-pencil-square-o' aria-hidden='true'></i></button>";
            Swal.fire({
                title: timeSlot.start.toLocaleDateString(locale + '-ca', dateTimeOptions),
                html: notes,
                showCancelButton: true,
                cancelButtonText: "Fermer",
                confirmButtonText: "Suppprimer la plage <i class='fa fa-trash-o' aria-hidden='true'></i>",
                confirmButtonColor: '#d33',
                onBeforeOpen: () => {
                    const content = Swal.getContent();
                    const $ = content.querySelector.bind(content);
                    const timeSlotEdit = $('#timeSlotEdit');
                    timeSlotEdit.addEventListener('click', () => { showTimeSlotEditor(timeSlot) });
                }
            }).then((result) => { if (result.value) showConfirmDeleteTimeSlot(timeSlot) });
        }

        function showTimeSlotEditor(timeSlot) {
            if (timeSlotsInfoArray[timeSlot.id] !== undefined) {
                var notes = (timeSlotsInfoArray[timeSlot.id].notesTimeSlot !== null)
                    ? timeSlotsInfoArray[timeSlot.id].notesTimeSlot : '';
            }
            else {
                var notes = (timeSlot.title != 'Aucune note') ? timeSlot.title : '';
            }
            Swal.fire({
                title: timeSlot.start.toLocaleDateString(locale + '-ca', dateTimeOptions),
                html: "Mode édition"
                    + "<br/><input class='swal2-input' type='text' id='swal-input-notes' placeholder='Notes' value='"
                    + notes
                    + "'></input>",
                showCancelButton: true,
                cancelButtonText: "Annuler",
                confirmButtonText: "Enregistrer <i class='fa fa-floppy-o' aria-hidden='true'></i>",
                confirmButtonColor: "#5cb85c",
                preConfirm: () => {
                    return { "notes": document.getElementById('swal-input-notes').value }
                }
            }).then((result) => {
                if (!result.dismiss) {
                    if (result.value.notes != notes)
                        ajaxUpdateTimeSlot(timeSlot, result.value.notes);
                    else
                        showTimeSlotDetails(timeSlot);
                }
                else if (result.dismiss != 'backdrop')
                    showTimeSlotDetails(timeSlot);
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

        ajaxGetTimeSlots();
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

<h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize('PageTitle-TimeSlotManagement') ?></h3>
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
