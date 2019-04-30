<?php
$title = Localize('Login-Title');
 ob_start(); ?>
 <section class="contact py-lg-4 py-md-3 py-sm-3 py-3">
  <div class="container py-lg-5 py-md-4 py-sm-4 py-3">
    <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo Localize('Login-Title');?></h3>
    <div class="row w3pvt-info-para pt-lg-5 pt-md-4 pt-3">
      <div class="col-lg-6 col-md-6">
        <form action="index.php?action=managediaporama" name="managediaporama" id="managediaporama" method="post">
          <div class="w3pvt-wls-contact-mid">
            <div class="form-group contact-forms">
                <label for="email"><h4><?php echo Localize('Login-Email');?></h4></label>
              <input type="file" name="newImage" />
            </div>
            <button type="submit" class="btn sent-butnn"><?php echo Localize('Login-Title');?></button>
          </div>
        </form>
      </div>
      <div class="col-lg-12 col-md-12">

      </div>
    </div>
  </div>
</section>
<?php $contenu = ob_get_clean(); 
$onHomePage = false;
require 'gabarit.php'; ?>