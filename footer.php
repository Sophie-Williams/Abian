    <?php
    if ($sidebar) {
      echo '
      </div>
      <div class="col-xs-12 col-sm-2 col-sm-pull-10">
      ';

      $ad = '';
      $weights = [0, 1, 1, 2, 2, 2, 3, 3, 3, 3];
      shuffle($weights);
      $weight = $weights[rand(0,9)];
      $stmt = $UserSystem->dbSel(
        [
          "ads",
          [
            "flavor" => "image",
            "weight" => $weight,
            "expiration" => [">", time()],
            "approved" => 1
          ]
        ]
      );
      if ($stmt[0] > 0) {
        unset($stmt[0]);
        shuffle($stmt);
        $ad = '
          <a href="'.$stmt[0]['link'].'" target="_blank">
            <img src="'.$stmt[0]['content'].'"
              style="max-width:99%;max-height:100%;" />
          </a>
        ';
        $UserSystem->dbUpd(
          [
            "ads",
            ["shown" => $stmt[0]["shown"]+1],
            ["id" => $stmt[0]["id"]]
          ]
        );
        $ad .= '<br><br>Good ad?
          <div class="btn-group btn-group-xs" role="group"
            aria-label="Up or down vote this ad">
            <button class="btn btn-default">
              <i class="fa fa-arrow-up"></i>
            </button>
            <button class="btn btn-default">
              <i class="fa fa-arrow-down"></i>
            </button>
          </div>
          <br>Pst! <a href="/a/premium">Premium users</a> don\'t see these!';
        echo '
        <!--Ad-->
        <div class="sidewidt text-center">
          <h1 class="text-left" style="margin-bottom:10px;">
            <span class="maintext"><i class="fa fa-money"></i> Ad</span>
          </h1>
          '.$ad.'
        </div>
        <!--/Ad-->';
      } else {
        echo "Can't find ad:<br><pre>";
        var_dump(["weight" => $weight, "query" => $stmt]);
        echo "</pre>";
      }

      echo '
      </div>
      ';
    }
    ?>

    </div><!-- /.row -->

  </div><!-- /.container -->

  <br>

  <footer class="footer">
    <div class="container text-muted">
      <div class="well well-sm">
        <div class="row">
          <div class="col-xs-6 col-sm-3">
            <ul>
              <li class="lin"><i class="fa fa-info-circle"></i> About</li>
              <li><a href="#">About the Bot Network</a> </li>
              <li><a href="#">Developer Blog</a> </li>
              <li><a href="#">FAQ</a> </li>
              <li><a href="#">Affiliates</a> </li>
              <li><a href="#">Ads</a> </li>
            </ul>
          </div>
          <div class="col-xs-6 col-sm-3">
            <ul>
              <li class="lin"><i class="fa fa-envelope"></i> Contact</li>
              <li><a href="mailto:support@abianbot.net">Support</a></li>
              <li><a href="#">Support Forum</a></li>
              <li><a href="mailto:suggestions@abianbot.net">Suggestions</a></li>
              <li><a href="#">Suggestions Forum</a></li>
              <li><a href="mailto:legal@abianbot.net">Legal</a></li>
            </ul>
          </div>
          <div class="col-xs-6 col-sm-3">
            <b><i class="fa fa-code"></i> Code</b>
            <br>
            Abian was created by <a href="https://github.com/zbee">Zbee</a>.
            <br>
            <a href="http://opensource.org/" target="_blank">
              <img src="/libs/img/OSI.png" style="width:30%">
            </a>
            <a href="http://github.com/zbee/abian" target="_blank">
              <img src="/libs/img/GitHub.png" style="width:30%">
            </a>
            <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">
              <img src="/libs/img/GPL.png" style="width:30%">
            </a>
          </div>
          <div class="col-xs-6 col-sm-3">
            <ul>
              <li class="lin"><i class="fa fa-gavel"></i> Legal</li>
              <li><a href="#"><b>Basic Legal Run-down</b></a></li>
              <li><a href="#">Privacy Policy</a></li>
              <li><a href="#">Terms &amp; Conditions</a></li>
              <li><a href="#">DMCA/C&amp;D Info</a></li>
              <li><a href="#">Transparency Info</a></li>
            </ul>
          </div>
        </div>
        <div class="row">
          <?php
          if (is_array($session)) {
            if ($session["timeZone"] == "America/Denver") {
              echo '
                <div class="col-xs-12 col-sm-6">
                  Server - and your - time is ' . date("Y-m-d\TH:i", time()) . '
                </div>
              ';
            } else {
              date_default_timezone_set("America/Denver");
              $server = date("Y-m-d h:i:s A");
              date_default_timezone_set($session["timeZone"]);
              $user = date("Y-m-d h:i:s A");
              $ahead = abs((strtotime($server) - strtotime($user)) / 3600);
              $ahead = $ahead>0 ?$ahead." hours ahead" : $ahead." hours behind";

              echo '
                <div class="col-xs-12 col-sm-6">
                  Server time is ' . date("Y-m-d\TH:i", time())
                   . ', which is ' . $ahead . ' you.
                </div>
              ';
            }
          }
          $l1 = file_get_contents("/var/www/abian/footer.php");
          $r1 = file_get_contents(
            "https://raw.githubusercontent.com/Zbee/Abian/master/footer.php"
          );
          $l2 = file_get_contents("/var/www/abian/header.php");
          $r2 = file_get_contents(
            "https://raw.githubusercontent.com/Zbee/Abian/master/header.php"
          );
          $l3 = file_get_contents("/var/www/abian//libs/Abian.php");
          $r3 = file_get_contents(
            "https://raw.githubusercontent.com/Zbee/Abian/master/libs/Abian.php"
          );
          $diff = "(modified)";
          if ($l1 === $r1 && $l2 === $r2 && $l3 === $r3) $diff = "";
          $c = $Abian->getCommit();
          $sc = substr($c, 0, 10);
          echo '
            <div class="col-xs-12 col-sm-6">
              Running
              <a href="https://GitHub.com/Zbee/Abian/commit/' . $c . '"
              target="_blank">
                Abian/' . $sc . '
              </a> '.$diff.'
            </div>
          ';
          ?>
        </div>
      </div>
    </div>
  </footer>

  <script src="/libs/js/bootstrap.js"></script>
  <script>
  $(function () {
    $('[data-toggle="popover"]').popover();
  });
  </script>
</body>
