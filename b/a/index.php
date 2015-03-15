<?php
require_once("/var/www/abian/header.php");
if ($session === false) $UserSystem->redirect301("/u/login");
$recaptcha = recaptcha_get_html($re["site"], null, true);

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
    if ($Abian->endsWith($_POST["f"], ".zip")) {
      $slug = strtolower(
        preg_replace(
          "/\PL/u",
          "",
          preg_replace(
            "/\:[^)]+\:/",
            "",
            $_POST["n"]
          )
        )
      );
      $dir = "/var/www/abian/dl/";
      $file = $dir . basename($UserSystem->sanitize($slug) . ".zip");
      $search = $UserSystem->dbSel(["bots", ["slug" => $slug]])[0];
      if (!file_exists("/var/www/abian/dl/" . $slug . ".zip")
        && $search === 0) {
        $size = $UserSystem->sanitize(
          array_change_key_case(
            get_headers(
              $_POST["f"],
              TRUE
            )
          )['content-length'],
          "n"
        );
        if ($size < 5500000) {
          file_put_contents(
            "/var/www/abian/dl/" . $slug . ".zip",
            file_get_contents($_POST["f"])
          );
          $m = $_POST["m"] == 1 ? 1 : 0;
          $UserSystem->dbIns(
            [
              "bots",
              [
                "body" => $UserSystem->sanitize($_POST["b"], "h"),
                "slug" => $slug,
                "name" => $UserSystem->sanitize($_POST["n"]),
                "description" => $UserSystem->sanitize($_POST["d"]),
                "dateCreate" => time(),
                "member" => $m,
                "user" => $session["id"]
              ]
            ]
          );
          $Abian->historify("bot.create", $UserSystem->sanitize($_POST["n"]));
          $UserSystem->redirect301("/b?created");
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

if ($error != "") {
  $post = [];
  foreach ($_POST as $key => $value) {
    $post[$key] = $UserSystem->sanitize($value);
  }
} else {
  $post = [
    "n" => "",
    "d" => "",
    "b" => "",
    "f" => "",
    "t" => ""
  ];
}

$b = $post["b"];
$d = $post["d"];

echo <<<EOT
<div class="col-xs-12">
  $error
  <div class="well well-md">
    <h1>Upload your bot</h1>
    <form class="form form-vertical" method="post" action="">
      <div class="form-group">
        <label for="n">Bot name</label>
        <input type="text" class="form-control" id="n" name="n"
          value="$post[n]">
      </div>
      <div class="form-group">
        <label for="d">Description</label>
        <textarea name="d" id="d" class="form-control" rows="5">$d</textarea>
        <span id="helpBlock" class="help-block">
          This will appear with your bot in search results.
        </span>
      </div>
      <div class="form-group">
        <label for="b">Page</label>
        <textarea name="b" id="b" class="form-control" rows="15">$b</textarea>
        <span id="helpBlock" class="help-block">
          Uses
          <a href="http://s.zbee.me/bsz" target="_blank">
            Github Flavored Markdown</a>
          and
          <a href="https://s.zbee.me/nje" target="_blank">
            emoji</a>.
        </span>
      </div>
      <div class="form-group">
        <label for="f">URL to File</label>
        <input type="text" class="form-control" id="f" name="f"
          value="$post[f]">
        <span id="helpBlock" class="help-block">
          Must be a .zip file.
        </span>
      </div>
      <div class="form-group">
        <label for="t">Tags</label>
        <input type="text" class="form-control" id="t" name="t"
          value="$post[t]">
        <span id="helpBlock" class="help-block">
          Separate with commas, spaces are removed
        </span>
      </div>
      <fieldset> <!--http://s.zbee.me/fzb-->
        <div class="form-group">
          <label class="control-label">Membership required</label>
          <br>
          <div class="radio">
            <label>
              <input name="m" value="0" type="radio" checked>
              Anyone can use
            </label>
          </div>
          <div class="radio">
            <label>
              <input name="m" value="1" type="radio">
              Members only
            </label>
          </div>
        </div>
      </fieldset>
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
