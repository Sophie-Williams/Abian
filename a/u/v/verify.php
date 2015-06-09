<?php
require_once("/var/www/abian/_secret_keys.php");
require_once("/var/www/abian/libs/usersystem/config.php");

if (!isset($_POST["u"])) { echo json_encode(["success"=>"0","u"=>$o]); exit; }
$o = $u = $UserSystem->sanitize($_POST["u"], "n");

$session = $UserSystem->verifySession();
if ($session === true) $session = $UserSystem->session();
if (!is_array($session)) { echo json_encode(["success"=>"1","u"=>$o]); exit; }
if ($u == $session["id"]) { echo json_encode(["success"=>"2","u"=>$o]); exit; }

$u = $UserSystem->dbSel(["users", ["id" => $u]]);
if ($u[0] !== 1) { echo json_encode(["success"=>"3","u"=>$o]); exit; }
$u = $u[1];

$aqName = urlencode($u["aqName"]);
$trim = file_get_contents("http://www.aq.com/character.asp?id=" . $aqName);
$trim = explode("&intColorTrim=", $trim);
if (!isset($trim[1])) { echo json_encode(["success"=>"4","u"=>$o]); exit; }
$trim = explode('&', $trim[1])[0];
$trim = dechex($trim);

$dec = dechex(substr($u["id"], 0, 6));

if ($trim != $dec) { echo json_encode(["s"=>"5","u"=>$u["id"]]); exit; }

$updated = $UserSystem->dbUpd(
  [
    "users",
    [
      "aqVerified" => "1"
    ],
    [
      "id" => $u["id"]
    ]
  ]
);

if (!$updated) { echo json_encode(["s"=>"6","u"=>$u["id"]]); exit; }

echo json_encode(["s"=>"7","u"=>$u["id"]]);
