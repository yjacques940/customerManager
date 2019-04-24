<?php ob_start();
session_start();
?>

<script type="text/javascript">
    <?php
    if($_SESSION['lastAuthentication'] + 10 * 60 < time()){
        echo  'AskPassword(false);';
    }
    else {
        echo 'OpenMedicalSurvey();';
    }
    ?>
    function AskPassword()
    {
        Swal.fire({
            title: '<?php echo localize('EmailUpdate-PasswordConfirm'); ?>',
            showCancelButton : true,
            input: 'password',
            inputPlaceholder: '<?php echo localize('Login-Password'); ?>',
            allowOutsideClick : false,
            cancelButtonText: '<?php echo localize('Cancel')?>',
            inputValidator: (value) => {
                return new Promise((resolve) => {
                    if (value) {
                        Swal.showLoading();
                        $.ajax({
                            url: '?action=medicalSurvey',
                            type: 'POST',
                            data: {
                                passwordToConfirm : value
                            }
                        }).success(function(content){
                            if(content != 'PasswordNotMatch')
                            {
                                $('#surveyHtmlContent').html(content);
                                resolve();
                            }
                            else
                            {
                                resolve('<?php echo localize('Validate-Error-PasswordDontMatch') ?>');
                            }
                            Swal.hideLoading();
                        });
                    } else {
                        resolve('<?php echo localize('Validate-Error-RequiredField') ?>');
                    }
                })
            }
        }).then((result) => {
            if(result.dismiss)
            {
                window.location.href = "?action=about";
            }
        })
    }

    function OpenMedicalSurvey()
    {
        $.ajax({
            url: '?action=medicalSurvey',
            type: 'GET'
        }).success(function(content){
            if(content)
            {
                $('#surveyHtmlContent').html(content);
            }
        });
    }
</script>
<div id="surveyHtmlContent"></div>
<?php $contenu = ob_get_clean();
$onHomePage = false;
require 'views/gabarit.php'; ?>