<?php
$title = localize('Header-TakeAppointment');
 ob_start(); ?>

<section class="contact py-lg-4 py-md-3 py-sm-3 py-3">
  <div class="container py-lg-5 py-md-4 py-sm-4 py-3">
    <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize('Header-TakeAppointment'); ?></h3>
    <div id="empty" style="color:#F00"></div>
    <div class="row w3pvt-info-para pt-lg-5 pt-md-4 pt-3">
      <div class="col-lg-6 col-md-6">
        <form action="index.php?action=inscription" id="forminscription1" method="post">
         
        </form>
      </div>
    </div>
  </div>
  </div>
</section>

<?php $contenu = ob_get_clean(); 
$onHomePage = false;
require 'gabarit.php'; ?>