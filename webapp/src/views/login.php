<?php
$title = Localize('Login-Title');
 ob_start(); ?>
 <section class="contact py-lg-4 py-md-3 py-sm-3 py-3">
  <div class="container py-lg-5 py-md-4 py-sm-4 py-3">
    <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo Localize('Login-Title');?></h3>
    <?php
        if (isset($_POST['email'])){
            ?>
            <p class="text-center error_class mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo Localize('Login-LoginFail');?>.</p>
            <?php
        }else if(isset($_SESSION['registered'])){
          ?>
            <p class="text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo Localize('Login-Success');?>.</p>
            <?php
            unset($_SESSION['registered']);
        }
    ?>
    <div class="row w3pvt-info-para pt-lg-5 pt-md-4 pt-3">
      <div class="col-lg-6 col-md-6">
        <form action="index.php?action=login" name="connection" id="connection" method="post">
          <div class="w3pvt-wls-contact-mid">
            <div class="form-group contact-forms">
                <label for="email"><h4><?php echo Localize('Login-Email');?></h4></label>
              <input type="email" id="email" name="email" class="form-control" placeholder="<?php echo Localize('Login-Email');?>" value="<?php if (isset($_POST['email'])){
                echo $_POST['email'];
            } ?>">
            </div>
            <div class="form-group contact-forms">
              <label for="password"><h4><?php echo Localize('Login-Password');?></h4></label>
              <input type="password" name="password" id="password" class="form-control" placeholder="<?php echo Localize('Login-Password');?>" >
            </div>
            <button type="submit" class="btn sent-butnn"><?php echo Localize('Login-Title');?></button>
          </div>
        </form>
      </div>
      <div class="col-lg-6 col-md-6 ">
        <div >
          <h4><?php echo Localize('Login-NotRegistered');?>?</h4>
            <a href="index.php?action=inscription"><h5><?php echo Localize('Login-Register');?>!</h5></a>
        </div>
        <div class=" mt-3">

        </div>
          <div style="padding-top: 2%">
              <h4><?php echo Localize('Login-ForgotPassword');?>?</h4>
              <a href="index.php?action=inscription"><h5><?php echo Localize('Password-Change');?></h5></a>
          </div>
          <div class=" mt-3">

          </div>
      </div>
    </div>
  </div>
</section>
<script>
$(document).ready(function(){
    $("#connection").validate({
        errorClass : "error_class",
        errorelement : "em",
        rules:{
          email: {
                required:true,
                email:true
            }
        },
        messages:{
          email:{
                required:'<?php echo localize('Validate-Error-RequiredField'); ?>.',
                email: '<?php echo localize('Validate-Error-InvalidEmail'); ?>.' 
            }
        }
    });
});
</script>
<?php $contenu = ob_get_clean(); 
$onHomePage = false;
require 'gabarit.php'; ?>