<?php
require_once("/var/www/abian/header.php");
$bot = null;
if (isset($_GET) && count($_GET) > 0) {
  $bot = array_search(array_values($_GET)[0], $_GET);
}
if ($bot == "created" || $bot == "updated") $bot = null;
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
  if (isset($_GET["updated"])) {
    $error = '
      <div class="alert alert-success">
        Bot has been updated and submitted for approval by staff. This process
        can take several days, plox be patient, plox.
      </div>
    ';
  }

  echo <<<EOT
  <div class="col-xs-12">
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
  </div>
EOT;
} elseif (is_array($session) && $bot !== null) {
  $bot = $UserSystem->dbSel(["bots", ["slug" => $bot]]);
  if ($bot[0] == 1) {
    $bot = $bot[1];
    $Parsedown = new Parsedown();
    $bot["name"] = ucfirst($bot["name"]);
    $bot["user"] = intval($bot["user"]);
    $user = $UserSystem->session($bot["user"]);
    echo <<<EOT
    <div class="col-xs-12">
      <div class="row">
        <div class="col-xs-12 col-sm-8">
          <h2>$bot[name]</h2>
        </div>
        <div class="col-xs-12 col-sm-4 text-right">
          <div class="btn-group">
            <a href="#" class="btn btn-default text-success">
              <i class="fa fa-arrow-up"></i>
            </a>
            <a href="#" class="btn btn-default text-danger">
              <i class="fa fa-arrow-down"></i>
            </a>
          </div>
          <a href="#" class="btn btn-default text-danger">
            <i class="fa fa-heart-o"></i>
          </a>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-9">
EOT;

echo $Parsedown->text($bot["body"]);

$email = md5(strtolower(trim($user["email"])));
$date = date("Y-m-d\TH:i", $bot["dateCreate"]);
echo <<<EOT
        </div>
        <script>emojify.run(document.getElementById("emoji"))</script>
        <div class="col-xs-12 col-sm-3 text-center">
            <img src="https://www.gravatar.com/avatar/$email?s=512" class="img-thumbnail" style="width:75%" />
            <br>
            Created by <a href="/u?$user[username]">$user[username]</a>
          <br>
          On $date
        </div>
      </div>
    </div>
EOT;
  }
}

require_once("/var/www/abian/footer.php");
?>
