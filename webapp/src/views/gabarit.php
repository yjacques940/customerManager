<html>
  <head>
    <title>
      <?php echo localize('Company-Name') . " - " . $title; ?>
    </title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all">
      <link href="css/font-awesome.min.css" rel="stylesheet">
      <link href="css/style.css" rel='stylesheet' type='text/css' media="all">
      <link href="//fonts.googleapis.com/css?family=Fira+Sans:400,500,600,700" rel="stylesheet">
      <link href="//fonts.googleapis.com/css?family=Oxygen:400,700" rel="stylesheet">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3/js/bootstrap.min.js"></script>
      <script src="JavaScript/script.js"></script>
      <script type="text/javascript" src="JavaScript/jquery-validation-1.17.0/lib/jquery-1.11.1.js"></script>
      <script type="text/javascript" src="JavaScript/jquery-validation-1.17.0/dist/jquery.validate.min.js"></script>
      <script type="text/javascript" src="JavaScript/jquery-validation-1.17.0/dist/localization/messages_fr.js"></script>
      <script type="text/javascript" src="addons/sweetalert2.all.min.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
      <script type="text/javascript" src="JavaScript/bootstrap.bundle.min.js"></script>
   </head>
  <body>

    <header class="header-outs inner_page-banner" id="home">

      <div class="headder-top">
          <div style="display: inline; float:left;margin: 10px 10px 10px 10px;"  >
              <a href="?action=home"><img
                          style=" width:30%;" class="img-fluid" src="images/completeLogo.png" alt="Carl et Mélanie Massothérapie" /></a>
          </div>
        <nav style="display: -moz-inline-block;">
          <label for="drop" class="toggle"><?php echo localize('Header-Menu'); ?></label>
          <input type="checkbox" id="drop">
          <ul class="menu mt-2" style="z-index:999;">
            <li class="active">
              <a href="index.php?action=home"><?php echo localize('PageTitle-Home'); ?></a>
            </li>
            <li class="mx-lg-3 mx-md-2 my-md-0 my-1">
              <a href="index.php?action=about"><?php echo localize('PageTitle-About'); ?></a>
            </li>
            <?php
            if(userHasPermission('IsEmployee')){
              ?>
            <li>
              <label for="drop-5" class="toggle toogle-2">
                <?php echo localize('Header-Administration'); ?>
                <span class="fa fa-angle-down" aria-hidden="true"></span>
              </label>
              <a href="#">
                <?php echo localize('Header-Administration'); ?>
                <span class="fa fa-angle-down" aria-hidden="true"></span>
              </a>
              <input type="checkbox" id="drop-5" />
              <ul>
                <li>
                  <a href="index.php?action=runDailyCronJobs" class="drop-text"><?php echo localize('CronJobs') ?></a>
                </li>
                <li>
                  <a href="index.php?action=timeSlotManagement" class="drop-text">
                    <?php echo localize('PageTitle-TimeSlotManagement'); ?>
                  </a>
                </li>
                <li>
                  <a href="?action=newAppointments"><?php echo localize('PageTitle-Appointments'); ?></a>
                </li>
                  <li>
                      <a href="index.php?action=customers" class="drop-text">
                          <?php echo localize('PageTitle-CustomersManagement'); ?>
                      </a>
                  </li>
                  <li>
                      <a href="index.php?action=createNewCustomer" class="drop-down">
                          <?php echo localize('CreateCustomerAccount-Title'); ?>
                      </a>
                  </li>
                <?php if(userHasPermission('SiteManager')){ ?>
                <li>
                  <a href="index.php?action=manageDiaporama" class="drop-text">
                  <?php echo localize('Diaporama-ManageDiaporama'); ?>
                  </a>
                </li>
                <li>
                  <a href="index.php?action=manageAboutText" class="drop-text">
                      <?php echo localize('PageTitle-ManageAboutText'); ?>
                  </a>
                </li>
                <?php } ?>
              </ul>
            </li>
            <?php
            } else if(isset($_SESSION['userid'])){ ?>
              <li>
              <label for="drop-4" class="toggle toogle-2">
                <?php echo localize('Header-Services'); ?>
                <span class="fa fa-angle-down" aria-hidden="true"></span>
              </label>
              <a href="#">
                <?php echo localize('Header-Services'); ?>
                <span class="fa fa-angle-down" aria-hidden="true"></span>
              </a>
              <input type="checkbox" id="drop-4" />
              <ul>
              <li>
                <a href="index.php?action=mainMedicalSurvey" class="drop-text">
                    <?php echo localize('Header-MedicalSurvey'); ?>
                </a>
              </li>
                <li>
                  <a href="index.php?action=reserveappointment" class="drop-text">
                    <?php echo localize('Header-TakeAppointment'); ?>
                  </a>
                </li>
                <li>
                  <a href="index.php?action=cancelappointments" class="drop-text">
                    <?php echo localize('UserAppointmentsList-Title'); ?>
                  </a>
                </li>
            </ul>
                <?php
            }
              if (!isset($_SESSION['userid'])) {
            ?>
            <li>
              <a href="index.php?action=login"><?php echo localize('PageTitle-Login'); ?></a>
            </li>
            <?php
              } else {
            ?>
            <li>
              <label for="drop-3" class="toggle toogle-2">
              <?php echo localize('Header-Account'); ?>
                <span class="fa fa-angle-down" aria-hidden="true"></span>
              </label>
              <a href="#">
                <?php echo localize('Header-Account'); ?>
                <span class="fa fa-angle-down" aria-hidden="true"></span>
              </a>
              <input type="checkbox" id="drop-3" />
              <ul>
              <?php
                  if(!userHasPermission('IsEmployee')){ ?>
                  <li>
                  <a href="index.php?action=personalinformation" class="drop-text">
                    <?php echo localize('Header-Account-Manage'); ?>
                  </a>
                </li>
                  <?php } ?>
                <li>
                  <a href="index.php?action=openUpdatePassword" class="drop-text">
                    <?php echo localize('Header-Manage-Password'); ?>
                  </a>
                </li>
                <li>
                  <a href="index.php?action=updateemail" class="drop-text">
                    <?php echo localize('Header-Manage-Email'); ?>
                  </a>
                </li>
                <li>
                  <a href="index.php?action=logout" class="drop-text">
                    <?php echo localize('Header-Logout'); ?>
                  </a>
                </li>
              </ul>
            </li>
                  <?php
              }
            if($_SESSION['locale'] == 'fr')
            {
                $setLocale = 'en';
            }
            else if($_SESSION['locale'] == 'en')
            {
                $setLocale = 'fr';
            }
            else
            {
                $setLocale = 'fr';
            }
            ?>
              <li class="mx-lg-3 mx-md-2 my-md-0 my-1">
              <?php
                  echo '<a href="?action='.htmlentities($_GET['action']).'&setLocale='.$setLocale.'">'.$setLocale.'</a>'
              ?>
              </li>
          </ul>
        </nav>
      </div>
    </header>
    <main>

    <content><?php echo $contenu ?></content>

          </main>
    <footer class="py-lg-4 py-md-3 py-sm-3 py-3" >
      <div class="container py-lg-5 py-md-5 py-sm-4 py-3">
        <div class="row">
          <div class="col-lg-6 col-md-6 footer-left-grid">
            <div class="footer-w3layouts-head">
              <h2><a href="index.html"><?php echo localize('Company-Name'); ?></a></h2>
            </div>
            <div class="mb-1 pt-lg-5 pt-md-4 pt-3 footer-address">
              <ul>
                <li>
                  <h4><?php echo localize('Footer-Text-Address'); ?></h4>
                </li>
                <li>
                  <p><?php echo localize('Company-Address'); ?></p>
                </li>
              </ul>
            </div>
            <div class="mb-1 footer-address">
              <ul>
                <li>
                  <h4><?php echo localize('Footer-Text-Email'); ?></h4>
                </li>
                <li>
                  <p>
                    <a href="mailto:<?php echo localize('Company-Email'); ?>">
                      <?php echo localize('Company-Email'); ?>
                    </a>
                  </p>
                </li>
              </ul>
            </div>
            <div class="mb-1 footer-address">
              <ul>
                <li>
                  <h4><?php echo localize('Footer-Text-Phone'); ?></h4>
                </li>
                <li>
                  <p><?php echo localize('Company-Phone'); ?></p>
                </li>
              </ul>
            </div>
          </div>
          <div class="footer-info-bottom col-lg-6 col-md-6">
            <div class="icons mt-3 ">
              <ul>
                <li><a href="<?php echo localize('Company-Fb'); ?>"><span class="fa fa-facebook"></span></a></li>
                <li><i><a href="?action=ask_for_appointment" style="color:white; font-style:normal;"><span class="fa fa-pencil-square-o" aria-hidden="true">
                          </span> <?php echo localize('Appointment-AskForAppointment') ?></a></i></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="bottem-wthree-footer text-center pt-lg-5 pt-md-4 pt-3">
          <p>© 2019 <?php echo localize('Company-Name').' - '.localize('Company-Rights'); ?></p>
          <p><?php echo localize('Website-Credits'); ?></p>
          <!-- <p><a href="http://www.W3Layouts.com" target="_blank">W3Layouts</a></p> -->
        </div>
        <div class="text-center">
          <a href="#home" class="move-top text-center mt-3"></a>
        </div>
      </div>
    </footer>

    <div id="modal01" class="modal" onclick="this.style.display='none'">
      <span class="close">&times;&nbsp;&nbsp;&nbsp;&nbsp;</span>
      <div class="modal-content">
        <img id="img02" style="max-width:100%">
      </div>
    </div>

  </body>
</html>
