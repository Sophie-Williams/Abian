<?php
require_once("../../header.php");

$session = $UserSystem->verifySession();
if ($session === true) {
  $UserSystem->redirect301("/");
}

$recaptcha = recaptcha_get_html($re["site"]);

if (isset($_POST["u"])) {
  $resp = recaptcha_check_answer ($re["secret"], $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
  if (!$resp->is_valid) {
    echo "The reCAPTCHA was incorrect: ".$resp->error.".";
  } else {
    if ($_POST["p"] === $_POST["cp"]) {
      $register = $UserSystem->addUser($_POST["u"], $_POST["p"], $_POST["e"]);
      if ($register === true) {
        $UserSystem->redirect301("/");
      }
    } else {
      echo "You suck. Stupid";
    }
  }
}
?>

<div class="col-xs-12 col-md-6">
  <div class="well well-md">
    <form class="form form-vertical" method="post" action="">
      <div class="form-group">
        <label for="u">Username</label>
        <input type="text" class="form-control" id="u" name="u">
      </div>
      <div class="form-group">
        <label for="e">Email</label>
        <input type="email" class="form-control" id="e" name="e">
      </div>
      <div class="form-group">
        <label for="p">Password</label>
        <input type="password" class="form-control" id="p" name="p">
      </div>
      <div class="form-group">
        <label for="cp">Confirm Password</label>
        <input type="password" class="form-control" id="cp" name="cp">
      </div>
      <div class="form-group">
        <?=$recaptcha?>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Register</button>
    </form>
  </div>
</div>

<div class="col-xs-12 col-md-6">
  <div class="well well-md">
    Maybe.
  </div>
</div>

<?php require_once("../../footer.php"); ?>
