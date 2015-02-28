<?php
require_once("/var/www/abian/header.php");
if (!is_array($session)) $UserSystem->redirect301("/u/login");
#if ($session["title"] !== "Moderator") $UserSystem->redirect301("/");

require_once("/var/www/abian/footer.php");
?>