<?phpob_start();require_once("/var/www/abian/_secret_keys.php");#These constants are used to connect to your MySQL database.define("DB_PREFACE", "");               #A preface to database table namesdefine("DB_LOCATION", $db["host"]);        #The location of the databasedefine("DB_USERNAME", $db["user"]);             #The username for the databasedefine("DB_PASSWORD", $db["pass"]);                 #The password for the databasedefine("DB_DATABASE", $db["db"]);               #The name of the database#These constants are used for URLs and cookies.define("SITENAME", "abian");          #Name of your site (no symbols)define("URL_PREFACE", "https");             #If http or https is useddefine("DOMAIN_SIMPLE", "abian.zbee.me");    #The root url of your websitedefine("DOMAIN", "abian.zbee.me");  #The url holding the systemdefine("SYSTEM_LOC", "/libs/usersystem");       #The folder path to the class filedefine("ACTIVATE_PG", "u/activate"); #Activation page relative to DOMAINdefine("RECOVERY_PG", "u/recover");  #Recovery page relative to DOMAINdefine("TWOSTEP_PG", "u/twostep");   #Two step page relative to DOMAIN#These constants are all optional, they could be left as-is.define("ENCRYPTION", false);               #Whether or not encryption is usedrequire_once("Utils.php");require_once("Database.php");require_once("UserSystem.php");$UserSystem = new UserSystem (DB_DATABASE);