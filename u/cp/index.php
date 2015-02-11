<?php
require_once("../../header.php");
$session = $UserSystem->verifySession();
if ($session === false) {
  $UserSystem->redirect301("/");
}
$session = $UserSystem->session();

$sessions = "";
$blobs = $UserSystem->dbSel(["userblobs", ["user" => $session["id"]]]);
foreach ($blobs as $key => $blob) {
  if ($key === 0) continue;
  $ext = "";
  if ($blob["code"] === $_COOKIE[SITENAME]) {
    $btn = "<a href='/u/logout' class='btn btn-info btn-xs'>Remove</a>";
    $ext = " class='bg-info text-info'"; #"bg info don't wurk, it iz rekt" - @mheetu
  } else {
    $btn = "<a href='/u/logout?specific=".$blob["code"]."' class='btn btn-default btn-xs'>Remove</a>";
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

echo <<<EOT

<div class="col-xs-12 col-sm-6">
  <div class="well well-md">
    <a href="/u/logout?all" class="btn btn-block btn-primary">
      Remove all Sessions
    </a>

    <br>

    <table class="table table-responsive table-rounded table-bordered
      table-striped">
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

<div class="col-xs-12 col-sm-6">
  <div class="well well-md">
    You have cancer.
  </div>
</div>

EOT;

require_once("../../footer.php");
?>
