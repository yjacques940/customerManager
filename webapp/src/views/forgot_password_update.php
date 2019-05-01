<?php
$titre = localize('Header-Manage-Password');
ob_start(); ?>
<section class="contact py-lg-4 py-md-3 py-sm-3 py-3">
    <div class="container py-lg-5 py-md-4 py-sm-4 py-3">
        <h3 class="text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize($titre) ?></h3>
        <h5 class="text-center"><?php echo localize('ForgotPassword-Instructions') ?></h5><br/>
        <div class="container">
            <form action="?action=" method="post" id="emailAddressForm" name="emailAddressForm">
                <div class="form-group col-lg-6 col-md-6">
                    <label for="newPassword">
                        <?php echo localize('ForgotPassword-EnterEmail')?> : </label>
                    <input class="form-control" id="newPassword" name="newPassword">
                    <button type="submit" class="btn btn-primary mt-3"><?php echo localize('ForgotPassword-SendRequest'); ?></button>
                </div>
                <div class="form-group col-lg-6 col-md-6">
                    <label for="emailAddress">
                        <?php echo localize('ForgotPassword-EnterEmail')?> : </label>
                    <input class="form-control" id="emailAddress" name="emailAddress">
                    <button type="submit" class="btn btn-primary mt-3"><?php echo localize('ForgotPassword-SendRequest'); ?></button>
                </div>

            </form>
        </div>
    </div></section>
<script>
    $(document).ready(function() {
        $("#emailAddressForm").validate({
            errorClass: "error_class",
            errorElement: "em",
            rules: {
                newPassword: {
                    required: true,
                    password: true
                },
            },
            messages: {
                emailAddress: {
                    required: '<?php echo localize('Validate-Error-RequiredField'); ?>.',
                    email: '<?php echo localize('Validate-Error-InvalidEmail'); ?>.'
                },
            }
        });
    });
</script>
<?php $contenu = ob_get_clean();
$onHomePage = false;
require 'gabarit.php'; ?>
