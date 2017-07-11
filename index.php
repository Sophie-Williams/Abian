<?php
require_once("/var/www/Abian/header.php");
if (isset($_GET["loggedin"])) {
  $info = "In " . $Abian->codeToCountry($_SERVER["HTTP_CF_IPCOUNTRY"]) . " using " . $Abian->getBrowser() . " on " . $Abian->getOS();
  $hist = $Abian->historify("user.login", $info);
  echo "<div class='alert alert-success'>You have been logged in.</div>";
}

echo $Abian->getBots(["id" => ["!=", "a"], "sort" => ["dateUpdate", "desc"]]);

require_once("/var/www/Abian/footer.php");
?>
