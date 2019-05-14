<?php
$title = localize('FollowUp-Add');
 ob_start(); ?>

  <div class="container py-lg-5 py-md-4 py-sm-4 py-3">
    <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize('FollowUp-Add'); ?></h3>
    <div class="row w3pvt-info-para pt-lg-5 pt-md-4 pt-3">
    <div class="col-lg-12 col-md-12">
        <h4 class=" text-center">
        <?php echo $customerName; ?>
        </h4>
      </div>
      <div class="col-lg-12 col-md-12">
        <form action="index.php?action=newFollowUp" id="newFollowUp" method="post">
            <input type="hidden" name="customerid" value="<?php echo $_GET['customerid']?>"/>
          <div class="w3pvt-wls-contact-mid">
            <div class="col-lg-4 col-md-4">
                <div class="form-group contact-forms">
                    <label for="date"><h4><?php echo localize('Appointment-Date');?></h4></label>
                    <input type="date" name="date" id="date" class="datepicker" value="<?php echo date('Y-m-d')?>">
                </div> 
            </div>
            <div class="col-lg-12 col-md-12">
                <div class="form-group contact-forms">
                    <label for="summary"><h4><?php echo localize('FollowUp-Summary'); ?></h4></label>
                    <input type="text" id="summary" name="summary" class="form-control" placeholder="<?php echo localize('FollowUp-Summary'); ?>">
                </div>
                <div class="form-group contact-forms">
                    <label for="detail"><h4><?php echo localize('FollowUp-Treatment'); ?></h4></label>
                    <textarea id="detail" name="detail" class="md-textarea form-control" rows="3"></textarea>
                </div>
            </div>
          </div>
          <button type="submit" class="btn sent-butnn"><?php echo localize('FollowUp-Add');?></button>
        </form>
      </div>
    </div>
  </div>
  <script>
$(document).ready(function(){
    $("#newFollowUp").validate({
        errorClass : "error_class",
        errorelement : "em",
        rules:{
            date:{
                required:true
            },
            summary: {
                required : true
            },
            detail: {
                required : true
            }
        },
        messages:{
            date:{
                required :'<?php echo localize('Validate-Error-RequiredField'); ?>.'
            },
            summary:{
                required :'<?php echo localize('Validate-Error-RequiredField'); ?>.'
            },
            detail:{
                required :'<?php echo localize('Validate-Error-RequiredField'); ?>.'
            }
        },
    });
});
</script>
<?php $contenu = ob_get_clean(); 
$onHomePage = false;
require 'gabarit.php'; ?>
