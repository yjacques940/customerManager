<?php
$titre = localize('Email-Send-Admins');
ob_start(); ?>

<a href="#">Consulter le questionnaire : id client = <?php echo $customerId?></a>
<?php $contenu = ob_get_clean();
$onHomePage = false;
require 'gabarit.php'; ?>
