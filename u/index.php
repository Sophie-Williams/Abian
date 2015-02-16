<?php
require_once("/var/www/abian/header.php");
$user = isset($_GET) && count($_GET) > 0 ? array_search(array_values($_GET)[0], $_GET) : null;
if (is_array($session) && $user === null) $UserSystem->redirect301("/u/cp");
if ($session === false && $user === null) $UserSystem->redirect301("/");
$user = $UserSystem->session($user);
$user["a2"] = strtolower($user["a2"]);
$a2 = $Abian->codeToCountry(strtoupper($user["a2"]));

$company = "";
if (!empty($user["company"])) $company = '<br><br>Works for ' . $user["company"];

$email = md5(strtolower(trim($user["email"])));
echo <<<EOT
<div class="col-xs-12 col-sm-3">
  <img src="https://www.gravatar.com/avatar/$email?s=512" class="img-thumbnail" />
  $company
  <br><br>
  <div class="panel panel-default">
    <div class="panel-heading">Social</div>
    <div class="list-group">
EOT;

if (!empty($user["twitchName"])) echo '<a target="_blank" href="https://twitch.tv/'.$user["twitchName"].'" class="list-group-item">Twitch: '.$user["twitchName"].'</a>';
if (!empty($user["githubName"])) echo '<a target="_blank" href="https://github.com/'.$user["githubName"].'" class="list-group-item">GitHub: '.$user["githubName"].'</a>';
if (!empty($user["aqName"])) echo '<a target="_blank" href="http://www.aq.com/aw-character.asp?id='.$user["aqName"].'" class="list-group-item">AQW: '.$user["aqName"].'</a>';

$dateR = date("Y-m-d", $user["dateRegistered"]);
date_default_timezone_set($user["timeZone"]);
$theirTime = date("Y-m-d\TH:i", time()) . " (" . $user["timeZone"] . ")";
is_array($session) ? date_default_timezone_set($session["timeZone"]) : date_default_timezone_set("America/Denver");

echo <<<EOT
    </div>
  </div>
  <br>
  Their time is $theirTime
</div>
<div class="col-xs-12 col-sm-9">
  <div class="row">
    <div class="col-xs-12 col-sm-9">
      <h1><span class='f32'><i class='flag $user[a2]' title='$a2'>&nbsp;</i></span> $user[username]</h1>
      Joined $dateR
    </div>
    <div class="col-xs-12 col-sm-3 text-center">
      <h2>Level 10</h2>
      <h3>36,150 / 75,000</h3>
      <div class="progress">
        <div class="progress-bar" role="progressbar" aria-valuenow="48" aria-valuemin="0" aria-valuemax="100" style="width: 48%;">
          48%
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12">
EOT;

$badges = $UserSystem->dbSel(["badging", ["user" => $user["id"]]]);
if ($badges[0] > 0) {
  foreach ($badges as $key => $badgeb) {
    if ($key === 0) continue;
    $badge = $UserSystem->dbSel(["badges", ["id" => $badgeb["badge"]]])[1];
    $desc = $badge["description"];
    $desc = str_replace("%aq", substr($session["id"], 0, 2), $desc);
    $desc = str_replace("%twitch", substr(sha1($session["id"].$session["username"]), 0, 7), $desc);
    $desc = str_replace("%github", substr(sha1($session["id"].$session["username"]), 0, 7), $desc);
    echo '<span class="label label-'.$badge["type"].'" data-toggle="popover" data-placement="top" data-content="'.$desc.'">'.$badge["name"].'</span> ';
  }
}

echo <<<EOT
    </div>
  </div>
  <br>
  <div class="row">
  </div>
</div>
EOT;

require_once("/var/www/abian/footer.php");
?>