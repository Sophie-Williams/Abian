<?php
$sidebar = false;
require_once("../../header.php");

if (is_array($session)) {
  if (isset($_GET["specific"])) {
    $hist = $Abian->historify("user.logout", "Specific session");
    $logout = $UserSystem->logOut($_GET["specific"], $session["id"], false);
    $UserSystem->redirect301("/");
  } elseif (isset($_GET["all"])) {
    $hist = $Abian->historify("user.logout", "All sessions");
    $logout = $UserSystem->logOut(
      $_COOKIE[SITENAME],
      $session["id"],
      true,
      true
    );
    $UserSystem->redirect301("/");
  } else {
    $hist = $Abian->historify("user.logout", "Current session");
    $logout = $UserSystem->logOut($_COOKIE[SITENAME], $session["id"], true);
    $UserSystem->redirect301("/");
  }
} else {
  $UserSystem->redirect301("/");
}

require_once("../../footer.php");
?>
