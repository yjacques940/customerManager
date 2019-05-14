<?php
$title = localize('FollowUp-Title');
ob_start();
?>

<div class="mx-auto" style="margin-top: 30px; width: 90%">
<h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize('FollowUp-Title'); ?></h3>
<p>
<?php echo localize('Appointment-Customer') . ': ' . $customer->firstName . ' ' . $customer->lastName; ?>
</p>
    <div class="search-header">
      <table class="table table-sm table-hover" id="tbl_followups">
        <thead class="thead-dark">
          <tr> 
            <th scope="col"> <?php echo localize('Appointment-Date') ?> </th>
            <th scope="col"> <?php echo localize('FollowUp-Summary') ?></th> 
          </tr>
        </thead>
        <tbody>
        <?php
            foreach($listOfFollowUps as $followUp){
                echo '<tr>
                        <td><a href="index.php?action=consultFollowUp&id='.$followUp->id.'&customerId='.$customerId.'">'. substr($followUp->date,0,10) . '</a></td>
                        <td>'. $followUp->summary .'</td>
                    </tr>';
            }
        ?>
        </tbody>
      </table>
      <a href="index.php?action=newFollowUp&customerid=<?php echo $customerId ?>" class="btn btn-success">
            <?php echo localize('FollowUp-Add'); ?>
        </a>
    </div>
    
</div>
<?php
  $contenu = ob_get_clean();
  $onHomePage = false;
  require 'gabarit.php';
?>
