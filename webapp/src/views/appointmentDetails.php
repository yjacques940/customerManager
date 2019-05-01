<?php
if (!userHasPermission('appointments-read')) error(403);
if (isset($appointmentDetails))
    $data = $appointmentDetails;
else
    error(500);

setlocale(LC_ALL, $_SESSION['locale'].'_CA.UTF-8');
$appointmentDateTime = new DateTime($data->timeslot->startDateTime);
$appointmentReservedDateTime = new DateTime($data->appointment->createdOn);

$title = localize('PageTitle-Appointments');
ob_start();
?>

<section class="contact py-lg-4 py-md-3 py-sm-3 py-3">
    <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2">
        Informations sur le rendez-vous
    </h3>
    <h4 class="text-center mb-sm-2">
        Date: <?php echo strftime("%x", strtotime($data->timeslot->startDateTime)) ?>
    </h4>
    <h4 class="text-center mb-md-2">
        Heure: <?php echo strftime("%H:%M", strtotime($data->timeslot->startDateTime)) ?>
    </h4>
    <p class="text-center mb-sm-2">
        Réservé en date du <?php echo strftime("%x", strtotime($data->appointment->createdOn)) ?>
    </p>
    <div class="container py-lg-5 py-md-4 py-sm-4 py-3">
        <div class="row w3pvt-info-para pt-lg-5 pt-md-4 pt-3">
            <div class="col-lg-6 col-md-6">
                <p class="text-center mb-md-4 mb-sm-3 mb-3 mb-2">
                    Client: <a href=""><strong>
                        <?php echo $data->customer->firstName.' '.$data->customer->lastName ?></strong></a>
                </p>
            </div>
            <div class="col-lg-6 col-md-6">
                <p>test</p>
            </div>
        </div>
    </div>
</section>

<?php
  $contenu = ob_get_clean();
  require 'gabarit.php';
?>
