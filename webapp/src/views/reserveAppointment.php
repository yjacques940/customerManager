<?php
$title = localize('Header-TakeAppointment');
 ob_start(); ?>

<section class="contact py-lg-4 py-md-3 py-sm-3 py-3">
  <div class="container py-lg-5 py-md-4 py-sm-4 py-3">
    <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize('Header-TakeAppointment'); ?></h3>
    <div id="empty" style="color:#F00"></div>
    <div class="row w3pvt-info-para pt-lg-5 pt-md-4 pt-3">
      <div class="col-lg-6 col-md-6">
        <form action="" id="reserveappointment" name="reserveappointment" method="post">
        <div class="form-group contact-forms">
          <label for="timeslots"><h4><?php echo localize('TakeAppointment-AvailableTimeSlot');?></h4></label>
          <select id="timeslots" name="timeslots" >
            <option></option>
            <?php 
              foreach($availableTimeSlots as $availableTimeSlot){
                echo '<option value="'. $availableTimeSlot->id.'">'. $availableTimeSlot->startDateTime.'</option>';
              }
            ?>
            </select>
        </div>
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
        },
        submitHandler:function(){
            var output = $.ajax({
                url:"index.php",
                type:'POST',
                data:{timeslot:$("#timeslots").val(), therapist:$("#therapist").val()},
                success:function(output){
                    if(output == 'taken'){
                      $("#timeslottaken").html("<p><?php echo localize('TakeAppointment-TimeSlotTaken');?></p>");
                    }
                    else if(output == 'available'){
                      window.location = 'index.php?action=about';
                    }
                }
            });
        }
    });
});
</script>

<?php $contenu = ob_get_clean(); 
$onHomePage = false;
require 'gabarit.php'; ?>