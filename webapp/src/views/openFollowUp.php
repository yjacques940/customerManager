<?php
$title = localize('FollowUp-Inspect');
 ob_start(); ?>

  <div class="container py-lg-5 py-md-4 py-sm-4 py-3">
    <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize('FollowUp-Inspect'); ?></h3>
    <div class="row w3pvt-info-para pt-lg-5 pt-md-4 pt-3">
      <div class="col-lg-12 col-md-12">
          <div class="w3pvt-wls-contact-mid">
            <div class="col-lg-4 col-md-4">
                <div class="form-group contact-forms">
                    <label for="date"><h4><?php echo localize('Appointment-Date');?></h4></label>
                    <?php 
                    echo '<p>'. $result->createdOn . '</p>';
                    ?>
                </div> 
            </div>
            <div class="col-lg-12 col-md-12">
                <div class="form-group contact-forms">
                    <label for="summary"><h4><?php echo localize('FollowUp-Summary'); ?></h4></label>
                    <?php 
                    echo '<p>'. $result->summary . '</p>';
                    ?>
                </div>
                <div class="form-group contact-forms">
                    <label for="detail"><h4><?php echo localize('FollowUp-Treatment'); ?></h4></label>
                    <?php
                    echo '<p>' . $result->treatment . '</p>';
                    ?>
                </div>
            </div>
          </div>
      </div>
    </div>
  </div>
<?php $contenu = ob_get_clean(); 
$onHomePage = false;
require 'gabarit.php'; ?>
