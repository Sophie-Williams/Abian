    </div><!-- /.row -->

  </div><!-- /.container -->

  <footer class="footer">
    <div class="container">
      <p class="pull-right" id="btt"><a href="#">Back to top</a></p>
      <p class="text-muted">
        Abian created by <a href="https://github.com/Zbee">Zbee</a> (with  a lot
        of help from Twitch viewers)
      </p>
      <p class="text-muted">Server time is <?=date("Y-m-d\TH:i", time())?> (America/Denver - MST)</p>
    </div>
  </footer>

  <script src="/libs/js/jquery.js"></script>
  <script src="/libs/js/bootstrap.js"></script>
  <script>
  if ($(window).height() > $(".container").height()) {
    //$("#btt").remove();
  }
  $(function () {
    $('[data-toggle="popover"]').popover();
  });
  </script>
</body>
