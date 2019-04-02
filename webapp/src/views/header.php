<!--header-->
<div class="<?php if($onHomePage) echo 'main-top'; else echo 'header-outs inner_page-banner'; ?>" id="home">
  <div class="headder-top">
    <!-- nav -->
    <nav >
      <div id="logo">
        <h1><a href="index.html">Lecturing</a></h1>
      </div>
      <label for="drop" class="toggle">Menu</label>
      <input type="checkbox" id="drop">
      <ul class="menu mt-2">
        <li class="active"><a href="index.html">Home</a></li>
        <li class="mx-lg-3 mx-md-2 my-md-0 my-1"><a href="about.html">About</a></li>
        <li>
          <!-- First Tier Drop Down -->
          <label for="drop-2" class="toggle toogle-2">Dropdown <span class="fa fa-angle-down" aria-hidden="true"></span>
          </label>
          <a href="#">Dropdown <span class="fa fa-angle-down" aria-hidden="true"></span></a>
          <input type="checkbox" id="drop-2">
          <ul>
            <li><a href="gallery.html" class="drop-text">Gallery</a></li>
            <li><a href="blog.html" class="drop-text">Blog</a></li>
          </ul>
        </li>
        <li><a href="contact.html">Contact Us</a></li>
      </ul>
    </nav>
    <!-- //nav -->
  </div>
</div>
<!--//header-->
<!-- short -->
<div class="using-border py-3">
  <div class="inner_breadcrumb  ml-4">
    <ul class="short_ls">
      <li>
        <a href="index.html">Home</a>
        <span>/ /</span>
      </li>
      <li>About</li>
    </ul>
  </div>
</div>
<!-- //short-->
