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
    <div class="row w3pvt-info-para pt-lg-5 pt-md-4 pt-3">
      <div class="col-lg-6 col-md-6">
        <form action="" id="reserveappointment" name="reserveappointment" method="post">
        <div id="calendarEl" class="form-group contact-forms"></div>
        <div id="timeslottaken" style="color:#F00"></div>
        <div class="form-group contact-forms">
          <label for="therapist"><h4><?php echo localize('TakeAppointment-Therapist');?></h4></label>
            <select id="therapist" name="therapist">
              <option></option>
              <option value="either"><?php echo localize('TakeAppointment-Either');?>
              <option value="Carl">Carl</option>
              <option value="Mélanie">Mélanie</option>
            </select>
            </div>
            <button type="submit" class="btn sent-butnn"><?php echo Localize('Header-TakeAppointment');?></button>
        </form>
      </div>
    </div>
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

    function addTimeSlotToCalendar(timeslot) {
        calendar.addEvent({
            id: timeslot.id,
            title: "Plage Disponible",
            start: timeslot.startDateTime.toLocaleString('it-IT'),
            end: timeslot.endDateTime.toLocaleString('it-IT')
        });
    }

    function ajaxGetTimeSlots() {
        showToastLoading();
        $.getJSON('?action=ajaxGetFreeTimeslots', { get_param: 'value' }, function(timeSlots) {
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
    $("#reserveappointment").validate({
        errorClass : "error_class",
        errorelement : "em",
        rules:{
          timeslots: {
                required:true
            },
          therapist: {
            required:true
          }
        },
        messages:{
          timeslots:{
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
            var output = $.ajax({
                url:"index.php",
                type:'POST',
                data: {
                    <?php if(isset($customerId)) echo 'customerId: urlParams.get("customerId"),' ?>
                    timeslot:currentSelection.id,
                    therapist:$("#therapist").val()
                },
                success:function(output){
                    if(output == 'taken')
                      $("#timeslottaken").html("<p><?php echo localize('TakeAppointment-TimeSlotTaken');?></p>");
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
