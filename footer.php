    <?php
    if ($sidebar) {
      echo '
        </div>
        <div class="col-xs-12 col-sm-2 col-sm-pull-10">
      ';

      echo '
        <h1 class="text-left" style="margin-bottom:10px;">
          <span class="maintext"><i class="fa fa-newspaper-o"></i> News</span>
        </h1>
        <i class="fa fa-angle-right"></i> <a href="/n?2015-03-01">
          Bests bots of February
        </a>
        <br>
        <i class="fa fa-angle-right"></i> <a href="/n?2015-02-01">
          Bests bots of January
        </a>
        <br>
        <i class="fa fa-angle-right"></i> <a href="/n?2015-01-01=2">
          Bests bots of 2014
        </a>
        <br>
        <i class="fa fa-angle-right"></i> <a href="/n?2015-03-01">
          Bests bots of December
        </a>
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
        <div class="text-center" id="adMain">
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
              <li><a href="/q">About the Bot Network</a></li>
              <li><a href="#">Developer Blog</a></li>
              <li><a href="/q/q">FAQ</a></li>
              <li><a href="#">Affiliates</a></li>
              <li><a href="#">Business</a></li>
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
            <ul>
              <li class="lin" style="font-weight:normal">
                <b><i class="fa fa-code"></i> Code</b>
                <br>
                <?php
                $c = $Abian->getCommit();
                $sc = substr($c, 0, 10);
                echo '
                <a href="https://GitHub.com/Zbee/Abian/commit/' . $c . '"
                target="_blank" title="Commit '.$c.'">
                  Abian</a>
                ';
                ?>
                was created by <a href="https://github.com/zbee">Zbee</a>.
                <br>
                <a href="http://opensource.org/" target="_blank">
                  <img src="/libs/img/OSI.png" style="width:25%"></a>
                <a href="http://github.com/zbee/abian" target="_blank">
                  <img src="/libs/img/GitHub.png" style="width:25%"></a>
                <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">
                  <img src="/libs/img/GPL.png" style="width:25%"></a>
              </li>
            </ul>
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
          } else {
            echo '
              <div class="col-xs-12 col-sm-6">
                Server time is ' . date("Y-m-d\TH:i", time()) . '
              </div>
            ';
          }
          $timee = microtime(true);
          $time = number_format(($timee - $times) / 60, 4);
          echo '
            <div class="col-xs-12 col-sm-6">
              This page loaded in <b>'.$time.'</b> seconds with
              <b>'.$UserSystem->QUERIES.'</b> queries.
            </div>
          ';
          ?>
        </div>
      </div>
      <!--Ad-->
      <div class="well well-sm" id="adText">
        <div class="row">
          <div class="col-xs-12 col-sm-1">
            <b><i class="fa fa-money"></i> Ad</b>
          </div>
          <div class="col-xs-12 col-sm-8">
            <?php
            $ad = $rate = '';
            $weights = [0, 1, 1, 2, 2, 2, 3, 3, 3, 3];
            shuffle($weights);
            $weight = $weights[rand(0,9)];
            $stmt = $UserSystem->dbSel(
              [
                "ads",
                [
                  "flavor" => "text",
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
                  '.$stmt[0]["content"].'
                </a>
              ';
              $UserSystem->dbUpd(
                [
                  "ads",
                  ["shown" => $stmt[0]["shown"]+1],
                  ["id" => $stmt[0]["id"]]
                ]
              );
              echo $ad;
              $rate = '
                Good ad?
                <div class="btn-group btn-group-xs" role="group"
                  aria-label="Up or down vote this ad">
                  <button class="btn btn-default">
                    <i class="fa fa-arrow-up"></i>
                  </button>
                  <button class="btn btn-default">
                    <i class="fa fa-arrow-down"></i>
                  </button>
                </div>
              ';
            } else {
              echo "Can't find ad:<br><pre>";
              var_dump(["weight" => $weight, "query" => $stmt]);
              echo "</pre>";
            }
            ?>
          </div>
          <div class="col-xs-12 col-sm-3 text-right">
            <?=$rate?>
          </div>
        </div>
      </div>
      <!--/Ad-->
    </div>
  </footer>

  <script src="/libs/js/bootstrap.js"></script>
  <script>
  $(function () {
    $('[data-toggle="popover"]').popover();
    emojify.setConfig({ignore_emoticons: true});
    emojify.run();
  });
  </script>
</body>
