<?php
if (!userHasPermission('customers-read') || !userHasPermission('appointments-write')) error(403);
$title = localize('PageTitle-Appointments');
ob_start();
?>

<div class="mx-auto" style="margin-top: 30px; width: 90%">
    <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize('UserAppointmentsList-Title');?></h3>
        <table class="table table-sm table-striped table-hover border" id="tbl_appointments">
            <thead class="thead-dark">
                <tr>
                    <th scope="col"><?php echo localize('Appointment-Date'); ?></th>
                    <th scope="col"><?php echo localize('Appointment-Time'); ?></th>
                    <th scope="col"><?php echo localize('Appointment-Customer'); ?></th>
                    <th scope="col"><?php echo localize('Personal-Phone'); ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = CallAPI('GET', 'Appointments/AppointmentsAndCustomers');
                $appointments = $result['response'];
                if($appointments)
                {
                    foreach ($appointments as $appointment) {
                    ?>
                    <tr id="<?php echo $appointment->appointment->idCustomer; ?>">
                        <td scope="row">
                        <?php
                            $appointmentDate = new DateTime($appointment->timeSlot->startDateTime);
                            echo $appointmentDate->format('Y-m-d');
                        ?>
                        </td>
                        <td>
                        <?php
                            echo $appointmentDate->format('H:i');
                        ?>
                        </td>
                        <td>
                        <?php
                            echo $appointment->customer->firstName
                                ." ".
                                $appointment->customer->lastName;
                        ?>
                        </td>
                        <td>
                        <?php
                        foreach ($appointment->phoneNumbers as $phoneNumber) {
                        ?>
                            <table style="width:100%;">
                                <tr>
                                    <div>
                                        <td style="text-align: right; border: none; width: 45%;"><?php echo $phoneNumber->phoneType . " :"; ?></td>
                                        <td style="text-align: left; border: none; float:left;">
                                            <?php echo $phoneNumber->phone; ?>
                                            <?php
                                            if($phoneNumber->extension)
                                            {
                                                echo "&nbsp&nbsp Ext. " .$phoneNumber->extension ;
                                            }
                                            ?>
                                        </td>

                                    </div>
                                </tr>
                            </table>
                        <?php
                        }
                        ?>
                        </td>
                        <td class="align-middle text-center">
                            <a style="color:inherit" title="<?php echo localize('SeeMoreInfo') ?>"
                                href="?action=showAppointmentDetails&appointmentId=<?php echo $appointment->appointment->id ?>">
                            <i class="fa fa-info-circle fa-2x" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                    <?php
                    }
                }
                ?>
            </tbody>
        </table>
</div>
<?php
  $contenu = ob_get_clean();
  $onHomePage = false;
  require 'gabarit.php';
  require 'OnClick.html'
?>
