<?php
require_once("/var/www/abian/header.php");
if (!is_array($session)) $UserSystem->redirect301("/u/login");
#if ($session["title"] !== "Moderator") $UserSystem->redirect301("/");
?>

<div class="row">
  <div class="col-xs-12 col-sm-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-user"></i> Users</h3>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-xs-12 col-sm-6">
            <a href="#" class="btn btn-default btn-block">Ban Appeals</a>
          </div>
          <div class="col-xs-12 col-sm-6">
            <a href="#" class="btn btn-default btn-block">Banned</a>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-xs-12 col-sm-6">
            <a href="#" class="btn btn-default btn-block">Statistics</a>
          </div>
          <div class="col-xs-12 col-sm-6">
            <a href="#" class="btn btn-default btn-block">Reported</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-sm-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-android"></i> Bots</h3>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-xs-12 col-sm-6">
            <a href="#" class="btn btn-default btn-block">Need Approving</a>
          </div>
          <div class="col-xs-12 col-sm-6">
            <a href="#" class="btn btn-default btn-block">Need Reapproving</a>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-xs-12 col-sm-6">
            <a href="#" class="btn btn-default btn-block">Reported</a>
          </div>
          <div class="col-xs-12 col-sm-6">
            <a href="#" class="btn btn-default btn-block">Heavily downvoted</a>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-xs-12 col-sm-6">
            <a href="#" class="btn btn-default btn-block">6-monthers</a>
          </div>
          <div class="col-xs-12 col-sm-6">
            <a href="#" class="btn btn-default btn-block">Unlisted</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-xs-12 col-sm-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-suitcase"></i> Business</h3>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-xs-12 col-sm-6">
            <a href="#" class="btn btn-default btn-block">Ads</a>
          </div>
          <div class="col-xs-12 col-sm-6">
            <a href="#" class="btn btn-default btn-block">Ad Statistics</a>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-xs-12 col-sm-6">
            <a href="#" class="btn btn-default btn-block">Clients</a>
          </div>
          <div class="col-xs-12 col-sm-6">
            <a href="#" class="btn btn-default btn-block">Financial Info</a>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-xs-12 col-sm-6">
            <a href="#" class="btn btn-default btn-block">DMCAs / C&Ds</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-sm-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-building"></i> Website</h3>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-xs-12 col-sm-6">
            <a href="#" class="btn btn-default btn-block">Blog</a>
          </div>
          <div class="col-xs-12 col-sm-6">
            <a href="#" class="btn btn-default btn-block">Staff</a>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-xs-12 col-sm-6">
            <a href="#" class="btn btn-default btn-block">Statistics</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
require_once("/var/www/abian/footer.php");
?>