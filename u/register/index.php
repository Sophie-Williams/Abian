<?php
require_once("../../header.php");

$session = $UserSystem->verifySession();
if ($session === true) {
  $UserSystem->redirect301("/");
}

if (isset($_POST["u"])) {
  if ($_POST["p"] === $_POST["cp"]) {
    $register = $UserSystem->addUser($_POST["u"], $_POST["p"], $_POST["e"]);
    if ($register === true) {
      $UserSystem->redirect301("/");
    } else {
      var_dump($register);
    }
  } else {
    echo "You suck. Stupid";
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
      <button type="submit" class="btn btn-primary btn-block">Register</button>
    </form>
  </div>
</div>

<?php require_once("../../footer.php"); ?>
