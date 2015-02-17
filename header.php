<?php
if (!isset($sidebar)) $sidebar = true;
require_once("/var/www/abian/_secret_keys.php");
require_once("/var/www/abian/libs/usersystem/config.php");
require_once("/var/www/abian/libs/Abian.php");
require_once("/var/www/abian/libs/recaptcha.php");
$Abian = new Abian;
$session = $UserSystem->verifySession();
if ($session === true) {
  $session = $UserSystem->session();
  date_default_timezone_set($session["timeZone"]);
} elseif ($session === "ban") {
  echo "You are banned from using Abian. If you are unsure why, please open an issue on github.com/zbee/abian.";
  exit;
} else {
  $session = false;
}
$xp = $Abian->calcXP($session["id"]);
?>

<!DOCTYPE html>
<html>
<head>
  <link href="/libs/css/bootstrap.css" rel="stylesheet" media="screen">
  <link href="/libs/css/flags.css" rel="stylesheet" media="screen">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet" media="screen">
  <style>
    body {
      margin-top: 70px;
      position: relative;
    }
    .label {
      cursor: pointer;
    }
    .lin {
      list-style: none;
    }
  </style>

  <title>Abian</title>

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
        <a class="navbar-brand" href="/">Abian</a>
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
            <li><a href="/u/?<?=$session["username"]?>">
              <!--<img src="https://www.gravatar.com/avatar/<?=md5(strtolower(trim($session["email"])))?>?s=32" style="height:20px" class="img-rounded" />-->
              <?=$session["username"]?>
              <span class="badge"><?=$Abian->calcLevel($xp)[0]?></span>
            </a></li>
            <li><a href="/b/a"><i class="fa fa-plus"></i></a></li>
            <li><a href="/u/cp"><i class="fa fa-cog"></i></a></li>
            <li><a href="/a"><i class="fa fa-database"></i></a></li>
            <li><a href="/u/logout"><i class="fa fa-sign-out"></i></a></li>
          <?php endif; ?>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </nav>

  <div class="container">

    <div class="row">

    <?php
    if ($sidebar) {
      echo '
      <div class="col-xs-12 col-sm-2">
      ';

      $ad = '';
      $weights = [0, 1, 1, 2, 2, 2, 3, 3, 3, 3];
      $weights = [1, 1, 1, 1, 1, 1, 1, 1, 1, 1];
      $weight = $weights[rand(0,9)];
      $stmt = $UserSystem->dbSel(["ads", ["weight" => $weight, "expiration" => [">", time()], "allowed" => 1, "flavor" => "image"], ["id", "desc, shown asc limit 1"]]);
      $ad = '<a href="'.$stmt[1]['link'].'"><img src="'.$stmt[1]['content'].'" style="max-width:95%;max-height:100%;" /></a>';
      $UserSystem->dbUpd(["ads", ["shown" => $stmt[1]["shown"]+1], ["id" => $stmt[1]["id"]]]);
      $ad .= '<br><br>Good ad? 
        <button class="btn btn-small"><i class="fa fa-thumbs-o-up"></i></button>
        <button class="btn btn-small"><i class="fa fa-thumbs-o-down"></i></button>
        <br>Pst! <a href="/a/premium">Premium users</a> don\'t see these!';
      echo '
      <!--Ad-->
      <div class="sidewidt text-center">
        <h1 class="text-left" style="margin-bottom:10px;"><span class="maintext"><i class="fa fa-money"></i> Ad</span></h1>
        '.$ad.'
      </div>
      <!--/Ad-->';
      
      echo '
      </div>
      <div class="col-xs-12 col-sm-10">
      ';
    }
    ?>