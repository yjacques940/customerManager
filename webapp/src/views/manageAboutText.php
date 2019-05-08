<?php
$title = localize('FollowUp-Add');
 ob_start(); ?>

<div class="container py-lg-5 py-md-4 py-sm-4 py-3">
    <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize('FollowUp-Add'); ?></h3>
    <div class="row w3pvt-info-para pt-lg-5 pt-md-4 pt-3">
      <div class="col-lg-12 col-md-12">
        <form action="index.php?action=manageabouttext" id="manageabouttext" method="post">
          <div class="w3pvt-wls-contact-mid">
          <div class="row">
            <div class="col-lg-6 col-md-8">
                <div class="form-group contact-forms">
                    <label for="titlefr"><h4><?php echo localize('Title');?></h4></label>
                    <input type="text" name="titlefr" id="titlefr" class="form-control">
                </div> 
                <div class="form-group contact-forms">
                    <label for="descrfr"><h4><?php echo localize('FollowUp-Treatment'); ?></h4></label>
                    <textarea id="descrfr" name="descrfr" class="md-textarea form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="col-lg-6 col-md-8">
                <div class="form-group contact-forms">
                    <label for="titleen"><h4><?php echo localize('Title');?></h4></label>
                    <input type="text" name="titleen" id="titleen" class="form-control">
                </div> 
                <div class="form-group contact-forms">
                    <label for="descren"><h4><?php echo localize('Title');?></h4></label>
                    <textarea id="descren" name="descren" class="md-textarea form-control" rows="3"></textarea>
                </div> 
                </div>
            </div>
          </div>
          <button type="submit" class="btn sent-butnn"><?php echo localize('FollowUp-Add');?></button>
        </form>
        <table class="table table-sm table-striped table-hover table-bordered" id="tbl_appointments">
        <thead class="thead-dark">
        <tr class="text-center">
            <th scope="col"><?php echo localize('Appointment-Customer'); ?></th>
            <th scope="col"><?php echo localize('Personal-Phone'); ?></th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        </tbody>
        </table>
      </div>
    </div>
  </div>
  <script>
$(document).ready(function(){
    $("#manageabouttext").validate({
        errorClass : "error_class",
        errorelement : "em",
        rules:{
            titlefr:{
                required:true
            },
            descrfr: {
                required : true
            }
        },
        messages:{
            titlefr:{
                required :'<?php echo localize('Validate-Error-RequiredField'); ?>.'
            },
            descrfr:{
                required :'<?php echo localize('Validate-Error-RequiredField'); ?>.'
            }
        },
    });
});
</script>
<?php $contenu = ob_get_clean(); 
$onHomePage = false;
require 'gabarit.php'; ?>
