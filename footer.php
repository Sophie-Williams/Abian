    <?php
    if ($sidebar) {
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
              <li class="lin"><b><i class="fa fa-info-circle"></i> About</b></li>
              <li><a href="#">About the Bot Network</a> </li>
              <li><a href="#">Developer Blog</a> </li>
              <li><a href="//beta.zbee.me/abian/about/faq">FAQ</a> </li>
              <li><a href="#">Affiliates</a> </li>
              <li><a href="#">Ads</a> </li>
            </ul>
          </div>
          <div class="col-xs-6 col-sm-3">
            <ul>
              <li class="lin"><b><i class="fa fa-envelope"></i> Contact</b></li>
              <li><a href="mailto:support@abianbot.net">Support</a></li>
              <li><a href="mailto:suggestions@abianbot.net">Suggestions</a></li>
              <li><a href="mailto:legal@abianbot.net">Legal</a></li>
            </ul>
          </div>
          <div class="col-xs-6 col-sm-3">
            Abian was created by <a href="https://github.com/zbee">Zbee</a>.
            <Br>
            <a href="http://opensource.org/"><img src="/libs/img/OSI.png" style="width:30%"></a>
            <a href="http://github.com/zbee/abian"><img src="/libs/img/GitHub.png" style="width:30%"></a>
            <a href="http://www.gnu.org/copyleft/gpl.html"><img src="/libs/img/GPL.png" style="width:30%"></a>
          </div>
          <div class="col-xs-6 col-sm-3">
            <ul>
              <li class="lin"><b><i class="fa fa-gavel"></i> Legal</b></li>
              <li><a href="#"><b>Basic Legal Run-down</b></a></li>
              <li><a href="#">Privacy Policy</a></li>
              <li><a href="#">Terms & Conditions</a></li>
              <li><a href="#">DMCA/C&amp;D Info</a></li>
              <li><a href="#">Transparency Info</a></li>
            </ul>
          </div>
        </div>
        Your time is <?=date("Y-m-d\TH:i", time())?> (<?=is_array($session) ? $session["timeZone"] : "America/Denver"?>) <sup><a href="/u/cp#settings">(not right?)</a></sup>, 
        Server time is <?php date_default_timezone_set("America/Denver");echo date("Y-m-d\TH:i", time());?> (America/Denver)
      </div>
    </div>
  </footer>

  <script src="/libs/js/jquery.js"></script>
  <script src="/libs/js/bootstrap.js"></script>
  <script>
  $(function () {
    $('[data-toggle="popover"]').popover();
  });
  </script>
</body>
