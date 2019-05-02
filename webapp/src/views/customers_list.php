<?php
if (!userHasPermission('customers-read')) error(403);
$title = localize('CustomersList');
ob_start(); ?>
<div class=" mx-auto" style="margin-top: 30px; width: 90%">
    <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2">
        <?php echo $title ?></h3>


    <table class="table table-sm table-striped table-hover table-bordered" id="tbl_appointments">
        <thead class="thead-dark">
        <tr class="text-center">
            <th scope="col"><?php echo localize('Appointment-Customer'); ?></th>
            <th scope="col"><?php echo localize('Personal-Phone'); ?></th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($data->value as $item) {
            ?>
            <tr id="<?php echo $item->customer->id; ?>">
                <td scope="row" class="align-middle text-center">
                    <?php
                    echo $item->customer->firstName . ' ' . $item->customer->lastName;
                    ?>
                </td>
                <td>
                    <?php
                    foreach ($item->phoneNumberAndTypes as $phoneNumber) {
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
                <td class="text-center align-middle" width="50px">
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false" style="border-radius: 5px">
                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item"
                                href="?action=showCustomerInfo&customerId=<?php echo $item->customer->id ?>">
                                <i class="fa fa-address-card-o" aria-hidden="true"></i>
                                <?php echo localize('Customers-Information') ?>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item"
                                href="?action=reserveappointment&customerId=<?php echo $item->customer->id ?>">
                                <i class="fa fa-calendar-o" aria-hidden="true"></i>
                                <?php echo localize('PageTitle-NewAppointment') ?>
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>
<?php $contenu = ob_get_clean();
$onHomePage = false;
require 'gabarit.php';
require 'OnClick.html'?>
