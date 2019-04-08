<?php
$title = localize('PageTitle-Error');
ob_start();
$errorCode = 403;
?>

<div class="text-center">
    <h2><?php echo localize('Error-Title').$errorCode ?></h2>
    <strong><?php echo localize('Error-Reason-'.$errorCode) ?></strong>
    <p><?php echo localize('Error-Help') ?></p>
</div>

<?php
  $contenu = ob_get_clean();
  $onHomePage = false;
  require 'gabarit.php';
  require 'OnClick.html'
?>
