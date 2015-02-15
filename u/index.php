<?php
require_once("/var/www/abian/header.php");
$user = isset($_GET) && count($_GET) > 0 ? array_search(array_values($_GET)[0], $_GET) : null;
$session = $UserSystem->verifySession();
if ($session === true) $UserSystem->redirect301("/u/cp");
if ($user === null) $UserSystem->redirect301("/");
if ($session ===true) {
  $session = $UserSystem->session();
}

echo $user;

require_once("/var/www/abian/footer.php");
?>