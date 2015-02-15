<?php
require_once("/var/www/abian/header.php");
$user = isset($_GET) && count($_GET) > 0 ? array_search(array_values($_GET)[0], $_GET) : null;
if (is_array($session) && $user === null) $UserSystem->redirect301("/u/cp");
if ($session === false && $user === null) $UserSystem->redirect301("/");
$user = $UserSystem->session($user);

$email = md5(strtolower(trim($user["email"])));
echo <<<EOT
<div class="col-xs-12 col-sm-3">
  <img src="https://www.gravatar.com/avatar/$email?s=512" class="img-thumbnail" />
</div>
<div class="col-xs-12 col-sm-9">
  <h1>$user[username]</h1><br>
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
    echo '&nbsp;<span class="label label-'.$badge["type"].'" data-toggle="popover" data-placement="top" data-content="'.$desc.'">'.$badge["name"].'</span> ';
  }
}

echo <<<EOT
</div>
EOT;

require_once("/var/www/abian/footer.php");
?>