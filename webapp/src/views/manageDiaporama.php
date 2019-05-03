<?php
$title = Localize('Diaporama-ManageDiaporama');
 ob_start(); ?>
 <section class="contact py-lg-4 py-md-3 py-sm-3 py-3">
  <div class="container py-lg-5 py-md-4 py-sm-4 py-3">
    <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo Localize('Diaporama-ManageDiaporama');?></h3>
    <?php if(isset($_POST)){?>
    <p class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo Localize('Modification-Success');?></p>
    <?php } ?>
    <div class="row w3pvt-info-para pt-lg-5 pt-md-4 pt-3">
      <div class="col-lg-6 col-md-6">
        <form action="index.php?action=managediaporama" name="addImage" id="addImage" method="post" enctype="multipart/form-data">
          <div class="w3pvt-wls-contact-mid">
            <div class="form-group contact-forms">
                <label for="file"><h4><?php echo Localize('Diaporama-AddNewPicture');?></h4></label>
                <input type="file" name="newImage" />
            </div>
            <button type="submit" class="btn sent-butnn"><?php echo Localize('Timeslot-Add');?></button>
          </div>
        </form>
      </div> 
      <div class="col-lg-12 col-md-12">
      <?php if(isset($images)){ ?>
      <form action="index.php?action=managediaporama" class="text-center" name="managediaporama" id="managediaporama" method="post">
        <div class="row ">
              <?php
              $maxOrder = count($images);
              foreach($images as $image){ 
                $display = $image->isDisplayed;?>
              <div class="col-sm-12 col-md-6 col-lg-4 pt-2 pb-2">
                <img style="max-height:200px;" src="<?php echo $image->path ?>">
                <div>
                  <input type="hidden" name="id<?php echo $image->id; ?>" value="id<?php echo $image->id; ?>">
                  <label > Afficher</label>
                  <label for="yes<?php echo $image->id?>"> oui</label>
                  <input type="radio" <?php if($display) echo'checked'?> id="yes<?php echo $image->id?>" name="display<?php echo $image->id?>" value="display1">
                  <label for="no<?php echo $image->id?>"> Non </label>
                  <input type="radio" <?php if(!$display) echo'checked'?> id="no<?php echo $image->id?>" name="display<?php echo $image->id?>" value="display0">
                  <select name="order<?php echo $image->id?>">
                    <option value="order0">Ordre</option>
                    <?php
                    $cpt=1;
                      while($cpt <= $maxOrder){
                        if($cpt == $image->displayOrder){
                          echo '<option selected value="order' . $cpt . '">'. $cpt . '</option>';
                        }else{
                          echo '<option value="order' . $cpt . '">'. $cpt . '</option>';
                        }
                        $cpt++;
                      }
                    ?>
                  </select>
                </div>
              </div>
              <?php
              }
              ?>
          </div>
          <button type="submit" class="btn sent-butnn"><?php echo Localize('Save');?></button>
      </form>
      <?php } ?>
    </div>
  </div>
</section>
<?php $contenu = ob_get_clean(); 
$onHomePage = false;
require 'gabarit.php'; ?>