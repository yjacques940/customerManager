<?php
$title = localize('Personal-Title');
 ob_start(); 
    $cpt=0;
    $address='';
    $city='';
    $zipcode='';
    $idProvince='';
    $occupation='';
    $phone1 = array('','','0','0');
    $phone2 = array('','','0','0');
    $phone3 = array('','','0','0');

$customerIdAction = isset($_GET['customerId']) ? '&customerId=' . $_GET['customerId'] : '' ;
    
if(isset($_SESSION['userid'])){
    $address = $personalInformation->physicalAddress->physicalAddress;
    $city = $personalInformation->physicalAddress->cityName;
    $zipcode = $personalInformation->physicalAddress->zipCode;
    $idProvince = $personalInformation->physicalAddress->idState;
    $occupation = $personalInformation->customer->occupation;
    foreach($personalInformation->phoneNumbers as $phoneNumber){
        switch($cpt){
            case 0:
                $phone1[0] = $personalInformation->phoneNumbers[0]->phone;
                $phone1[1] = $personalInformation->phoneNumbers[0]->extension;
                $phone1[2] = $personalInformation->phoneNumbers[0]->idPhoneType;
            break;
            case 1:
                $phone2[0] = $personalInformation->phoneNumbers[1]->phone;
                $phone2[1] = $personalInformation->phoneNumbers[1]->extension;
                $phone2[2] = $personalInformation->phoneNumbers[1]->idPhoneType;
            break;
            case 2:
                $phone3[0] = $personalInformation->phoneNumbers[2]->phone;
                $phone3[1] = $personalInformation->phoneNumbers[2]->extension;
                $phone3[2] = $personalInformation->phoneNumbers[2]->idPhoneType;
            break;
        }
        $cpt++;
    }
}
 ?>

<section class="contact py-lg-4 py-md-3 py-sm-3 py-3">
  <div class="container py-lg-5 py-md-4 py-sm-4 py-3">
    <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize('Personal-Title');?></h3>
    <div class="row w3pvt-info-para pt-lg-5 pt-md-4 pt-3">
      <div class="col-lg-10 col-md-10">
        <form action="index.php?action=personalinformation<?php echo $customerIdAction ?>" id="personalinformation" method="post">
          <div class="w3pvt-wls-contact-mid">
          <div class="form-group contact-forms">
              <label for="address"><h4><?php echo localize('Personal-Address');?></h4></label>
              <input type="text" name="address" id="address" class="form-control" value="<?php echo $address; ?>" placeholder="<?php echo localize('Personal-Address');?>">
            </div>
            <div class="form-row">
                <div class="form-group contact-forms col-md-4">
                    <label for="city"><h4><?php echo localize('Personal-City');?></h4></label>
                    <input type="text" name="city" id="city" value="<?php echo $city ?>" class="form-control" placeholder="<?php echo localize('Personal-City');?>">
                </div>
                <div class="form-group contact-forms col-md-4">
                    <label for="province"><h4><?php echo localize('Personal-Province');?></h4></label>
                    <select name="province" id="province">
                    <option value=""></option>
                    
                    <?php foreach($states as $state)
                    {
                        if($state->id == $idProvince){
                            echo '<option selected value="' . $state->id.'">'.$state->name.'</option>';
                        }else{
                            echo '<option value="' . $state->id.'">'.$state->name.'</option>';
                        }
                    }
                    ?>
                    </select>
                </div>
                <div class="form-group contact-forms col-md-4">
                    <label for="zipcode"><h4><?php echo localize('Personal-Zip');?></h4></label>
                    <input type="text" value="<?php echo $zipcode ?>" name="zipcode" id="zipcode" class="form-control" placeholder="<?php echo localize('Personal-Zip');?>">
                </div>
            </div>
         <div class="form-row">   
                
                <div class="form-group contact-forms col-md-8">
                    <label for="occupation"><h4><?php echo localize('Personal-Occupation');?></h4></label>
                    <input type="text" value="<?php echo $occupation; ?>" name="occupation" id="occupation" class="form-control" placeholder="<?php echo localize('Personal-Occupation');?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group contact-forms col-md-4">
                    <label for="phone"><h4><?php echo localize('Personal-Phone');?></h4></label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group contact-forms col-md-4">
                    <input type="text" value="<?php echo $phone1[0];?>" name="phone1" id="phone1" class="form-control" placeholder="<?php echo localize('Personal-Phone');?>">
                </div>
                <div class="form-group contact-forms col-md-2">
                    <input type="text" value="<?php echo $phone1[1];?>" name="extension1" id="extension1" class="form-control" placeholder="<?php echo localize('Personal-Ext');?>:">
                </div>
                <div class="form-group contact-forms col-md-4">
                    <select name="type1" id="type1">
                    <option value=""></option>
                    <?php foreach($phoneTypes as $phoneType){
                        if($phoneType->id == $phone1[2]){
                            echo '<option selected value="'.$phoneType->id.'">'.$phoneType->name.'</option>';
                        }else{
                            echo '<option value="'.$phoneType->id.'">'.$phoneType->name.'</option>';
                        }
                    }
                    ?>
                    </select>
                </div>
                <div class="form-group contact-forms col-md-1">
                <img src="images/plus.gif" id="addphone2" <?php
                if($cpt > 1){echo 'style="visibility:hidden;"';} ?> class="plus-minus" onclick="AddPhone2()">
                </div>
            </div>
            <div class="form-row" id="phonerow2" <?php if($cpt<2){echo 'style="visibility:hidden;"';}?>>
                <div class="form-group contact-forms col-md-4">
                    <input type="text" id="phone2" name="phone2" value="<?php echo $phone2[0];?>" class="form-control" placeholder="Téléphone">
                </div>
                <div class="form-group contact-forms col-md-2">
                    <input type="text" name="extension2" id="extension2" value="<?php echo $phone2[1];?>" class="form-control" placeholder="Ext:">
                </div>
                <div class="form-group contact-forms col-md-4">
                    <select name="type2" id="type2">
                    <option value=""></option>
                    <?php
                    foreach($phoneTypes as $phoneType){
                        if($phoneType->id == $phone2[2]){
                            echo '<option selected value="'.$phoneType->id.'">'.$phoneType->name.'</option>';
                        }else{
                            echo '<option value="'.$phoneType->id.'">'.$phoneType->name.'</option>';
                        }
                    }
                    ?>
                    </select>
                </div>
                <div class="form-group contact-forms col-md-1">
                    <img src="images/plus.gif" id="addphone3"<?php
                if($cpt > 2){echo 'style="visibility:hidden;"';} ?> class="plus-minus" onclick="AddPhone3()">
                </div>
                <div class="form-group contact-forms col-md-1"<?php
                if($cpt > 2){echo 'style="visibility:hidden;"';} ?>>
                    <img src="images/minus.gif" id="removephone2" class="plus-minus" onclick="RemovePhone2()">
                </div>
            </div>
            <div class="form-row" id="phonerow3" <?php if($cpt<3){echo 'style="visibility:hidden;"';}?>>
                <div class="form-group contact-forms col-md-4">
                    <input type="text" value="<?php echo $phone3[0] ?>" id="phone3" name="phone3" class="form-control" placeholder="Téléphone">
                </div>
                <div class="form-group contact-forms col-md-2">
                    <input type="text" value="<?php echo $phone3[1] ?>" name="extension3" id="extension3" class="form-control" placeholder="Ext:">
                </div>
                <div class="form-group contact-forms col-md-4">
                    <select name="type3" id="type3">
                    <option value=""></option>
                    <?php
                    foreach($phoneTypes as $phoneType){
                        if($phoneType->id == $phone3[2]){
                            echo '<option selected value="'.$phoneType->id.'">'.$phoneType->name.'</option>';
                        }else{
                            echo '<option value="'.$phoneType->id.'">'.$phoneType->name.'</option>';
                        }
                    }
                    ?>
                    </select>
                </div>
                <div class="form-group contact-forms col-md-1">
                    <img src="images/minus.gif" id="removephone3" class="plus-minus" onclick="RemovePhone3()">
                </div>
            </div>
            <div>
            <?php if(!isset($_SESSION['userid'])){ ?>
                <button type="submit" class="btn sent-butnn"><?php echo localize('Inscription-Finish');?></button> <?php
            }else{ ?>
                <button type="submit" class="btn sent-butnn"><?php echo localize('Personal-Update');?></button> <?php
            }
                ?>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<script>
$(document).ready(function(){
    $('#phone1').mask('(000) 000-0000');
    $('#phone2').mask('(000) 000-0000');
    $('#phone3').mask('(000) 000-0000');
    $("#personalinformation").validate({
        errorClass : "error_class",
        errorelement : "em",
        rules:{
            address: {
                required:true
            },
            city:{
                required:true
            },
            zipcode:{
                required:true
            },
            occupation:{
                required:true
            },
            phone1:{
                required:true,
                minlength:13
            },
            phone2:{
                minlength:13
            },
            phone3:{
                minlength:13
            },
            type1:{
                required:true
            },
            extension1:{
                number:true
            },
            extension2:{
                number:true
            },
            extension3:{
                number:true
            },
            province:{
                required:true
            }
        },
        messages:{
            address:{
                required:'<?php echo localize('Validate-Error-RequiredField'); ?>.'
            },
            city:{
                required :'<?php echo localize('Validate-Error-RequiredField'); ?>.'
            },
            zipcode:{
                required :'<?php echo localize('Validate-Error-RequiredField'); ?>.'
            },
            occupation:{
                required :'<?php echo localize('Validate-Error-RequiredField'); ?>.'
            },
            phone1:{
                required :'<?php echo localize('Validate-Error-RequiredField'); ?>.',
                minlength:'<?php echo localize('Validate-Error-ValidPhone'); ?>'
            },
            phone2:{
                minlength:'<?php echo localize('Validate-Error-ValidPhone'); ?>'
            },
            phone3:{
                minlength:'<?php echo localize('Validate-Error-ValidPhone'); ?>'
            },
            type1:{
                required:'<?php echo localize('Validate-Error-RequiredField'); ?>.'
            },
            extension1:{
                number:'<?php echo localize('Validate-Error-Number'); ?>.'
            },
            extension2:{
                number:'<?php echo localize('Validate-Error-Number'); ?>.'
            },
            extension3:{
                number:'<?php echo localize('Validate-Error-Number'); ?>.'
            },
            province:{
                required:'<?php echo localize('Validate-Error-RequiredField'); ?>'
            }
        },
        submitHandler:function(){
          if(confirm('<?php echo localize("PasswordUpdate-UpdateConfirmation"); ?>'))
          {
            form.submit();
          }
        }
    })
});

</script>

<?php $contenu = ob_get_clean(); 
$onHomePage = false;
require 'gabarit.php'; ?>