<?php
$titre = localize('Inscription-Title');
 ob_start(); ?>

<section class="contact py-lg-4 py-md-3 py-sm-3 py-3">
  <div class="container py-lg-5 py-md-4 py-sm-4 py-3">
    <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize('Inscription-Title'); ?></h3>
    <div id="empty" style="color:#F00"></div>
    <div class="row w3pvt-info-para pt-lg-5 pt-md-4 pt-3">
      <div class="col-lg-6 col-md-6">
        <form action="index.php?action=inscription" id="forminscription1" method="post">
          <div class="w3pvt-wls-contact-mid">
            <div class="radio">
              <div class="form-check form-check-inline">
                <label class="form-check-inline">
                <input type="radio" class="form-check-input" name="gender" value="F">
                <h4><?php echo localize('Inscription-Madam'); ?></h4></label>
              </div>
              <div class="form-check form-check-inline">
                <label class="form-check-inline">
                <input type="radio" class="form-check-input" name="gender" value="M">
                <h4><?php echo localize('Inscription-Sir'); ?></h4></label>
              </div>
            </div>
            <div class="form-group contact-forms">
              <label for="firstname"><h4><?php echo localize('Inscription-Firstname'); ?></h4></label>
              <input type="text" name="firstname" id="firstname" class="form-control" placeholder="<?php echo localize('Inscription-Firstname'); ?>">
            </div>
            <div class="form-group contact-forms">
              <label for="lastname"><h4><?php echo localize('Inscription-Lastname'); ?></h4></label>
              <input type="text" name="lastname" id="lastname" class="form-control" placeholder="<?php echo localize('Inscription-Lastname'); ?>">
            </div>
            <div class="form-group contact-forms">
              <label for="dateofbirth"><h4><?php echo localize('Personal-DateOfBirth');?></h4></label>
              <input type="date" name="dateofbirth" id="dateofbirth" class="datepicker"
                     max="<?php echo date("Y-m-d"); ?>">
            </div>
            <div class="form-group contact-forms">
              <label for="email"><h4><?php echo localize('Login-Email'); ?></h4></label>
              <input type="email" id="email" name="email" class="form-control" placeholder="<?php echo localize('Login-Email'); ?>">
              <div id="emailinuse" style="color:#F00"></div>
            </div>
            <div class="form-group contact-forms">
                <label for="email2"><h4><?php echo localize('Inscription-ConfirmEmail'); ?></h4></label>
              <input type="email" id="email2" name="email2" class="form-control" placeholder="<?php echo localize('Login-Email'); ?>">
            </div>
            <div id="emailerror" style="color:#F00"></div>
            <div class="form-group contact-forms">
              <label for="password"><h4><?php echo localize('Login-Password'); ?></h4></label>
              <input type="password" name="password" id="password" class="form-control" placeholder="<?php echo localize('Login-Password'); ?>" >
            </div>
            <div class="form-group contact-forms">
              <label for="password2"><h4><?php echo localize('Inscription-ConfirmPassword'); ?></h4></label>
              <input type="password" name="password2" id="password2" class="form-control" placeholder="<?php echo localize('Login-Password'); ?>" >
            </div>
            <div id="passworderror" style="color:#F00"></div>
            <div>
                <button type="submit" class="btn sent-butnn"><?php echo localize('Inscription-NextStep'); ?></button>
            </div>
          </div>
        </form>
      </div>
      <div class=" col-lg-6 col-md-6 ">
        <div >
          <h4><?php echo localize('Inscription-AlreadyRegistered'); ?>?</h4>
        </div>
        <div class=" mt-3">
        <a href="index.php?action=login"><h4><?php echo localize('Inscription-Login'); ?>!</h4></a>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
$(document).ready(function(){

    $("#forminscription1").validate({
        errorClass : "error_class",
        errorelement : "em",
        rules:{
            gender:{
                required:true
            },
            password: {
                required : true,
                minlength : 7
            },
            password2: {
                required : true,
                equalTo:"#password"
            },
            firstname:{
                required:true
            },
            dateofbirth:{
                required:true
            },
            lastname:{
                required:true
            },
            email:{
                required:true,
                email:true
            },
            email2:{
                required:true,
                email:true,
                equalTo:"#email"
            }
        },
        messages:{
            gender:{
                required :'<?php echo localize('Validate-Error-RequiredField'); ?>.'
            },
            firstname:{
                required :'<?php echo localize('Validate-Error-RequiredField'); ?>.'
            },
            lastname:{
                required :'<?php echo localize('Validate-Error-RequiredField'); ?>.'
            },
            email:{
                required :'<?php echo localize('Validate-Error-RequiredField'); ?>.',
                email: '<?php echo localize('Validate-Error-InvalidEmail'); ?>.'
            },
            dateofbirth:{
                required :'<?php echo localize('Validate-Error-RequiredField'); ?>.'
            },
            email2:{
                required :'<?php echo localize('Validate-Error-RequiredField'); ?>.',
                email: '<?php echo localize('Validate-Error-InvalidEmail'); ?>.',
                equalTo: '<?php echo localize('Validate-Error-EmailDontMatch'); ?>.'
            },
            password2:{
                required : '<?php echo localize('Validate-Error-RequiredField'); ?>.',
                equalTo:'<?php echo localize('Validate-Error-PasswordDontMatch'); ?>.'
            },
            password:{
                required : '<?php echo localize('Validate-Error-RequiredField'); ?>.',
                minlength: '<?php echo localize('Validate-Error-PasswordMinLength'); ?>.'
            }
        },
        errorPlacement: function(error, element){
            if(element.is(":radio")){
                error.appendTo(element.parents('.radio'));
            }
            else{
                error.insertAfter(element);
            }
        },
        submitHandler:function(){
            var output = $.ajax({
                url:"index.php",
                type:'POST',
                dataType: 'html',
                data:{email:$("#email").val(),email2:$("#email2").val(),password:$("#password").val(),
                      password2:$("#password2").val(),firstname:$("#firstname").val(),lastname:$("#lastname").val(),
                      gender:$("[name=gender]:checked").val(), dateofbirth:$('#dateofbirth').val()},
                success:function(output){
                    if(output.trim() == 'availlable'){
                        window.location = 'index.php?action=inscription';
                    }
                    else if(output.trim() == 'taken'){
                        $("#emailinuse").html("<p><?php echo localize('Validate-Error-EmailInUse'); ?>.</p>");
                    }
                    else if(output.trim() == 'passworderror'){
                        $("#passworderror").html("<p><?php echo localize('Validate-Error-PasswordDontMatch'); ?>.</p>");
                    }else if(output.trim() == 'emailerror'){
                        $("#emailerror").html("<p><?php echo localize('Validate-Error-EmailDontMatch'); ?>.</p>");
                    }else if(output.trim() == 'emptyfield'){
                        $("#empty").html("<p> <?php echo localize('Validate-Error-EmptyFields'); ?>.</p>");
                    }
                },
            });
        }
    });
});
</script>
<?php $contenu = ob_get_clean(); 
$onHomePage = false;
require 'gabarit.php'; ?>