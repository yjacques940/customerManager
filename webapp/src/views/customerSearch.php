<?php
$title = localize('searchClient');
ob_start();
?>

<div class="mx-auto" style="margin-top: 30px; width: 90%">
<h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize('searchClient'); ?></h3>
    <div class="search-header">
        <div class="radio">
            <div class="form-check form-check-inline">
                <label class="form-check-inline">
                <input type="radio" class="form-check-input" checked name="searchType" value="1">
                <h4><?php echo localize('Personal-Phone');?></h4></label>
                <input id="phoneNumber" type="text" class="form-control search-bar" 
                name="phoneNumber" placeholder='<?php echo localize('Personal-Phone'); ?>' />
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-inline">
                <input type="radio" class="form-check-input" name="searchType" value="2">
                <h4><?php echo localize('Inscription-Lastname'); ?></h4></label>
                <input id="customerName" type="text" class="form-control search-bar" 
                name="customerName" placeholder='<?php echo localize('Inscription-Lastname'); ?>' />
            </div>
            <input type="button" value="<?php echo localize("searchClient"); ?>" class="form-check-inline btn btn-success" onclick="SearchCustomer();">
        </div>
    </div>
    <div id="customers" class="form-group contact-forms col-md-4">
    </div>
    <div id="customerInformation" class="mt-3">
    </div>
</div>
<script>
$(document).ready(function(){
    $('#phoneNumber').mask('(000) 000-0000');
});

function SearchCustomer(){
    if(document.getElementsByName('searchType')[0].checked){
        var output = $.ajax({
            url:"index.php",
            type:'POST',
            dataType: 'html',
            data:{customerPhone:$("#phoneNumber").val()},
            success:function(output){
                $("#customers").html(output);
            },
        });
    }else{
        var output = $.ajax({ 
            url:"index.php",
            type:'POST',
            dataType: 'html',
            data:{customerName:$("#customerName").val()},
            success:function(output){
                $("#customers").html(output);
            },
        });
    }
}

function GetCustomerById(){
    var output = $.ajax({
        url:'index.php',
        type:'POST',
        dataType: 'html',
        data:{customerId:$("#customerSelect").val()},
        success:function(output){
            $("#customerInformation").html(output);
        }
    });
}
</script>
<?php
  $contenu = ob_get_clean();
  $onHomePage = false;
  require 'gabarit.php';
?>
