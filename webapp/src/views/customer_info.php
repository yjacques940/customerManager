<?php
$titre = localize('Email-Send-Admins');
ob_start();
unset($_SESSION['TempCustomerId']);
?>
<section class="about-inner py-lg-4 py-md-3 py-sm-3 py-3">
        <section class="contact py-lg-4 py-md-3 py-sm-3 py-3">
            <div class="container py-lg-5 py-md-4 py-sm-4 py-3">
                <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize('CustomersManagement-CustomerInfo') ?></h3>
                <div class="row w3pvt-info-para pt-lg-5 pt-md-4 pt-3">
                    <div class="col-lg-6 col-md-6">
                        <?php if($customerInfo->sex =='M'){
                            echo '<p><b>'.localize('Name') .':</b> M. '. $customerInfo->fullName .'</p>';
                        }
                        elseif($customerInfo->sex == 'F')
                        {
                            echo '<p>Mme. '. $customerInfo->fullName .'</p>';
                        }?>
                        <p><b><?php echo localize('Personal-DateOfBirth'); ?>: </b><?php echo $customerInfo->birthDate ;?></p>
                        <p><b><?php echo localize('Footer-Text-Address') ?>: </b><?php echo $customerInfo->fullAddress ;?></p>
                        <p><b><?php echo localize('Personal-Occupation')?>: </b><?php echo  $customerInfo->occupation ;?></p>
                        <p><b><?php echo localize('Login-Email'); ?>: </b><?php echo $customerInfo->email ? $customerInfo->email : ' '  ;?></p>

                    </div>
                    <div class="col-lg-6 col-md-6">

                        <?php echo '<u>'. localize('PhoneNumbers') .'</u>';
                        foreach($customerInfo->phoneNumbers as $phone){
                            if($phone->extension)
                            {
                                echo '<p><b>' .$phone->phoneType. ':</b> ' . $phone->phone.' Ext.  ' . $phone->extension .'</p>';
                            }
                            else
                            {
                                echo '<p><b>' .$phone->phoneType. ':</b> ' .$phone->phone.'</p>';
                            }
                        } ?>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <a href="?action=mainMedicalSurvey&idCustomer=<?php echo $_GET['customerId']?>"><button  class="btn btn-lg btn-primary mr-2"><?php echo localize('Header-MedicalSurvey'); ?></button></a>
                <a href=""><button  class="btn btn-lg btn-primary mr-2"><?php echo localize('Customers-Appointments'); ?></button></a>
                <a href="?action=followuplist&customerId=<?php echo $customerId?>"><button  class="btn btn-lg btn-primary"><?php echo localize('Customers-FollowUps'); ?></button></a>
                <a href="?action=personalinformation&idCustomer=<?php echo $_GET['customerId']?>"><button class="btn btn-lg btn-primary mr-2"><?php echo localize('Personal-ModificationEmployee'); ?></button></a>
            </div>
            <div class="text-center" style="padding-top: 3%">
                <a href=""><button type="submit" class="btn btn-danger" disabled><?php echo localize('Customers-Disable'); ?></button></a>
            </div>
        </section>
    </div>
</section>
<?php $contenu = ob_get_clean();
$onHomePage = false;
require 'gabarit.php'; ?>
