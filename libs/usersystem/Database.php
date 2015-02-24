<?php
/**
* Class full of methods for dealing with databases effectively adn securely.
*
* @package    UserSystem
* @author     Ethan Henderson <ethan@zbee.me>
* @copyright  2015 Ethan Henderson
* @license    http://aol.nexua.org  AOL v0.1
* @link       https://github.com/zbee/usersystem
* @since      Class available since Release 0.59
*/
class Database extends Utils {
  /**
  * A shortcut for easily escaping a table/column name for PDO
  * Example: $UserSystem->dbIns(["users",["u"=>"Bob","e"=>"bob@ex.com"]])
  *
  * @access public
  * @param string $field
  * @return string
  */
  function quoteIdent ($field) {
    return "`".str_replace("`","``",$field)."`";
  }

  /**
  * A shortcut for eaily inserting a new item into a database.
  * Example: $UserSystem->dbIns(["users",["u"=>"Bob","e"=>"bob@ex.com"]])
  *
  * @access public
  * @param array $data
  * @return boolean
  */
  public function dbIns ($data) {
    $data[0] = $this->quoteIdent(DB_PREFACE.ucfirst($data[0]));
    $dataArr = [];
    foreach ($data[1] as $col => $val) {
      array_push($dataArr, [$col, $val]);
    }
    $cols = "";
    $entries = "";
    $enArr = [];
    foreach ($dataArr as $item) {
      $cols .= $this->quoteIdent($item[0]).", ";
      $entries .= "?, ";
      array_push($enArr, $item[1]);
    }
    $cols = substr($cols, 0, -2);
    $entries = substr($entries, 0, -2);
    #print($this->str_replace_arr("?", $enArr, "INSERT INTO $data[0] ($cols) VALUES ($entries)"));
    $stmt = $this->DATABASE->prepare("
      INSERT INTO $data[0] ($cols) VALUES ($entries)
    "); #Twitch @Sniperzeelite I love u 5 ever!!!
    return $stmt->execute($enArr);
  }


  /**
  * A shortcut for eaily updating an item into a database.
  * Example: $UserSystem->dbUpd(["users",[e"=>"bob@ex.com"],["u"=>"Bob"]])
  *
  * @access public
  * @param array $data
  * @return boolean
  */
  public function dbUpd ($data) {
    $data[0] = $this->quoteIdent(DB_PREFACE.ucfirst($data[0]));
    $dataArr = [];
    foreach ($data[1] as $col => $item) {
      array_push($dataArr, [$col, $item]);
    }
    $update = "";
    $qArr = [];
    foreach ($dataArr as $item) {
      $update .= $this->quoteIdent($item[0])."=?, ";
      array_push($qArr, $item[1]);
    }
    $equalsArr = [];
    foreach ($data[2] as $col => $item) {
      array_push(
        $equalsArr,
        [
          $this->sanitize($col, "q"),
          $this->sanitize($item, "q")
        ]
      );
    }
    $equals = "";
    foreach ($equalsArr as $item) {
      $equals .= $this->quoteIdent($item[0])."=? AND ";
      array_push($qArr, $item[1]);
    }
    $equals = substr($equals, 0, -5);
    $update = substr($update, 0, -2);
    $stmt = $this->DATABASE->prepare("
      UPDATE $data[0] SET $update WHERE $equals
    ");
    return $stmt->execute($qArr);
  }


  /**
  * A shortcut for eaily deleting an item in a database.
  * Example: $UserSystem->dbDel(["users",["u"=>"Bob"]])
  *
  * @access public
  * @param array $data
  * @return boolean
  */
  public function dbDel ($data) {
    $data[0] = $this->quoteIdent(DB_PREFACE.ucfirst($data[0]));
    $dataArr = [];
    foreach ($data[1] as $col => $item) {
      array_push($dataArr, [$col, $item]);
    }
    $equals = "";
    $eqArr = [];
    foreach ($dataArr as $item) {
      $equals .= $this->quoteIdent($item[0])."=? AND ";
      array_push($eqArr, $item[1]);
    }
    $equals = substr($equals, 0, -5);
    $stmt = $this->DATABASE->prepare("
      DELETE FROM ".$data[0]." WHERE $equals
    ");
    return $stmt->execute($eqArr);
  }

  /**
  * Returns an array for the database search performed, again, just a shortcut
  * for hitting required functions
  * Example: $UserSystem->dbSel(["users", ["username"=>"Bob","id"=>0]])
  *
  * @access public
  * @param array $data
  * @return array
  */
  public function dbSel ($data) {
    $data[0] = $this->quoteIdent(DB_PREFACE.ucfirst($data[0]));
    $dataArr = [];
    foreach ($data[1] as $col => $item) {
      array_push(
        $dataArr,
        [
          $col,
          is_array($item) ? "@~#~@".$item[0]."~=exarg@@".$item[1] : $item
        ]
      );
    }
    $equals = '';
    $qmark = [];
    foreach ($dataArr as $item) {
      $diff = '=';
      if (substr($item[1], 0, 5) === "@~#~@") {
        $diff = explode("~=exarg@@", substr($item[1], 5))[0];
        $item[1] = explode("~=exarg@@", $item[1])[1];
      }
      $equals .= " AND ".$this->quoteIdent($item[0]).$diff."?";
      array_push($qmark, $item[1]);
    }
    $equals = substr($equals, 5);
    if (isset($data[2])) {
      $order = "order by ".$data[2][0]." ".$data[2][1];
    } else {
      $order = "";
    }
    #print($this->str_replace_arr("?", $qmark, "select * from ".$data[0]." where $equals ".$order));
    $stmt = $this->DATABASE->prepare("
      select * from ".$data[0]." where $equals ".$order."
    ");
    $stmt->execute($qmark);
    $arr = [(is_object($stmt) ? $stmt->rowCount() : 0)];
    if ($arr[0] > 0) {
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($arr, $row);
      }
    }
    return $arr;
  }
}
