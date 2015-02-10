<?php
require_once("_secret_keys.php");
require_once("/var/www/abian/libs/usersystem/config.php");
$session = $UserSystem->verifySession();
if ($session === true) {
  $session = $UserSystem->session();
} else {
  $session = false;
}
?>

<!DOCTYPE html>
<html>
<head>
  <link href="/libs/css/bootstrap.css" rel="stylesheet" media="screen">
  <style>
    body {
      margin-top: 70px;
    }
  </style>

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>

  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/">Abian Bot Network for AQW</a>
      </div>
      <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
          <!--<li><a href="#about">About</a></li>-->
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <?php if (!$session): ?>
            <li><a href="/u/login">Login</a></li>
            <li><a href="/u/register">Register</a></li>
          <?php elseif (is_array($session)): ?>
            <li><a href="/u/login">Hello, <?=$session["username"]?></a></li>
          <?php endif; ?>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </nav>

  <div class="container">

    <div class="row">
