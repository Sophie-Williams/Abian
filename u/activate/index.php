<?php
require_once("../../header.php");

$session = $UserSystem->verifySession();
if ($session === true) {
  $UserSystem->redirect301("/");
} elseif (isset($_GET["blob"])) {
  $activate = $UserSystem->activateUser($_GET["blob"]);
  var_dump($activate);
} else {
  $UserSystem->redirect301("/");
}
