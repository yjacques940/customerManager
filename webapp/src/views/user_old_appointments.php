<?php
$title = localize('PageTitle-OldAppointments');
ob_start();
?>
<section class="contact py-lg-4 py-md-3 py-sm-3 py-3">
    <div class="container py-lg-5 py-md-4 py-sm-4 py-3">
        <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2">
            <?php echo localize('PageTitle-OldAppointments');?></h3>
        <div class="row w3pvt-info-para pt-lg-5 pt-md-4 pt-3">
            <?php if($oldAppointments['response']){?>
                <div class="col-lg-12 col-md-12">
                    <form>
                        <div class="col-lg-12 col-md-12">
                        </div>
                        <table class="table table-sm table-striped table-hover table-bordered" id="tbl_appointments">
                            <thead class="thead-dark">
                            <tr class="text-center">
                                <th scope="col"><?php echo localize('Appointment-Date'); ?></th>
                                <th scope="col"><?php echo localize('Appointment-Time'); ?></th>
                                <th scope="col"><?php echo localize('TakeAppointment-Therapist'); ?></th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($oldAppointments['response'] as $appointment) {
                                ?>
                                <tr>
                                    <td scope="row" align="center">
                                        <?php
                                        $appointmentDate = new DateTime($appointment->date);
                                        echo $appointmentDate->format('Y-m-d');
                                        ?>
                                    </td>
                                    <td align="center">
                                        <?php
                                        $startTime= new DateTime($appointment->startTime);
                                        $endTime = new DateTime($appointment->endTime);
                                        echo $startTime->format('H:i') .' - ' . $endTime->format('H:i');
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $appointment->appointment->therapist;
                                        ?>
                                    </td>
                                    <td align="center">
                                        <a style="color:inherit" title="Voir des informations supplémentaires"
                                       href="?action=showAppointmentDetails&appointmentId=
                                       <?php echo $appointment->appointment->id ?>">
                                        <i class="fa fa-info-circle fa-2x" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </form>
                </div>
            <?php } else {
                echo '<h4>' . localize('UserAppointmentsList-NoAppointmentsFound').'</h4>';
            }?>
        </div>
        <div class="text-center" style="padding-top: 2%">
            <a href="?action=cancelappointments"><button  class="btn btn-secondary">
                    <?php echo localize('UserAppointmentsList-Title'); ?></button></a>
        </div>

    </div>
</section>

<?php $contenu = ob_get_clean();
$onHomePage = false;
require 'gabarit.php'; ?>
