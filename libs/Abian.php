<?php
/**
* Class for performing operations specific to Abian
*
* @package    Abian
* @author     Ethan Henderson <ethan@zbee.me>
* @copyright  2015 Ethan Henderson
* @license    GNU GENERAL PUBLIC LICENSE
* @link       https://github.com/zbee/abian
* @since      Class available since Release 0.11
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
    $country = filter_var(
      $_SERVER["HTTP_CF_IPCOUNTRY"],
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
          "actorA2" => $country,
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

  /**
  * Returns the operating system a user is using
  * Example: $Abian->getOS()
  *
  * @access public
  * @return string
  */
  public function getOS () { 
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    $os_platform = "Unknown OS Platform";
    $os_array = [
      '/windows nt 6.3/i'     =>  'Windows 8.1',
      '/windows nt 6.2/i'     =>  'Windows 8',
      '/windows nt 6.1/i'     =>  'Windows 7',
      '/windows nt 6.0/i'     =>  'Windows Vista',
      '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
      '/windows nt 5.1/i'     =>  'Windows XP',
      '/windows xp/i'         =>  'Windows XP',
      '/windows nt 5.0/i'     =>  'Windows 2000',
      '/windows me/i'         =>  'Windows ME',
      '/win98/i'              =>  'Windows 98',
      '/win95/i'              =>  'Windows 95',
      '/win16/i'              =>  'Windows 3.11',
      '/macintosh|mac os x/i' =>  'Mac OS X',
      '/mac_powerpc/i'        =>  'Mac OS 9',
      '/linux/i'              =>  'Linux',
      '/ubuntu/i'             =>  'Ubuntu',
      '/iphone/i'             =>  'iPhone',
      '/ipod/i'               =>  'iPod',
      '/ipad/i'               =>  'iPad',
      '/android/i'            =>  'Android',
      '/blackberry/i'         =>  'BlackBerry',
      '/webos/i'              =>  'Mobile'
    ];

    foreach ($os_array as $regex => $value) { 
      if (preg_match($regex, $user_agent)) {
        $os_platform = $value;
      }
    }
    return $os_platform;
  }

  /**
  * Returns the browser a user is using
  * Example: $Abian->getBrowser()
  *
  * @access public
  * @return string
  */
  public function getBrowser () {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    $browser = "Unknown Browser";
    $browser_array = [
      '/msie/i'       =>  'Internet Explorer',
      '/firefox/i'    =>  'Firefox',
      '/safari/i'     =>  'Safari',
      '/chrome/i'     =>  'Chrome',
      '/opera/i'      =>  'Opera',
      '/netscape/i'   =>  'Netscape',
      '/maxthon/i'    =>  'Maxthon',
      '/konqueror/i'  =>  'Konqueror',
      '/mobile/i'     =>  'Handheld Browser'
    ];

    foreach ($browser_array as $regex => $value) {
      if (preg_match($regex, $user_agent)) {
        $browser = $value;
      }
    }
    return $browser;
  }

  /**
  * Returns the amount of xp a user has from badges, votes, and downloads
  * Example: $Abian->calcXP($session["id"])
  *
  * @access public
  * @param integer $user
  * @return integer
  */
  public function calcXP ($user) {
    $user = $this->sanitize($user, "n");

    #Check XP from badges
    $xFB = 0;
    $badges = $this->dbSel(["badges", ["value" => [">", 0]]]); #all badges with value
    unset($badges[0]);
    $badging = $this->dbSel(["badging", ["user" => $user]]); #all badges user has
    unset($badging[0]);
    foreach ($badging as $badge) { #each badge user has
      foreach ($badges as $b) { #check if it's one of the valued badges
        if ($b["id"] == $badge["badge"]) $xFB += intval($b["value"]); #if yes, add value
      }
    }
    return $xFB;
  }

  /**
  * Returns the array of te level of a user based off of their xp ([level, min xp, max xp])
  * Example: $Abian->calcLevel(0)
  *
  * @access public
  * @param integer $xp
  * @return array
  */
  public function calcLeveL ($xp) {
    $xp = $this->sanitize($xp, "n");
    switch ($xp) {
      case $xp >= 2000000: return [14, 2000000, 10000000];
      case $xp >= 675000: return [13, 675000, 2000000];
      case $xp >= 225000: return [12, 225000, 675000];
      case $xp >= 75000: return [11, 75000, 225000];
      case $xp >= 25000: return [10, 25000, 75000];
      case $xp >= 10000: return [9, 10000, 25000];
      case $xp >= 3500: return [8, 3500, 10000];
      case $xp >= 1250: return [7, 1250, 3500];
      case $xp >= 500: return [6, 500, 1250];
      case $xp >= 200: return [5, 200, 500];
      case $xp >= 75: return [4, 75, 200];
      case $xp >= 30: return [3, 30, 75];
      case $xp >= 12: return [2, 12, 30];
      case $xp >= 5: return [1, 5, 12];
      case $xp >= 0: return [0, 0, 5];
    }
    return 0;
  }

  /**
  * Returns the full name of the country given the country's ISO 3166 ALPHA-2 code
  * Example: $Abian->codeToCountry("AF")
  *
  * @access public
  * @param string $code
  * @return string
  */
  public function codeToCountry ($code) {
    $countries = [ #https://github.com/umpirsky/country-list/blob/master/country/cldr/en/country.php
      "AF" => "Afghanistan",
      "AL" => "Albania",
      "DZ" => "Algeria",
      "AS" => "American Samoa",
      "AD" => "Andorra",
      "AO" => "Angola",
      "AI" => "Anguilla",
      "AQ" => "Antarctica",
      "AG" => "Antigua and Barbuda",
      "AR" => "Argentina",
      "AM" => "Armenia",
      "AW" => "Aruba",
      "AU" => "Australia",
      "AT" => "Austria",
      "AZ" => "Azerbaijan",
      "BS" => "Bahamas",
      "BH" => "Bahrain",
      "BD" => "Bangladesh",
      "BB" => "Barbados",
      "BY" => "Belarus",
      "BE" => "Belgium",
      "BZ" => "Belize",
      "BJ" => "Benin",
      "BM" => "Bermuda",
      "BT" => "Bhutan",
      "BO" => "Bolivia",
      "BA" => "Bosnia and Herzegovina",
      "BW" => "Botswana",
      "BV" => "Bouvet Island",
      "BR" => "Brazil",
      "BQ" => "British Antarctic Territory",
      "IO" => "British Indian Ocean Territory",
      "VG" => "British Virgin Islands",
      "BN" => "Brunei",
      "BG" => "Bulgaria",
      "BF" => "Burkina Faso",
      "BI" => "Burundi",
      "KH" => "Cambodia",
      "CM" => "Cameroon",
      "CA" => "Canada",
      "CT" => "Canton and Enderbury Islands",
      "CV" => "Cape Verde",
      "KY" => "Cayman Islands",
      "CF" => "Central African Republic",
      "TD" => "Chad",
      "CL" => "Chile",
      "CN" => "China",
      "CX" => "Christmas Island",
      "CC" => "Cocos [Keeling] Islands",
      "CO" => "Colombia",
      "KM" => "Comoros",
      "CG" => "Congo - Brazzaville",
      "CD" => "Congo - Kinshasa",
      "CK" => "Cook Islands",
      "CR" => "Costa Rica",
      "HR" => "Croatia",
      "CU" => "Cuba",
      "CY" => "Cyprus",
      "CZ" => "Czech Republic",
      "CI" => "Côte d’Ivoire",
      "DK" => "Denmark",
      "DJ" => "Djibouti",
      "DM" => "Dominica",
      "DO" => "Dominican Republic",
      "NQ" => "Dronning Maud Land",
      "DD" => "East Germany",
      "EC" => "Ecuador",
      "EG" => "Egypt",
      "SV" => "El Salvador",
      "GQ" => "Equatorial Guinea",
      "ER" => "Eritrea",
      "EE" => "Estonia",
      "ET" => "Ethiopia",
      "FK" => "Falkland Islands",
      "FO" => "Faroe Islands",
      "FJ" => "Fiji",
      "FI" => "Finland",
      "FR" => "France",
      "GF" => "French Guiana",
      "PF" => "French Polynesia",
      "TF" => "French Southern Territories",
      "FQ" => "French Southern and Antarctic Territories",
      "GA" => "Gabon",
      "GM" => "Gambia",
      "GE" => "Georgia",
      "DE" => "Germany",
      "GH" => "Ghana",
      "GI" => "Gibraltar",
      "GR" => "Greece",
      "GL" => "Greenland",
      "GD" => "Grenada",
      "GP" => "Guadeloupe",
      "GU" => "Guam",
      "GT" => "Guatemala",
      "GG" => "Guernsey",
      "GN" => "Guinea",
      "GW" => "Guinea-Bissau",
      "GY" => "Guyana",
      "HT" => "Haiti",
      "HM" => "Heard Island and McDonald Islands",
      "HN" => "Honduras",
      "HK" => "Hong Kong SAR China",
      "HU" => "Hungary",
      "IS" => "Iceland",
      "IN" => "India",
      "ID" => "Indonesia",
      "IR" => "Iran",
      "IQ" => "Iraq",
      "IE" => "Ireland",
      "IM" => "Isle of Man",
      "IL" => "Israel",
      "IT" => "Italy",
      "JM" => "Jamaica",
      "JP" => "Japan",
      "JE" => "Jersey",
      "JT" => "Johnston Island",
      "JO" => "Jordan",
      "KZ" => "Kazakhstan",
      "KE" => "Kenya",
      "KI" => "Kiribati",
      "KW" => "Kuwait",
      "KG" => "Kyrgyzstan",
      "LA" => "Laos",
      "LV" => "Latvia",
      "LB" => "Lebanon",
      "LS" => "Lesotho",
      "LR" => "Liberia",
      "LY" => "Libya",
      "LI" => "Liechtenstein",
      "LT" => "Lithuania",
      "LU" => "Luxembourg",
      "MO" => "Macau SAR China",
      "MK" => "Macedonia",
      "MG" => "Madagascar",
      "MW" => "Malawi",
      "MY" => "Malaysia",
      "MV" => "Maldives",
      "ML" => "Mali",
      "MT" => "Malta",
      "MH" => "Marshall Islands",
      "MQ" => "Martinique",
      "MR" => "Mauritania",
      "MU" => "Mauritius",
      "YT" => "Mayotte",
      "FX" => "Metropolitan France",
      "MX" => "Mexico",
      "FM" => "Micronesia",
      "MI" => "Midway Islands",
      "MD" => "Moldova",
      "MC" => "Monaco",
      "MN" => "Mongolia",
      "ME" => "Montenegro",
      "MS" => "Montserrat",
      "MA" => "Morocco",
      "MZ" => "Mozambique",
      "MM" => "Myanmar [Burma]",
      "NA" => "Namibia",
      "NR" => "Nauru",
      "NP" => "Nepal",
      "NL" => "Netherlands",
      "AN" => "Netherlands Antilles",
      "NT" => "Neutral Zone",
      "NC" => "New Caledonia",
      "NZ" => "New Zealand",
      "NI" => "Nicaragua",
      "NE" => "Niger",
      "NG" => "Nigeria",
      "NU" => "Niue",
      "NF" => "Norfolk Island",
      "KP" => "North Korea",
      "VD" => "North Vietnam",
      "MP" => "Northern Mariana Islands",
      "NO" => "Norway",
      "OM" => "Oman",
      "PC" => "Pacific Islands Trust Territory",
      "PK" => "Pakistan",
      "PW" => "Palau",
      "PS" => "Palestinian Territories",
      "PA" => "Panama",
      "PZ" => "Panama Canal Zone",
      "PG" => "Papua New Guinea",
      "PY" => "Paraguay",
      "YD" => "People\"s Democratic Republic of Yemen",
      "PE" => "Peru",
      "PH" => "Philippines",
      "PN" => "Pitcairn Islands",
      "PL" => "Poland",
      "PT" => "Portugal",
      "PR" => "Puerto Rico",
      "QA" => "Qatar",
      "RO" => "Romania",
      "RU" => "Russia",
      "RW" => "Rwanda",
      "RE" => "Réunion",
      "BL" => "Saint Barthélemy",
      "SH" => "Saint Helena",
      "KN" => "Saint Kitts and Nevis",
      "LC" => "Saint Lucia",
      "MF" => "Saint Martin",
      "PM" => "Saint Pierre and Miquelon",
      "VC" => "Saint Vincent and the Grenadines",
      "WS" => "Samoa",
      "SM" => "San Marino",
      "SA" => "Saudi Arabia",
      "SN" => "Senegal",
      "RS" => "Serbia",
      "CS" => "Serbia and Montenegro",
      "SC" => "Seychelles",
      "SL" => "Sierra Leone",
      "SG" => "Singapore",
      "SK" => "Slovakia",
      "SI" => "Slovenia",
      "SB" => "Solomon Islands",
      "SO" => "Somalia",
      "ZA" => "South Africa",
      "GS" => "South Georgia and the South Sandwich Islands",
      "KR" => "South Korea",
      "ES" => "Spain",
      "LK" => "Sri Lanka",
      "SD" => "Sudan",
      "SR" => "Suriname",
      "SJ" => "Svalbard and Jan Mayen",
      "SZ" => "Swaziland",
      "SE" => "Sweden",
      "CH" => "Switzerland",
      "SY" => "Syria",
      "ST" => "São Tomé and Príncipe",
      "TW" => "Taiwan",
      "TJ" => "Tajikistan",
      "TZ" => "Tanzania",
      "TH" => "Thailand",
      "TL" => "Timor-Leste",
      "TG" => "Togo",
      "TK" => "Tokelau",
      "TO" => "Tonga",
      "TT" => "Trinidad and Tobago",
      "TN" => "Tunisia",
      "TR" => "Turkey",
      "TM" => "Turkmenistan",
      "TC" => "Turks and Caicos Islands",
      "TV" => "Tuvalu",
      "UM" => "U.S. Minor Outlying Islands",
      "PU" => "U.S. Miscellaneous Pacific Islands",
      "VI" => "U.S. Virgin Islands",
      "UG" => "Uganda",
      "UA" => "Ukraine",
      "SU" => "Union of Soviet Socialist Republics",
      "AE" => "United Arab Emirates",
      "GB" => "United Kingdom",
      "US" => "United States",
      "ZZ" => "Unknown or Invalid Region",
      "UY" => "Uruguay",
      "UZ" => "Uzbekistan",
      "VU" => "Vanuatu",
      "VA" => "Vatican City",
      "VE" => "Venezuela",
      "VN" => "Vietnam",
      "WK" => "Wake Island",
      "WF" => "Wallis and Futuna",
      "EH" => "Western Sahara",
      "YE" => "Yemen",
      "ZM" => "Zambia",
      "ZW" => "Zimbabwe",
      "AX" => "Åland Islands"
    ];
    return $this->handleUTF8($countries[strtoupper($code)]);
  }
}

?>
