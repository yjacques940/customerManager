<?php
$titre = localize('Header-Manage-Password');
ob_start(); ?>
<section class="contact py-lg-4 py-md-3 py-sm-3 py-3">
    <div class="container py-lg-5 py-md-4 py-sm-4 py-3">
        <h3 class="text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize($titre) ?></h3>
        <h5 class="text-center"><?php echo localize('ForgotPassword-Instructions') ?></h5><br/>
<div class="container">
            <form action="?action=saveMedicalSurvey" method="post" id="emailAddress" name="emailAddress">
                <div class="form-group col-lg-6 col-md-6">
                <label for="emailAddress">
                <?php echo localize('ForgotPassword-EnterEmail')?> : </label>
                <input class="form-control" id="emailAddress" name="emailAddress">
                </div>
                <button type="submit" class="btn btn-primary mb-2 "><?php ecjp ?></button>
            </form>
</div>
    </div></section>
<?php $contenu = ob_get_clean();
$onHomePage = false;
require 'gabarit.php'; ?>
