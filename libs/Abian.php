<?php
/**
* Class for performing operations specific to Abian
*
* @package    Abian
* @author     Ethan Henderson <ethan@zbee.me>
* @copyright  2015 Ethan Henderson
* @license    http://aol.nexua.org AOL v0.6
* @link       https://github.com/zbee/abian
* @since      Class available since Release 0.10
*/
class Abian extends UserSystem {

  /**
  * Adds an item to history
  * Example: $Abian->historify("user.login", "user.19")
  *
  * @access public
  * @param string $action
  * @param string $description
  * @param string $target
  * @return boolean
  */
  public function historify ($action, $description, $target = null) {
    $session = $this->session();
    $actor = $session["id"];
    $ipAddress = filter_var(
      $_SERVER["REMOTE_ADDR"],
      FILTER_SANITIZE_FULL_SPECIAL_CHARS
    );
    if (ENCRYPTION === true) {
      $ipAddress = encrypt($ipAddress, $username);
    }

    if ($target !== null && strpos($target, ".") === false) {
      return false;
    }

    $ins = $this->dbIns(
      [
        "history",
        [
          "date" => time(),
          "actor" => $actor,
          "actorIp" => $ipAddress,
          "action" => $action,
          "target" => $target,
          "description" => $this->sanitize($description)
        ]
      ]
    );

    if ($ins === true) {
      return true;
    } else {
      return "db";
    }
  }
}

?>
