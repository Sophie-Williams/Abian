<?php
$sidebar = false;
require_once("/var/www/abian/header.php");
$bot = isset($_GET) && count($_GET) > 0 ? array_search(array_values($_GET)[0], $_GET) : null;
if ($bot == "created") $bot = null;
if ($session === false && $bot === null) $UserSystem->redirect301("/u/login");
if (is_array($session) && $bot === null) {
  $error = "";
  if (isset($_GET["created"])) {
    $error = '
      <div class="alert alert-success">
        Bot has been submitted for approval by staff. This process
        can take several days, plox be patient, plox.
      </div>
    ';
  }
  
  echo <<<EOT
  $error
  <div class="row">
    <div class="col-xs-12">
      <a class="btn btn-default btn-lg btn-block text-center" href="a">
        <i class="fa fa-plus" style="font-size:75px"></i>
        <br>
        Add bot
      </a>
    </div>
  </div>
  <br>
  <div class="row">
    <div class="col-xs-12">
      <a class="btn btn-default btn-lg btn-block text-center" href="c">
        <i class="fa fa-magic" style="font-size:75px"></i>
        <br>
        Create Bot
      </a>
    </div>
  </div>
  <br>
  <div class="row">
    <div class="col-xs-12">
      <a class="btn btn-default btn-lg btn-block text-center" href="m">
        <i class="fa fa-list" style="font-size:75px"></i>
        <br>
        My Bots
      </a>
    </div>
  </div>
EOT;
}

require_once("/var/www/abian/footer.php");
?>
