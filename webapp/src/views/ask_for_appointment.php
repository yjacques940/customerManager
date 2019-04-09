<?php
$titre = localize('Appointment-AskForAppointment');
ob_start(); ?>
<section class="contact py-lg-4 py-md-3 py-sm-3 py-3">
    <div class="container py-lg-5 py-md-4 py-sm-4 py-3">
    <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize($titre) ?></h3>
    <div class="container">
        <p><?php echo localize('Report-Bug-Instructions')?></p></br>
            <form action="?action=send_ask_for_appointment" method="post" id="AskForAppointmentInformation">
                <?php if(!isset($_SESSION['userid'])){ ?>
                    <div class="form-group">
                        <label for="AskForAppointmentUserName">
                            *<?php echo localize('Inscription-Lastname')?>: </label>
                        <input class="form-control" id="AskForAppointmentUserName"
                               name="AskForAppointmentUserName">
                    </div>

                    <div class="form-group">
                        <label for="AskForAppointmentPhoneNumber">
                            *<?php echo localize('Personal-Phone')?>: </label>
                        <input class="form-control" id="AskForAppointmentPhoneNumber"
                               name="AskForAppointmentPhoneNumber" ">
                    </div>

                    <div class="form-group">
                        <label for="AskForAppointmentEmail">*<?php echo localize('Login-Email')?>: </label>
                        <input type="email" class="form-control" id="AskForAppointmentEmail"
                               name="AskForAppointmentEmail"
                               aria-describedby="emailHelp" placeholder="<?php echo localize('Login-Email') ?>">
                    </div>
                <?php }?>

                    <div class="form-group">
                            <label for="askForAppointmentDate"><p>*<?php echo localize('Appointment-DateRequested'); ?></p></label>
                            <input type="date" min="<?php echo date('Y-m-d'); ?>" id="askForAppointmentDate"
                                   name="askForAppointmentDate" class="form-control"
                                   placeholder="Date du rendez-vous">
                    </div>

                <div class="form-group">
                    <label for="appointmentTimeOfDay">*<?php echo localize('AskForAppointment-TimeOfDay'); ?></label>
                    <div>
                        <select id="appointmentTimeOfDay" name="appointmentTimeOfDay">
                            <option disabled selected value></option>
                            <option value="AM">AM</option>
                            <option value="PM">PM</option>
                            <option value="SOIR">
                                <?php echo localize('AskForAppointment-Evening'); ?></option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="TypeOfTreatment">*<?php echo localize('AskForAppointment-TypeOfTreatment'); ?></label>
                    <div>
                        <select id="TypeOfTreatment" name="TypeOfTreatment">
                            <option disabled selected value></option>
                            <option value="Régulier"><?php echo localize('AskForAppointment-Treatment-Regular'); ?></option>
                            <option value="Rendez-vous d'1h30"><?php echo localize('AskForAppointment-Treatment-1h30')?></option>
                            <option value="Pierres chaudes"><?php echo localize('AskForAppointment-Treatment-HotStones')?></option>

                        </select>
                    </div>
                </div>
                    <div class="form-group">
                        <label for="moreInformation"><?php echo localize('Appointment-AskForAppointmentDescription')?>: </label>
                        <textarea class="form-control" rows="5" id="moreInformation" name="moreInformation"></textarea>
                    </div>
                <button type="submit" class="btn sent-butnn"><?php echo localize('Send') ?></button>
            </form>
    </div>
</div></section>
<script>
    $(document).ready(function() {
        $('#AskForAppointmentPhoneNumber').mask('(000) 000-0000');
        $("#AskForAppointmentInformation").validate({
            errorClass : "error_class",
            errorElement : "em",
            rules:{
                AskForAppointmentUserName :{
                    required: true
                },
                AskForAppointmentPhoneNumber :{
                    required: true,
                    minlength: 13
                },
                AskForAppointmentEmail :{
                    required: true
                },
                askForAppointmentDate :{
                    required: true
                },
                appointmentTimeOfDay :{
                    required: true
                },
                TypeOfTreatment :{
                    required: true
                }
            },
            messages:{
                AskForAppointmentUserName :{
                    required:'<?php echo localize('Validate-Error-RequiredField'); ?>'
                },
                AskForAppointmentPhoneNumber :{
                    required:'<?php echo localize('Validate-Error-RequiredField'); ?>',
                    minlength:'<?php echo localize('Validate-Error-ValidPhone'); ?>'
                },
                AskForAppointmentEmail :{
                    required:'<?php echo localize('Validate-Error-RequiredField'); ?>'
                },
                askForAppointmentDate :{
                    required:'<?php echo localize('Validate-Error-RequiredField'); ?>'
                },
                appointmentTimeOfDay :{
                    required:'<?php echo localize('Validate-Error-RequiredField'); ?>'
                },
                TypeOfTreatment :{
                    required:'<?php echo localize('Validate-Error-RequiredField'); ?>'
                }
            }
        })
    })
</script>
<?php $contenu = ob_get_clean();
$onHomePage = false;
require 'gabarit.php'; ?>
