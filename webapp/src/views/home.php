<html>
  <head>
    <title>
      <?php echo localize('Company-Name') . " - " . localize('PageTitle-Home'); ?>
    </title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/style.css" rel='stylesheet' type='text/css' media="all">
    <link href="//fonts.googleapis.com/css?family=Fira+Sans:400,500,600,700" rel="stylesheet">
    <link href="//fonts.googleapis.com/css?family=Oxygen:400,700" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  </head>
  <body>

    <header class="main-top" id="home">
      <div class="headder-top">
        <div style="display: inline" id="logo">
          <img src="images/completeLogo.svg" alt="Carl et Mélanie Orthothérapie" />
        </div>
        <nav style="display: inline">
          <label for="drop" class="toggle"><?php echo localize('Header-Menu'); ?></label>
          <input type="checkbox" id="drop">
          <ul class="menu mt-2">
            <li class="active">
              <a href="index.php?action=home"><?php echo localize('PageTitle-Home'); ?></a>
            </li>
            <li class="mx-lg-3 mx-md-2 my-md-0 my-1">
              <a href="index.php?action=about"><?php echo localize('PageTitle-About'); ?></a>
            </li>
            <?php
              if (!isset($_SESSION['username'])) {
            ?>
            <li>
              <a href="index.php?action=login"><?php echo localize('PageTitle-Login'); ?></a>
            </li>
            <?php
              } else {
            ?>
            <li>
              <label for="drop-3" class="toggle toogle-2">
                <?php echo $_SESSION['username']; ?>
                <span class="fa fa-angle-down" aria-hidden="true"></span>
              </label>
              <a href="#">
                <?php echo localize('Header-Account'); ?>
                <span class="fa fa-angle-down" aria-hidden="true"></span>
              </a>
              <input type="checkbox" id="drop-3" />
              <ul>
                <li>
                  <a href="index.php?action=logout" class="drop-text">
                    <?php echo localize('Header-Account'); ?>
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
                  <a href="?setLocale=<?php echo $setLocale ?>"><?php echo $setLocale ?></a>
              </li>
          </ul>
        </nav>
        <div class="main-banner text-center">
          <div class="container">
           <div class="style-banner ">
             <h4 class="mb-lg-3 mb-2"><?php echo localize('Home-Text-Title'); ?></h4>
             <h5><?php echo localize('Home-Text-Bold'); ?></h5>
            </div>
         </div>
        </div>
      </div>
    </header>
    <?php require 'footer.php'; ?>
  </body>
</html>
