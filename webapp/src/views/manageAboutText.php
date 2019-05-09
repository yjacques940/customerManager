<?php
$title = localize('FollowUp-Add');
 ob_start(); 
 if(isset($textToModify)){
     $titleFr = $textToModify->titleFr;
     $titleEn = $textToModify->titleEn;
     $descrFr = $textToModify->descrFr;
     $descrEn = $textToModify->descrEn;
     $zone    = $textToModify->zone;
 }else{
    $titleFr = '';
    $titleEn = '';
    $descrFr = '';
    $descrEn = '';
    $zone    = '';
 }
 $id = isset($_GET['id']) ? $_GET['id'] : 0;
 ?>

<div class="container py-lg-5 py-md-4 py-sm-4 py-3">
    <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize('FollowUp-Add'); ?></h3>
    <div class="row w3pvt-info-para pt-lg-5 pt-md-4 pt-3">
      <div class="col-lg-12 col-md-12">
        <form action="index.php?action=manageabouttext" id="manageabouttext" method="post">
        <input type="hidden" value="<?php echo $id ?>">
          <div class="w3pvt-wls-contact-mid">
            <div class="row">
                <div class="col-lg-6 col-md-8">
                    <div class="form-group contact-forms">
                        <label for="titlefr"><h4><?php echo localize('Title-French');?></h4></label>
                        <input type="text" name="titlefr" id="titlefr" value="<?php echo $titleFr ?>" class="form-control">
                    </div> 
                    <div class="form-group contact-forms">
                        <label for="descrfr"><h4><?php echo localize('Description-French'); ?></h4></label>
                        <textarea id="descrfr" name="descrfr" class="md-textarea form-control" rows="3"><?php echo $descrFr ?></textarea>
                    </div>
                </div>
                <div class="col-lg-6 col-md-8">
                    <div class="form-group contact-forms">
                        <label for="titleen"><h4><?php echo localize('Title-English');?></h4></label>
                        <input type="text" name="titleen" id="titleen" value="<?php echo $titleEn ?>" class="form-control">
                    </div> 
                    <div class="form-group contact-forms">
                        <label for="descren"><h4><?php echo localize('Description-English');?></h4></label>
                        <textarea id="descren" name="descren" class="md-textarea form-control" rows="3"><?php echo $descrEn ?></textarea>
                    </div> 
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 form-group contact-forms">
                    <label for="file"><h4>Zone d'affichage</h4></label>
                    <select name="zone">
                        <option value=""></option>
                        <option <?php if($zone == 'Coin supérieur droit') echo 'selected' ?> value="Coin supérieur droit">Coin supérieur droit</option>
                        <option <?php if($zone == 'Coin supérieur gauche') echo 'selected' ?> value="Coin supérieur gauche">Coin supérieur gauche</option>
                        <option <?php if($zone == 'Traitement') echo 'selected' ?> value="Traitement">Traitement</option>
                    </select>
                </div>
            </div>
          </div>
          <button type="submit" class="btn sent-butnn"><?php echo localize('FollowUp-Add');?></button>
        </form>
        <table class="table table-sm table-striped table-hover table-bordered" id="tbl_appointments">
        <thead class="thead-dark">
        <tr class="text-center">
            <th scope="col">Zone</th>
            <th scope="col"><?php echo localize('Title'); ?></th>
            <th scope="col">Description</th>
        </tr>
        </thead>
        <tbody>
        <?php
            foreach($aboutText as $text){
                if($_SESSION['locale'] == 'fr'){
                    $tableTitle = $text->titleFr;
                    $tableDescr = $text->descrFr;
                }else{
                    $tableTitle = $text->titleEn;
                    $tableDescr = $text->descrEn;
                }?>
                <tr>
                    <td><a href="?action=manageAboutText&id=<?php echo $text->id ?>"><?php echo $text->zone ?> </a> </td>
                    <td><?php echo $tableTitle ?> </td>
                    <td><?php echo $tableDescr ?> </td>
                </tr>
                <?php
            }
        ?>
        </tbody>
        </table>
      </div>
    </div>
  </div>
  <script>
$(document).ready(function(){
    $("#manageabouttext").validate({
        errorClass : "error_class",
        errorelement : "em",
        rules:{
            titlefr:{
                required:true
            },
            descrfr: {
                required : true
            }
        },
        messages:{
            titlefr:{
                required :'<?php echo localize('Validate-Error-RequiredField'); ?>.'
            },
            descrfr:{
                required :'<?php echo localize('Validate-Error-RequiredField'); ?>.'
            }
        },
    });
});
</script>
<?php $contenu = ob_get_clean(); 
$onHomePage = false;
require 'gabarit.php'; ?>
