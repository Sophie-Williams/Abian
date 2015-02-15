<?php
require_once("../../header.php");

if (is_array($session)) $UserSystem->redirect301("/");

if (isset($_POST["u"])) {
  $login = $UserSystem->logIn($_POST["u"], $_POST["p"]);
  if ($login === true) {
    $UserSystem->redirect301("/");
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
        <label for="p">Password</label>
        <input type="password" class="form-control" id="p" name="p">
      </div>
      <button type="submit" class="btn btn-primary btn-block">Log in</button>
    </form>
  </div>
</div>
<div class="col-xs-12 col-md-6">
  <div class="well well-md">
    Go register. Now.
    <br><br>
    <a href="/u/register" class="btn btn-primary btn-block">Register plox</a>
  </div>
</div>

<?php require_once("../../footer.php"); ?>
