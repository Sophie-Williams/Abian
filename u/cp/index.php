<?php
$sidebar = false;
if (isset($_GET["code"])) $chillS = true;
require_once("/var/www/abian/header.php");
if ($session === false) $UserSystem->redirect301("/u/login");

$error = "";

if (isset($_GET["code"]) && !isset($_GET["tw"])) {
  $url = "https://github.com/login/oauth/access_token";
  $myvars = http_build_query(
    [
      "client_id" => $gh["client"],
      "client_secret" => $gh["secret"],
      "code" => $_GET["code"]
    ],
    "",
    "&"
  );
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $myvars);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $response = curl_exec($ch);

  $authToken = explode("&", $response)[0];
  $authToken = explode("=", $authToken)[1];

  $url = "https://api.github.com/user?access_token=".$authToken;
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
  $response = json_decode(curl_exec($ch));
  $ghName = $response->login;
  if ($session["githubName"] !== $ghName) {
    if (empty($session["githubName"])) {
      $hist = $Abian->historify("githubName.added", $ghName);
    } else {
      $hist = $Abian->historify("githubName.updated", "To: " . $ghName);
    }
    $UserSystem->dbUpd(
      [
        "users",
        [
          "githubName" => $ghName
        ],
        [
          "id" => $session["id"]
        ]
      ]
    );
    $session["githubName"] = $ghName;
  }
  $Abian->giveBadge(14, $session["id"]);
  $UserSystem->redirect301("/u/cp?gh=".$ghName);
}

if (isset($_GET["code"]) && isset($_GET["tw"])) {
  function get_url_contents($url){
    $crl = curl_init();
    $timeout = 5;
    curl_setopt ($crl, CURLOPT_URL,$url);
    curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
    $ret = curl_exec($crl);
    curl_close($crl);
    return $ret;
  }

  function post_url_contents($url, $fields) {
    $fields_string = "";
    foreach($fields as $key=>$value) { $fields_string .= $key.'='.
      urlencode($value).'&'; }
    rtrim($fields_string, '&');

    $crl = curl_init();
    $timeout = 5;

    curl_setopt($crl, CURLOPT_URL,$url);
    curl_setopt($crl,CURLOPT_POST, count($fields));
    curl_setopt($crl,CURLOPT_POSTFIELDS, $fields_string);

    curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
    $ret = curl_exec($crl);
    curl_close($crl);
    return $ret;
  }

  $login_params = array(
    'client_id'=> $tw["client"],
    'client_secret' => $tw["secret"],
    'scope' => 'user_read',
    'grant_type' => 'authorization_code',
    'code' => $_GET["code"],
    'redirect_uri' => 'https://abian.zbee.me/u/cp?tw'
  );

  $result = json_decode(
    post_url_contents(
      "https://api.twitch.tv/kraken/oauth2/token",
      $login_params
    )
  );

  $user = json_decode(
    get_url_contents("https://api.twitch.tv/kraken/user?oauth_token="
      .$result->access_token)
  );
  $twName = $user->display_name;
  if ($session["twitchName"] !== $twName) {
    if (empty($session["twitchName"])) {
      $hist = $Abian->historify("twitchName.added", $twName);
    } else {
      $hist = $Abian->historify("twitchName.updated", "To: " . $twName);
    }
    $UserSystem->dbUpd(
      [
        "users",
        [
          "twitchName" => $twName
        ],
        [
          "id" => $session["id"]
        ]
      ]
    );
    $session["twitchName"] = $twName;
  }
  $Abian->giveBadge(2, $session["id"]);
  $UserSystem->redirect301("/u/cp?tw=".$twName);
}

if (isset($_GET["tw"])) {
  $_GET["tw"] = $UserSystem->sanitize($_GET["tw"]);
  $error = "<div class='col-xs-12'>
    <div class='alert alert-success'>
      Twitch username updated to ".$_GET["tw"].". This will
      now be displayed publicly on your profile.
    </div>
  </div>";
}

if (isset($_GET["gh"])) {
  $_GET["gh"] = $UserSystem->sanitize($_GET["gh"]);
  $error = "<div class='col-xs-12'>
    <div class='alert alert-success'>
      Github username updated to ".$_GET["gh"].". This will
      now be displayed publicly on your profile.
    </div>
  </div>";
}

if (isset($_POST["c"])) {
  $_POST["c"] = $UserSystem->sanitize($_POST["c"]);
  if ($session["company"] !== $_POST["c"]) {
    if (empty($session["company"])) {
      $hist = $Abian->historify("company.added", $_POST["c"]);
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
  if ($session["aqName"] !== $_POST["a"]) {
    if (empty($session["aqName"])) {
      $hist = $Abian->historify("aqName.added", $_POST["a"]);
    } else {
      $hist = $Abian->historify("aqName.updated", "To: " . $_POST["a"]);
    }
    $UserSystem->dbUpd(
      [
        "users",
        [
          "aqName" => $_POST["a"],
          "aqVerified" => 0
        ],
        [
          "id" => $session["id"]
        ]
      ]
    );
    $session["aqName"] = $_POST["a"];
  }
  $error = "<div class='col-xs-12'>
    <div class='alert alert-success'>
      Adventure Quest Worlds username updated to ".$_POST["a"].". This will
      now be displayed publicly on your profile.
    </div>
  </div>";
}

if (isset($_POST["z"])) {
  $_POST["z"] = $UserSystem->sanitize($_POST["z"]);
  if ($session["timeZone"] !== $_POST["z"]) {
    $hist = $Abian->historify("timeZone.updated", "To: " . $_POST["z"]);
    $UserSystem->dbUpd(
      [
        "users",
        [
          "timeZone" => $_POST["z"]
        ],
        [
          "id" => $session["id"]
        ]
      ]
    );
    $session["timeZone"] = $_POST["z"];
    date_default_timezone_set($session["timeZone"]);
  }
  $error = "<div class='col-xs-12'>
    <div class='alert alert-success'>
      Timezone updated to ".$_POST["z"].". This will
      now be displayed publicly on your profile.
    </div>
  </div>";
}

if (isset($_POST["e"])) {
  $_POST["e"] = $UserSystem->sanitize($_POST["e"], "e");
  if ($session["email"] !== $_POST["e"]) {
    $pass = hash("sha256", $_POST["pe"].$session["salt"]);
    if ($pass === $session["password"]) {
      $hist = $Abian->historify("email.updated", "To: " . $_POST["e"]);
      $UserSystem->dbUpd(
        [
          "users",
          [
            "email" => $_POST["e"],
            "oldEmail" => $session["email"],
            "emailChanged" => time()
          ],
          [
            "id" => $session["id"]
          ]
        ]
      );
      $se = true;
      if ($_POST["e"] == $session["oldEmail"]
        || $_POST["e"] == $session["email"]) $se = false;
      if ($se) {
        $UserSystem->sendMail(
          [$session["email"], $session["oldEmail"], $_POST["e"]],
          "Your email has been changed on " . SITENAME,
          "        Hello, ".$session["username"].".

          Your email has been updated on ".SITENAME." from ".$session["email"]." to ".$_POST["e"].".

          ---

          If you did not initiate this change, you should update your passwords.

          Thank you.
          "
        );
        $error = "<div class='col-xs-12'>
          <div class='alert alert-success'>
            Email updated to ".$_POST["e"].". A notification email has been sent
            to your previous emails and the new one.
          </div>
        </div>";
      } else {
        $error = "<div class='col-xs-12'>
          <div class='alert alert-success'>
            Email updated to ".$_POST["e"].".
          </div>
        </div>";
      }
      $session["oldEmail"] = $session["email"];
      $session["email"] = $_POST["e"];
    } else {
      $error = "<div class='col-xs-12'>
        <div class='alert alert-danger'>
          The entered password was not correct. Email has not been changed.
        </div>
      </div>";
    }
  }
}

if (isset($_POST["cp"])) {
  $_POST["cp"] = hash("sha256", $_POST["cp"].$session["salt"]);
  if ($session["password"] === $_POST["cp"]) {
    if ($_POST["cnp"] === $_POST["np"]) {
      $info = "In " . $Abian->codeToCountry($_SERVER["HTTP_CF_IPCOUNTRY"])
      . " using " . $Abian->getBrowser() . " on " . $Abian->getOS();
      $hist = $Abian->historify("password.updated", $info);
      $salt = $UserSystem->createSalt($session["id"]);
      $_POST["np"] = hash("sha256", $_POST["np"].$salt);
      $UserSystem->dbUpd(
        [
          "users",
          [
            "password" => $_POST["np"],
            "salt" => $salt,
            "oldPassword" => $session["password"],
            "oldSalt" => $session["salt"],
            "passwordChanged" => time()
          ],
          [
            "id" => $session["id"]
          ]
        ]
      );
      $session["passwordChanged"] = time();
      $error = "<div class='col-xs-12'>
        <div class='alert alert-success'>
          Password has been updated.
        </div>
      </div>";
    } else {
      $error = "<div class='col-xs-12'>
        <div class='alert alert-danger'>
          New passwords do not equal each other. Password has not been changed.
        </div>
      </div>";
    }
  } else {
    $error = "<div class='col-xs-12'>
      <div class='alert alert-danger'>
        Current password is not correct. Password has not been changed.
      </div>
    </div>";
  }
}

if (isset($_POST["s"])) {
  $_POST["s"] = $UserSystem->sanitize($_POST["s"], "n");
  $human = $_POST["s"] == 0 ? "Off" : "On";
  $hist = $Abian->historify("twoStep.updated", "To: " . $human);
  $UserSystem->dbUpd(
    [
      "users",
      [
        "twoStep" => $_POST["s"]
      ],
      [
        "id" => $session["id"]
      ]
    ]
  );
  $session["twoStep"] = $_POST["s"];
  $error = "<div class='col-xs-12'>
    <div class='alert alert-success'>
      Updated Two Step Authentication to be ".$human.".
    </div>
  </div>";
}

echo $error;

echo <<<EOT
<div class="col-xs-12 col-sm-2">
  <div class="list-group">
    <a href="#profile" class="list-group-item">Profile</a>
    <a href="#settings" class="list-group-item">Settings</a>
    <a href="#security" class="list-group-item">Security</a>
  </div>
</div>
EOT;

$twURL = "https://api.twitch.tv/kraken/oauth2/authorize";
$twURL .= "?response_type=code&client_id=$tw[client]";
$twURL .= "&redirect_uri=https://abian.zbee.me/u/cp?tw&scope=user_read";

echo <<<EOT
<div class="col-xs-12 col-sm-10">
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
              <label for="a">Currently: $session[aqName]</label>
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
          Currently:
          <a href="https://twitch.tv/$session[twitchName]" target="_blank">
            $session[twitchName]</a>
          <br>
          <a class="btn btn-block btn-primary" href="$twURL">
            <i class="fa fa-twitch"></i>
            Verify Twitch
          </a>
        </div>
      </div>
    </div>

    <div class="col-xs-6">
      <div class="panel panel-default">
        <div class="panel-heading">GitHub Username</div>
        <div class="panel-body">
          Currently:
          <a href="https://github.com/$session[githubName]" target="_blank">
            $session[githubName]</a>
          <br>
          <a class="btn btn-block btn-primary"
            href="https://github.com/login/oauth/authorize?scope=
            &client_id=$gh[client]">
            <i class="fa fa-github"></i>
            Verify GitHub
          </a>
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

$badges = $UserSystem->dbSel(
  ["badges", ["id" => ["!=", "a"]], ["order", "asc"]]
);
foreach ($badges as $key => $badge) {
  if ($key === 0) continue;
  $desc = $badge["description"];
  $desc = str_replace("%aq", substr($session["id"], 0, 2), $desc);
  echo '<span class="label label-'.$badge["type"].'" data-toggle="popover"
    data-placement="top" data-content="'.$desc.'">'.$badge["name"].'</span> ';
}

$emailChanged = $session["emailChanged"] > 0 ?
  date("Y-m-d\TH:i", $session["emailChanged"]) : "Never";
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
    <div class="col-xs-6">
      <div class="panel panel-default">
        <div class="panel-heading">Timezone</div>
        <div class="panel-body">
          <form class="form form-vertical" method="post" action="">
            <select name="z" class="form-control input-lg">
EOT;

echo '<option value="'.$session["timeZone"].'" SELECTED>'.$session["timeZone"].'</option>';

$timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
foreach ($timezones as $timezone) {
  echo '<option value="'.$timezone.'">'.$timezone.'</option>';
}

$passChanged = $session["passwordChanged"] > 0 ? date("Y-m-d\TH:i", $session["passwordChanged"]) : "Never";
$twoStep = $session["twoStep"] == 0 ? 1 : 0;
$twoStepH = $session["twoStep"] == 0 ? "Off" : "On";

echo <<<EOT
            </select>
            <br>
            <button type="submit" class="btn btn-primary btn-block">
              Update timezone
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-6">
      <div class="panel panel-default">
        <div class="panel-heading">Password</div>
        <div class="panel-body">
          Last changed <b>$passChanged</b>
          <br>
          <form class="form form-vertical" method="post" action="">
            <div class="form-group">
              <label for="cp">Current Password</label>
              <input type="password" class="form-control" id="cp" name="cp">
            </div>
            <div class="form-group">
              <label for="np">New Password</label>
              <input type="password" class="form-control" id="np" name="np">
            </div>
            <div class="form-group">
              <label for="cnp">Confirm New Password</label>
              <input type="password" class="form-control" id="cnp" name="cnp">
            </div>
            <button type="submit" class="btn btn-primary btn-block">
              Update password
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
  <div class="row">
    <div class="col-xs-6">
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
        <span class='f32'><i class='flag ".strtolower($blob["a2"])."' title='".$blob["a2"]."'>&nbsp;</i></span>
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
    </div>
    <div class="col-xs-6">
      <div class="panel panel-default">
        <div class="panel-heading">Two Step Authentication</div>
        <div class="panel-body">
          Two Step Authentication is currently <b>$twoStepH</b>.
          <br><br>
          If Two Step Authentication is activated then when you attempt to
          login, you will be stopped after entering the correct password, and
          will need to follow a link that gets emailed to you.
          <br>
          This makes it so that someone cannot simple hack your Abian account,
          but must also hack your email to gain access to your Abian account.
          <br><br>
          <form class="form form-vertical" method="post" action="">
            <input type="hidden" name="s" value="$twoStep">
            <button type="submit" class="btn btn-primary btn-block">
              Toggle Two Step Authentication
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
EOT;

/*
 * Security
 * History
 */
echo <<<EOT
  <div class="panel panel-default" style="max-height:500px;overflow-y:auto">
    <div class="panel-heading">History</div>
    <div class="panel-body">
      This is a log of events involving your account.
    </div>

    <table class="table table-responsive table-rounded table-bordered">
EOT;

$iBO = $UserSystem->dbSel(
  [
    "history",
    ["actor" => $session["id"]],
    ["id", "desc"]
  ]
);
$iBU = $UserSystem->dbSel(
  [
    "history",
    ["targeted" => $session["id"]],
    ["id", "desc"]
  ]
);
unset($iBO[0]);
unset($iBU[0]);
$history = array_merge($iBO, $iBU);
foreach ($history as $hist) {
  $ext = "";
  echo "<tr".$ext.">
      <td>".date("Y-m-d\TH:i", $hist["date"])."</td>
      <td>
        <span class='f32'><i class='flag ".strtolower($hist["actorA2"])."'
          title='".$hist["actorA2"]."'>&nbsp;</i></span>
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
