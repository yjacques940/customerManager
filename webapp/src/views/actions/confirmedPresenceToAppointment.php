<?php
$title = 'ConfirmedPresenceToAppointment';
if (!isset($idAppointment)) error(500);
ob_start();
$appointment = CallAPI('GET', 'Appointments/AppointmentsAndCustomers')['response'];
?>

<?php
require('OnClick.html');
$contenu = ob_get_clean();
require 'gabarit.php'; ?>
