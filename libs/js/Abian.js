/**
* Script for performing front-end actions specific to Abian
*
* @package    Abian
* @author     Ethan Henderson <ethan@zbee.me>
* @copyright  2015 Ethan Henderson
* @license    GNU GENERAL PUBLIC LICENSE
* @link       https://github.com/zbee/abian
* @since      Class available since Release 0.47
*/


/**
* Sends data to the local ajax.php script for voting
* Example: <a onClick="sendVote(1, 'bot', 2, $sess['id']), $user['id']">Vote</a>
*
* @access public
* @param integer type
* @param string on
* @param integer onId
* @param integer user
* @param integer target
* @param string location
* @return null
*/
function sendVote (type, on, onId, user, target, location) {
  location = typeof location !== 'undefined' ? location : '';
  if (type == 0) {
    $.ajax({
      type: "POST",
      url: location + "ajax.php",
      data: {down:onId,user:user,target:target},
      dataType: "json",
      context: document.body,
      async: true,
      complete: function(res, stato) {
        if (res.responseJSON == "add") {
          $("#" + on + "Down").addClass("btn-danger");
          $("#" + on + "Up").removeClass("btn-success");
        }
        if (res.responseJSON == "remove") {
          $("#" + on + "Down").removeClass("btn-danger");
          $("#" + on + "Up").removeClass("btn-success");
        }
      }
    });
  } else {
    $.ajax({
      type: "POST",
      url: location + "ajax.php",
      data: {up:onId,user:user,target:target},
      dataType: "json",
      context: document.body,
      async: true,
      complete: function(res, stato) {
        if (res.responseJSON == "add") {
          $("#" + on + "Up").addClass("btn-success");
          $("#" + on + "Down").removeClass("btn-danger");
        }
        if (res.responseJSON == "remove") {
          $("#" + on + "Up").removeClass("btn-success");
          $("#" + on + "Down").removeClass("btn-danger");
        }
      }
    });
  }
}