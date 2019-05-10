<?php
$title = 'Connection';
 ob_start(); ?>
    <section class="about-inner py-lg-4 py-md-3 py-sm-3 py-3">
      <div class="container py-lg-5 py-md-4 py-sm-4 py-3">
        <?php
        if (isset($_SESSION['username'])) {
            ?> <h4 class="text-center mb-md-4 mb-sm-3 mb-3 mb-2"> <?php
          echo localize('About-Welcome').', ' . $_SESSION['username'] ; ?> </h4> <?php
        }
        if (!empty($_POST)) {
            if(isset($_POST['newpassword'])){
            ?> <h4 class="text-center mb-md-4 mb-sm-3 mb-3 mb-2"> <?php
            echo localize('About-PasswordChange') ; ?> </h4> <?php
          }else if(isset($_POST['address'])){
            ?> <h4 class="text-center mb-md-4 mb-sm-3 mb-3 mb-2"> <?php
            echo localize('About-InformationChanged') ; ?> </h4> <?php
          }else if(isset($_SESSION['emailchanged'])){
            unset($_SESSION['emailchanged']);
            ?> <h4 class="text-center mb-md-4 mb-sm-3 mb-3 mb-2"> <?php
            echo localize('About-EmailChanged') ; ?> </h4> <?php
          }else if(isset($_SESSION['appointmenttaken'])){
            unset($_SESSION['appointmenttaken']);
            ?> <h4 class="text-center mb-md-4 mb-sm-3 mb-3 mb-2"> 
             <?php echo localize("TakeAppointment-AppointementTaken") ?> </h4> <?php
          }
        }
        ?>
        <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize('About-Title'); ?></h3>
        <div class="container">
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
          <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <?php 
              $cpt = 1;
              $nbrOfImabge = count($carouselImages);
              while ($cpt < $nbrOfImabge){
                echo '<li data-target="#myCarousel" data-slide-to="'.$cpt.'"></li>';
                $cpt++;
              }
            ?>
            </ol>
            <div class="carousel-inner">
            <?php 
            $firstImage = true;
            foreach($carouselImages as $image){
              if($firstImage){?>
                <div class="carousel-item active">
                <?php
                $firstImage = false;
              }else{ ?>
                <div class="carousel-item">
              <?php 
              } ?>
                <img src="<?php echo $image->path;?>" class="d-block w-100" style="width:100%;">
              </div>
              <?php
            } ?>
            </div>
            <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
              <span class="glyphicon glyphicon-chevron-left"></span>
              <span class="sr-only"></span>
            </a>
            <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
              <span class="carousel-control-next-icon"></span>
              <span class="sr-only"></span>
            </a>
          </div>
        </div>
        <div class="title-wls-text text-center mb-lg-5 mb-md-4 mb-sm-4 mb-3">
          <p><?php echo localize('About-Introduction'); ?></p>
        </div>
        <div class="row">
          <div class="col-lg-4 w3layouts-right-side-img">
            <div class="abut-inner-wls-head">
              <h4 class="pb-3"><?php
              if($_SESSION['locale'] == 'en' and $topLeft->titleEn != ''){
                echo $topLeft->titleEn;
              }else{
                echo $topLeft->titleFr;
              }
               ?></h4>
              <p><?php
              if($_SESSION['locale'] == 'en' and $topLeft->descrEn != ''){
                echo $topLeft->descrEn;
              }else{
                echo $topLeft->descrFr;
              }
               ?></p>
            </div>
          </div>
          <div class="col-lg-4 w3layouts-left-side-img">
            <img src="images/blog2.jpg" class="img-thumbnail" alt="">
          </div>
          <div class="col-lg-4 w3layouts-right-side-img">
            <div class="abut-inner-wls-head">
              <h4 class="pb-3"><?php
              if($_SESSION['locale'] == 'en' and $topRight->titleEn != ''){
                echo $topRight->titleEn;
              }else{
                echo $topRight->titleFr;
              }
               ?></h4>
              <p><?php
              if($_SESSION['locale'] == 'en' and $topRight->descrEn != ''){
                echo $topRight->descrEn;
              }else{
                echo $topRight->descrFr;
              }
               ?></p>
            </div>
          </div>
        </div>
      </div>
      <div class="row" style="background-color:#99CC99;">
      <div class="col-lg-2">
      </div>
      <div  class="col-lg-8 p-5 text-center">
        <h4 class="pb-3"><?php
          if($_SESSION['locale'] == 'en' and $treatment->titleEn != ''){
            echo $treatment->titleEn;
          }else{
            echo $treatment->titleFr;
          }
            ?></h4>
          <p><?php
          if($_SESSION['locale'] == 'en' and $treatment->descrEn != ''){
            echo $treatment->descrEn;
          }else{
            echo $treatment->descrFr;
          }
            ?></p>
            </div>
      </div>
    </section>
    <!--//about-->
    <!--States-->
    <section class="stats-count">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-6 stats">
          </div>
          <div class="col-lg-6 col-md-6 col-sm-6 counter-number">
            <div class="row text-center">
              <div class="col-lg-6 col-md-6 col-sm-6 number-w3three-info ">
                <h5><?php echo localize('About-Stats-Diplomas-Count'); ?></h5>
                <h6 class="pt-2"><?php echo localize('About-Stats-Diplomas-Text'); ?></h6>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 number-w3three-info">
                <h5><?php echo localize('About-Stats-Customers-Count'); ?></h5>
                <h6 class="pt-2"><?php echo localize('About-Stats-Customers-Text'); ?></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!--//States-->
    <!-- team -->
    <section class="team py-lg-4 py-md-3 py-sm-3 py-3" id="team">
      <div class="container py-lg-5 py-md-4 py-sm-4 py-3">
        <h3 class="title text-center mb-md-4 mb-sm-3 mb-3 mb-2"><?php echo localize('About-OurTeam'); ?></h3>
        <div class="title-wls-text text-center mb-lg-5 mb-md-4 mb-sm-4 mb-3">
          <p><?php echo localize('About-OurTeam-Text'); ?></p>
        </div>
        <div class="row">
          <div class="col-lg-3 col-md-6 col-sm-6 team-row-grid">
            <div class="team-grid">
              <div class="team-image mb-3">
                <img src="images/t1.jpg" alt="" class="img-fluid">
              </div>
              <h4>Carl Giguère</h4>
              <p class="mt-2">Orthothérapeute</p>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 team-row-grid">
            <div class="team-grid">
              <div class="team-image mb-3">
                <img src="images/t2.jpg" alt="" class="img-fluid">
              </div>
              <h4>Mélanie Plante</h4>
              <p class="mt-2">Orthothérapeute</p>
            </div>
          </div>
        </div>
      </div>
    </section>
<script>
    <?php
    if(isset($_POST['isfirstlogin'])&& htmlentities($_POST['isfirstlogin']))
    {
        echo 'AskAboutMedicalForm();';
    }
    ?>
    function AskAboutMedicalForm()
    {
        Swal.fire({
            title: 'Voulez-vous remplir votre questionnaire santé maintenant?',
                text: "Il est possible de remplir ce questionnaire en ligne" +
                    " dès maintenant ou lors de votre prochaine visite.",
            showCancelButton: true,
            cancelButtonText: "Non",
            confirmButtonText: "Oui",
            confirmButtonColor: '#3d3',
            type: 'info'
        }).then((result) => {
            if (result.value) {
               window.location = "?action=mainMedicalSurvey"
            }
        });
    }
</script>
<?php $contenu = ob_get_clean();
$onHomePage = false;
require 'gabarit.php'; ?>
