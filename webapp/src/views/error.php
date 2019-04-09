<?php
$title = localize('PageTitle-Error');
ob_start();
$errorReason = localize('Error-Reason-'.$errorCode);
if ($errorReason == 'Error-Reason-'.$errorCode)
    $errorReason = localize('Error-Reason-Unknown');
?>

<div class="text-center">
    <h2><?php echo localize('Error-Title').$errorCode; ?></h2>
    <strong><?php echo $errorReason; ?></strong>
    <p><?php echo localize('Error-Help'); ?></p>
</div>

<?php
  $contenu = ob_get_clean();
  $onHomePage = false;
  require 'gabarit.php';
?>
