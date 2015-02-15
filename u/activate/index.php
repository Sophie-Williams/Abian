<?php
require_once("../../header.php");

if (is_array($session)) {
  $UserSystem->redirect301("/");
} elseif (isset($_GET["blob"])) {
  $activate = $UserSystem->activateUser($_GET["blob"]);
  var_dump($activate);
} else {
  $UserSystem->redirect301("/");
}
