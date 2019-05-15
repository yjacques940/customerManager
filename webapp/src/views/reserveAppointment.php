<?php
$title = localize('Header-TakeAppointment');
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

<section class="contact py-lg-4 py-md-3 py-sm-3 py-3">
    <div class="container py-lg-5 py-md-4 py-sm-4 py-3">
    <h3 class="title text-center"><?php echo localize('Header-TakeAppointment') ?></h3>
    <?php
        if(isset($customerId)){
            $customerFullName = CallAPI('GET', 'Customers/FullName/'.$customerId)['response'];
            echo '<h4 class="text-center">'. localize('For') .' '. $customerFullName .'</h4>';
        }
    ?>
        <div id="empty" style="color:#F00"></div>
        <form action="" id="reserveappointment" name="reserveappointment" method="post">
            <div class="row w3pvt-info-para pt-lg-5 pt-md-4 pt-3">
                <div class="col-lg-7 col-md-7">
                    <h4><?php echo localize('ReserveAppointment-ChooseTimeSlot') ?></h4>
                    <div id="calendarEl" class="form-group contact-forms"></div>
                </div>
                <div class="col-lg-5 col-md-5">
                    <div class="form-group contact-forms">
                        <label for="therapist"><h4><?php echo localize('TakeAppointment-Therapist') ?></h4></label>
                        <select class="form-control mx-sm-3 w-200" id="therapist" name="therapist">
                          <option></option>
                          <option value="either"><?php echo localize('TakeAppointment-Either') ?>
                          <option value="Carl">Carl</option>
                          <option value="Mélanie">Mélanie</option>
                        </select>
                    </div>
                    <div class="form-group contact-forms">
                        <label class="col-form-label" for="consultation-reason">
                            <h4><?php echo localize('ReserveAppointment-ConsultationReason') ?></h4>
                        </label>
                        <input class="form-control mx-sm-3 w-200"
                            name="consultation-reason" id="consultation-reason" required>
                    </div>
                    <div class="form-group contact-forms">
                        <label for="therapist-bool">
                            <h4><?php echo localize('ReserveAppointment-HasSeenDoctor') ?></h4>
                        </label>
                        <div class="form-group form-inline">
                            <input style="width:24px;height: 24px;" class="mx-sm-2"
                                    type="radio" value="true" name="doctor-bool" required>
                                <?php echo localize('Answer-Yes') ?>
                            </input>
                            <input style="width:24px;height: 24px;" class="mx-sm-2"
                                    type="radio" value="false" name="doctor-bool">
                                <?php echo localize('Answer-No') ?>
                            </input>
                            <div class="mx-sm-4"></div>
                        </div>
                    </div>
                    <div id="form_doctor-diagnostic" class="form-group contact-forms" style="display: none;">
                        <label class="col-form-label" for="therapist-reason">
                            <h4><?php echo localize('ReserveAppointment-DoctorDiagnostic') ?></h4>
                        </label>
                        <input class="form-control mx-sm-3 w-200" name="doctor-diagnostic" id="doctor-diagnostic">
                    </div>
                    <button type="submit" class="btn sent-butnn">
                        <?php echo Localize('CreateAppointment-MakeAppointment') ?>
                    </button>
                    <div id="timeslottaken" style="color:#F00"></div>
                </div>
            </div>
        </form>
    </div>
</section>
<script>
const urlParams = new URLSearchParams(window.location.search);
var dateTimeOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
var dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
var timeOptions = { hour: '2-digit', minute: '2-digit' };
var locale = '<?php echo $_SESSION['locale']; ?>';
var currentSelection = null;

document.addEventListener('DOMContentLoaded', function() {
    var calendar = new FullCalendar.Calendar(document.getElementById('calendarEl'), {
        plugins: [ 'dayGrid', 'timeGrid', 'bootstrap', 'interaction' ],
        locale: locale,
        themeSystem: 'bootstrap',
        defaultView: 'timeGridWeek',
        nowIndicator: true,
        allDaySlot: false,
        weekends: false,
        selectable: false,
        minTime: '8:00',
        maxTime: '22:00',
        eventClick: function(info) { addSelectionInArray(info.event); },
        header: {
            left: 'prev,next',
            center: 'title',
            right: 'today'
        }
    });

    function addSelectionInArray(event){
        if (currentSelection != null) {
            calendar.getEventById(currentSelection.id).remove();
            calendar.addEvent({
                id: currentSelection.id,
                title: "Plage Disponible",
                backgroundColor: "#3788d8",
                start: currentSelection.start,
                end: currentSelection.end
            });
        }
        event.remove();
        calendar.addEvent({
            id: event.id,
            title: "Plage Sélectionnée",
            backgroundColor: "#0a0",
            start: event.start,
            end: event.end
        });
        currentSelection = event;
    }

    function addTimeSlotToCalendar(timeSlot) {
        calendar.addEvent({
            id: timeSlot.id,
            title: "Plage Disponible",
            start: timeSlot.startDateTime.toLocaleString('it-IT'),
            end: timeSlot.endDateTime.toLocaleString('it-IT')
        });
    }

    function ajaxGetTimeSlots() {
        showToastLoading();
        $.getJSON('?action=ajaxGetFreeTimeSlots', { get_param: 'value' }, function(timeSlots) {
            $.each(timeSlots, function(index, timeSlot) { addTimeSlotToCalendar(timeSlot) });
        }).done(function() {
            calendar.render();
            Swal.close();
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

    ajaxGetTimeSlots();
});

$(document).ready(function(){
    $("input[name=doctor-bool]").on("change", function() {
        if ($(this).val() == "true")
            $("#form_doctor-diagnostic").show();
        else
            $("#form_doctor-diagnostic").hide();
    } );

    $("#reserveappointment").validate({
        errorClass : "error_class",
        errorelement : "em",
        errorPlacement : function(error,element) {
            error.appendTo(element.parent());
        },
        rules:{
            timeSlots: {
                required:true
            },
            therapist: {
            required:true
            }
        },
        messages:{
            timeSlots:{
                required:'<?php echo localize('Validate-Error-RequiredField'); ?>.'
            },
            therapist:{
                required:'<?php echo localize('Validate-Error-RequiredField'); ?>.'
            }
        }
    });
    $('#reserveappointment').submit(function(e){
        e.preventDefault();
        if (currentSelection != null) {
            $.ajax({
                url:"?action=makeAnAppointment",
                type:'POST',
                data:{
                    <?php if(isset($customerId)) echo 'customerId: urlParams.get("customerId"),' ?>
                    timeSlot: currentSelection.id,
                    consultationReason: $("#consultation-reason").val(),
                    therapist: $("#therapist").val(),
                    hasSeenDoctor: $("input[name=doctor-bool]:checked").val(),
                    doctorDiagnostic: $("#doctor-diagnostic").val()
                },
                success:function(output){
                    if(output == 'taken')
                        $("#timeSlottaken").html("<p><?php echo localize('TakeAppointment-TimeSlotTaken');?></p>");
                    else if(output == 'available')
                        window.location = 'index.php?action=about';
                    else alert(output + "Une erreur s'est produite");
                }
            });
        } else {
            Swal.fire({
                text: 'Veuillez sélectionner une plage horaire.',
                type: 'info',
                toast: true,
                position: 'top',
                showConfirmButton: false
            });
        }
    });
});
</script>

<?php $contenu = ob_get_clean();
$onHomePage = false;
require 'gabarit.php'; ?>
