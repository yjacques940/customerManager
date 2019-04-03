<?php
$title = localize('CustomersList');
ob_start(); ?>

    <form class="form-inline">
        <div class="input-group">
            <input id="search_customer" type="text" class="form-control" onkeyup="SearchCustomer()" name="search_customer"
                   placeholder=<?php echo localize('searchClient'); ?>>
        </div>
    </form>

    <div class="container">
        <div class="row">
            <div class="col-sm"><b>Nom</b></div>
            <div class="col-sm"><b>Téléphone</b></div>
        </div>

    <?php
    $customer = array("Yannick", "Jessy", "Nicolas","Vincent");
    $telephone = array("111111","222222","333333","444444");
    $count =0;
    foreach($customer as $customer) {
        ?>
        <div class ="row" id="customers_list">
            <div class="col-sm"><?php echo $customer?></div>
            <div class="col-sm" id="customer_phone_number"><?php echo $telephone[$count] ?></div>
        </div>
        <?php
        $count++;
    }
    ?>
    </div>
<?php $contenu = ob_get_clean();
$onHomePage = false;
require 'gabarit.php';
require 'OnClick.html'?>
