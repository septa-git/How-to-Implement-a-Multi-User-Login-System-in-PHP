<?php
 //session_start();
?>
      <!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <!-- STYLE -->
    <link rel="stylesheet" type="text/css" href="/css/style.css">
      <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
      <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <style>
      .nav-link:hover{
        color: blue;
        cursor: pointer;
      }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg bg-light mb-4">
      <div class="container-fluid">
        <a class="navbar-brand" href="/index.php">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="/index.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/contact/contact.php">Contact</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/members/login/register.php">Register Member</a>
            </li>
            <?php if(isset($_SESSION["member"])): ?>
              <li class="nav-item">
                <a class="nav-link" href="/members/login/member-dashboard.php">Your Dashboard</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/members/login/account-settings.php">Account Settings</a>
              </li>
            <?php endif; ?>
            <li class="nav-item">
              <a class="nav-link" href="/partners/signup-partner.php">Register Partner</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/admins/login/login.php">Login Admin</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>