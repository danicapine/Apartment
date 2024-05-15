<div class="site-wrap">

<div class="site-navbar mt-4">
    <div class="container py-1">
      <div class="row align-items-center">
        <div class="col-8 col-md-8 col-lg-4">
          <h1 class="mb-0"><a class="text-white h2 mb-0"><strong>TTPD HOMES<span class="text-primary">.</span></strong></a></h1>
        </div>
        <div class="col-4 col-md-4 col-lg-8">
          <nav class="site-navigation text-right text-md-right" role="navigation">

            <div class="d-inline-block d-lg-none ml-md-0 mr-auto py-3"><a href="#" class="site-menu-toggle js-menu-toggle text-white"><span class="icon-menu h3"></span></a></div>

            <ul class="site-menu js-clone-nav d-none d-lg-block">
              <li <?php if(basename($_SERVER['PHP_SELF']) == 'homepage.php') echo 'class="active"'; ?>><a href="homepage.php">Home</a></li>
              <li <?php if(basename($_SERVER['PHP_SELF']) == 'aboutpage.php') echo 'class="active"'; ?>><a href="aboutpage.php">About</a></li>
              <li <?php if(basename($_SERVER['PHP_SELF']) == 'rooms.php') echo 'class="active"'; ?>><a href="rooms.php">Rooms</a></li>
              <li <?php if(basename($_SERVER['PHP_SELF']) == 'contact.php') echo 'class="active"'; ?>><a href="contact.php">Contact</a></li>
              <li <?php if(basename($_SERVER['PHP_SELF']) == 'login.php') echo 'class="active"'; ?>><a href="login.php">Login</a></li>
              <li <?php if(basename($_SERVER['PHP_SELF']) == 'signup.php') echo 'class="active"'; ?>><a href="signup.php">Signup</a></li>
          </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>