<?php
$titre = localize('Appointment-AskForAppointment');
ob_start(); ?>
<section class="contact py-lg-4 py-md-3 py-sm-3 py-3">
    <div class="container py-lg-5 py-md-4 py-sm-4 py-3">
    <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize($titre) ?></h3>
    <div class="container">
        <p><?php echo localize('Report-Bug-Instructions')?></p></br>
            <form action="?action=send_ask_for_appointment" method="post">
                <?php if(!isset($_SESSION['userid'])){ ?>
                    <div class="form-group">
                        <label for="AskForAppointment-UserName">*<?php echo localize('Inscription-Lastname')?>: </label>
                        <input class="form-control" id="AskForAppointment-UserName" required>
                    </div>

                    <div class="form-group">
                        <label for="AskForAppointment-PhoneNumber">*<?php echo localize('Personal-Phone')?>: </label>
                        <input class="form-control" id="AskForAppointment-PhoneNumber" required>
                    </div>

                    <div class="form-group">
                        <label for="AskForAppointment-Email">*<?php echo localize('Login-Email')?>: </label>
                        <input type="email" class="form-control" id="AskForAppointment-Email"
                               aria-describedby="emailHelp" placeholder="<?php echo localize('Login-Email') ?>" required>
                    </div>
                <?php }?>

                    <div class="form-group">
                            <label for="askForAppointmentDate"><p>*<?php echo localize('Appointment-DateRequested'); ?></p></label>
                            <input type="date" min="<?php echo date('Y-m-d'); ?>" id="askForAppointmentDate"
                                   name="askForAppointmentDate" class="form-control"
                                   placeholder="Date du rendez-vous" required>
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
                            <option value=""><?php echo localize('AskForAppointment-Treatment-Regular'); ?></option>
                            <option value="1h30"><?php echo localize('AskForAppointment-Treatment-1h30')?></option>
                            <option value="PM"><?php echo localize('AskForAppointment-Treatment-HotStones')?></option>

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
<?php $contenu = ob_get_clean();
$onHomePage = false;
require 'gabarit.php'; ?>
