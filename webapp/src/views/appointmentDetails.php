<?php
if (isset($appointmentDetails))
    $data = $appointmentDetails;
else
    error(500);

if ($data->customer->sex =='M')
    $namePrefix = 'M. ';
elseif ($data->customer->sex =='M')
    $namePrefix = 'Mme ';

$appointmentTime = strtotime($data->timeSlot->startDateTime);
$appointmentReservedTime = strtotime($data->appointment->createdOn);

$title = localize('PageTitle-Appointments');
ob_start();
?>

<section class="container py-lg-4 py-md-3 py-sm-3 py-3">
    <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize('PageTitle-AppointmentDetails') ?></h3>
    <h4 class="text-center mb-sm-2">
        <?php echo localize('Appointment-Date') .': '. strftime("%x", $appointmentTime) ?>
    </h4>
    <h4 class="text-center mb-md-2">
        <?php echo localize('Appointment-Time') .': '. strftime("%H:%M", $appointmentTime) ?>
    </h4>
    <div class="row m-5">
        <div class="col-md-6">
            <p class="m-2">
                <b><?php echo localize('Name') ?>: </b>
                <?php echo $namePrefix . $data->customer->firstName.' '.$data->customer->lastName ?>
            </p>
            <p class="m-2">
                <?php
                    echo '<b>'. localize('Appointment-ReservationDate') .':</b> '.
                        strftime("%x %X", $appointmentReservedTime);
                ?>
            </p>
        </div>
        <div class="col-md-6">
            <div class="m-2">
                <p>
                    <?php
                        echo '<b>'. localize('Appointment-RequestedTherapist') .':</b> '. $data->appointment->therapist
                    ?>
                </p>
            </div>
            <div class="text-justify m-2">
                <p><b><?php echo localize('TimeSlot-Notes-Text')?>:</b></p>
                <p><?php echo ($data->timeSlot->notes != null) ? $data->timeSlot->notes : localize('NoNotes') ?></p>
            </div>
        </div>
    </div>
    <div class="text-center m-5">
        <a href="?action=showCustomerInfo&customerId=<?php echo $data->customer->id ?>">
            <button  class="btn btn-lg btn-primary m-1"><?php echo localize('Customers-Information'); ?></button>
        </a>
        <a href="?action=mainMedicalSurvey&idCustomer=<?php echo $data->customer->id ?>">
            <button  class="btn btn-lg btn-primary m-1"><?php echo localize('Customer-MedicalSurvey'); ?></button>
        </a>
        <a href="?action=followuplist&customerId=<?php echo $data->customer->id?>">
            <button  class="btn btn-lg btn-primary m-1"><?php echo localize('Customers-FollowUps'); ?></button>
        </a>
    </div>
    <div class="text-center">
        <a href="">
            <button type="submit" class="btn btn-danger" disabled><?php echo localize('Appointment-Delete'); ?></button>
        </a>
    </div>
</section>

<?php
  $contenu = ob_get_clean();
  require 'gabarit.php';
?>
