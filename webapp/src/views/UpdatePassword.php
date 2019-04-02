<?php
$titre = Localize('Header-Manage-Password');
 ob_start(); ?>
 <section class="contact py-lg-4 py-md-3 py-sm-3 py-3">
  <div class="container py-lg-5 py-md-4 py-sm-4 py-3">
    <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo Localize('Header-Manage-Password');?></h3>
    <?php
        if (isset($_POST['oldpassword'])){
            ?>
            <p class="text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo Localize('PasswordUpdate-Fail');?>.</p>
            <?php
        }
    ?>
    <div class="row w3pvt-info-para pt-lg-5 pt-md-4 pt-3">
      <div class="col-lg-6 col-md-6">
        <form action="?action=updatepassword" name="updatepassword" id="updatepassword" method="post">
          <div class="w3pvt-wls-contact-mid">
            <div class="form-group contact-forms">
                <label for="oldpassword"><h4><?php echo Localize('PasswordUpdate-OldPassword');?></h4></label>
              <input type="password" id="oldpassword" name="oldpassword" class="form-control" placeholder="<?php echo Localize('PasswordUpdate-OldPassword');?>">
            </div>
            <div class="form-group contact-forms">
              <label for="newpassword"><h4><?php echo Localize('PasswordUpdate-NewPassword');?></h4></label>
              <input type="password" name="newpassword" id="newpassword" class="form-control" placeholder="<?php echo Localize('PasswordUpdate-NewPassword');?>">
            </div>
            <div class="form-group contact-forms">
              <label for="confirmedpassword"><h4><?php echo Localize('PasswordUpdate-ConfirmPassword');?></h4></label>
              <input type="password" name="confirmedpassword" id="confirmedpassword" class="form-control" placeholder="<?php echo Localize('PasswordUpdate-ConfirmPassword');?>">
            </div>
            <button type="submit" class="btn sent-butnn"><?php echo Localize('Header-Manage-Password');?></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
<script>
$(document).ready(function(){
    $("#updatepassword").validate({
        errorClass : "error_class",
        errorelement : "em",
        rules:{
          oldpassword: {
                required:true
            },
            newpassword:{
                required:true,
                minlength: 7
            },
            confirmedpassword:{
                required:true,
                equalTo:"#newpassword"
            }
        },
        messages:{
          oldpassword:{
                required:'<?php echo localize('Validate-Error-RequiredField'); ?>.',
            },
            newpassword:{
                required :'<?php echo localize('Validate-Error-RequiredField'); ?>.',
                minlength: '<?php echo localize('Validate-Error-PasswordMinLength'); ?>.'
            },
            confirmedpassword:{
                required :'<?php echo localize('Validate-Error-RequiredField'); ?>.',
                equalTo:'<?php echo localize('Validate-Error-PasswordDontMatch'); ?>.'
            }
        },
        submitHandler:function(){
          if(confirm('<?php echo localize("PasswordUpdate-UpdateConfirmation"); ?>'))
          {
            form.submit();
          }
        }
    });
});
</script>

<?php $contenu = ob_get_clean(); 
$onHomePage = false;
require 'gabarit.php'; ?>