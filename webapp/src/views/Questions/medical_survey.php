<?php
$titre = localize('Header-MedicalSurvey');
ob_start();
?>
<section class="contact py-lg-4 py-md-3 py-sm-3 py-3">
    <div class="container py-lg-5 py-md-4 py-sm-4 py-3">
        <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize($titre) ?></h3>
        <p style="text-align: center;"><b><?php echo localize('MedicalSurvey-Instructions')?></b></p>
        <?php
        if($hasDoneTheSurvey) {
        echo '<p style="text-align: center; color: red"><b>'
        . localize('MedicalSurvey-DeletionWarning'). '</b></p>';
         }?>
        </br>
        <div class="container">
    <form action="?action=saveMedicalSurvey" method="post" id="medicalSurvey" name="medicalSurvey">
        <?php if(isset($_SESSION['userid'])){ ?>
            <div class="form-group">
                <?php foreach($questions as $question) {
                    if($question->answerType == 'bool') {
                        if ($_SESSION['locale'] == 'fr') {
                        echo '<fieldset id="' . $question->id . '">'.
                        '<label for="bool-'. $question->id .'">' . $question->questionFr . '</label>'.
                        '<div class="form-group form-inline">'.
                        '<input style="width:24px;height: 24px;" class="mx-sm-2"  type="radio" value="true" name="bool-' . $question->id . '" required>'.localize('Answer-Yes').''.
                        '<input style="width:24px;height: 24px;" class="mx-sm-2"  type="radio" value="false" name="bool-' . $question->id . '">'.localize('Answer-No').'';
                            foreach($questions as $childQuestion)
                            {
                                if($childQuestion->idParent == $question->id)
                                {
                                        echo '<div class="mx-sm-4"></div><label class="col-form-label" for="string-'. $childQuestion->id .'">'.$childQuestion->questionFr.'</label>'.
                                          '<input class="form-control mx-sm-3 w-50"  name="string-'. $childQuestion->id .'" id="'. $childQuestion->id .'" >';
                                }
                            };
                            echo '</div></fieldset></br>';
                        }
                        else
                        {
                            echo '<fieldset id="' . $question->id . '">'.
                                '<label for="bool-'. $question->id .'">' . $question->questionEn . '</label>'.
                                '<div class="form-group form-inline">'.
                                '<input style="width:24px;height: 24px;" class="mx-sm-2" type="radio" value="true" name="bool-' . $question->id . '">'.localize('Answer-Yes').''.
                                '<input style="width:24px;height: 24px;" class="mx-sm-2" type="radio" value="false" name="bool-' . $question->id . '">'.localize('Answer-No').'';
                            foreach($questions as $childQuestion)
                            {
                                if($childQuestion->idParent == $question->id)
                                {
                                    echo '<div class="mx-sm-4"></div><label class="col-form-label" for="string-'. $childQuestion->id .'">'.$childQuestion->questionEn.'</label>'.
                                        '<input class="form-control mx-sm-3 w-50"  name="string-'. $childQuestion->id .'" id="'.$chilQuestion->id.'" >';
                                }
                            };
                            echo '</div></fieldset></br>';
                        }
                    }elseif($question->answerType == 'string' && !$question->idParent){
                     echo   '<label for="string-'. $question->id .'">';
                        if ($_SESSION['locale'] == 'fr') {
                            echo $question->questionFr;
                        } else {
                            echo $question->questionEn;
                        }
                      echo  ' </label><input class="form-control" id="'.$question->id.'" name="string-'. $question->id .'">';
                    }
                    elseif($question->answerType =='string_multiple'){
                        echo   '<label for="tatata">';
                        if ($_SESSION['locale'] == 'fr') {
                            echo $question->questionFr;
                        } else {
                            echo $question->questionEn;
                        }
                        echo  ' </label><div class="form-group form-inline">'.
                                '<input style="width:24px;height: 24px;" class="mx-sm-2" type="radio" value="Very" name="multiple_string-' . $question->id . '" required>'.localize('MedicalSurvey-Very').''.
                                '<input style="width:24px;height: 24px;" class="mx-sm-2" type="radio" value="Moderatly" name="multiple_string-' . $question->id . '">'.localize('MedicalSurvey-Moderatly').''.
                                '<input style="width:24px;height: 24px;" class="mx-sm-2" type="radio" value="NotAtAll" name="multiple_string-' . $question->id . '">'.localize('MedicalSurvey-NotAtAll').' </div>';
                    }
                        ?>

                <?php
                }?>
            </div>
        <button type="submit" class="btn sent-butnn"><?php echo localize('Send') ?></button>
        <?php } ?>
    </form>
        </div>
</div>
</section>
<script>
    $(document).ready(function() {
        $("#medicalSurvey").validate({
            errorClass : "error_class",
            errorElement : "em",
            errorPlacement : function(error,element) {
               error.appendTo(element.parent());
            }
        })
    })
</script>
<?php $contenu = ob_get_clean();
$onHomePage = false;
require 'views/gabarit.php';?>
