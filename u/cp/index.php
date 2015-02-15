<?php
require_once("/var/www/abian/header.php");
$session = $UserSystem->verifySession();
if ($session === false) {
  $UserSystem->redirect301("/");
}
$session = $UserSystem->session();

$error = "";

if (isset($_POST["c"])) {
  $_POST["c"] = $UserSystem->sanitize($_POST["c"]);
  if ($session["company"] !== $_POST["c"]) {
    if (empty($session["company"])) {
      $hist = $Abian->historify("company.added", "To: " . $_POST["c"]);
    } else {
      $hist = $Abian->historify("company.updated", "To: " . $_POST["c"]);
    }
    $UserSystem->dbUpd(
      [
        "users",
        [
          "company" => $_POST["c"]
        ],
        [
          "id" => $session["id"]
        ]
      ]
    );
    $session["company"] = $_POST["c"];
  }
  $error = "<div class='col-xs-12'>
    <div class='alert alert-success'>
      Updated company to ".$_POST["c"].".
    </div>
  </div>";
}

if (isset($_POST["a"])) {
  $_POST["a"] = $UserSystem->sanitize($_POST["a"]);
  if ($session["twitchName"] !== $_POST["a"]) {
    if (empty($session["twitchName"])) {
      $hist = $Abian->historify("twitchName.added", "To: " . $_POST["a"]);
    } else {
      $hist = $Abian->historify("twitchName.updated", "To: " . $_POST["a"]);
    }
    $UserSystem->dbUpd(
      [
        "users",
        [
          "twitchName" => $_POST["a"]
        ],
        [
          "id" => $session["id"]
        ]
      ]
    );
    $session["twitchName"] = $_POST["a"];
  }
  $error = "<div class='col-xs-12'>
    <div class='alert alert-success'>
      Adventure Quest Worlds username updated to ".$_POST["a"].". This will
      now be displayed publicly on your profile.
    </div>
  </div>";
}

if (isset($_POST["t"])) {
  $_POST["t"] = $UserSystem->sanitize($_POST["t"]);
  if ($session["twitchName"] !== $_POST["t"]) {
    if (empty($session["twitchName"])) {
      $hist = $Abian->historify("twitchName.added", "To: " . $_POST["t"]);
    } else {
      $hist = $Abian->historify("twitchName.updated", "To: " . $_POST["t"]);
    }
    $UserSystem->dbUpd(
      [
        "users",
        [
          "twitchName" => $_POST["t"]
        ],
        [
          "id" => $session["id"]
        ]
      ]
    );
    $session["twitchName"] = $_POST["t"];
  }
  $error = "<div class='col-xs-12'>
    <div class='alert alert-success'>
      Twitch username updated to ".$_POST["t"].". This will
      now be displayed publicly on your profile.
    </div>
  </div>";
}

if (isset($_POST["g"])) {
  $_POST["g"] = $UserSystem->sanitize($_POST["g"]);
  if ($session["githubName"] !== $_POST["g"]) {
    if (empty($session["githubName"])) {
      $hist = $Abian->historify("githubName.added", "To: " . $_POST["g"]);
    } else {
      $hist = $Abian->historify("githubName.updated", "To: " . $_POST["g"]);
    }
    $UserSystem->dbUpd(
      [
        "users",
        [
          "githubName" => $_POST["g"]
        ],
        [
          "id" => $session["id"]
        ]
      ]
    );
    $session["githubName"] = $_POST["g"];
  }
  $error = "<div class='col-xs-12'>
    <div class='alert alert-success'>
      Github username updated to ".$_POST["g"].". This will
      now be displayed publicly on your profile.
    </div>
  </div>";
}

echo $error;

echo <<<EOT
<div class="col-xs-12 col-sm-3">
  <div class="list-group">
    <a href="#profile" class="list-group-item">Profile</a>
    <a href="#settings" class="list-group-item">Settings</a>
    <a href="#security" class="list-group-item">Security</a>
  </div>
</div>
EOT;

echo <<<EOT
<div class="col-xs-12 col-sm-9">
  <div class="well well-sm" id="profile">
    Profile
  </div>
  <div class="row">
    <div class="col-xs-6">
      <div class="panel panel-default">
        <div class="panel-heading">Company</div>
        <div class="panel-body">
          <form class="form form-vertical" method="post" action="">
            <div class="form-group">
              <label for="c">Currently: $session[company]</label>
              <input type="text" class="form-control" id="c" name="c">
            </div>
            <button type="submit" class="btn btn-primary btn-block">
              Update company
            </button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-xs-6">
      <div class="panel panel-default">
        <div class="panel-heading">AQW Name</div>
        <div class="panel-body">
          <form class="form form-vertical" method="post" action="">
            <div class="form-group">
              <label for="a">Currently: $session[twitchName]</label>
              <input type="text" class="form-control" id="a" name="a">
            </div>
            <button type="submit" class="btn btn-primary btn-block">
              Update AQW Name
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-6">
      <div class="panel panel-default">
        <div class="panel-heading">Twitch Username</div>
        <div class="panel-body">
          <form class="form form-vertical" method="post" action="">
            <div class="form-group">
              <label for="t">Currently: $session[twitchName]</label>
              <input type="text" class="form-control" id="t" name="t">
            </div>
            <button type="submit" class="btn btn-primary btn-block">
              Update Twitch Username
            </button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-xs-6">
      <div class="panel panel-default">
        <div class="panel-heading">Github Username</div>
        <div class="panel-body">
          <form class="form form-vertical" method="post" action="">
            <div class="form-group">
              <label for="g">Currently: $session[githubName]</label>
              <input type="text" class="form-control" id="g" name="g">
            </div>
            <button type="submit" class="btn btn-primary btn-block">
              Update Github Username
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-6">
      <div class="panel panel-default">
        <div class="panel-heading">Badges</div>
        <div class="panel-body">
EOT;

$badges = $UserSystem->dbSel(["badges", ["type" => ["!=", "a"]]]);
foreach ($badges as $key => $badge) {
  if ($key === 0) continue;
  $desc = $badge["description"];
  $desc = str_replace("%aq", substr($session["id"], 0, 2), $desc);
  $desc = str_replace("%twitch", substr(sha1($session["id"].$session["username"]), 0, 7), $desc);
  $desc = str_replace("%github", substr(sha1($session["id"].$session["username"]), 0, 7), $desc);
  $has = $hasIt = '';
  $hasIt = $UserSystem->dbSel(["badging", ["user" => $session["id"], "badge" => $badge["id"]]]);
  if (!isset($hasIt[1])) $hasIt[1] = ["badge" => -1];
  if ($hasIt[0] > 0 && intval($hasIt[1]["badge"]) == $badge["id"]) {
    $has = '<abbr title="You have this one!"><i class="fa fa-check"></i></abbr>';
  }
  echo '&nbsp;<span class="label label-'.$badge["type"].'" data-toggle="popover" data-placement="top" data-content="'.$desc.'">'.$badge["name"].' '.$has.'</span> ';
}

$emailChanged = $session["emailChanged"] > 0 ? date("Y-m-d\TH:i", $session["emailChanged"]) : "Never";
echo <<<EOT
        </div>
      </div>
    </div>
  </div>
  <div class="well well-sm" id="settings">
    Settings
  </div>
  <div class="row">
    <div class="col-xs-6">
      <div class="panel panel-default">
        <div class="panel-heading">Email</div>
        <div class="panel-body">
          <form class="form form-vertical" method="post" action="">
            <div class="form-group">
              <label for="e">Currently: <abbr title="Changed $emailChanged">
                $session[email]</abbr></label>
              <input type="text" class="form-control" id="e" name="e">
            </div>
            <div class="form-group">
              <label for="pe">Current Password</label>
              <input type="password" class="form-control" id="pe" name="pe">
            </div>
            <button type="submit" class="btn btn-primary btn-block">
              Update email address
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
EOT;

/*
 * Security
 * Active sessions (userblobs)
 */
echo <<<EOT
  <div class="well well-sm" id="security">
    Security
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">Active Sessions</div>
    <div class="panel-body">
      <a href="/u/logout?all" class="btn btn-block btn-default">
        Remove all Sessions
      </a>
    </div>

    <table class="table table-responsive table-rounded table-bordered">
EOT;

$blobs = $UserSystem->dbSel(["userblobs", ["user" => $session["id"]]]);
foreach ($blobs as $key => $blob) {
  if ($key === 0) continue;
  $ext = "";
  if ($blob["code"] === $_COOKIE[SITENAME]) {
    $btn = "<a href='/u/logout' class='btn btn-info btn-xs'>Remove</a>";
    $ext = " class='bg-info text-info'";
  } else {
    $btn = "<a href='/u/logout?specific=".$blob["code"]."'
      class='btn btn-default btn-xs'>
      Remove
    </a>";
  }
  echo "<tr".$ext.">
      <td>".date("Y-m-d\TH:i", $blob["date"])."</td>
      <td>
        <img src='http://api.hostip.info/flag.php?ip=".$blob["ip"]."' 
          height='16'>
        ".$blob["ip"]."
      </td>
      <td>".ucfirst($blob["action"])."</td>
      <td>".$btn."</td>
    </tr>
  ";
}

echo <<<EOT
    </table>
  </div>
EOT;

/*
 * Security
 * History
 */
echo <<<EOT
  <div class="panel panel-default">
    <div class="panel-heading">History</div>
    <div class="panel-body">
      This is a log of events involving your account.
    </div>

    <table class="table table-responsive table-rounded table-bordered">
EOT;

$iBO = $UserSystem->dbSel(["history", ["actor" => $session["id"]], ["id", "desc"]]);
$iBU = $UserSystem->dbSel(["history", ["targeted" => $session["id"]], ["id", "desc"]]);
unset($iBO[0]);
unset($iBU[0]);
$history = array_merge($iBO, $iBU);
foreach ($history as $hist) {
  $ext = "";
  echo "<tr".$ext.">
      <td>".date("Y-m-d\TH:i", $hist["date"])."</td>
      <td>
        <img src='http://api.hostip.info/flag.php?ip=".$hist["actorIp"]."' height='16'>
        ".$hist["actorIp"]."
      </td>
      <td>".$hist["action"]."</td>
      <td>".ucfirst($hist["description"])."</td>
    </tr>
  ";
}

echo <<<EOT
    </table>
  </div>
</div>
EOT;

require_once("../../footer.php");
?>
