<?php
$sidebar = false;
require_once("/var/www/abian/header.php");
$user = null;
if (isset($_GET) && count($_GET) > 0) {
  $user = array_search(array_values($_GET)[0], $_GET);
}
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
if (!empty($user["aqName"])) echo '<a target="_blank" href="http://www.aq.com/character.asp?id='.$user["aqName"].'" class="list-group-item">AQW: '.$user["aqName"].'</a>';

$dateR = date("Y-m-d", $user["dateRegistered"]);
$dateL = date("Y-m-d\TH:i", $user["lastActive"]);
date_default_timezone_set($user["timeZone"]);
$theirTime = date("Y-m-d\TH:i", time()) . " (" . $user["timeZone"] . ")";
$tHour = date("g", time());
$tMin = round(date("i", time())/30)*30;
if ($tMin == 0) {
  $tMin = "";
} elseif ($tMin == "60") {
  $tMin = "";
  $tHour += 1;
} else {
  $tMin = "30";
}
$theirClock = ":clock" . $tHour . $tMin . ":";
date_default_timezone_set(is_array($session) ? $session["timeZone"] : "America/Denver");

if ($user["id"] != $session["id"]) $xp = $Abian->calcXP($user["id"]);
$lvl = $Abian->calcLevel($xp);
$percent = number_format(($xp - $lvl[1]) / ($lvl[2] - $lvl[1]) * 100, 0);
$xp = number_format($xp, 0);
$level = number_format($lvl[0], 0);
$maxXP = number_format($lvl[2], 0);

echo <<<EOT
    </div>
  </div>
  <br>
  Their time is $theirClock $theirTime
</div>
<div class="col-xs-12 col-sm-9">
  <div class="row">
    <div class="col-xs-12 col-sm-9">
      <h1><span class='f32'><i class='flag $user[a2]' title='$a2'>&nbsp;</i></span> $user[username]</h1>
      Joined $dateR
      <br>
      Last seen $dateL
    </div>
    <div class="col-xs-12 col-sm-3 text-center">
      <h2>Level $level</h2>
      <h3>$xp / $maxXP</h3>
      <div class="progress">
        <div class="progress-bar" role="progressbar" aria-valuenow="$percent" aria-valuemin="0" aria-valuemax="100" style="width: $percent%;">
          $percent%
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12">
EOT;

$badged = $UserSystem->dbSel(["badging", ["user" => $user["id"]]]);
if ($badged[0] > 0) {
  $badges = $UserSystem->dbSel(
    ["badges", ["id" => ["!=", "a"]], ["order", "asc"]]
  );
  foreach ($badges as $key => $badge) {
    if ($key === 0) continue;
    foreach ($badged as $badgeb) {
      if ($badge["id"] == $badgeb["badge"]) {
        $desc = $badge["description"];
        echo '<span class="label label-'.$badge["type"].'" data-toggle="popover"
            data-placement="top" data-content="'.$desc.'">
            '.$badge["name"].'
          </span> &nbsp;';
      }
    }
  }
}

echo <<<EOT
    </div>
  </div>
  <hr>
  <div class="row">
    <div class="col-xs-12">
EOT;

$bots = $Abian->getBots(
  [
    "user" => $user["id"],
    "sort" => ["dateUpdate", "desc"]
  ],
  true,
  false
);
echo "This user has created $bots[1] bots.<br><br>";
echo $bots[0];

echo <<<EOT
    </div>
  </div>
</div>
EOT;

require_once("/var/www/abian/footer.php");
?>
