<?php
require_once("/var/www/abian/header.php");
if ($session === false) $UserSystem->redirect301("/u/login");

$bots = $UserSystem->dbSel(["bots", ["user" => $session["id"]]]);

echo <<<EOT
  <div class="col-xs-12">
    <div class="row">
      <div class="col-xs-12 col-sm-6 col-sm-push-6 text-right">
        <div class="btn-group">
          <a href="/b/a" class="btn btn-default">
            <i class="fa fa-plus"></i> Add a bot
          </a>
          <a href="/b/c" class="btn btn-default">
            <i class="fa fa-magic"></i> Create a bot
          </a>
        </div>
      </div>
      <div class="col-xs-12 col-sm-6 col-sm-pull-6">
        You have $bots[0] bots.
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-xs-12">
        <div class="table-responsive">
          <table class="table table-striped table-bordered">
            <tr>
              <th>Title</th>
              <th>Downloads</th>
              <th>Views</th>
              <th>Points</th>
              <th>Status</th>
              <th></th>
            </tr>
EOT;

foreach ($bots as $key => $bot) {
  if ($key === 0) continue;
  echo '
    <tr>
      <td>'.$bot["name"].'</td>
      <td>0</td>
      <td>0</td>
      <td>0</td>
      <td>Not listed</td>
      <td>
        <div class="btn-group">
          <a class="btn btn-default" href="/b/e?'.$bot["slug"].'">
            <i class="fa fa-pencil"></i> Edit
          </a>
          <a class="btn btn-default" href="/b?'.$bot["slug"].'">
            <i class="fa fa-eye"></i> View
          </a>
        </div>
      </td>
    </tr>
  ';
}

echo <<<EOT
          </table>
        </div>
      </div>
    </div>
  </div>
EOT;

require_once("/var/www/abian/footer.php");
?>
