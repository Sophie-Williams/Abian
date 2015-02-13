<?php
require_once("../../header.php");
$session = $UserSystem->verifySession();
if ($session === false) {
  $UserSystem->redirect301("/");
}
$session = $UserSystem->session();

$error = "";

if (isset($_POST["c"])) {
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
  $error = "<div class='col-xs-12'>
    <div class='alert alert-success'>
      Updated company to ".$_POST["c"].".
    </div>
  </div>";
}

if (isset($_POST["a"])) {
  $UserSystem->dbUpd(
    [
      "users",
      [
        "aqName" => $_POST["a"]
      ],
      [
        "id" => $session["id"]
      ]
    ]
  );
  $error = "<div class='col-xs-12'>
    <div class='alert alert-success'>
      Adventure Quest Worlds username updated to ".$_POST["a"].". This will
      now be displayed publicly on your profile.
    </div>
  </div>";
}

$sessions = "";
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
  $sessions .= "<tr".$ext.">
    <td>".date("Y-m-d\TH:i", $blob["date"])."</td>
    <td>
      <img src='http://api.hostip.info/flag.php?ip=".$blob["ip"]."' height='16'>
      ".$blob["ip"]."
    </td>
    <td>".ucfirst($blob["action"])."</td>
    <td>".$btn."</td>
  </tr>
  ";
}

echo $error;

echo <<<EOT
<div class="col-xs-12 col-sm-6">
  <div class="well well-md">
    <a href="/u/logout?all" class="btn btn-block btn-default">
      Remove all Sessions
    </a>
    <br>
    <table class="table table-responsive table-rounded table-bordered">
      <tr>
        <th>Date</th>
        <th>IP</th>
        <th>Type</th>
        <th></th>
      </tr>
      $sessions
    </table>
  </div>
</div>
EOT;

echo <<<EOT
<div class="col-xs-12 col-sm-3">
  <div class="well well-md">
    <form class="form form-vertical" method="post" action="">
      <div class="form-group">
        <label for="c">Company: <u>$session[company]</u></label>
        <input type="text" class="form-control" id="c" name="c">
      </div>
      <button type="submit" class="btn btn-primary btn-block">
        Update company
      </button>
    </form>
  </div>
</div>
EOT;

echo <<<EOT
<div class="col-xs-12 col-sm-3">
  <div class="well well-md">
    <form class="form form-vertical" method="post" action="">
      <div class="form-group">
        <label for="a">AQW Username: <u>$session[aqName]</u></label>
        <input type="text" class="form-control" id="a" name="a">
      </div>
      <button type="submit" class="btn btn-primary btn-block">
        Update AQW Username
      </button>
    </form>
    <br>
    In the event that Artix Entertainment does crawl this website for users to
    ban, adding your username here would make that very easy.
    <br>
    <span class='text-danger'>
      If you add your username, it will be displayed.
    </span>
  </div>
</div>
EOT;

require_once("../../footer.php");
?>
