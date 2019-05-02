<?php
$titre = localize('Header-Manage-Password');
ob_start(); ?>
<section class="contact py-lg-4 py-md-3 py-sm-3 py-3">
    <div class="container py-lg-5 py-md-4 py-sm-4 py-3">
        <h3 class="text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize($titre) ?></h3><br/>
        <div class="container">
            <form action="?action=updatepassword&userId=<?php echo $idUser.'&token='. $_GET['token']?>"
                  method="post" id="updatepassword" name="updatepassword">
                <div class="form-group col-lg-6 col-md-6">
                    <h5> <label for="newpassword">
                        <?php echo localize('PasswordUpdate-NewPassword')?> : </label></h5>
                    <input type="password" class="form-control" id="newpassword" name="newpassword">
                </div>
                <div class="form-group col-lg-6 col-md-6">
                    <h5><label for="confirmedpassword">
                        <?php echo localize('PasswordUpdate-ConfirmPassword')?> : </label></h5>
                    <input type="password" class="form-control" id="confirmedpassword" name="confirmedpassword">

                </div>
                <div class="form-group col-lg-6 col-md-6">
                    <button type="submit" class="btn btn-primary mt-3"><?php echo localize('Header-Manage-Password'); ?></button>
                </div>
            </form>
        </div>
    </div></section>
<script>
    $(document).ready(function(){
        $("#updatepassword").validate({
            errorClass : "error_class",
            errorelement : "em",
            rules:{
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
                newpassword:{
                    required :'<?php echo localize('Validate-Error-RequiredField'); ?>.',
                    minlength: '<?php echo localize('Validate-Error-PasswordMinLength'); ?>.'
                },
                confirmedpassword:{
                    required :'<?php echo localize('Validate-Error-RequiredField'); ?>.',
                    equalTo:'<?php echo localize('Validate-Error-PasswordDontMatch'); ?>.'
                }
            }
        })
    });
    </script>
<?php $contenu = ob_get_clean();
$onHomePage = false;
require 'gabarit.php'; ?>
