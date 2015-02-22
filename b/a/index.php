<?php
require_once("/var/www/abian/header.php");
if ($session === false) $UserSystem->redirect301("/u/login");
$recaptcha = recaptcha_get_html($re["site"]);

if (isset($_POST["n"])) {
  $resp = recaptcha_check_answer ($re["secret"], $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
  if (!$resp->is_valid) {
    echo "The reCAPTCHA was incorrect: ".$resp->error.".";
  } else {
    
  }
}

echo <<<EOT
<div class="col-xs-12">
  <div class="well well-md">
    <h1>Upload your bot</h1>
    <form class="form form-vertical" method="post" action="">
      <div class="form-group">
        <label for="n">Bot name</label>
        <input type="text" class="form-control" id="n" name="n">
      </div>
      <div class="form-group">
        <label for="d">Description</label>
        <input type="text" class="form-control" id="d" name="nd>
        <span id="helpBlock" class="help-block">This will appear with your bot in search results.</span>
      </div>
      <div class="form-group">
        <label for="b">Page</label>
        <textarea name="b" class="form-control" rows="15"></textarea>
        <span id="helpBlock" class="help-block">Uses <a href="https://help.github.com/articles/github-flavored-markdown/" target="_blank">Github Flavored Markdown</a>.</span>
      </div>
      <div class="form-group">
        <label for="f">File</label>
        <input id="bf" type="file" style="display:none">
        <div class="input-group">
          <input id="f" class="form-control input-sm" type="text">
          <span class="input-group-btn">
            <a class="btn btn-sm btn-default" id="ff">Browse</a>
          </span>
        </div>
        <span id="helpBlock" class="help-block">Must be a .zip file. Name of file will be changed.</span>
        <script>
        $("#ff").click(function () {
          $("#bf").click();
          $("input[type='file']").change(function(e) {
            fname = $(this).val().split("fakepath").pop();
            fname = fname.substring(1,fname.length);
            $('#b').val(fname);
          });
        });
        </script>
      </div>
      <div class="form-group">
        <label for="t">Tags</label>
        <input type="text" class="form-control" id="t" name="t">
        <span id="helpBlock" class="help-block">Separate with commas, spaces are removed</span>
      </div>
      <div class="form-group">
        $recaptcha
      </div>
      <button type="submit" class="btn btn-primary btn-block">Submit Bot for Approval</button>
    </form>
  </div>
</div>
EOT;

require_once("/var/www/abian/footer.php");
?>
