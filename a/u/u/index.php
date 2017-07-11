<?php
require_once("/var/www/Abian/header.php");
if (!is_array($session)) $UserSystem->redirect301("/u/login");
#if ($session["title"] !== "Moderator") $UserSystem->redirect301("/");
$users = $UserSystem->dbSel(["users", ["username" => ["!=", ""]]]);
$numUsers = $users[0];
unset($users[0]);

$page = isset($_GET["pg"]) ? $_GET["pg"] : 1;

$users = array_slice($users, $page - 1, $page + 24);

$pages = ceil($numUsers / 25) - $page;
$new = $pages >= $page + 1 ? "" : "disabled";

$activated = $active = 0;
if ($numUsers > 0) foreach ($users as $user) {
  if ($user["activated"] == 1) $activated += 1;
  if ($user["lastActive"] > time() - 86400*14) $active += 1;
} 
?>

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-user"></i> Users: Browse</h3>
  </div>
  <div class="panel-body">
    Here you can browse through all Abian users, or search for one.
    <br>
    There are currently <?=$numUsers?> users, of which <?=$activated?> are
    activated, and <?=$active?> are active.
  </div>
  <table class="table">
    <?php
    if ($numUsers > 0) foreach ($users as $user)
      echo "<tr>"
      . "<td><img src='" . $Abian->getAvatar($user["id"], true) . "' height='20'> "
      . $user["username"] . "</td>"
      . "<td>Last seen <time class='timeago' datetime='"
      . date("Y-m-d\TH:i", $user["lastActive"])
      . (($tz = date('Z') / 3600) > 0 ? "+" : "-")
      . str_pad(abs($tz), 2, "0", STR_PAD_LEFT)
      . "00'>" . date("Y-m-d\TH:i", $user["lastActive"]) . "</time></td>"
      . "<td><a href='/u/?" . $user["username"] . "' class='btn btn-xs btn-default'>Profile</a> "
      . "<a href='#' class='btn btn-xs btn-primary'>View</a></td>"
      . "</tr>";
    ?>
  </table>
  <div class="panel-footer">
    <nav>
      <ul class="pager">
        <li class="previous disabled">
          <a href="#">
            <span aria-hidden="true">&larr;</span>
            Older
          </a>
        </li>
        <li class="next <?=$new?>">
          <a href="#">
            Newer (<?=$pages?> other pages)
            <span aria-hidden="true">&rarr;</span>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</div>

<?php require_once("/var/www/Abian/footer.php"); ?>