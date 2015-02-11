<?php
require_once("../../header.php");

$session = $UserSystem->verifySession();
if ($session === true) {
  $session = $UserSystem->session();
  if (isset($_GET["specific"])) {
    $logout = $UserSystem->logOut($_GET["specific"], $session["id"], false);
    $UserSystem->redirect301("/");
  } elseif (isset($_GET["all"])) {
    $logout = $UserSystem->logOut(
      $_COOKIE[SITENAME],
      $session["id"],
      true,
      true
    );
    $UserSystem->redirect301("/");
  } else {
    $logout = $UserSystem->logOut($_COOKIE[SITENAME], $session["id"], true);
    $UserSystem->redirect301("/");
  }
} else {
  $UserSystem->redirect301("/");
}

require_once("../../footer.php");
?>
