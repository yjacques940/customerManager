<?php $title = localize('CreateCustomerAccount-Title');
ob_start();
if(userHasPermission('Customers-Write')){?>
<?php
$titre = localize('Inscription-Title');
ob_start();
$_SESSION['email'] ?>

    <section class="contact py-lg-4 py-md-3 py-sm-3 py-3">
        <div class="container py-lg-5 py-md-4 py-sm-4 py-3">
            <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize('CreateCustomerAccount-Title'); ?></h3>
            <div id="empty" style="color:#F00"></div>
            <div class="row w3pvt-info-para pt-lg-5 pt-md-4 pt-3">
                <div class="col-lg-6 col-md-6">
                    <form action="index.php?action=personalInformationToCreateCustomer" id="createNewCustomer" method="post">
                        <div class="w3pvt-wls-contact-mid">
                            <div class="radio">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-inline">
                                        <input type="radio" class="form-check-input" name="gender" value="F">
                                        <h4><?php echo localize('Inscription-Madam'); ?></h4></label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-inline">
                                        <input type="radio" class="form-check-input" name="gender" value="M">
                                        <h4><?php echo localize('Inscription-Sir'); ?></h4></label>
                                </div>
                            </div>
                            <div class="form-group contact-forms">
                                <label for="firstname"><h4><?php echo localize('Inscription-Firstname'); ?></h4></label>
                                <input type="text" name="firstname" id="firstname" class="form-control" placeholder="<?php echo localize('Inscription-Firstname'); ?>">
                            </div>
                            <div class="form-group contact-forms">
                                <label for="lastname"><h4><?php echo localize('Inscription-Lastname'); ?></h4></label>
                                <input type="text" name="lastname" id="lastname" class="form-control" placeholder="<?php echo localize('Inscription-Lastname'); ?>">
                            </div>
                            <div class="form-group contact-forms">
                                <label for="dateofbirth"><h4><?php echo localize('Personal-DateOfBirth');?></h4></label>
                                <input type="date" name="dateofbirth" id="dateofbirth" class="datepicker">
                            </div>
                            <div>
                                <button type="submit" class="btn sent-butnn"><?php echo localize('Inscription-NextStep'); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function(){

            $("#createNewCustomer").validate({
                errorClass : "error_class",
                errorelement : "em",
                rules:{
                    gender:{
                        required:true
                    },

                    firstname:{
                        required:true
                    },
                    dateofbirth:{
                        required:true
                    },
                    lastname:{
                        required:true
                    }
                },
                messages:{
                    gender:{
                        required :'<?php echo localize('Validate-Error-RequiredField'); ?>.'
                    },
                    firstname:{
                        required :'<?php echo localize('Validate-Error-RequiredField'); ?>.'
                    },
                    lastname:{
                        required :'<?php echo localize('Validate-Error-RequiredField'); ?>.'
                    },
                    email:{
                        required :'<?php echo localize('Validate-Error-RequiredField'); ?>.',
                        email: '<?php echo localize('Validate-Error-InvalidEmail'); ?>.'
                    },
                    dateofbirth:{
                        required :'<?php echo localize('Validate-Error-RequiredField'); ?>.'
                    }
                },
                errorPlacement: function(error, element){
                    if(element.is(":radio")){
                        error.appendTo(element.parents('.radio'));
                    }
                    else{
                        error.insertAfter(element);
                    }
                },
            });
        });
    </script>

<?php } else error(403);
    $contenu = ob_get_clean();
$onHomePage = false;
require 'gabarit.php'; ?>