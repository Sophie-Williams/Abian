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
} elseif ($bot !== null) {
  $bot = $UserSystem->dbSel(["bots", ["slug" => $bot]]);
  if ($bot[0] == 1) {
    $bot = $bot[1];
    $error = "";
    if (isset($_POST["m"]) && is_array($session)) {
      $r = $UserSystem->sanitize($_POST["r"], "n");
      $r = $r == 0 ? null : $r;
      $UserSystem->dbIns(
        [
          "comments",
          [
            "date" => time(),
            "on" => "bot.".$bot["id"],
            "user" => $session["id"],
            "reply" => $r,
            "message" => $UserSystem->sanitize($_POST["m"])
          ]
        ]
      );
      $error = '
        <div class="alert alert-success">
          Your comment has been added.
        </div>
      ';
    }

    $Parsedown = new Parsedown();
    $bot["name"] = ucfirst($bot["name"]);
    $bot["user"] = intval($bot["user"]);
    $user = $UserSystem->session($bot["user"]);
    $mine = "";
    if ($bot["user"] == $session["id"]) {
      $mine = '
        <a href="/b/e/?'.$bot["slug"].'" class="btn btn-default">
          <i class="fa fa-pencil"></i>
        </a>
      ';
    }

    echo <<<EOT
    <div class="col-xs-12">
      $error
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
          <a href="/dl/$bot[slug].zip" class="btn btn-default text-info">
            <i class="fa fa-cloud-download"></i>
          </a>
          $mine
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-9">
EOT;

echo $Parsedown->text($bot["body"]);

$email = md5(strtolower(trim($user["email"])));
$date = date("Y-m-d\TH:i", $bot["dateCreate"]);
$upDate = date("Y-m-d\TH:i", $bot["dateUpdate"]);
$updated = $bot["dateUpdate"] != 0 ? "<br><br>Updated $upDate" : "";
echo <<<EOT
        </div>
        <div class="col-xs-12 col-sm-3 text-center">
            <img src="https://www.gravatar.com/avatar/$email?s=512" 
              class="img-thumbnail" style="width:75%" />
            <br>
            Created by <a href="/u?$user[username]">$user[username]</a>
          <br>
          On $date$updated
        </div>
      </div>
      <hr>
      <div class="row">
EOT;

$Abian->getComments(["on" => "bot.".$bot["id"]], true);

echo <<<EOT
      </div>
    </div>
EOT;
  }


  $recaptcha = recaptcha_get_html($re["site"], null, true);
  echo <<<EOT
  <div class="modal fade" id="addComment" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <form action="" method="post">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" 
              aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title">Add comment</h4>
          </div>
          <div class="modal-body">
            <textarea class="form-control" rows="5" name="m"
              maxlength="512"></textarea>
            <input type="hidden" name="r" value="0">
            $recaptcha
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Close
            </button>
            <button type="submit" class="btn btn-primary">
              Submit <i class="fa fa-paper-plane"></i>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
EOT;
}

require_once("/var/www/abian/footer.php");
?>