<?php
session_start();

function fetchData($sql) {
    include 'connection.php';

    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    } catch(PDOException $e) {
        return "Error: " . $e->getMessage();
    } finally {
        $conn = null;
    }
}

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $sql = "SELECT name FROM users WHERE username = '$username'";
    $result = fetchData($sql);

    $name = !empty($result) ? $result[0]['name'] : 'User';
} else {
    $name = 'User';
}

?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
              <li <?php if(basename($_SERVER['PHP_SELF']) == 'homepage2.php') echo 'class="active"'; ?>><a href="homepage2.php">Home</a></li>
              <li <?php if(basename($_SERVER['PHP_SELF']) == 'aboutpage2.php') echo 'class="active"'; ?>><a href="aboutpage2.php">About</a></li>
              <li <?php if(basename($_SERVER['PHP_SELF']) == 'rooms2.php') echo 'class="active"'; ?>><a href="rooms2.php">Rooms</a></li>
              <li <?php if(basename($_SERVER['PHP_SELF']) == 'contact2.php') echo 'class="active"'; ?>><a href="contact2.php">Contact</a></li>
              <li><div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa-solid fa-user"></i> <?php  echo htmlspecialchars($name); ?>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="profile.php">Edit Profile</a>
                    <a class="dropdown-item" href="logout.php">Log out</a>
                </div>
            </div></li>
          </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>