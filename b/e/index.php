<?php
require_once("/var/www/abian/header.php");
$bot = null;
if (isset($_GET) && count($_GET) > 0) {
  $bot = array_search(array_values($_GET)[0], $_GET);
}
if ($bot == "saved") $bot = null;
if ($session === false && $bot === null) $UserSystem->redirect301("/u/login");
if (is_array($session) && $bot !== null) {
  $bot = $UserSystem->dbSel(["bots", ["slug" => $bot]]);
  if ($bot[0] == 1) {
    $bot = $bot[1];
    $b = $bot["body"];
    $error = "";
    if (isset($_POST["n"])) {
      #$type = $_FILES["f"]["type"];
      $type = "application/zip";
      if (
        $Abian->endsWith($_POST["fn"], ".zip") && 
        ($type == "application/zip" || $type == "application/x-zip-compressed")
      ) {
        $slug = strtolower(preg_replace('/\PL/u', '', $_POST["n"]));
        if (file_exists("/var/www/abian/dl/" . $slug . ".zip")) {
          $search = $UserSystem->dbSel(
            [
              "bots",
              [
                "slug" => $slug,
                "id" => ["!=", $bot["id"]]
              ]
            ]
          )[0];
          if ($search === 0) {
            $UserSystem->dbUpd(
              [
                "bots",
                [
                  "body" => $UserSystem->sanitize($_POST["b"], "h"),
                  "slug" => $slug,
                  "name" => $UserSystem->sanitize($_POST["n"]),
                  "description" => $UserSystem->sanitize($_POST["d"]),
                  "dateUpdate" => time()
                ],
                [
                  "id" => $bot["id"]
                ]
              ]
            );
            $Abian->historify("bot.edit", $UserSystem->sanitize($_POST["n"]));
            $UserSystem->redirect301("/b?updated");
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
              File changed.
            </div>
          ';
        }
      } else {
        $error = '
          <div class="alert alert-danger">
            File selected is not .zip file (application/zip or 
            application/x-zip-compressed).
          </div>
        ';
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
              <input type="text" class="form-control" id="n" name="n" 
                value="$bot[name]">
            </div>
            <div class="form-group">
              <label for="d">Description</label>
              <input type="text" class="form-control" id="d" name="d" 
                value="$bot[description]">
              <span id="helpBlock" class="help-block">
                This will appear with your bot in search results.
              </span>
            </div>
            <div class="form-group">
              <label for="b">Page</label>
              <textarea name="b" class="form-control" rows="15">$b</textarea>
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
                <input id="f" class="form-control input-sm" type="text" 
                  name="fn" value="$bot[slug].zip">
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
                Separate with commas, spaces are removed.
              </span>
            </div>
            <button type="submit" class="btn btn-primary btn-block">
              Submit Bot for Reapproval
            </button>
        </form>
      </div>
    </div>
EOT;
  }
}

require_once("/var/www/abian/footer.php");
?>
