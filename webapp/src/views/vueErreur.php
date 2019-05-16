<?php $title = 'Error'; ?>

<?php ob_start() ?>
<p>Error : <?php echo $msgErreur ?></p>
<?php $contenu = ob_get_clean();
$onHomePage = false;?>

<?php require 'gabarit.php'; ?>
