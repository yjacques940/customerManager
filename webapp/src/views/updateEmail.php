<?php
$title = Localize('Header-Manage-Email');
 ob_start(); ?>
 <section class="contact py-lg-4 py-md-3 py-sm-3 py-3">
  <div class="container py-lg-5 py-md-4 py-sm-4 py-3">
    <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo Localize('Header-Manage-Email');?></h3>
    <div class="row w3pvt-info-para pt-lg-5 pt-md-4 pt-3">
      <div class="col-lg-6 col-md-6">
        <form action="?action=updateemail" name="updateemail" id="updateemail" method="post">
          <div class="w3pvt-wls-contact-mid">
            <div class="form-group contact-forms">
              <label for="newemail"><h4><?php echo Localize('EmailUpdate-NewEmail');?></h4></label>
              <input type="email" name="newemail" id="newemail" class="form-control" placeholder="<?php echo Localize('EmailUpdate-NewEmail');?>">
              <div id="emailinuse" style="color:#F00"></div>
            </div>
            <div class="form-group contact-forms">
              <label for="newemailconfirmed"><h4><?php echo Localize('EmailUpdate-NewEmailConfirmed');?></h4></label>
              <input type="email" name="newemailconfirmed" id="newemailconfirmed" class="form-control" placeholder="<?php echo Localize('EmailUpdate-NewEmail');?>">
              <div id="emaildontmatch" name="emaildontmatch"></div>
            </div>
            <div class="form-group contact-forms">
              <label for="password"><h4><?php echo Localize('EmailUpdate-PasswordConfirm');?></h4></label>
              <input type="password" name="password" id="password" class="form-control" placeholder="<?php echo Localize('Login-Password');?>">
              <div id="passworderror" name="passworderror"></div>
            </div>
            <button type="submit" class="btn sent-butnn"><?php echo Localize('Header-Manage-Email');?></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
<script>
$(document).ready(function(){
    $("#updateemail").validate({
        errorClass : "error_class",
        errorelement : "em",
        rules:{
            newemail:{
                email:true,
                required:true
            },
            newemailconfirmed:{
                email:true,
                required:true,
                equalTo:"#newemail"
            },
            password:{
              required:true
            }
        },
        messages:{
            newemail:{
                required :'<?php echo localize('Validate-Error-RequiredField'); ?>.',
                email: '<?php echo localize('Validate-Error-InvalidEmail'); ?>.'
            },
            newemailconfirmed:{
                required :'<?php echo localize('Validate-Error-RequiredField'); ?>.',
                equalTo:'<?php echo localize('Validate-Error-EmailDontMatch'); ?>.',
                email: '<?php echo localize('Validate-Error-InvalidEmail'); ?>.'
            },
            password:{
              required :'<?php echo localize('Validate-Error-RequiredField'); ?>.'
            }
        },
        submitHandler:function(){
          if(confirm('<?php echo localize("PasswordUpdate-UpdateConfirmation"); ?>'))
          {
            var output = $.ajax({
                url:"index.php",
                type:'POST',
                dataType: 'html',
                data:{newemail:$("#newemail").val(),newemailconfirmed:$("#newemailconfirmed").val(),password:$("#password").val()},
                success:function(output){
                    if(output.trim() == 'changed'){
                      window.location = 'index.php?action=about';
                    }else if(output.trim() == 'taken'){
                        $("#emailinuse").html("<p><?php echo localize('Validate-Error-EmailInUse'); ?>.</p>");
                    }else if(output.trim() == 'passworderror'){
                      $("#passworderror").html('<p style="color:red;" class=" mb-md-4 mb-sm-3 mb-3 mb-2"><?php 
                      echo localize("EmailUpdate-Error-password");?></p>');
                    }else if(output.trim()== 'emaildontmatch'){
                      $("#emaildontmatch").html('<p style="color:red;" class=" mb-md-4 mb-sm-3 mb-3 mb-2"><?php 
                      echo localize("Validate-Error-EmailDontMatch");?></p>');
                    }
                },
            });
          }
        }
    });
});
</script>

<?php $contenu = ob_get_clean(); 
$onHomePage = false;
require 'gabarit.php'; ?>
