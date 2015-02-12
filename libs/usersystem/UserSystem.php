<?php
/**
* System class for performing common user system operations.
*
* @package    UserSystem
* @author     Ethan Henderson <ethan@zbee.me>
* @copyright  2015 Ethan Henderson
* @license    http://aol.nexua.org  AOL v0.6
* @link       https://github.com/zbee/usersystem
* @since      Class available since Release 0.1
*/
class UserSystem extends Database {

  /**
   * Checks if a user is banned
   * Example: $UserSystem->checkBan("127.0.0.1", "bob)
   *
   * @access public
   * @param string $ip
   * @param mixed $username
   * @return boolean
   */
  public function checkBan ($ipAddress, $username = false) {
    $ipAddress = filter_var(
      $ipAddress,
      FILTER_SANITIZE_FULL_SPECIAL_CHARS
    );
    if (ENCRYPTION === true) {
      $ipAddress = encrypt($ipAddress, $username);
    }
    $stmt = $this->dbSel(["ban", ["ip" => $ipAddress]]);
    $rows = $stmt[0];
    if ($rows > 0) {
      if ($stmt[1]['appealed'] == 0) {
        $thing = true;
      } else {
        $thing = false;
      }
    } else {
      $thing = false;
    }

    if ($username !== false) {
      $stmt = $this->dbSel(["ban", ["username" => $username]]);
      $rows = $stmt[0];
      if ($rows > 0) {
        if ($stmt[1]['appealed'] == 0) {
          $thing = true;
        } else {
          $thing = false;
        }
      }
    }

    return $thing;
  }

  /**
   * Verifies a user's session
   * Example: $UserSystem->verifySession($_COOKIE[SITENAME])
   *
   * @access public
   * @param mixed $session
   * @return mixed
   */
  public function verifySession ($session = false) {
    if (!isset($_COOKIE[SITENAME])) { return false; }
    $COOKIE = filter_var(
      $_COOKIE[SITENAME],
      FILTER_SANITIZE_FULL_SPECIAL_CHARS
    );
    if (!$session) { $session = $COOKIE; }
    $tamper = substr($session, -32);
    $ipAddress = filter_var(
      $_SERVER["REMOTE_ADDR"],
      FILTER_SANITIZE_FULL_SPECIAL_CHARS
    );
    if (ENCRYPTION === true) {
      $ipAddress = encrypt($ipAddress, $username);
    }
    $time = strtotime("+30 days");
    $stmt = $this->dbSel(
      [
        "userblobs",
        [
          "code" => $session,
          "date" => ["<", $time],
          "action" => "session"
        ]
      ]
    );

    $rows = $stmt[0];
    if ($rows == 1) {
      $id = $stmt[1]["user"];
      if (md5($id.substr($session, 0, 128)) == $tamper) {
        if ($this->checkBan($ipAddress) === false) {
          return true;
        } else {
          return "ban";
        }
      } else {
        $this->dbDel(["userblobs", ["code"=>$session, "action"=>"session"]]);
        return "tamper";
      }
    } else {
      if ($rows > 1) {
        return "sessions";
        $this->dbDel(["userblobs", ["code"=>$session, "action"=>"session"]]);
      }
      return "session";
    }
  }

  /**
   * Inserts a new user
   * Example: $UserSystem->addUser("Bob","jg85h58gh58","bob@example.com")
   *
   * @access public
   * @param string $username
   * @param string $password
   * @param string $email
   * @param mixed $more
   * @return mixed
   */
   public function addUser ($username, $password, $email, $more = false) {
     $usernameUse = $this->dbSel(["users", ["username" => $username]])[0];
     if ($usernameUse == 0) {
       $emailUse = $this->dbSel(["users", ["email" => $email]])[0];
       if ($emailUse == 0) {
         if ($this->sanitize($email, "e") !== "FAILED SANITIZATION") {
           $salt = $this->createSalt($username);
           $data = [
             "username" => $username,
             "password" => hash("sha256", $password.$salt),
             "email" => $email,
             "salt" => $salt,
             "dateRegistered" => time()
           ];

           $morech = $this->sanitize($more, "b");

           if ($morech !== false && is_array($more)) {
             foreach ($more as $item) {
               $data[array_search($item, $more)] = $item;
             }
           }

           $this->dbIns(["users", $data]);
           $blob = $this->insertUserBlob($username, "activate");
           $link = $this->sanitize(
             URL_PREFACE."://".DOMAIN."/".ACTIVATE_PG."/?blob={$blob}",
             "u"
           );
           $mail = $this->sendMail(
             $email,
             "Activate your ".SITENAME." account",
             "           Hello {$username}

             To activate your account, click the link below.
             {$link}

             ======

             If this wasn't you, you can ignore this email.

             Thank you"
           );
           return $mail;
        } else {
          return "emailbad";
        }
       } else {
         return "emailused";
       }
     } else {
       return "username";
     }
   }

  /**
   * Activates a new user's account
   * Example: $UserSystem->activateUser("mrogjsruicyu78chsr87thmrsu")
   *
   * @access public
   * @param string $code
   * @return boolean
   */
  public function activateUser ($code) {
    $rows = $this->dbSel(["userblobs", ["code"=>$code, "action"=>"activate"]]);
    if ($rows[0] == 1) {
      $user = $rows[1]["user"];
      $this->dbUpd(["users", ["activated"=>1], ["id"=>$user]]);
      $activated = $this->dbSel(["users", ["id"=>$user,"activated"=>1]])[0];
      $this->dbDel(["userblobs", ["code"=>$code, "action"=>"activate"]]);
      $blob=$this->dbSel(["userblobs",["code"=>$code,"action"=>"activate"]])[0];
      if ($activated == 1 && $blob == 0) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  /**
   * Logs in a user
   * Example: $UserSystem->logIn("Bob", "Bob's Password")
   *
   * @access public
   * @param string $username
   * @param string $password
   * @return boolean
   */
  public function logIn ($username, $password, $ignoreTS = false) {
    $ignoreTS = $this->sanitize($ignoreTS, "b");
    $ipAddress = filter_var(
      $_SERVER["REMOTE_ADDR"],
      FILTER_SANITIZE_FULL_SPECIAL_CHARS
    );
    $user = $this->session($username);
    if (is_array($user)) {
      $password = hash("sha256", $password.$user["salt"]);
      $oldPassword = hash("sha256", $password.$user["oldSalt"]);
      if ($password == $user["password"]) {
        if ($user["activated"] == 1) {
          if ($user["twoStep"] == 0 || $ignoreTS !== false) {
            if (ENCRYPTION === true) {
              $ipAddress = encrypt($ipAddress, $username);
            }
            $this->dbUpd(
              [
                "users",
                [
                  "ip" => $ipAddress,
                  "lastLoggedIn" => time(),
                  "oldLastLoggedIn" => $user["lastLoggedIn"]
                ],
                [
                  "username" => $username
                ]
              ]
            );
            $hash = $this->insertUserBlob($user["id"]);
            if (!headers_sent()) {
              setcookie(
                SITENAME,
                $hash,
                strtotime('+30 days'),
                "/",
                DOMAIN_SIMPLE
              );
              return true;
            } else {
              return true;
            }
          } else {
            return "twoStep";
          }
        } else {
          return "activate";
        }
      } else {
        if ($password == $oldPassword) {
          return "oldPassword";
        } else {
          return "password";
        }
      }
    } else {
      return "username";
    }
  }

  /**
   * Logs out a selected userblob or group of user blobs
   * Example: $UserSystem->logOut($_COOKIE[$sitename], "Bob", true)
   *
   * @access public
   * @param string $code
   * @param string $user
   * @param mixed $cursess
   * @param mixed $all
   * @return boolean
   */
  public function logOut ($code, $user, $cursess = false, $all = false) {
    if ($all === false) {
      $this->dbDel(
        [
          "userblobs",
          [
            "code" => $code,
            "user" => $user
          ]
        ]
      );
    } else {
      $this->dbDel(["userblobs", ["user"=>$user]]);
    }
    if ($cursess === true) {
      setcookie(
        SITENAME,
        null,
        strtotime('-60 days'),
        "/",
        DOMAIN_SIMPLE
      );
    }
    return true;
  }

  /**
   * Finishes logging a user in if they have twoStep enabled.
   * Example: $UserSystem->twoStep($blob)
   *
   * @access public
   * @param string $code
   * @return mixed
   */
  public function twoStep ($code) {
    $ipAddress = filter_var(
      $_SERVER["REMOTE_ADDR"],
      FILTER_SANITIZE_FULL_SPECIAL_CHARS
    );
    $return = "";
    $select = $this->dbSel(["userblobs", ["code"=>$code, "action"=>"twoStep"]]);
    if ($select[0] === 1) {
      if ($select[1]["date"] > time() - 3600) {
        if ($select[1]["ip"] == $ipAddress) {
          $this->logIn(
            $select[1]["user"],
            $this->session($select[1]["user"])["password"],
            true
          );
          $return = true;
        } else {
          $return = "ip";
        }
      } else {
        $return = "expired";
      }
    } else {
      $return = "code";
    }

    $this->dbDel(["userblobs", ["code"=>$code, "action"=>"twoStep"]]);
    return $return;
  }

  /**
  * Allows a user to send a link to reset their passsword if they forgot it.
  * Example: $UserSystem->sendRecover("example@pie.com")
  *
  * @access public
  * @param string $email
  * @return mixed
  */
  public function sendRecover ($email) {
    $select = $this->dbSel(["users", ["email"=>$email]]);
    if ($select[0] == 1) {
      $blob = $this->insertUserBlob($select[1]["username"], "recover");
      $link = $this->sanitize(
        URL_PREFACE."://".DOMAIN."/".RECOVERY_PG."?blob={$blob}",
        "u"
      );
      $this->sendMail(
        $email,
        "Recover your ".SITENAME." account",
        "        Hello ".$select[1]["username"]."

        To reset your password click the link below.
        {$link}

        ======

        If this wasn't you,you should update your password on ".SITENAME.".

        Thank you"
      );
      return true;
    } else {
      return "email";
    }
  }

  /**
  * Allows a user to reset their pass using the link received from sendRecover
  * Example: $UserSystem->recover("fmg49t4c8u5ym8598yv5")
  *
  * @access public
  * @param string $blob
  * @param string $pass
  * @param string $passconf
  * @return mixed
  */
  public function recover ($blob, $pass, $passconf) {
    if ($pass === $passconf) {
      $select = $this->dbSel(["userblobs",["code"=>$blob, "action"=>"recovery"]]);
      if ($select[0] == 1) {
        $user = $select[1]["user"];
        $salt = $this->session($user)["salt"];
        $pass = hash("sha256", $pass.$salt);
      }
    } else {
      return "password";
    }
  }
}
