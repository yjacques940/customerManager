<?php
$title = localize('PageTitle-NewAppointments');
ob_start();
?>
<script language="JavaScript">
    function checkAll(source) {
            checkboxes = document.getElementsByName('checkbox_new');
            for(var i=0;i<checkboxes.length;i++) {
                checkboxes[i].checked = source.checked;
            }
            changeButtonText();
    }

    function changeButtonText() {
        hasToSave = hasACheckboxChecked();
        button = document.getElementById('button_save_and_show_appointments');
        button.firstChild.data = hasToSave ? "<?php echo localize('NewAppointments-ShowAppointmentsAndSave')?>":
          "<?php echo localize('NewAppointments-ShowAppointments'); ?>"
        unCheckTheCheckAllCheckbox(hasToSave);
    }

    function unCheckTheCheckAllCheckbox(hasToSave)
    {
        checkbox = document.getElementById('chk_allCheckboxes');
        if(!hasToSave) { checkbox.checked = false; }
        checkbox.checked = areAllChecked();
    }

    function areAllChecked()
    {
        checkboxes = document.getElementsByName('checkbox_new');
        count = 0;
        for(var i=0;i<checkboxes.length;i++){
            if(checkboxes[i].checked)
            {
                count++;
            }
        }
        return count === checkboxes.length;
    }
    function hasACheckboxChecked()
    {
        checkboxes = document.getElementsByName('checkbox_new');
        for(var i=0;i<checkboxes.length;i++) {
            if(checkboxes[i].checked)
            {
                return true;
            }
        }
    }

    function getTheCheckedBoxesId()
    {
        ids = [];
        checkboxes = document.getElementsByName('checkbox_new');
        for(var i=0;i<checkboxes.length;i++) {
            if(checkboxes[i].checked)
            {
               ids.push(checkboxes[i].id);
            }
        }
        return ids;
    }

    function saveIsNewStatusIfChecked()
    {
        let newAppointmentIds;
        if (hasACheckboxChecked()) {
            newAppointmentIds = getTheCheckedBoxesId();
            $.ajax({
                url: '?action=changeAppointmentIsNewStatus',
                type: "post",
                data: {newAppointmentIds},
                success: function (output) {
                    if (output === 'success') {
                        alert('Enregistrement effectué avec succès.');
                        window.location.href = '?action=appointments';
                    } else {
                        alert(output);
                        alert('Une erreur est survenue lors de l\'enregistrement')
                    }
                }
            });
        }
        else
        {
            window.location.href = '?action=appointments';
        }
    }
</script>
<div class=" mx-auto" style="margin-top: 30px; width: 90%">
    <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2">
        <?php echo localize('PageTitle-NewAppointments') ?></h3>
        <a href="#" class="btn btn-success" style="float:right; margin-bottom: 10px;"
           id="button_save_and_show_appointments" onClick="saveIsNewStatusIfChecked()">
            <?php echo localize('NewAppointments-ShowAppointments'); ?>
        </a>

    <table class="table table-sm table-striped table-hover table-bordered" id="tbl_appointments">
        <thead class="thead-dark">
            <tr class="text-center">
                <th scope="col"><?php echo localize('Appointment-Date'); ?></th>
                <th scope="col"><?php echo localize('Appointment-Time'); ?></th>
                <th scope="col"><?php echo localize('Appointment-Duration'); ?></th>
                <th scope="col"><?php echo localize('Appointment-Customer'); ?></th>
                <th scope="col"><?php echo localize('Appointment-ChangeNewStatus'); ?>
                    <input onClick="checkAll(this)" type="checkbox" id="chk_allCheckboxes"></th>
            </tr>
        </thead>
        <tbody>
        <?php
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
                <td class="align-middle text-center">
                   <input onClick="changeButtonText()" id="<?php echo $appointment->appointment->id ?>"
                          name="checkbox_new" type="checkbox" style="width:32px;height: 32px;">
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>
<?php
$contenu = ob_get_clean();
$onHomePage = false;
require 'gabarit.php';
require 'OnClick.html';
?>
