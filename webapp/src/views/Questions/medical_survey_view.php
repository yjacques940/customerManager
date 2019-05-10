<?php $titre = localize('Header-MedicalSurvey')?>
<section class="contact py-lg-4 py-md-3 py-sm-3 py-3">
        <div class="container py-lg-5 py-md-4 py-sm-4 py-3">
        <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize($titre) ?></h3>
        <h4 class="text-center"><?php echo $customerName['response'] ?></h4>
         <?php if($customerId != GetCustomerIdByUserId($_SESSION['userid']))
         {
             $_SESSION['TempCustomerId'] = $customerId;
         }?>
         <form action="?action=medicalSurveyUpdate" method="post"><button style="float: right" class="btn btn-success">
         <?php echo localize('MedicalSurvey-Update'); ?></button></form>
        </div>
        <div class="container">
            <?php
            echo '<fieldset><div>'.localize('MedicalSurvey-CreatedOn').'</div>'.
            '<div><b>'.$createdOn.'</b></div></fieldset></br>';

            foreach ($questions as $question)
            {
                if($question->answerType == 'bool')
                {
                    echo '<fieldset><div>';
                    echo $_SESSION['locale'] == 'fr' ? $question->questionFr : $question->questionEn;
                    echo '</br>';
                    foreach($responses as $response)
                    {
                        if($question->id == $response->idQuestion)
                        {
                            echo $response->responseBool ? '<b>'.localize('Answer-Yes').'</b>' :
                                '<b>' . localize('Answer-No') . '</b>';
                        }
                    }
                    foreach($questions as $childQuestion) {
                        if ($childQuestion->idParent == $question->id) {
                            foreach($responses as $childResponse)
                            {
                                if($childResponse->idQuestion == $childQuestion->id
                                    && $childResponse->responseString != '')
                                {
                                    echo '<b>, '.$childResponse->responseString.'</b>';
                                }
                            }
                        }
                    }
                    echo'</div></fieldset></br>';
                }
                elseif($question->answerType == 'string_multiple') {
                    echo '<div>';
                    echo $_SESSION['locale'] == 'fr' ? $question->questionFr : $question->questionEn;
                    echo '</br>';
                    foreach($responses as $response)
                    {
                        if($response->idQuestion == $question->id)
                        {
                            echo '<b>'. localize('MedicalSurvey-' . $response->responseString).'</b>';
                        }
                    }
                }
                elseif(!$question->idParent)
                {
                    echo '<div>';
                    echo $_SESSION['locale'] == 'fr' ? $question->questionFr : $question->questionEn;
                    echo '</br>';
                    foreach($responses as $response)
                    {
                        if($response->idQuestion == $question->id){
                            echo '<b>'. $question->responseString .'</b>';
                        }
                    }
                }
            }
            ?>
        </div>
    </section>