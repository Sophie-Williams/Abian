<?php
require_once("/var/www/abian/header.php");
if ($session === false) $UserSystem->redirect301("/u/login");
$recaptcha = recaptcha_get_html($re["site"]);

echo <<<EOT
<div class="col-xs-12">
  <div class="well well-md">
    <h1>Upload your bots</h1>
    <form class="form form-vertical" method="post" action="">
      <div class="form-group">
        <label for="u">Username</label>
        <input type="text" class="form-control" id="u" name="u">
      </div>
      <div class="form-group">
        <label for="e">Email</label>
        <input type="email" class="form-control" id="e" name="e">
      </div>
      <div class="form-group">
        <label for="p">Password</label>
        <input type="password" class="form-control" id="p" name="p">
      </div>
      <div class="form-group">
        <label for="cp">Confirm Password</label>
        <input type="password" class="form-control" id="cp" name="cp">
      </div>
      <div class="form-group">
        <label for="b">Bot file</label>
        <input id="bf" type="file" style="display:none">
        <div class="input-group">
          <input id="b" class="form-control input-sm" type="text">
          <span class="input-group-btn">
            <a class="btn btn-sm btn-default" onclick="$('#bf').click();">Browse</a>
          </span>
        </div>
        <script type="text/javascript">
         $('input[type=file]').change(function(e) {
          console.log("piiiiie");
          $('#b').val($(this).val());
        });
        </script>
      </div>
      <div class="form-group">
        $recaptcha
      </div>
      <button type="submit" class="btn btn-primary btn-block">Register</button>
    </form>
  </div>
</div>
EOT;

require_once("/var/www/abian/footer.php");
?>