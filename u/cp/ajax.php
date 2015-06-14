<?php
#0: invalid input, 1: already most recent, 2: failed to copy, 3: updated
require_once("/var/www/abian/_secret_keys.php");
require_once("/var/www/abian/libs/usersystem/config.php");
require_once("/var/www/abian/libs/Abian.php");
$Abian = new Abian;

if (!isset($_POST["i"]) || !isset($_POST["e"])) {
  echo json_encode(["success"=>"0"]);
  exit;
}

$i = $UserSystem->sanitize($_POST["i"], "n");
$e = $UserSystem->sanitize($_POST["e"]);

$link = "https://www.gravatar.com/avatar/".md5(strtolower(trim($e)));
$hash = hash_file("sha512", $link."?s=32");
$file32 = "/var/www/abian/cache/$i-32x.jpg";
$file = "/var/www/abian/cache/$i.jpg";

if (is_file($file32) && $hash === hash_file("sha512", $file32)) {
  echo json_encode(["success"=>"1"]);
  exit;
}

if (!@copy($link."?s=512", $file)) {
  echo json_encode(["success"=>"2"]);
} else {
  copy($link."?s=32", $file32);
  $hist = $Abian->historify("avatar.updated", "Using: $e");
  echo json_encode(["success"=>"3"]);
}