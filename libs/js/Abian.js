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
* Example: <a onClick="sendVote(1, 'bot.2', 0)"">Hi</a>
*
* @access public
* @param integer type
* @param string on
* @param integer onId
* @param integer user
* @return null
*/
function sendVote (type, on, onId, user) {
  if (type == 0) {
    $.ajax({
      type: "POST",
      url: "ajax.php",
      data: {down:onId,user:user},
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
      url: "ajax.php",
      data: {up:onId,user:user},
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