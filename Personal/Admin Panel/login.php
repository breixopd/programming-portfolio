<?php
// Import server.php file to process login requests
include('server.php');
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="assets/css/stars.css">
    <link rel="stylesheet" href="assets/css/main.css">

    <title>Login</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
  </head>
  <body>

    <!-- Background -->
    <div class="page-bg"></div>
    <div class="animation-wrapper">
      <div class="particle particle-1"></div>
      <div class="particle particle-2"></div>
      <div class="particle particle-3"></div>
      <div class="particle particle-4"></div>
    </div>
    <main style="color: rgb(255, 255, 255); z-index: 0;">
      <section id="top" style="text-align: center; width: 100vw">
        <h1 style="margin-top: 10px;">Login<blink>_</blink></h1>
        <h3 style="margin-bottom: 15px; margin-top: 10px;">
          <span>Wrong page? - </span>
          <span style="text-decoration: underline"><a style="color: inherit" title="Register" href="register.php">Register</a></span>
        </h3>

        <div class="container" style="width: 100%;">
          <div class="row">
            <div class="col " style="margin-bottom: 10px;">
              <!-- Centered login card that uses server.php and errors.php -->
              <div class="card" style="font-size: 15px; color: black; height: 100%; width: 90%; margin: auto; opacity: 0.95">
                <div class="card-body">
                  <form method="post" action="login.php">
                      <?php include('errors.php'); ?>
                      <div class="form-group">
                          <label for="exampleInputEmail1">Email address</label>
                          <input type="email" class="form-control" name="email" placeholder="Enter email" required="">
                      </div>
                      <div class="form-group">
                          <label for="exampleInputPassword1">Password</label>
                          <input type="password" class="form-control" name="password" placeholder="Password" required="">
                      </div>
                      <button type="submit" name="log_user" class="btn btn-dark">Login</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
  </body>
</html>