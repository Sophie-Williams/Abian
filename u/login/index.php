<?php
require_once("../../header.php");
$error = "";

if (is_array($session)) $UserSystem->redirect301("/");

if (isset($_POST["u"]) && isset($_POST["p"])) {
  $login = $UserSystem->logIn($_POST["u"], $_POST["p"]);
  if ($login === true) $error = $UserSystem->redirect301("/?loggedin");
  if ($login === "twoStep") $error = "<div class='alert alert-danger'>TwoStep login enabled.<br>Check your email for the link to finish logging in.</div>";
  if ($login === "activate") $error = "<div class='alert alert-warning'>Your account has not yet been activated.<br>Follow the link in your email.</div>";
  if ($login === "password") $error = "<div class='alert alert-danger'>That password was incorrect.</div>";
  if ($login === "oldPassword") $error = "<div class='alert alert-warning'>That was your last password, use your most recent one.</div>";
  if ($login === "username") $error = "<div class='alert alert-danger'>No user with that username was found.</div>";
  if (!isset($error)) "*shrug* I dunno:<br>" . $login;
}
?>

<div class="col-xs-12 col-md-6">
  <?=$error?>
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
