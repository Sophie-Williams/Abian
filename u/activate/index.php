<?php
$sidebar = false;
require_once("../../header.php");

if (is_array($session)) {
  $UserSystem->redirect301("/");
} elseif (isset($_GET["blob"])) {
  $activate = $UserSystem->activateUser($_GET["blob"]);
  $UserSystem->redirect301("/u/login");
} else {
  $UserSystem->redirect301("/");
}
