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
        input: 'password',
        inputPlaceholder: '<?php echo localize('Login-Password'); ?>',
        allowOutsideClick : false
        }).then((result) => {
            if(result.value)
            {
                $.ajax({
                    url: '?action=medicalSurvey',
                    type: 'POST',
                    data: {
                        passwordToConfirm : result.value
                    }
                }).success(function(content){
                    if(content != 'PasswordNotMatch')
                    {
                        $('#surveyHtmlContent').html(content);
                    }
                    else
                    {
                        AskPassword();
                    }
                })
             }
             else
            {
                AskPassword();
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