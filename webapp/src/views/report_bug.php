<?php
$titre = localize('Report-Problem');
ob_start(); ?>
<section class="contact py-lg-4 py-md-3 py-sm-3 py-3">
    <div class="container py-lg-5 py-md-4 py-sm-4 py-3">
    <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize($titre) ?></h3>
    <div class="container">
        <p><?php echo localize('Report-Bug-Instructions')?></p></br>
            <form action="?action=send_bug" method="post">
                    <div class="form-group">
                        <label for="bug-description"><?php echo localize('Bug-Description')?> :</label>
                        <textarea class="form-control" rows="5" id="bug-description" name="bug-description" required></textarea>
                    </div>
                <button type="submit" class="btn sent-butnn"><?php echo localize('Send') ?></button>
            </form>
    </div>
</div></section>
<?php $contenu = ob_get_clean();
$onHomePage = false;
require 'gabarit.php'; ?>
