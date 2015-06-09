<?php
require_once("/var/www/abian/header.php");
if (!is_array($session)) $UserSystem->redirect301("/u/login");
#if ($session["title"] !== "Moderator") $UserSystem->redirect301("/");
?>

<div class="row">
  <div class="col-xs-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">
        <i class="fa fa-user"></i> Users: Verify AQ Accounts
        </h3>
      </div>
      <div class="panel-body">
        Until the cron job system is online, AQ accounts must be verified 
        "manually."
        <br><br>
        You'll have to go through and click the verify button for each of these
        users.
        <br><br>
        If the verification fails, then they have an invalid username or
        didn't bother to do the verification action.
      </div>
      <table class="table">
        <?php
          $users = $UserSystem->dbSel(
            [
              "users",
              [
                "aqName" => ["!=", ""],
                "aqVerified" => "0"
                #,"id" => ["!=", $session["id"]]
              ]
            ]
          );
          if ($users[0] > 0) {
            unset($users[0]);
            foreach ($users as $user)
              echo "<tr><td><a href='/u/?$user[username]'>$user[username]</a>"
                . "</td><td>wants to verify</td><td>"
                . "<a href='http://www.aq.com/character.asp?id=$user[aqName]'>"
                . "$user[aqName]</a></td><td><a user='$user[id]' "
                . "class='btn btn-xs btn-primary'>Verify</a></td></tr>";
          }
        ?>
      </table>
    </div>
    <script type="text/javascript">
    $("a[user]").click(function() {
      var btn = $(this);
      $.ajax({
        type: "POST",
        url: "verify.php",
        data: {u: $(this).attr("user")},
        dataType: "json",
        context: document.body,
        async: true,
        complete: function(res, stato) {
          console.log(res);
          if (res.responseJSON.s == "4") {
            btn.html('Verified <i class="fa fa-check"></i>')
              .removeClass("btn-priamry").addClass("btn-success");
          } else {
            btn.html('Failed <i class="fa fa-times"></i>')
              .removeClass("btn-priamry").addClass("btn-danger");
          }
        }
      });
    });
    </script>
  </div>
</div>

<?php
require_once("/var/www/abian/footer.php");
?>