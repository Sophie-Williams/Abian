<?php
require_once("/var/www/abian/header.php");
if ($session === false) $UserSystem->redirect301("/u/login");
$recaptcha = recaptcha_get_html($re["site"]);

$error = "";
if (isset($_POST["n"])) {
  $ipAddress = filter_var(
    $_SERVER["REMOTE_ADDR"],
    FILTER_SANITIZE_FULL_SPECIAL_CHARS
  );
  $resp = recaptcha_check_answer(
    $re["secret"],
    $ipAddress,
    $_POST["recaptcha_challenge_field"],
    $_POST["recaptcha_response_field"]
  );
  if (!$resp->is_valid) {
    $error = '
      <div class="alert alert-danger">
        ReCAPTCHA was incorrect.
      </div>
    ';
  } else {
    $type = $_FILES["f"]["type"];
    if (
      $Abian->endsWith($_POST["fn"], ".zip") &&
      ($type == "application/zip" || $type == "application/x-zip-compressed")
    ) {
      $slug = preg_replace('/\PL/u', '', $_POST["n"]);
      $dir = "/var/www/abian/dl/";
      $file = $dir . basename($UserSystem->sanitize($slug) . ".zip");
      $search = $UserSystem->dbSel(["bots", ["slug" => $slug]])[0];
      if (!file_exists("/var/www/abian/dl/" . $slug . ".zip") || $search != 0) {
        if ($_FILES["f"]["size"] < 5000000) {
          if (move_uploaded_file($_FILES["f"]["tmp_name"], $file)) {
            $UserSystem->dbIns(
              [
                "bots",
                [
                  "body" => $UserSystem->sanitize($_POST["b"], "h"),
                  "slug" => $slug,
                  "name" => $UserSystem->sanitize($_POST["n"]),
                  "description" => $UserSystem->sanitize($_POST["d"]),
                  "dateCreate" => time(),
                  "user" => $session["id"]
                ]
              ]
            );
            $Abian->historify("bot.create", $UserSystem->sanitize($_POST["n"]));
            $UserSystem->redirect301("/b?created");
          } else {
            $error = '
              <div class="alert alert-danger">
                Unknown error whilst uploading file.
              </div>
            ';
          }
        } else {
          $error = '
            <div class="alert alert-danger">
              File is bigger than 5mb.
            </div>
          ';
        }
      } else {
        $error = '
          <div class="alert alert-danger">
            Name is already in use.
          </div>
        ';
      }
    } else {
      $error = '
        <div class="alert alert-danger">
          File selected is not .zip file
          (application/zip or application/x-zip-compressed).
        </div>
      ';
    }
  }
}

echo <<<EOT
<div class="col-xs-12">
  $error
  <div class="well well-md">
    <h1>Upload your bot</h1>
    <form class="form form-vertical" method="post" action="">
      <div class="form-group">
        <label for="n">Bot name</label>
        <input type="text" class="form-control" id="n" name="n">
      </div>
      <div class="form-group">
        <label for="d">Description</label>
        <input type="text" class="form-control" id="d" name="d">
        <span id="helpBlock" class="help-block">
          This will appear with your bot in search results.
        </span>
      </div>
      <div class="form-group">
        <label for="b">Page</label>
        <textarea name="b" class="form-control" rows="15"></textarea>
        <span id="helpBlock" class="help-block">
          Uses
          <a href="https://s.zbee.me/bsz" target="_blank">
            Github Flavored Markdown
          </a>.
        </span>
      </div>
      <div class="form-group">
        <label for="bf">File</label>
        <input id="bf" type="file" style="display:none" name="f">
        <div class="input-group">
          <input id="f" class="form-control input-sm" type="text" name="fn">
          <span class="input-group-btn">
            <a class="btn btn-sm btn-default" id="ff">Browse</a>
          </span>
        </div>
        <span id="helpBlock" class="help-block">
          Must be a .zip file (application/zip or 
          application/x-zip-compressed).
        </span>
        <script>
        $("#ff").click(function () {
          $("#bf").click();
          $("input[type='file']").change(function(e) {
            fname = $(this).val().split("fakepath").pop();
            fname = fname.substring(1,fname.length);
            $('#f').val(fname);
          });
        });
        </script>
      </div>
      <div class="form-group">
        <label for="t">Tags</label>
        <input type="text" class="form-control" id="t" name="t">
        <span id="helpBlock" class="help-block">
          Separate with commas, spaces are removed
        </span>
      </div>
      <div class="form-group">
        $recaptcha
      </div>
      <button type="submit" class="btn btn-primary btn-block">
        Submit Bot for Approval
      </button>
    </form>
  </div>
</div>
EOT;

require_once("/var/www/abian/footer.php");
?>
