<?php
$title = localize('CancelAppointment-Title');
 ob_start(); 
 ?>
<script language="JavaScript">
    function checkAll(source) {
            var checkboxes = document.getElementsByName('checkboxAppointments[]');
            for(var i=0;i<checkboxes.length;i++) {
                checkboxes[i].checked = source.checked;
            }
    }

    function CheckAllManagement()
    {
        checkbox = document.getElementById('chk_allCheckboxes');
        checkbox.checked = areAllChecked();
    }

    function areAllChecked()
    {
        var checkboxes = document.getElementsByName('checkboxAppointments[]');
        count = 0;
        for(var i=0;i<checkboxes.length;i++){
            if(checkboxes[i].checked)
            {
                count++;
            }
        }
        return count === checkboxes.length;
    }

</script>

<section class="contact py-lg-4 py-md-3 py-sm-3 py-3">
  <div class="container py-lg-5 py-md-4 py-sm-4 py-3">
    <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize('CancelAppointment-Title');?></h3>
    <div class="row w3pvt-info-para pt-lg-5 pt-md-4 pt-3">
      <div class="col-lg-10 col-md-10">
        <form action="index.php?action=cancelappointment" id="cancelappointment" method="post">
        <div class="col-lg-10 col-md-10">
        <?php
        if($tooLateToCancel != 0){
            echo '<h4>'. localize('CancelAppointment-TooLate') . ' ' .
            localize('Company-Phone') . ' ' . localize('CancelAppointment-TooLateToCancel') . '</h4>';
        } 
        else if(isset($_POST['checkboxAppointments'])){
            echo '<h4>'. localize('CancelAppointment-Success') .'</h4>';
        }
        $tooLateToCancel = false;
        ?>
        </div>
        <table class="table table-sm table-striped table-hover table-bordered" id="tbl_appointments">
        <thead class="thead-dark">
            <tr class="text-center">
                <th scope="col"><?php echo localize('Appointment-Date'); ?></th>
                <th scope="col"><?php echo localize('Appointment-Time'); ?></th>
                <th scope="col"><?php echo localize('TakeAppointment-Therapist'); ?></th>
                <th scope="col"><?php echo localize('CancelAppointment-CheckAll'); ?>
                    <input onClick="checkAll(this)" type="checkbox" id="chk_allCheckboxes"></th>
            </tr>
        </thead>
        <tbody>
        <?php
        if($appointments['response'] != null){
            foreach ($appointments['response'] as $appointment) {
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
                    <td class="align-middle text-center">
                    <input id="<?php echo $appointment->appointment->id ?>"
                            onclick="CheckAllManagement();" name="checkboxAppointments[]" type="checkbox" 
                            value="<?php echo $appointment->appointment->id ?>" style="width:32px;height: 32px;">
                    </td>
                </tr>
                <?php
            }
        }
        ?>
        </tbody>
    </table>
    <?php 
    if($appointments['response'] != null){ ?>
    <button type="submit" class="btn sent-butnn"><?php echo Localize('CancelAppointment-CancelSelected');?></button>
    <?php } ?>
        </form>
      </div>
    </div>
  </div>
</section>

<?php $contenu = ob_get_clean(); 
$onHomePage = false;
require 'gabarit.php'; ?>
