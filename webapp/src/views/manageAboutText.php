<?php
$title = localize('AboutText-Title');
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
    <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize('AboutText-Title'); ?></h3>
    <?php
    if(isset($_POST['id'])){
        if($_POST['id'] == 0){
            echo '<p style="color:red;" class="text-center mb-md-4 mb-sm-3 mb-3 mb-2">'. localize('AboutText-NoId').'</p>';
        }
    } ?>
    <div class="row w3pvt-info-para pt-lg-5 pt-md-4 pt-3">
      <div class="col-lg-12 col-md-12">
        <form action="index.php?action=manageAboutText" id="manageabouttext" method="post">
        <input type="hidden" name="id" value="<?php echo $id ?>">
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
          </div>
          <button type="submit" class="btn sent-butnn"><?php echo localize('Save');?></button>
        </form>
        <table class="table table-sm table-striped table-hover table-bordered" id="tbl_appointments">
        <thead class="thead-dark">
        <tr class="text-center">
            <th width="10%" scope="col">Zone</th>
            <th width="10%" scope="col"><?php echo localize('Title'); ?></th>
            <th scope="col">Description</th>
        </tr>
        </thead>
        <tbody>
        <?php
            foreach($aboutTexts as $aboutText){
                $text = $aboutText->aboutText;
                $zone = $aboutText->zoneDescription;
                if($_SESSION['locale'] == 'fr'){
                    $tableTitle = $text->titleFr;
                    $tableDescr = $text->descrFr;
                }else{
                    $tableTitle = $text->titleEn;
                    $tableDescr = $text->descrEn;
                }?>
                <tr>
                    <td><a href="?action=manageAboutText&id=<?php echo $text->id ?>"><?php echo $zone ?> </a> </td>
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
