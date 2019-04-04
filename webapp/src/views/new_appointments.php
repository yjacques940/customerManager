<?php
$title = localize('PageTitle-NewAppointments');
ob_start();
?>

<div class=" mx-auto" style="margin-top: 30px; width: 90%">
        <a href="?action=appointments" class="btn btn-success" style="float:right; margin-bottom: 10px;">
            <i class="fa fa-lg" ></i> <?php echo localize('Button-ShowAppointments'); ?>
        </a>

    <table class="table table-sm table-striped table-hover" id="tbl_appointments">
        <thead class="thead-dark">
        <tr>
            <th scope="col"><?php echo localize('Appointment-Date'); ?></th>
            <th scope="col"><?php echo localize('Appointment-Time'); ?></th>
            <th scope="col"><?php echo localize('Appointment-Duration'); ?></th>
            <th scope="col"><?php echo localize('Appointment-Customer'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $count =0;
        foreach ($newAppointments as $appointment) {
            ?>
            <tr id="<?php echo $appointment->appointment->idCustomer; ?>">
                <td scope="row">
                    <?php
                    $appointmentDate = new DateTime($appointment->appointment->appointmentDateTime);
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
                        <table style="width:100%; background-color: rgba(255,255,255,0)">
                            <tr>
                                <th><?php echo $phoneNumber->idPhoneType; ?></th>
                                <td><?php echo $phoneNumber->phone.$phoneNumber->extension; ?></td>
                            </tr>
                        </table>
                        <?php
                    }
                    ?>
                </td>
            </tr>
            <?php
            $count++;
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
