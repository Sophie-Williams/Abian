<?php
require_once("/var/www/Abian/_secret_keys.php");
require_once("/var/www/Abian/libs/usersystem/config.php");
$response = "";

if (isset($_POST["up"]) || isset($_POST["down"])) {
  if (isset($_POST["up"])) {
    $type = 1;
    $from = $_POST["up"];
  } else {
    $type = 0;
    $from = $_POST["down"];
  }

  $id = $UserSystem->sanitize($from, "n");
  $user = $UserSystem->sanitize($_POST["user"], "n");
  $target = $UserSystem->sanitize($_POST["target"], "n");
  $votes = $UserSystem->dbSel(["votes", ["on" => "bot.".$id, "user" => $user]]);
  
  if ($votes[0] == 0) {
    $UserSystem->dbIns(
      [
        "votes",
        [
          "user" => $user,
          "on" => "bot." . $id,
          "type" => $type,
          "target" => $target
        ]
      ]
    );
    $response = "add";
  } else {
    $UserSystem->dbDel(
      [
        "votes",
        [
          "user" => $user,
          "on" => "bot." . $id
        ]
      ]
    );
    if ($votes[1]["type"] != $type) {
      $UserSystem->dbIns(
        [
          "votes",
          [
            "user" => $user,
            "on" => "bot." . $id,
            "type" => $type,
            "target" => $target
          ]
        ]
      );
      $response = "add";
    } else {
      $response = "remove";
    }
  }
}

echo json_encode($response);
?>