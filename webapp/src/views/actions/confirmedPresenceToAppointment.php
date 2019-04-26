<?php
$title = 'ConfirmedPresenceToAppointment';
if (!isset($idAppointment) || !isset($idUser)) error(500);
ob_start();
$customer = CallAPI('GET', 'Customers/ByUserId/'.$idUser)['response'];
//$timeslot = CallAPI('GET', 'TimeSlots/GetByAppointment/'.$idAppointment)['response'];
?>
<div class=" mx-auto" style="margin-top: 30px; width: 90%">
    <h2 class="text-center mb-md-4 mb-sm-3 mb-3 mb-2">
        <?php echo $customer->firstName . ' ' . $customer->lastName ?>, votre rendez-vous a été confirmé!
    </h2>
</div>
<?php
$contenu = ob_get_clean();
require 'views/gabarit.php';
?>
