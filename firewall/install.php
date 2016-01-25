<?php
/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (Pro edition)                                         |
 |                                                                     |
 | (c) NinTechNet - http://nintechnet.com/                             |
 |                                                                     |
 +---------------------------------------------------------------------+
 | REVISION: 2015-12-05 17:08:00                                       |
 +---------------------------------------------------------------------+
 | This program is free software: you can redistribute it and/or       |
 | modify it under the terms of the GNU General Public License as      |
 | published by the Free Software Foundation, either version 3 of      |
 | the License, or (at your option) any later version.                 |
 |                                                                     |
 | This program is distributed in the hope that it will be useful,     |
 | but WITHOUT ANY WARRANTY; without even the implied warranty of      |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       |
 | GNU General Public License for more details.                        |
 +---------------------------------------------------------------------+
*/

@error_reporting(E_ALL);
@ini_set('display_errors',1);

if (version_compare(PHP_VERSION, '5.4', '<') ) {
	if (! session_id() ) {
		@ini_set('session.cookie_httponly', 1);
		@ini_set('session.use_only_cookies', 1);
		if ($_SERVER['SERVER_PORT'] == 443) {
			@ini_set('session.cookie_secure', 1);
		}
		session_start();
	}
} else {
	if (session_status() !== PHP_SESSION_ACTIVE) {
		@ini_set('session.cookie_httponly', 1);
		@ini_set('session.use_only_cookies', 1);
		if ($_SERVER['SERVER_PORT'] == 443) {
			@ini_set('session.cookie_secure', 1);
		}
		session_start();
	}
}

// Don't cache anything :
header('Pragma: no-cache');
header('Cache-Control: private, no-cache, no-store, max-age=0, must-revalidate, proxy-revalidate');
header('Expires: Mon, 01 Sep 2014 01:01:01 GMT');

if ( file_exists(__DIR__ . '/conf/options.php') && empty($_SESSION['nfw_install']) ) {
	_error('you already have a <code>conf/options.php</code> configuration file.<br />If you want to re-install NinjaFirewall, delete it first.');
	exit;
}
if (! file_exists(__DIR__ . '/conf/.rules.php') ) {
	_error('unable to find the <code>conf/.rules.php</code> configuration file.<br />Please ensure your package is not corrupted, or <a href="http://ninjafirewall.com/" class="links" style="border-bottom:dotted 1px #FDCD25;">download NinjaFirewall from the official website</a>.');
	exit;
}

// Set the installation flag :
$_SESSION['nfw_install'] = 1;

require(__DIR__ . '/conf/.rules.php');
$nfw_rules = unserialize($nfw_rules_new);
// Required constants :
require(__DIR__ . '/lib/constants.php');
if ( NFW_EDN == 2 ) {
	$nfedn = 'Pro+ Edition';
} else {
	$nfedn = 'Pro Edition';
}

// PHP INFO ?
if (@$_GET['nfw_act'] == 99) {
	phpinfo(33);
	exit;
}
// Firewall test ?
if (@$_GET['nfw_act'] == 98) {
	nfw_activation_test();
	exit;
}

// First run ?
if ( empty($_REQUEST['nfw_act']) || ! preg_match('/^[1-8]$/', $_REQUEST['nfw_act']) ) {
	$_REQUEST['nfw_act'] = 1;
}
//global $mid;

if ( $_REQUEST['nfw_act'] == 1 ) {
	$mid = 10;
	nfw_regional_settings();

} elseif ( $_REQUEST['nfw_act'] == 2 ) {
	if ( empty($_POST['timezone']) ) {
		$_SESSION['timezone'] = 'UTC';
	} else {
		$_SESSION['timezone'] = $_POST['timezone'];
	}
	if ( empty($_POST['admin_lang']) ) {
		$_SESSION['admin_lang'] = 'en';
	} else {
		$_SESSION['admin_lang'] = $_POST['admin_lang'];
	}
	$mid = 20;
	nfw_system_requirements();

} else if ( $_REQUEST['nfw_act'] == 3 ) {
	$mid = 30;
	nfw_changelog();

} else if ( $_REQUEST['nfw_act'] == 4 ) {
	$mid = 40;
	nfw_license();

} else if ( $_REQUEST['nfw_act'] == 5 ) {
	$mid = 50;
	// Firewall test failure:
	if (!empty($_REQUEST['nfw_test']) ) {
		$mid = 60;
		nfw_integration(0);
		exit;
	}
	if (empty($_POST['save']) ) {
		nfw_admin_setup(0);
	} else {
		nfw_admin_setup_save();
		$mid = 60;
		nfw_integration(0);
	}

} else if ( $_REQUEST['nfw_act'] == 6 ) {
	$mid = 70;
	nfw_integration_save();
	nfw_activation();
}

exit;

/* ------------------------------------------------------------------ */

function nfw_regional_settings() {

	// Get all available language :
	$reg = array();
	if ( $handle = opendir('./lib/lang') ) {
		while (false !== ( $file = readdir($handle) ) ) {
			if ( preg_match('/^([a-z]{2})$/', $file, $match) ) {
				include('./lib/lang/' . $file . '/index.php');
				$reg[$match[1]]['language'] = $language;
				$reg[$match[1]]['author'] = $author;
			}

		}
		closedir($handle);
	}
	ksort($reg);

	if ( empty($_SESSION['admin_lang']) ) {
		// Load EN/English language file by default :
		$tmp_lang = 'en';
	} else {
		$tmp_lang = $_SESSION['admin_lang'];
	}

	require (__DIR__ . '/lib/lang/' . $tmp_lang . '/' . basename(__FILE__) );

	install_header('', '');

	$zonelist = array('UTC', 'Africa/Abidjan', 'Africa/Accra', 'Africa/Addis_Ababa', 'Africa/Algiers', 'Africa/Asmara', 'Africa/Asmera', 'Africa/Bamako', 'Africa/Bangui', 'Africa/Banjul', 'Africa/Bissau', 'Africa/Blantyre', 'Africa/Brazzaville', 'Africa/Bujumbura', 'Africa/Cairo', 'Africa/Casablanca', 'Africa/Ceuta', 'Africa/Conakry', 'Africa/Dakar', 'Africa/Dar_es_Salaam', 'Africa/Djibouti', 'Africa/Douala', 'Africa/El_Aaiun', 'Africa/Freetown', 'Africa/Gaborone', 'Africa/Harare', 'Africa/Johannesburg', 'Africa/Kampala', 'Africa/Khartoum', 'Africa/Kigali', 'Africa/Kinshasa', 'Africa/Lagos', 'Africa/Libreville', 'Africa/Lome', 'Africa/Luanda', 'Africa/Lubumbashi', 'Africa/Lusaka', 'Africa/Malabo', 'Africa/Maputo', 'Africa/Maseru', 'Africa/Mbabane', 'Africa/Mogadishu', 'Africa/Monrovia', 'Africa/Nairobi', 'Africa/Ndjamena', 'Africa/Niamey', 'Africa/Nouakchott', 'Africa/Ouagadougou', 'Africa/Porto-Novo', 'Africa/Sao_Tome', 'Africa/Timbuktu', 'Africa/Tripoli', 'Africa/Tunis', 'Africa/Windhoek', 'America/Adak', 'America/Anchorage', 'America/Anguilla', 'America/Antigua', 'America/Araguaina', 'America/Argentina/Buenos_Aires', 'America/Argentina/Catamarca', 'America/Argentina/ComodRivadavia', 'America/Argentina/Cordoba', 'America/Argentina/Jujuy', 'America/Argentina/La_Rioja', 'America/Argentina/Mendoza', 'America/Argentina/Rio_Gallegos', 'America/Argentina/Salta', 'America/Argentina/San_Juan', 'America/Argentina/San_Luis', 'America/Argentina/Tucuman', 'America/Argentina/Ushuaia', 'America/Aruba', 'America/Asuncion', 'America/Atikokan', 'America/Atka', 'America/Bahia', 'America/Barbados', 'America/Belem', 'America/Belize', 'America/Blanc-Sablon', 'America/Boa_Vista', 'America/Bogota', 'America/Boise', 'America/Buenos_Aires', 'America/Cambridge_Bay', 'America/Campo_Grande', 'America/Cancun', 'America/Caracas', 'America/Catamarca', 'America/Cayenne', 'America/Cayman', 'America/Chicago', 'America/Chihuahua', 'America/Coral_Harbour', 'America/Cordoba', 'America/Costa_Rica', 'America/Cuiaba', 'America/Curacao', 'America/Danmarkshavn', 'America/Dawson', 'America/Dawson_Creek', 'America/Denver', 'America/Detroit', 'America/Dominica', 'America/Edmonton', 'America/Eirunepe', 'America/El_Salvador', 'America/Ensenada', 'America/Fort_Wayne', 'America/Fortaleza', 'America/Glace_Bay', 'America/Godthab', 'America/Goose_Bay', 'America/Grand_Turk', 'America/Grenada', 'America/Guadeloupe', 'America/Guatemala', 'America/Guayaquil', 'America/Guyana', 'America/Halifax', 'America/Havana', 'America/Hermosillo', 'America/Indiana/Indianapolis', 'America/Indiana/Knox', 'America/Indiana/Marengo', 'America/Indiana/Petersburg', 'America/Indiana/Tell_City', 'America/Indiana/Vevay', 'America/Indiana/Vincennes', 'America/Indiana/Winamac', 'America/Indianapolis', 'America/Inuvik', 'America/Iqaluit', 'America/Jamaica', 'America/Jujuy', 'America/Juneau', 'America/Kentucky/Louisville', 'America/Kentucky/Monticello', 'America/Knox_IN', 'America/La_Paz', 'America/Lima', 'America/Los_Angeles', 'America/Louisville', 'America/Maceio', 'America/Managua', 'America/Manaus', 'America/Marigot', 'America/Martinique', 'America/Matamoros', 'America/Mazatlan', 'America/Mendoza', 'America/Menominee', 'America/Merida', 'America/Mexico_City', 'America/Miquelon', 'America/Moncton', 'America/Monterrey', 'America/Montevideo', 'America/Montreal', 'America/Montserrat', 'America/Nassau', 'America/New_York', 'America/Nipigon', 'America/Nome', 'America/Noronha', 'America/North_Dakota/Center', 'America/North_Dakota/New_Salem', 'America/Ojinaga', 'America/Panama', 'America/Pangnirtung', 'America/Paramaribo', 'America/Phoenix', 'America/Port-au-Prince', 'America/Port_of_Spain', 'America/Porto_Acre', 'America/Porto_Velho', 'America/Puerto_Rico', 'America/Rainy_River', 'America/Rankin_Inlet', 'America/Recife', 'America/Regina', 'America/Resolute', 'America/Rio_Branco', 'America/Rosario', 'America/Santa_Isabel', 'America/Santarem', 'America/Santiago', 'America/Santo_Domingo', 'America/Sao_Paulo', 'America/Scoresbysund', 'America/Shiprock', 'America/St_Barthelemy', 'America/St_Johns', 'America/St_Kitts', 'America/St_Lucia', 'America/St_Thomas', 'America/St_Vincent', 'America/Swift_Current', 'America/Tegucigalpa', 'America/Thule', 'America/Thunder_Bay', 'America/Tijuana', 'America/Toronto', 'America/Tortola', 'America/Vancouver', 'America/Virgin', 'America/Whitehorse', 'America/Winnipeg', 'America/Yakutat', 'America/Yellowknife', 'Arctic/Longyearbyen', 'Asia/Aden', 'Asia/Almaty', 'Asia/Amman', 'Asia/Anadyr', 'Asia/Aqtau', 'Asia/Aqtobe', 'Asia/Ashgabat', 'Asia/Ashkhabad', 'Asia/Baghdad', 'Asia/Bahrain', 'Asia/Baku', 'Asia/Bangkok', 'Asia/Beirut', 'Asia/Bishkek', 'Asia/Brunei', 'Asia/Calcutta', 'Asia/Choibalsan', 'Asia/Chongqing', 'Asia/Chungking', 'Asia/Colombo', 'Asia/Dacca', 'Asia/Damascus', 'Asia/Dhaka', 'Asia/Dili', 'Asia/Dubai', 'Asia/Dushanbe', 'Asia/Gaza', 'Asia/Harbin', 'Asia/Ho_Chi_Minh', 'Asia/Hong_Kong', 'Asia/Hovd', 'Asia/Irkutsk', 'Asia/Istanbul', 'Asia/Jakarta', 'Asia/Jayapura', 'Asia/Jerusalem', 'Asia/Kabul', 'Asia/Kamchatka', 'Asia/Karachi', 'Asia/Kashgar', 'Asia/Kathmandu', 'Asia/Katmandu', 'Asia/Kolkata', 'Asia/Krasnoyarsk', 'Asia/Kuala_Lumpur', 'Asia/Kuching', 'Asia/Kuwait', 'Asia/Macao', 'Asia/Macau', 'Asia/Magadan', 'Asia/Makassar', 'Asia/Manila', 'Asia/Muscat', 'Asia/Nicosia', 'Asia/Novokuznetsk', 'Asia/Novosibirsk', 'Asia/Omsk', 'Asia/Oral', 'Asia/Phnom_Penh', 'Asia/Pontianak', 'Asia/Pyongyang', 'Asia/Qatar', 'Asia/Qyzylorda', 'Asia/Rangoon', 'Asia/Riyadh', 'Asia/Saigon', 'Asia/Sakhalin', 'Asia/Samarkand', 'Asia/Seoul', 'Asia/Shanghai', 'Asia/Singapore', 'Asia/Taipei', 'Asia/Tashkent', 'Asia/Tbilisi', 'Asia/Tehran', 'Asia/Tel_Aviv', 'Asia/Thimbu', 'Asia/Thimphu', 'Asia/Tokyo', 'Asia/Ujung_Pandang', 'Asia/Ulaanbaatar', 'Asia/Ulan_Bator', 'Asia/Urumqi', 'Asia/Vientiane', 'Asia/Vladivostok', 'Asia/Yakutsk', 'Asia/Yekaterinburg', 'Asia/Yerevan', 'Atlantic/Azores', 'Atlantic/Bermuda', 'Atlantic/Canary', 'Atlantic/Cape_Verde', 'Atlantic/Faeroe', 'Atlantic/Faroe', 'Atlantic/Jan_Mayen', 'Atlantic/Madeira', 'Atlantic/Reykjavik', 'Atlantic/South_Georgia', 'Atlantic/St_Helena', 'Atlantic/Stanley', 'Australia/ACT', 'Australia/Adelaide', 'Australia/Brisbane', 'Australia/Broken_Hill', 'Australia/Canberra', 'Australia/Currie', 'Australia/Darwin', 'Australia/Eucla', 'Australia/Hobart', 'Australia/LHI', 'Australia/Lindeman', 'Australia/Lord_Howe', 'Australia/Melbourne', 'Australia/NSW', 'Australia/North', 'Australia/Perth', 'Australia/Queensland', 'Australia/South', 'Australia/Sydney', 'Australia/Tasmania', 'Australia/Victoria', 'Australia/West', 'Australia/Yancowinna', 'Europe/Amsterdam', 'Europe/Andorra', 'Europe/Athens', 'Europe/Belfast', 'Europe/Belgrade', 'Europe/Berlin', 'Europe/Bratislava', 'Europe/Brussels', 'Europe/Bucharest', 'Europe/Budapest', 'Europe/Chisinau', 'Europe/Copenhagen', 'Europe/Dublin', 'Europe/Gibraltar', 'Europe/Guernsey', 'Europe/Helsinki', 'Europe/Isle_of_Man', 'Europe/Istanbul', 'Europe/Jersey', 'Europe/Kaliningrad', 'Europe/Kiev', 'Europe/Lisbon', 'Europe/Ljubljana', 'Europe/London', 'Europe/Luxembourg', 'Europe/Madrid', 'Europe/Malta', 'Europe/Mariehamn', 'Europe/Minsk', 'Europe/Monaco', 'Europe/Moscow', 'Europe/Nicosia', 'Europe/Oslo', 'Europe/Paris', 'Europe/Podgorica', 'Europe/Prague', 'Europe/Riga', 'Europe/Rome', 'Europe/Samara', 'Europe/San_Marino', 'Europe/Sarajevo', 'Europe/Simferopol', 'Europe/Skopje', 'Europe/Sofia', 'Europe/Stockholm', 'Europe/Tallinn', 'Europe/Tirane', 'Europe/Tiraspol', 'Europe/Uzhgorod', 'Europe/Vaduz', 'Europe/Vatican', 'Europe/Vienna', 'Europe/Vilnius', 'Europe/Volgograd', 'Europe/Warsaw', 'Europe/Zagreb', 'Europe/Zaporozhye', 'Europe/Zurich', 'Indian/Antananarivo', 'Indian/Chagos', 'Indian/Christmas', 'Indian/Cocos', 'Indian/Comoro', 'Indian/Kerguelen', 'Indian/Mahe', 'Indian/Maldives', 'Indian/Mauritius', 'Indian/Mayotte', 'Indian/Reunion', 'Pacific/Apia', 'Pacific/Auckland', 'Pacific/Chatham', 'Pacific/Easter', 'Pacific/Efate', 'Pacific/Enderbury', 'Pacific/Fakaofo', 'Pacific/Fiji', 'Pacific/Funafuti', 'Pacific/Galapagos', 'Pacific/Gambier', 'Pacific/Guadalcanal', 'Pacific/Guam', 'Pacific/Honolulu', 'Pacific/Johnston', 'Pacific/Kiritimati', 'Pacific/Kosrae', 'Pacific/Kwajalein', 'Pacific/Majuro', 'Pacific/Marquesas', 'Pacific/Midway', 'Pacific/Nauru', 'Pacific/Niue', 'Pacific/Norfolk', 'Pacific/Noumea', 'Pacific/Pago_Pago', 'Pacific/Palau', 'Pacific/Pitcairn', 'Pacific/Ponape', 'Pacific/Port_Moresby', 'Pacific/Rarotonga', 'Pacific/Saipan', 'Pacific/Samoa', 'Pacific/Tahiti', 'Pacific/Tarawa', 'Pacific/Tongatapu', 'Pacific/Truk', 'Pacific/Wake', 'Pacific/Wallis', 'Pacific/Yap');

	// Get current timezone :
	$current_tz = @date_default_timezone_get();

	printf('<strong>' . $lang['welcome'] . '</strong>', $GLOBALS['nfedn']);
	?>
	<br />
	<br />
	<form method="post">
	<fieldset><legend>&nbsp;<b><?php echo $lang['region'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="5" cellspacing="0">
			<tr>
				<td width="55%" align="left"><br /><?php echo $lang['language'] ?><br />&nbsp;</td>
				<td width="45%" align="left"><br />
					<select name="admin_lang" class="input" style="width:250px">
					<?php
					foreach ($reg as $key => $value) {
						echo '<option value ="' . $key . '"';
						if ( $key == $tmp_lang ) {
							echo ' selected';
						}
						echo '>' . $value['language'] . ' - ' . $lang['by'] . ' ' . $value['author'] . '</option>';
					}
					?>
					</select><br />&nbsp;
				</td>
			</tr>
			<tr>
				<td width="55%" align="left" class="dotted"><br /><?php echo $lang['timezone'] ?><br />&nbsp;</td>
				<td width="45%" align="left" class="dotted"><br />
					<select name="timezone" class="input" style="width:250px">
					<?php
					foreach ($zonelist as $tz_place) {
						echo '<option value ="' . $tz_place . '"';
						if ($current_tz == $tz_place) { echo ' selected'; }
						date_default_timezone_set($tz_place);
						echo '>'. $tz_place .' (' .date('O'). ')</option>';
					}
					?>
					</select><br />&nbsp;
				</td>
			</tr>
		</table>
	</fieldset>
	<p><input type="submit" style="width:100px;" value="<?php echo $lang['next'] . ' &#187;' ?>" /></p>
	<input type="hidden" name="nfw_act" value="2" />
	</form>
	<?php

	install_footer();
}

/* ------------------------------------------------------------------ */

function nfw_system_requirements() {

	require (__DIR__ . '/lib/lang/' . $_SESSION['admin_lang'] . '/' . basename(__FILE__) );

	install_header('', '');
	$critical = 0;
	?>
	<form method="post">
	<fieldset><legend>&nbsp;<b><?php echo $lang['sysreq'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="2" cellspacing="0">
			<tr>
				<td width="45%" align="left"><br /><?php echo $lang['php_version'] ?><br />&nbsp;</td>
			<?php
			// We need at least PHP 5.3 :
			if ( version_compare( PHP_VERSION, '5.3.0', '<' ) ) {
				?>
				<td width="10%" align="center"><img src="static/icon_error.png" border="0" width="21" height="21"></td>
				<td width="45%" align="left"><?php printf( $lang['php_error'], PHP_VERSION) ?></td>
			</tr>
			<?php
			$critical = 1;
			} else {
			?>
				<td width="10%" align="center"><img src="static/icon_ok.png" border="0" width="21" height="21"></td>
				<td width="45%" align="left"><?php echo PHP_VERSION . ' (' . strtoupper(PHP_SAPI) . ')' ?></td>
			</tr>
			<?php
			}
			?>

			<tr>
				<td width="45%" align="left" class="dotted"><br /><?php echo $lang['php_os'] ?><br />&nbsp;</td>
			<?php
			// We don't do Windows :
			if ( PATH_SEPARATOR == ';' ) {
				?>
				<td width="10%" align="center" class="dotted"><img src="static/icon_error.png" border="0" width="21" height="21"></td>
				<td width="45%" align="left" class="dotted"><?php echo $lang['windows'] ?></td>
			</tr>
			<?php
			$critical = 1;
			} else {
			?>
				<td width="10%" align="center" class="dotted"><img src="static/icon_ok.png" border="0" width="21" height="21"></td>
				<td width="45%" align="left" class="dotted"><?php echo PHP_OS ?></td>
			</tr>
			<?php
			}
			?>

			<tr>
				<td width="45%" align="left" class="dotted"><br /><?php echo $lang['safemode'] ?><br />&nbsp;</td>
			<?php
			// Yes, there are still some people who have SAFE_MODE enabled with PHP 5.3+ !
			if ( ini_get('safe_mode')) {
				?>
				<td width="10%" align="center" class="dotted"><img src="static/icon_error.png" border="0" width="21" height="21"></td>
				<td width="45%" align="left" class="dotted"><?php echo $lang['safemode_error'] ?></td>
			</tr>
			<?php
			$critical = 1;
			} else {
			?>
				<td width="10%" align="center" class="dotted"><img src="static/icon_ok.png" border="0" width="21" height="21"></td>
				<td width="45%" align="left" class="dotted"><?php echo $lang['safemode_ok'] ?></td>
			</tr>
			<?php
			}
			?>

			<tr>
				<td width="45%" align="left" class="dotted"><br /><?php echo $lang['prepend'] ?><br />&nbsp;</td>
			<?php
			// Warn if auto_prepend_file is in used :
			if ( $tmp = ini_get('auto_prepend_file')) {
				?>
				<td width="10%" align="center" class="dotted"><img src="static/icon_warn.png" border="0" width="21" height="21"></td>
				<td width="45%" align="left" class="dotted"><?php printf( $lang['prepend_error'],  $tmp) ?></td>
			</tr>
			<?php
			} else {
			?>
				<td width="10%" align="center" class="dotted"><img src="static/icon_ok.png" border="0" width="21" height="21"></td>
				<td width="45%" align="left" class="dotted"><?php echo $lang['prepend_ok'] ?></td>
			</tr>
			<?php
			}
			?>

			<tr>
				<td width="45%" align="left" class="dotted"><br /><?php echo $lang['magic_quotes'] ?><br />&nbsp;</td>
			<?php
			// Deprecated as of PHP 5.3 :
			if (get_magic_quotes_gpc() ) {
				?>
				<td width="10%" align="center" class="dotted"><img src="static/icon_error.png" border="0" width="21" height="21"></td>
				<td width="45%" align="left" class="dotted"><?php echo $lang['magic_quotes_error'] ?></td>
			</tr>
			<?php
			$critical = 1;
			} else {
			?>
				<td width="10%" align="center" class="dotted"><img src="static/icon_ok.png" border="0" width="21" height="21"></td>
				<td width="45%" align="left" class="dotted"><?php echo $lang['magic_quotes_ok'] ?></td>
			</tr>
			<?php
			}
			?>

			<tr>
				<td width="45%" align="left" class="dotted"><br /><?php echo $lang['curl'] ?><br />&nbsp;</td>
			<?php
			// Needed for updates and license (we cancel the installation if not found) :
			if (! function_exists('curl_init') ) {
				?>
				<td width="10%" align="center" class="dotted"><img src="static/icon_error.png" border="0" width="21" height="21"></td>
				<td width="45%" align="left" class="dotted"><?php echo $lang['curl_error'] ?></td>
			</tr>
			<?php
			$critical = 1;
			} else {
			?>
				<td width="10%" align="center" class="dotted"><img src="static/icon_ok.png" border="0" width="21" height="21"></td>
				<td width="45%" align="left" class="dotted"><?php echo $lang['curl_ok'] ?></td>
			</tr>
			<?php
			}
			?>

			<tr>
				<td width="45%" align="left" class="dotted"><br /><?php echo $lang['ziparchive'] ?><br />&nbsp;</td>
			<?php
			// Needed to unpack updates (we only issue a warning, because NF can still work without it) :
			if (! class_exists('ZipArchive') ) {
				?>
				<td width="10%" align="center" class="dotted"><img src="static/icon_warn.png" border="0" width="21" height="21"></td>
				<td width="45%" align="left" class="dotted"><?php echo $lang['ziparchive_error'] ?></td>
			</tr>
			<?php
			} else {
			?>
				<td width="10%" align="center" class="dotted"><img src="static/icon_ok.png" border="0" width="21" height="21"></td>
				<td width="45%" align="left" class="dotted"><?php echo $lang['ziparchive_ok'] ?></td>
			</tr>
			<?php
			}
			?>

			<tr>
				<td width="45%" align="left" class="dotted"><br /><?php echo $lang['confdir'] ?><br />&nbsp;</td>
			<?php
			// Configuration directory must be writable :
			if (! is_writable('./conf/') ) {
				?>
				<td width="10%" align="center" class="dotted"><img src="static/icon_error.png" border="0" width="21" height="21"></td>
				<td width="45%" align="left" class="dotted"><?php echo $lang['confdir_error'] ?></td>
			</tr>
			<?php
			$critical = 1;
			} else {
			?>
				<td width="10%" align="center" class="dotted"><img src="static/icon_ok.png" border="0" width="21" height="21"></td>
				<td width="45%" align="left" class="dotted"><?php echo $lang['confdir_ok'] ?></td>
			</tr>
			<?php
			}
			?>

			<tr>
				<td width="45%" align="left" class="dotted"><br /><?php echo $lang['logdir'] ?><br />&nbsp;</td>
			<?php
			// Log directory must be writable :
			if (! is_writable('./nfwlog/') ) {
				?>
				<td width="10%" align="center" class="dotted"><img src="static/icon_error.png" border="0" width="21" height="21"></td>
				<td width="45%" align="left" class="dotted"><?php echo $lang['logdir_error'] ?></td>
			</tr>
			<?php
			$critical = 1;
			} else {
			?>
				<td width="10%" align="center" class="dotted"><img src="static/icon_ok.png" border="0" width="21" height="21"></td>
				<td width="45%" align="left" class="dotted"><?php echo $lang['logdir_ok'] ?></td>
			</tr>
			<?php
			}
			?>
		</table>
	</fieldset>
	<?php
	if (! $critical ) {
	?>
		<p><input type="submit" style="width:100px;" value="<?php echo $lang['next'] . ' &#187;' ?>" /></p>
		<input type="hidden" name="nfw_act" value="3" />
	<?php
	} else {
	?>
		<br />
		<br />
		<div class="error"><p><?php echo $lang['aborting'] ?></p></div>
		<br />
	<?php
		// clear installation flag :
		$_SESSION['nfw_install'] = '';
	}
	?>
	</form>
	<?php

	install_footer();
}

/* ------------------------------------------------------------------ */

function nfw_changelog() {

	require (__DIR__ . '/lib/lang/' . $_SESSION['admin_lang'] . '/' . basename(__FILE__) );
	install_header('', '');
	require(__DIR__ . '/changelog.php');
	?>
	<form method="post">
	<fieldset><legend>&nbsp;<b><?php echo $lang['notes'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="2" cellspacing="0">
			<tr>
				<td width="100%" align="center"><textarea name="txtlog" style="background-color:#ffffff;width:95%;height:300px;border:1px solid #FDCD25;padding:4px;font-family:monospace;font-size:13px;"><?php echo htmlspecialchars($changelog) ?></textarea></td>
			</tr>
		</table>
	</fieldset>
	<p><input type="submit" style="width:100px;" value="<?php echo $lang['next'] . ' &#187;' ?>" /></p>
	<input type="hidden" name="nfw_act" value="4" />
	</form>
	<?php
	install_footer();
}

/* ------------------------------------------------------------------ */

function nfw_license() {

	require (__DIR__ . '/lib/lang/' . $_SESSION['admin_lang'] . '/' . basename(__FILE__) );

	if (! $license = @file_get_contents(__DIR__ . '/license.txt') ) {
		_error('NinjaFirewall cannot find the <code>license.txt</code> file.<br />Please ensure your package is not corrupted, or <a href="http://ninjafirewall.com/" class="links" style="border-bottom:dotted 1px #FDCD25;">download NinjaFirewall from the official website</a>.');
		exit;
	}

	install_header('', '');
	?>
	<script>function eula(){if (!document.install.eul.checked){alert("<?php echo $lang['license_accept'] ?>");document.install.eul.focus();return false;}}</script>
	<form method="post" name=install onsubmit="return eula();">
	<fieldset><legend>&nbsp;<b><?php echo $lang['license'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="2" cellspacing="0">
			<tr>
				<td width="100%" align="center"><textarea name="txtlog" style="background-color:#ffffff;width:95%;height:300px;border:1px solid #FDCD25;padding:4px;font-family:monospace;font-size:13px;"><?php echo $license ?></textarea></td>
			</tr>
		</table>
	</fieldset>
	<p><label><input type=checkbox name=eul>&nbsp;<?php echo $lang['license_checkbox'] ?></label></p>
	<p><input type="submit" style="width:100px;" value="<?php echo $lang['next'] . ' &#187;' ?>" /></p>
	<input type="hidden" name="nfw_act" value="5" />
	</form>
	<?php
	install_footer();
}

/* ------------------------------------------------------------------ */

function nfw_admin_setup( $err ) {

	require(__DIR__ . '/lib/lang/' . $_SESSION['admin_lang'] . '/' . basename(__FILE__) );
	install_header('', '');

	$admin_name = $admin_pass = $admin_pass2 = $admin_email = $lic = '';
	if (! empty($_SESSION['admin_name']) ) {
		$admin_name  = $_SESSION['admin_name'];
	}
	if (! empty($_SESSION['admin_pass']) ) {
		$admin_pass  = $_SESSION['admin_pass'];
	}
	if (! empty($_SESSION['admin_pass2']) ) {
		$admin_pass2  = $_SESSION['admin_pass2'];
	}
	if (! empty($_SESSION['admin_email']) ) {
		$admin_email  = $_SESSION['admin_email'];
	}
	if (! empty($_SESSION['lic']) ) {
		$lic  = $_SESSION['lic'];
	}
	?>
	<script>
	function check_fields() {
		if (! document.admin_form.admin_name.value) {
			alert("<?php echo $lang['js_admin_name'] ?>");
			document.admin_form.admin_name.focus();
			return false;
		}
		if (!document.admin_form.admin_name.value.match(/^\w{6,20}$/)) {
			alert("<?php echo $lang['js_admin_name_char'] ?>");
			document.admin_form.admin_name.focus();
			return false;
		}
		if (! document.admin_form.admin_pass.value) {
			alert("<?php echo $lang['js_admin_pass'] ?>");
			document.admin_form.admin_pass.focus();
			return false;
		}
		if (!document.admin_form.admin_pass.value.match(/^.{6,20}$/)) {
			alert("<?php echo $lang['js_admin_pass_char'] ?>");
			document.admin_form.admin_pass.focus();
			return false;
		}
		if (! document.admin_form.admin_pass2.value) {
			alert("<?php echo $lang['js_admin_pass_2'] ?>");
			document.admin_form.admin_pass2.focus();
			return false;
		}
		if (document.admin_form.admin_pass.value != document.admin_form.admin_pass2.value) {
			alert("<?php echo $lang['js_admin_pass_both'] ?>");
			document.admin_form.admin_pass2.select();
			return false;
		}
		if (! document.admin_form.admin_email.value) {
			alert("<?php echo $lang['js_admin_email'] ?>");
			document.admin_form.admin_email.focus();
			return false;
		}
		if (! document.admin_form.lic.value) {
			alert("<?php echo $lang['js_license'] ?>");
			document.admin_form.lic.focus();
			return false;
		}
		return true;
	}
	</script>

	<?php
	if ($err) {
		echo '<div class="error"><p>' . $err . '</p></div><br />';
	}
	?>

	<form method="post" name="admin_form" onSubmit="return check_fields();">
	<fieldset><legend>&nbsp;<b><?php echo $lang['account'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="2" cellspacing="0">
			<tr>
				<td width="55%" align="left"><br /><?php echo $lang['admin_name'] ?><br />&nbsp;</td>
				<td width="45%" align="left">
					<input class="input" size="20" maxlength="20" name="admin_name" type="text" value="<?php echo $admin_name ?>">
				</td>
			</tr>

			<tr>
				<td width="55%" align="left" class="dotted"><br /><?php echo $lang['admin_pass'] ?><br />&nbsp;</td>
				<td width="45%" align="left" class="dotted">
					<input class="input" size="20" maxlength="20" name="admin_pass" type="password" value="<?php echo $admin_pass ?>">
				</td>
			</tr>

			<tr>
				<td width="55%" align="left" class="dotted"><br /><?php echo $lang['admin_pass2'] ?><br />&nbsp;</td>
				<td width="45%" align="left" class="dotted">
					<input class="input" size="20" maxlength="20" name="admin_pass2" type="password" value="<?php echo $admin_pass2 ?>">
				</td>
			</tr>

			<tr>
				<td width="55%" align="left" class="dotted"><br /><?php echo $lang['admin_email'] ?><br />&nbsp;</td>
				<td width="45%" align="left" class="dotted">
					<input class="input" size="20" maxlength="500" name="admin_email" type="text" value="<?php echo $admin_email ?>">
				</td>
			</tr>

			<?php if (NFW_EDN == 2) { ?>
			<tr>
				<td width="55%" align="left" class="dotted"><br /><?php echo $lang['nf_license'] ?><br />&nbsp;</td>
				<td width="45%" align="left" class="dotted">
					<input class="input" maxlength="500" size="40" name="lic" type="text" value="<?php echo $lic ?>">
				</td>
			</tr>
			<?php } ?>

		</table>
	</fieldset>
	<p><input type="submit" style="width:100px;" value="<?php echo $lang['next'] . ' &#187;' ?>" /></p>
	<input type="hidden" name="nfw_act" value="5" />
	<input type="hidden" name="save" value="1" />
	</form>
	<?php
	install_footer();

}

/* ------------------------------------------------------------------ */

function nfw_admin_setup_save() {

	require_once(__DIR__ . '/lib/lang/' . $_SESSION['admin_lang'] . '/' . basename(__FILE__) );

	// Fetch default configuration :
	$nfw_options = fw_conf_options();

	$error_msg = 0;
	$_SESSION['admin_name']  = @$_POST['admin_name'];
	$_SESSION['admin_pass']  = @$_POST['admin_pass'];
	$_SESSION['admin_pass2'] = @$_POST['admin_pass2'];
	$_SESSION['admin_email'] = @$_POST['admin_email'];
	$_SESSION['lic']         = @$_POST['lic'];

	if ( empty($_POST['admin_name']) || ! preg_match('/^\w{6,20}$/', $_POST['admin_name']) ) {
		$error_msg = $lang['js_admin_name_char'];
		goto ADMIN_SAVE_END;
	} else {
		$nfw_options['admin_name'] = $_POST['admin_name'];
	}

	if ( empty($_POST['admin_pass']) || ! preg_match('/^.{6,20}$/', $_POST['admin_pass']) ) {
		$error_msg = $lang['js_admin_pass_char'];
		goto ADMIN_SAVE_END;
	} else {
		$nfw_options['admin_pass'] = sha1($_POST['admin_pass']);
	}

	if ( empty($_POST['admin_email']) || ! filter_var( $_POST['admin_email'], FILTER_VALIDATE_EMAIL ) ) {
		$error_msg = $lang['js_admin_email'];
		goto ADMIN_SAVE_END;
	} else {
		$nfw_options['admin_email'] = $_POST['admin_email'];
	}

	if ( NFW_EDN != 2 ) {
		goto ADMIN_SAVE_END;
	}

	if ( empty($_POST['lic']) ) {
		$error_msg = $lang['js_license'];
		goto ADMIN_SAVE_END;
	}
	$_SESSION['lic'] = $_POST['lic'];
	$error_msg = '';
	$domain = strtolower( $_SERVER['SERVER_NAME'] );
	$data  = 'action=checklicense';
	$data .= '&host=' . urlencode( $domain );
	$data .= '&lic=' . urlencode( $_POST['lic'] );
	$data .= '&ver=' . urlencode( NFW_ENGINE_VERSION );

	$nfw_res = account_license_connect( $data );
	// cURL error ?
	if (! empty($nfw_res['curl']) ) {
		if ($nfw_res['curl'] == 1) {
			$error_msg = $lang['curl_connect'];
		} elseif ($nfw_res['curl'] == 2) {
			$error_msg = $lang['curl_retcode'];
		} elseif ($nfw_res['curl'] == 3) {
			$error_msg = $lang['curl_empty'];
		} elseif ($nfw_res['curl'] == 4) {
			$error_msg = $lang['curl_wrong'];
		}
		goto ADMIN_SAVE_END;
	}
	// Parse results :
	if ( preg_match('/^\d{4}-\d{2}-\d{2}$/', $nfw_res['exp']) ) {
		$nfw_options['lic'] = $_POST['lic'];
		$nfw_options['lic_exp'] = $nfw_res['exp'];
	} elseif (! empty($nfw_res['err']) ) {
		$error_msg = sprintf( $lang['invalid_lic'], $nfw_res['err']);
	} else {
		$error_msg = $lang['err_server'];
	}

ADMIN_SAVE_END:

	if ( $error_msg ) {
		nfw_admin_setup($error_msg);
		exit;
	}

	// save conf and rules
	$nfw_options['timezone'] = $_SESSION['timezone'];
	$nfw_options['admin_lang'] = $_SESSION['admin_lang'];

	$nfw_rules = fw_conf_rules();

	// Add the DOCUMENT_ROOT :
	if ( strlen($_SERVER['DOCUMENT_ROOT']) > 5 ) {
		$nfw_rules[NFW_DOC_ROOT]['what'] = $_SERVER['DOCUMENT_ROOT'];
	} elseif ( strlen( getenv('DOCUMENT_ROOT') ) > 5 ) {
		$nfw_rules[NFW_DOC_ROOT]['what'] = getenv( 'DOCUMENT_ROOT' );
	} else {
		$nfw_rules[NFW_DOC_ROOT]['on']  = 0;
	}

	// Clear the rules for bad bots/UA if we are running the Pro+ edn,
	// because this will be managed by the Access Control menu :
	if (NFW_EDN == 2) {
		unset($nfw_rules[531]);
	}

	// Save options....
	if (! $fh = fopen(__DIR__ . '/conf/options.php', 'w') ) {
		nfw_admin_setup( $lang['error_conf'] );
		exit;
	}
	fwrite($fh, '<?php' . "\n\$nfw_options = <<<'EOT'\n" . serialize( $nfw_options ) . "\nEOT;\n" );
	fclose($fh);

	// ...and rules :
	if (! $fh = fopen(__DIR__ . '/conf/rules.php', 'w') ) {
		nfw_admin_setup( $lang['error_rules'] );
		// Delete options file :
		unlink('./conf/options.php');
		exit;
	}
	fwrite($fh, '<?php' . "\n\$nfw_rules = <<<'EOT'\n" . serialize( $nfw_rules ) . "\nEOT;\n" );
	fclose($fh);

	unset($_SESSION['admin_pass']);
	unset($_SESSION['admin_pass2']);
	unset($_SESSION['lic']);

	// OK
	return;
}

/* ------------------------------------------------------------------ */

function nfw_integration( $err ) {

	require(__DIR__ . '/lib/lang/' . $_SESSION['admin_lang'] . '/' . basename(__FILE__) );

	// Let's try to detect the system configuration :
	$s1 = $s2 = $s3 = $s4 = $s5 = $s7 = '';
	if ( defined('HHVM_VERSION') ) {
		// HHVM
		$http_server = 7;
		$s7 = $lang['recommended'];
		$htaccess = 0;
		$php_ini = 0;
	} elseif ( preg_match('/apache/i', PHP_SAPI) ) {
		// Apache running mod_php5/7 :
		$http_server = 1;
		$s1 = $lang['recommended'];
		$htaccess = 1;
		$php_ini = 0;
	} elseif ( preg_match( '/litespeed/i', PHP_SAPI ) ) {
		// Because Litespeed can handle PHP INI and mod_php-like .htaccess,
		// we will create both of them as we have no idea which one should be used:
		$http_server = 4;
		$php_ini = 1;
		$htaccess = 1;
		$s4 = $lang['recommended'];
	} else {
		// PHP CGI: we will only require a PHP INI file:
		$php_ini = 1;
		$htaccess = 0;
		// Try to find out the HTTP server :
		if ( preg_match('/apache/i', $_SERVER['SERVER_SOFTWARE']) ) {
			$http_server = 2;
			$s2 = $lang['recommended'];
		} elseif ( preg_match('/nginx/i', $_SERVER['SERVER_SOFTWARE']) ) {
			$http_server = 3;
			$s3 = $lang['recommended'];
		} else {
			// Mark it as unknown, that is not important :
			$http_server = 5;
			$s5 = $lang['recommended'];
		}
	}

	// By default, NinjaFirewall will protect the directory above its installation folder :
	if (! empty($_SESSION['document_root'])) {
		$document_root = $_SESSION['document_root'];
	} else {
		$document_root  = dirname( __DIR__ );
	}

	install_header('', '');

	?>
	<script>
	function check_fields() {
		if (! document.integration_form.document_root.value) {
			alert('<?php echo $lang['js_docroot'] ?>');
			document.integration_form.document_root.focus();
			return false;
		}
		var ischecked = 0;
		for (var i = 0; i < document.integration_form.php_ini_type.length; i++) {
			if(document.integration_form.php_ini_type[i].checked) {
				ischecked = 1;
				break;
			}
		}
		// Dont warn if user selected Apache/mod_php5/7 or HHVM
		if (! ischecked && document.integration_form.http_server.value != 1 && document.integration_form.http_server.value != 7 ) {
			alert('<?php echo $lang['js_phpini'] ?>');
			return false;
		}
		return true;
	}
	function ini_toogle(what) {
		if (what == 1) {
			document.getElementById('trini').style.display = 'none';
			document.getElementById('hhvm').style.display = 'none';
		} else if(what == 7) {
			document.getElementById('trini').style.display = 'none';
			document.getElementById('hhvm').style.display = '';
		} else {
			document.getElementById('trini').style.display = '';
			document.getElementById('hhvm').style.display = 'none';
		}
	}
	</script>

	<?php
	if ($err) {
		echo '<div class="error"><p>' . $err . '</p></div><br />';
	}
	?>

	<form method="post" name="integration_form" onSubmit="return check_fields();">
	<fieldset><legend>&nbsp;<b><?php echo $lang['integration'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="2" cellspacing="0">
			<tr>
				<td width="45%" align="left">
					<p><?php printf( $lang['docroot'], dirname( __DIR__ ) ) ?></p>
				</td>
				<td width="10%">&nbsp;</td>
				<td width="45%" align="left">
					<p><input class="input" size="40" name="document_root" type="text" value="<?php echo $document_root ?>"><p>
				</td>
			</tr>
			<tr>
				<td width="45%" align="left" class="dotted"><br /><?php echo $lang['httpsapi'] ?><br />&nbsp;</td>
				<td width="10%" class="dotted">&nbsp;</td>
				<td width="45%" align="left" class="dotted">
					<select class="input" name="http_server" onchange="ini_toogle(this.value);">
						<option value="1"<?php _selected($http_server, 1) ?>>Apache + PHP module<?php echo $s1 ?></option>
						<option value="2"<?php _selected($http_server, 2) ?>>Apache + CGI/FastCGI<?php echo $s2 ?></option>
						<option value="6"<?php _selected($http_server, 6) ?>>Apache + suPHP</option>
						<option value="3"<?php _selected($http_server, 3) ?>>Nginx + CGI/FastCGI<?php echo $s3 ?></option>
						<option value="4"<?php _selected($http_server, 4) ?>>Litespeed + LSAPI<?php echo $s4 ?></option>
						<option value="5"<?php _selected($http_server, 5) ?>><?php echo $lang['other'] . ' + CGI/FastCGI' . $s5 ?></option>
						<option value="7"<?php _selected($http_server, 7) ?>><?php echo $lang['other'] . ' + HHVM' . $s7 ?></option>
					</select>&nbsp;&nbsp;&nbsp;<a class="links" href="javascript:popup('?nfw_act=99',640,500,0);" style="border-bottom:dotted 1px #FDCD25;"><?php echo $lang['phpinfo'] ?></a>
					<?php
					if ($http_server == 7) {
						echo '<p id="hhvm">';
					} else {
						echo '<p id="hhvm" style="display:none;">';
					}
					?>
					<a href="http://blog.nintechnet.com/installing-ninjafirewall-with-hhvm-hiphop-virtual-machine/" target="_blank" class="links" style="border-bottom:dotted 1px #FDCD25;"><?php echo $lang['hhvm_doc'] ?></a></p>
				</td>
			</tr>
			<?php
			// We check in the document root if there is already a PHP INI file :
			$f1 = $f2 = $f3 = $php_ini_type = '';
			if ( file_exists( dirname( __DIR__ ) . '/php.ini') ) {
				if (empty($_SESSION['php_ini_type']) ) {
					$f1 = $lang['recommended'];
				}
				$php_ini_type = 1;
			} elseif ( file_exists( dirname( __DIR__ ) . '/.user.ini') ) {
				if (empty($_SESSION['php_ini_type']) ) {
					$f2 = $lang['recommended'];
				}
				$php_ini_type = 2;
			} elseif ( file_exists( dirname( __DIR__ ) . '/php5.ini') ) {
				if (empty($_SESSION['php_ini_type']) ) {
					$f3 = $lang['recommended'];
				}
				$php_ini_type = 3;
			}
			if ($http_server == 1 || $http_server == 7) {
				// We don't need PHP INI if the server is running Apache/mod_php5/7 or HHVM :
				echo '<tr id="trini" style="display:none;">';
			} else {
				echo '<tr id="trini">';
			}
			?>
				<td width="45%" align="left" class="dotted" style="vertical-align:top;"><br /><?php echo $lang['phpini'] ?><br />&nbsp;</td>
				<td width="10%" class="dotted">&nbsp;</td>
				<td width="45%" align="left" class="dotted">
					<p><label><input type="radio" name="php_ini_type" value="1"<?php _checked($php_ini_type, 1) ?>>&nbsp;<code>php.ini</code></label><?php echo $f1 ?><br /><i class="tinyblack">&nbsp;<?php echo $lang['ini_1'] ?>.</i></p>
					<p><label><input type="radio" name="php_ini_type" value="2"<?php _checked($php_ini_type, 2) ?>>&nbsp;<code>.user.ini</code></label><?php echo $f2 ?><br /><i class="tinyblack">&nbsp;<?php echo $lang['ini_2'] ?> (<a href="http://php.net/manual/en/configuration.file.per-user.php" class="links" style="border-bottom:dotted 1px #FDCD25;"><?php echo $lang['more_info'] ?></a>).</i></p>
					<p><label><input type="radio" name="php_ini_type" value="3"<?php _checked($php_ini_type, 3) ?>>&nbsp;<code>php5.ini</code></label><?php echo $f3 ?><br /><i class="tinyblack">&nbsp;<?php echo $lang['ini_3'] ?>.</i></p>
				</td>
			</tr>
		</table>
	</fieldset>
	<p><input type="submit" style="width:100px;" value="<?php echo $lang['next'] . ' &#187;' ?>" /></p>
	<input type="hidden" name="nfw_act" value="6" />
	</form>
	<?php
	install_footer();
	exit;
}


/* ------------------------------------------------------------------ */

function nfw_integration_save() {

	require(__DIR__ . '/lib/lang/' . $_SESSION['admin_lang'] . '/' . basename(__FILE__) );

	$error_msg = 0;

	// Directory to monitor :
	$_SESSION['document_root'] = @$_POST['document_root'];
	if (empty($_SESSION['document_root']) ) {
		$error_msg = $lang['js_docroot'];
		goto INTEGRATION_SAVE_END;
	}
	if (! is_dir($_SESSION['document_root']) ) {
		$error_msg = sprintf( $lang['invalid_docroot'], $_SESSION['document_root']);
		goto INTEGRATION_SAVE_END;
	}

	// NinjaFirewall must be installed in that directory :
	if ( strpos(__FILE__, $_SESSION['document_root']) === FALSE ) {
		$error_msg = $lang['ninja_docroot'];
		goto INTEGRATION_SAVE_END;
	}

	$_SESSION['document_root'] = rtrim($_SESSION['document_root'],"/");

	// HTTP server type:
	// 1: Apache + PHP5 module
	// 2: Apache + CGI/FastCGI
	// 3: Nginx
	// 4: Litespeed (either LSAPI or Apache-style configuration directives (php_value)
	// 5: Other + CGI/FastCGI
	// 6: Apache + suPHP
	// 7: Other + HHVM
	if ( empty($_POST['http_server']) || ! preg_match('/^[1-7]$/', $_POST['http_server']) ) {
		$error_msg = $lang['httpsapi'];
		goto INTEGRATION_SAVE_END;
	}

	// We must have a PHP INI type, except if the server is running Apache/mod_php5/7 or HHVM:
	if ( preg_match('/^[2-6]$/', $_POST['http_server']) ) {
		if ( empty($_POST['php_ini_type']) || ! preg_match('/^[1-3]$/', $_POST['php_ini_type']) ) {
			$error_msg = $lang['phpini'];
			goto INTEGRATION_SAVE_END;
		}
	} else {
		$_POST['php_ini_type'] = 0;
	}
	$_SESSION['http_server'] = $_POST['http_server'];
	$_SESSION['php_ini_type'] = $_POST['php_ini_type'];

INTEGRATION_SAVE_END:

	if ( $error_msg ) {
		nfw_integration($error_msg);
		exit;
	}
}

/* ------------------------------------------------------------------ */

function nfw_activation() {

	require(__DIR__ . '/lib/lang/' . $_SESSION['admin_lang'] . '/' . basename(__FILE__) );


	if ( empty($_SESSION['http_server']) || empty($_SESSION['document_root']) ) {
		nfw_integration('Error #122');
		exit;
	}
	if ( empty($_SESSION['php_ini_type']) && preg_match('/^[2-6]$/', $_POST['http_server']) ) {
		nfw_integration('Error #123');
		exit;
	}

	install_header('', '');

	if ($_SESSION['php_ini_type'] == 1) {
		$php_file = 'php.ini';
	} elseif ($_SESSION['php_ini_type'] == 2) {
		$php_file = '.user.ini';
	} elseif ($_SESSION['php_ini_type'] == 3) {
		$php_file = 'php5.ini';
	} else {
		$php_file = 0;
	}

	?>
	<fieldset><legend>&nbsp;<b><?php echo $lang['activation'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="2" cellspacing="0">
			<tr>
				<td width="100%" valign="top">
			<?php
			$fdata = $height = '';
			$bullet = '<img src="static/bullet_on.gif" border="0">&nbsp;';
			// Apache mod_php5/7 : only .htaccess changes are required :
			if ($_SESSION['http_server'] == 1) {
				echo '<p>' . sprintf( $lang['single_file'], '.htaccess') . '</p>';
				if ( file_exists($_SESSION['document_root'] . '/.htaccess') ) {
					// Edit it :
					printf($bullet . $lang['edit_file'], $_SESSION['document_root'] . '/.htaccess');
					$color_start = '<font color="red">'; $color_end = '</font>';
					$fdata = "\n<font color='#aaa'>" . htmlentities( file_get_contents($_SESSION['document_root'] . '/.htaccess') ) . '</font>';
					$height = 'height:200px;';
				} else {
					// Create it :
					printf($bullet . $lang['create_file'], $_SESSION['document_root'] . '/.htaccess');
					$color_start = $color_end = '';
				}
				echo '<br /><br /><center><pre style="background-color:#FAFAFA;border:1px solid #FDCD25;margin:0px;padding:6px;overflow:auto;width:90%;text-align:left;' . $height . '">' . "\n" .
				$color_start . '# BEGIN NinjaFirewall' . "\n" .
				'&lt;IfModule mod_php' . PHP_MAJOR_VERSION . '.c&gt;' . "\n" .
				'   php_value auto_prepend_file ' . __DIR__ . '/firewall.php' . "\n" .
				'&lt;/IfModule&gt;' . "\n" .
				'# END NinjaFirewall' . "\n" .
				$color_end . $fdata . "\n" .
				'</pre></center><br />';


			// Litespeed : we create both INI and a .htaccess files as we have
			// no way to know which one will be used :
			} elseif ($_SESSION['http_server'] == 4) {
				echo '<p>' . sprintf( $lang['multi_files'], '.htaccess', $php_file) . '</p>';

				if ( file_exists($_SESSION['document_root'] . '/.htaccess') ) {
					// Edit it :
					printf($bullet . $lang['edit_file'], $_SESSION['document_root'] . '/.htaccess');
					$color_start = '<font color="red">'; $color_end = '</font>';
					$fdata = "\n<font color='#aaa'>" . htmlentities( file_get_contents($_SESSION['document_root'] . '/.htaccess') ) . '</font>';
					$height = 'height:200px;';
				} else {
					// Create it :
					printf($bullet . $lang['create_file'], $_SESSION['document_root'] . '/.htaccess');
					$color_start = $color_end = '';
				}
				echo '<br /><br /><center><pre style="background-color:#FAFAFA;border:1px solid #FDCD25;margin:0px;padding:6px;overflow:auto;width:90%;text-align:left;' . $height . '">' . "\n" .
				$color_start . '# BEGIN NinjaFirewall' . "\n" .
				'php_value auto_prepend_file ' . __DIR__ . '/firewall.php' . "\n" .
				'# END NinjaFirewall' . "\n" .
				$color_end . $fdata . "\n" .
				'</pre></center><br />';

				echo '<br /><br />';

				$fdata = $height = '';
				if ( file_exists($_SESSION['document_root'] . '/' . $php_file) ) {
					// Edit it :
					printf($bullet . $lang['edit_file'], $_SESSION['document_root'] . '/' . $php_file);
					$color_start = '<font color="red">'; $color_end = '</font>';
					$fdata = "\n<font color='#aaa'>" . htmlentities( file_get_contents($_SESSION['document_root'] . '/' . $php_file) ) . '</font>';
					$height = 'height:200px;';
				} else {
					// Create it :
					printf($bullet . $lang['create_file'], $_SESSION['document_root'] . '/' . $php_file);
					$color_start = $color_end = '';
				}

				echo '<br /><br /><center><pre style="background-color:#FAFAFA;border:1px solid #FDCD25;margin:0px;padding:6px;overflow:auto;width:90%;text-align:left;' . $height . '">' . "\n" .
				$color_start . '; BEGIN NinjaFirewall' . "\n" .
				'auto_prepend_file = ' . __DIR__ . '/firewall.php' . "\n" .
				'; END NinjaFirewall' . "\n" .
				$color_end . $fdata . "\n" .
				'</pre></center><br />';

			// HHVM
			} elseif ($_SESSION['http_server'] == 7) {
				echo '<p><a href="http://blog.nintechnet.com/installing-ninjafirewall-with-hhvm-hiphop-virtual-machine/" target="_blank" class="links" style="border-bottom:dotted 1px #FDCD25;">' . $lang['hhvm_doc'] .'</a></p>' . $bullet . 'Add the following code to your <code>/etc/hhvm/php.ini</code> file, and restart HHVM afterwards:
				<br /><br />
				<pre style="background-color:#FFF;border:1px solid #ccc;margin:0px;padding:6px;overflow:auto;height:50px;"><font color="red">auto_prepend_file = ' . __DIR__ . '/firewall.php</font></pre>
				<br />';

			// Other servers (nginx etc) :
			} else {

				// Apache + suPHP : we create both INI and .htaccess files as we need
				// to add the suPHP_ConfigPath directive (otherwise the INI will not
				// apply recursively) :
				if ($_SESSION['http_server'] == 6) {
					echo '<p>' . sprintf( $lang['multi_files'], '.htaccess', $php_file) . '</p>';
					if ( file_exists($_SESSION['document_root'] . '/.htaccess') ) {
						// Edit it :
						printf($bullet . $lang['edit_file'], $_SESSION['document_root'] . '/.htaccess');
						$color_start = '<font color="red">'; $color_end = '</font>';
						$fdata = "\n<font color='#aaa'>" . htmlentities( file_get_contents($_SESSION['document_root'] . '/.htaccess') ) . '</font>';
						$height = 'height:200px;';
					} else {
						// Create it :
						printf($bullet . $lang['create_file'], $_SESSION['document_root'] . '/.htaccess');
						$color_start = $color_end = '';
					}
					echo '<br /><br /><center><pre style="background-color:#FAFAFA;border:1px solid #FDCD25;margin:0px;padding:6px;overflow:auto;width:90%;text-align:left;' . $height . '">' . "\n" .
					$color_start . '# BEGIN NinjaFirewall' . "\n" .
					'&lt;IfModule mod_suphp.c&gt;' . "\n" .
					'   suPHP_ConfigPath ' . rtrim($_SESSION['document_root'], '/') . "\n" .
					'&lt;/IfModule&gt;' . "\n" .
					'# END NinjaFirewall' . "\n" .
					$color_end . $fdata . "\n" .
					'</pre></center><br />';
					echo '<br /><br />';
					$fdata = $height = '';
				// Apache + suPHP
				} else {
					echo '<p>' . sprintf( $lang['single_file'], $php_file) . '</p>';
				}
				if ( file_exists($_SESSION['document_root'] . '/' . $php_file) ) {
					// Edit it :
					printf($bullet . $lang['edit_file'], $_SESSION['document_root'] . '/' . $php_file);
					$color_start = '<font color="red">'; $color_end = '</font>';
					$fdata = "\n<font color='#aaa'>" . htmlentities( file_get_contents($_SESSION['document_root'] . '/' . $php_file) ) . '</font>';
					$height = 'height:200px;';
				} else {
					// Create it :
					printf($bullet . $lang['create_file'], $_SESSION['document_root'] . '/' . $php_file);
					$color_start = $color_end = '';
				}

				echo '<br /><br /><center><pre style="background-color:#FAFAFA;border:1px solid #FDCD25;margin:0px;padding:6px;overflow:auto;width:90%;text-align:left;' . $height . '">' . "\n" .
				$color_start . '; BEGIN NinjaFirewall' . "\n" .
				'auto_prepend_file = ' . __DIR__ . '/firewall.php' . "\n" .
				'; END NinjaFirewall' . "\n" .
				$color_end . $fdata . "\n" .
				'</pre></center><br />';
			}
			?>
					<p><?php echo $lang['test_it'] ?></p>
				</td>
			</tr>
		</table>
	</fieldset>
	<p><input type="button" value="<?php echo $lang['test_button'] ?> &#187" name="test_nfw" onclick="popup('?nfw_act=98',550,450,0)" /></p>

	<?php
	install_footer();
	exit;
}

/* ------------------------------------------------------------------ */

function nfw_activation_test() {

	global $err_fw;

	require(__DIR__ . '/lib/lang/' . $_SESSION['admin_lang'] . '/' . basename(__FILE__) );

	if ($_SESSION['php_ini_type'] == 1) {
		$php_file = 'php.ini';
	} elseif ($_SESSION['php_ini_type'] == 2) {
		$php_file = '.user.ini';
	} else {
		$php_file = 'php5.ini';
	}

	?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link href="static/styles.css" rel="stylesheet" type="text/css">
		<script>
		function goback() {
			window.opener.location.href = '?nfw_act=5&nfw_test=1';
			window.close();
		}
		</script>
	</head>
	<body bgcolor="white" class="smallblack">
		<fieldset><legend>&nbsp;<b>NinjaFirewall</b>&nbsp;</legend>
		<table border="0" width="100%"><tr><td style="vertical-align:middle" class="smallblack">
	<?php
	if (! defined('NFW_STATUS') ) {
		// The firewall is not loaded :
		?>
		<center>
			<h3><img src="static/icon_error.png" width="21" height="21" border="0">&nbsp;<?php echo $lang['failed'] ?></h3>
			<p><strong><?php echo $lang['not_loaded'] ?></strong></p>
		</center>
		<p><?php echo $lang['suggestions'] ?></p>

		<?php
		if ($_SESSION['http_server'] == 1) {
			// User choosed Apache/mod_php instead of CGI/FCGI:
			?>
			<p><img src="static/bullet_on.gif" border="0">&nbsp;<?php echo $lang['modphp5tocgi'] ?> <a style="border-bottom:dotted 1px #FDCD25;" class="links" onClick="goback();"><?php echo $lang['goback_01'] ?></a>.</p>
		<?php
		} else {
			// Very likely a PHP INI issue :
			if ($_SESSION['php_ini_type'] == 2) {
				?>
				<p><img src="static/bullet_on.gif" border="0">&nbsp;<?php echo $lang['userini_select'] ?></p>
				<?php
			}
			if ($_SESSION['http_server'] == 2) {
				// User choosed Apache/CGI instead of mod_php:
				?>
				<p><img src="static/bullet_on.gif" border="0">&nbsp;<?php echo $lang['cgitomodphp5'] ?> <a style="border-bottom:dotted 1px #FDCD25;" class="links" onClick="goback();"><?php echo $lang['goback_01'] ?></a>.</p>
				<?php
			}
			?>
			<p><img src="static/bullet_on.gif" border="0">&nbsp;<?php echo $lang['wrong_ini'] ?> <a style="border-bottom:dotted 1px #FDCD25;" class="links" onClick="goback();"><?php echo $lang['goback_02'] ?></a>.</p>
		<?php
		}
		?>
		</td></tr></table>
		<p style="text-align:center"><strong><?php echo $lang['need_help'] ?></strong>
		<br />
		<a style="border-bottom:dotted 1px #FDCD25;" class="links" href="http://blog.nintechnet.com/troubleshoot-ninjafirewall-installation-problems/" target="_blank">Troubleshoot NinjaFirewall installation problems</a>.</p>
		</fieldset>
		<p style="text-align:center"><input type="button" value="<?php echo $lang['try_again'] ?>" onClick="location.reload(); " /></p>

		<?php
	} elseif ( NFW_STATUS != 22 ) {
		// Firewall is loaded, but returned and error :
		if (empty($err_fw[NFW_STATUS])) {
			$msg = 'Unknown error #' . NFW_STATUS;
		} else {
			$msg = $err_fw[NFW_STATUS];
		}
		?>
		<center>
			<h3><img src="static/icon_error.png" width="21" height="21" border="0">&nbsp;<?php echo $lang['error'] ?></h3>
			<p><?php echo $lang['error_loaded'] ?></p>
			<p>#<?php echo NFW_STATUS . ': ' . $msg ?></p>
			<p><?php echo $lang['error_restart'] ?></p>
		</center>
		</td></tr></table>
		</fieldset>
		<p style="text-align:center"><input type="button" style="width:100px" value="<?php echo $lang['close'] ?>" onClick="window.close();" /></p>
		<?php
	} else {
		// Everything is fine. We redirect user to the login page :
		?>
		<center>
			<h3><img src="static/icon_ok.png" width="21" height="21" border="0">&nbsp;<?php echo $lang['it_works'] ?></h3>
			<p><?php echo $lang['congrats'] ?></p>
			<p><?php echo $lang['redir_admin'] ?></p>
		</center>
		</td></tr></table>
		</fieldset>
		<p style="text-align:center"><input type="button" style="width:100px" value="<?php echo $lang['next'] .  ' &#187' ?>" onClick="opener.location.href='login.php';window.close();" /></p>
		<?php
		// Send an email to the admin with links and info about NinjaFirewall:
		if (! empty($_SESSION['admin_email']) ) {
			$subject = '[NinjaFirewall] ' . $lang['mail_subject'];

			$message = $lang['hi'] . "\n\n";

			$message.= $lang['hi2'] . "\n\n";

			$message.= '1) ' . $lang['hi3'] . "\n";
			$message.= 'http://nintechnet.com/ninjafirewall/pro-edition/help/?troubleshooting ' . "\n\n";

			$message.= $lang['hi4'] . "\n";
			$message.= $lang['hi5'] . "\n";
			$message.= $lang['hi6'] . "\n";
			$message.= $lang['hi7'] . "\n\n";

			$message.= '2) ' . $lang['hi8'] . "\n";
			$message.= 'http://nintechnet.com/share/pro-check.txt ' . "\n\n";
			$message.=  $lang['hi9'] . "\n";
			$message.=  $lang['hi10'] . "\n";
			$message.=  $lang['hi11'] . "\n";
			$message.=  $lang['hi12'] . "\n\n";

			$message.= '3) '. $lang['hi13'] . "\n";
			$message.= 'http://nintechnet.com/ninjafirewall/pro-edition/help/?faq ' . "\n\n";

			$message.= $lang['hi14'] . "\n";
			$message.= $lang['hi15'] . "\n";
			$message.= $lang['hi16'] . "\n";
			$message.= $lang['hi17'] . "\n";
			$message.= $lang['hi18'] . "\n";
			$message.= $lang['hi19'] . "\n";
			$message.= $lang['hi20'] . "\n";
			$message.= $lang['hi21'] . "\n";
			$message.= $lang['hi22'] . "\n\n";

			$message.= '4) '. $lang['hi23'] . "\n\n";

			$message.= $lang['hi24'] . "\n";
			$message.= 'http://blog.nintechnet.com/testing-ninjafirewall-without-blocking-your-visitors/ ' . "\n\n";

			$message.= $lang['hi25'] . "\n";
			$message.= 'http://nintechnet.com/ninjafirewall/pro-edition/help/?htninja ' . "\n\n";

			$message.= $lang['hi25b'] . "\n";
			$message.= 'http://blog.nintechnet.com/upgrading-to-php-7-with-ninjafirewall-installed/ ' . "\n\n";

			$message.= '5) '. $lang['hi26'] . "\n\n";

			$message.= $lang['hi27'] . "\n";
			$message.= $lang['hi28'] . " https://twitter.com/nintechnet \n";
			$message.= '-NinjaFirewall (Pro edition) - http://ninjafirewall.com/ ' . "\n";

			$headers = 'From: "'. $_SESSION['admin_email'] .'" <'. $_SESSION['admin_email'] .'>' . "\r\n";
			$headers .= "Content-Transfer-Encoding: 7bit\r\n";
			$headers .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			mail($_SESSION['admin_email'], $subject, $message, $headers, '-f'. $_SESSION['admin_email']);
		}
		session_destroy();
	}
	echo '</body></html>';
	exit;
}

/* ------------------------------------------------------------------ */


function install_header($msg, $lev) {

	if (! empty($_SESSION['timezone']) ) {
		date_default_timezone_set($_SESSION['timezone']);
	}

	if (empty($_SESSION['admin_lang']) ) {
		require (__DIR__ . '/lib/lang/en/' . basename(__FILE__) );
	} else {
		require (__DIR__ . '/lib/lang/' . $_SESSION['admin_lang'] . '/' . basename(__FILE__) );
	}

	if (! isset($GLOBALS['mid']) ) { $GLOBALS['mid'] = '10'; }

	$m10 = $m20 = $m30 = $m40 = $m50 = $m60 = $m70 = 'static/bullet_off.gif';
	$c10 = $c20 = $c30 = $c40 = $c50 = $c60 = $c70 = '#999999';

	if    ( $GLOBALS['mid'] == 10 ) {
		$m10 = 'static/bullet_on.gif';
		$c10 = '#000000';
	} elseif( $GLOBALS['mid'] == 20 ) {
		$m20 = 'static/bullet_on.gif';
		$c20 = '#000000';
	} elseif( $GLOBALS['mid'] == 30 ) {
		$m30 = 'static/bullet_on.gif';
		$c30 = '#000000';
	} elseif( $GLOBALS['mid'] == 40 ) {
		$m40 = 'static/bullet_on.gif';
		$c40 = '#000000';
	} elseif( $GLOBALS['mid'] == 50 ) {
		$m50 = 'static/bullet_on.gif';
		$c50 = '#000000';
	} elseif( $GLOBALS['mid'] == 60 ) {
		$m60 = 'static/bullet_on.gif';
		$c60 = '#000000';
	} elseif( $GLOBALS['mid'] == 70 ) {
		$m70 = 'static/bullet_on.gif';
		$c70 = '#000000';
	}

	?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>NinjaFirewall : <?php echo $lang['setup'] ?></title>
	<link href="static/styles.css" rel="stylesheet" type="text/css">
	<link rel="Shortcut Icon" type="image/gif" href="static/favicon.ico">
	<script>window.name='nfwmain';function popup(url,width,height,scroll_bar) {height=height+20;width=width+20;var str = "height=" + height + ",innerHeight=" + height;str += ",width=" + width + ",innerWidth=" + width;if (window.screen){var ah = screen.availHeight - 30;var aw = screen.availWidth -10;var xc = (aw - width) / 2;var yc = (ah - height) / 2;str += ",left=" + xc + ",screenX=" + xc;str += ",top=" + yc + ",screenY=" + yc;if (scroll_bar) {str += ",scrollbars=no";}else {str += ",scrollbars=yes";}str += ",status=no,location=no,resizable=yes";}win = open(url, "nfpop", str);setTimeout("win.window.focus()",1300);}</script>
</head>
<body bgcolor="white" class="smallblack">

<table border="0" width="95%" align="center">
	<tr>
		<td align="left" width="250">
			<img src="static/logo.png" width="192" height="62">
		</td>
		<td align="left">
			<div class="<?php echo $lev ?>"><?php echo $msg ?></div>
		</td>
		<td align="right" width="150"><img src="static/logopro_60.png" width="60" height="60">&nbsp;</td>
	</tr>
</table>

<table border="0" width="95%" cellpadding="0" cellspacing="0" align="center">
	<tr valign="top">
		<td width="150" align="left">
			<table border="0" width="150" height="400" cellpadding="0" cellspacing="7">
				<tr valign="top">
					<td class="tinyblack">
						<center style="border:1px solid #FDCD25;"><?php echo $lang['setup'] ?></center>
						<p style="color:<?php echo $c10 ?>"><img src="<?php echo  $m10 ?>" width="10" height="10">&nbsp;<?php echo $lang['region'] ?></p>
						<p style="color:<?php echo $c20 ?>"><img src="<?php echo $m20 ?>" width="10" height="10">&nbsp;<?php echo $lang['sysreq'] ?></p>
						<p style="color:<?php echo $c30 ?>"><img src="<?php echo $m30 ?>" width="10" height="10">&nbsp;<?php echo $lang['notes'] ?></p>
						<p style="color:<?php echo $c40 ?>"><img src="<?php echo $m40 ?>" width="10" height="10">&nbsp;<?php echo $lang['license'] ?></p>
						<p style="color:<?php echo $c50 ?>"><img src="<?php echo $m50 ?>" width="10" height="10">&nbsp;<?php echo $lang['account'] ?></p>
						<p style="color:<?php echo $c60 ?>"><img src="<?php echo $m60 ?>" width="10" height="10">&nbsp;<?php echo $lang['integration'] ?></p>
						<p style="color:<?php echo $c70 ?>"><img src="<?php echo $m70 ?>" width="10" height="10">&nbsp;<?php echo $lang['activation'] ?></p>
						<br />
						<br />
						<br />
						<font color="#999999">
						Edition :
						<?php
						if (NFW_EDN ==1) {
							echo 'Pro Edition';
						} else {
							echo 'Pro+ Edition';
						}
						?>
						<br />
						Version : <?php echo NFW_ENGINE_VERSION ?>
						</font>
					</td>
				</tr>
			</table>
		</td>
		<td width="20">&nbsp;</td>
		<td>
			<table style="border:0px solid #666666;" width="100%" cellpadding=6>
				<tr>
					<td class=smallblack>
<?php
}

/* ------------------------------------------------------------------ */

function install_footer() {
?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<center class="tinygrey">&copy; 2011-<?php echo date('Y') ?> <a style="border-bottom:dotted 1px #FDCD25;color:#999999;" href="http://nintechnet.com/" target="_blank" title="The Ninja Technologies Network">NinTechNet</a><br />The Ninja Technologies Network</center>
	</body>
</html>
<?php
   exit;
}

/* ------------------------------------------------------------------ */

function fw_conf_options() {

	// Populate the options :

	$nfw_options = array (

		'engine_version' 		=> NFW_ENGINE_VERSION,
		'rules_version'		=> NFW_RULES_VERSION,

		// Account > Options :
		'admin_login_alert'	=> 1,
		'admin_ssl' 			=> 0,

		// Firewall > Options :
		'enabled' 				=> 1,
		'debug' 					=> 0,
		'ret_code' 				=> 403,
		'blocked_msg' 			=> base64_encode( NFW_DEFAULT_MSG ),
		'ban_ip' 				=> 0,
		'ban_time' 				=> 0,

		// Firewall > Access Control :
		'admin_wl'				=>	0,
		'admin_wl_session'	=>	0,
		'ac_ip' 					=> 1,
		'ac_ip_2' 				=> 0,
		'ac_scan_loopback'	=> 1,
		'ac_method' 			=> 'GETPOSTHEAD',
		'ac_geoip' 				=> 0,
		'ac_geoip_db' 			=> 1,
		'ac_geoip_db2' 		=> '',
		'ac_geoip_cn' 			=> '',
		'ac_geoip_ninja' 		=> 0,
		'ac_allow_ip' 			=> 0,
		'ac_block_ip' 			=> 0,
		'ac_rl_on' 				=> 0,
		'ac_rl_time' 			=> 30,
		'ac_rl_conn' 			=> 10,
		'ac_rl_intv' 			=> 5,
		'ac_bl_url'				=> 0,
		'ac_wl_url' 			=> 0,
		'ac_bl_bot' 			=> NFW_BOT_LIST,

		// Access Control logs :
		'ac_geoip_log' 		=> 1,
		'ac_allow_ip_log' 	=> 0,
		'ac_block_ip_log' 	=> 1,
		'ac_rl_log' 			=> 1,
		'ac_bl_url_log' 		=> 1,
		'ac_bl_bot_log' 		=> 1,
		'ac_wl_url_log' 		=> 0,

		// Firewall > Web Filter :
		'wf_enable' 			=> 0,
		'wf_pattern' 			=> 0,
		'wf_case' 				=> 0,
		'wf_alert' 				=> 30,
		'wf_attach' 			=> 1,

		// Firewall > Policies :
		'scan_protocol' 		=> 3,
		'uploads' 				=> 0,
		'sanitise_fn' 			=> 0,
		'upload_maxsize' 		=> 1048576,
		'get_scan' 				=> 1,
		'get_sanitise' 		=> 0,
		'post_scan'				=> 1,
		'post_sanitise' 		=> 0,
		'post_b64' 				=> 1,
		'request_sanitise'	=> 0,
		'cookies_scan' 		=> 1,
		'cookies_sanitise'	=> 0,
		'ua_scan' 				=> 1,
		'ua_sanitise' 			=> 1,
		'ua_mozilla' 			=> 0,
		'ua_accept' 			=> 0,
		'ua_accept_lang' 		=> 0,
		'block_bots'			=> 0,	// Pro edn only :
		'referer_scan' 		=> 0,
		'referer_sanitise'	=> 1,
		'referer_post' 		=> 0,
		'php_errors' 			=> 1,
		'php_self' 				=> 1,
		'php_path_t' 			=> 1,
		'php_path_i' 			=> 1,
		'no_host_ip' 			=> 0,
		'request_method' 		=> 0,

		// Firewall > File Guard :
		'fg_enable' 			=> 1,
		'fg_mtime'				=> 10,
		'fg_exclude'			=>	'',

		// Firewall > Log :
		'logging' 				=> 1,
		'log_rotate' 			=> 1,
		'log_maxsize' 			=> 2097152,
	);

	if ( function_exists('header_register_callback') && function_exists('headers_list') && function_exists('header_remove') ) {
		$nfw_options['response_headers'] = '000000';
	}
	return $nfw_options;
}

/* ------------------------------------------------------------------ */

function fw_conf_rules() {

	// Populate the custom rules :

	global $nfw_rules;

	// Try to get the document root :
	if ( strlen( $_SERVER['DOCUMENT_ROOT'] ) > 5 ) {
		$nfw_rules[NFW_DOC_ROOT]['what'] = $_SERVER['DOCUMENT_ROOT'];
		$nfw_rules[NFW_DOC_ROOT]['on'] = 1;
	} elseif ( strlen( getenv( 'DOCUMENT_ROOT' ) ) > 5 ) {
		$nfw_rules[NFW_DOC_ROOT]['what'] = getenv( 'DOCUMENT_ROOT' );
		$nfw_rules[NFW_DOC_ROOT]['on'] = 1;
	} else {
		$nfw_rules[NFW_DOC_ROOT]['on'] = 0;
	}

	$nfw_rules[NFW_WRAPPERS]['on'] 	= 1;
	$nfw_rules[NFW_NULL_BYTE]['on'] 	= 1;
	$nfw_rules[NFW_ASCII_CTRL]['on'] = 1;
	$nfw_rules[NFW_LOOPBACK]['on'] 	= 0;

	return $nfw_rules;

}

/* ------------------------------------------------------------------ */

function account_license_connect($data) {

	// Check license validity (Pro+ edition only) :

	global $lang;
	global $domain;
	require(__DIR__ . '/lib/misc.php');

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_USERAGENT, 'NinjaFirewall/' . NFW_ENGINE_VERSION . ':' . NFW_EDN . '; ' . $domain );
	curl_setopt( $ch, CURLOPT_ENCODING, '');
	curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
	curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
	curl_setopt( $ch, CURLOPT_URL, 'http://' . NFW_UPDATE . '/index.php' );
	curl_setopt( $ch, CURLOPT_POST, true );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
	curl_setopt ($ch, CURLOPT_HEADER, 0);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

	if ( ($content = curl_exec($ch)) === FALSE ) {
		@curl_close( $ch );
		$package['curl'] = 1;
		return $package;
	}
	$response = curl_getinfo( $ch );
	curl_close( $ch );

	// Errors ?
	if ( $response['http_code'] != 200 ) {
		$package['curl'] = 2;
		return $package;
	}
	if (! $content ) {
		$package['curl'] = 3;
		return $package;
	}

	$package = @unserialize( $content );
	if (! isset($package['exp']) || ! isset($package['err']) ) {
		$package['curl'] = 4;
		return $package;
	}

	// Looks good :
	$package['curl'] = 0;
	return $package;
}

/* ------------------------------------------------------------------ */

function _selected( $var, $val) {

	if ( $var == $val ) {
		echo " selected='selected'";
	}

}

/* ------------------------------------------------------------------ */

function _checked( $var, $val) {

	if ( $var == $val ) {
		echo " checked='checked'";
	}

}

/* ------------------------------------------------------------------ */

function _error($msg) {

	?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link href="static/styles.css" rel="stylesheet" type="text/css">
</head>
<body class="smallblack" bgcolor="white">
	<center>
		<div class="error" style="width:500px"><p>Error : <?php echo $msg ?></p></div>
	</center>
</body>
</html><?php

}

/* ------------------------------------------------------------------ */
// EOF

