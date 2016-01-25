<?php
/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (Pro edition)                                         |
 |                                                                     |
 | (c) NinTechNet - http://nintechnet.com/                             |
 |                                                                     |
 +---------------------------------------------------------------------+
 | REVISION: 2014-10-15 20:17:45                                       |
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

if (! defined( 'NFW_ENGINE_VERSION' ) ) { die( 'Forbidden' ); }

// Get all available languages first:
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

// Load current language file :
require (__DIR__ .'/lang/' . $nfw_options['admin_lang'] . '/' . basename(__FILE__) );

// Saved options ?
if (! empty($_POST['post']) ) {
	$err_msg = save_account_options();

	// Reload language file :
	require (__DIR__ .'/lang/' . $nfw_options['admin_lang'] . '/' . basename(__FILE__) );
	html_header();

	if ($err_msg) {
      echo '<br /><div class="error"><p>' . $err_msg .'</p></div>';
   } else {
      echo '<br /><div class="success"><p>'. $lang['saved_conf'] . '</p></div>';
   }
} else {
	html_header();
}

echo '<script>
function ssl_warn() {';
	// Obviously, if we are already in HTTPS mode, we don't send any warning:
	if ($_SERVER['SERVER_PORT'] == 443 ) {
		echo 'return true;';
	} else {
		echo '
		if (document.nf_options.admin_ssl.checked == false) { return true;}
		if (confirm("' . $lang['ssl_warn'] . '") ) {
			return true;
		}
		return false;';
	}
	echo '
}
</script>';
?>
<br />
<form method="post" name="nf_options">
	<fieldset><legend>&nbsp;<b><?php echo $lang['chang_pass'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td width="55%" align="left"><br /><?php echo $lang['pass_txt'] ?><br />&nbsp;</td>
				<td width="45%" align="left"><br />
					<input type="password" class="input" size="20" maxlength="20" name="old_admin_pass">&nbsp;&nbsp;<?php echo $lang['old_admin_pass'] ?>
					<br /><br />
				  <input type="password" class="input" size="20" maxlength="20" name="new_admin_pass">&nbsp;&nbsp;<?php echo $lang['new_admin_pass'] ?>
				  <br /><br />
				  <input type="password" class="input" size="20" maxlength="20" name="new_admin_pass_2">&nbsp;&nbsp;<?php echo $lang['new_admin_pass_2'] ?><br />&nbsp;
				</td>
			</tr>
		</table>
	</fieldset>

	<br /><br />

	<fieldset><legend>&nbsp;<b><?php echo $lang['contact'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td width="55%" align="left"><br /><?php echo $lang['contact_txt'] ?><br />&nbsp;</td>
				<td width="45%" align="left"><br /><input type="text" class="input" style="width:250px" value="<?php echo htmlspecialchars($nfw_options['admin_email']) ?>" name="admin_email">&nbsp;&nbsp;<?php echo $lang['email'] ?><br />&nbsp;</td>
			</tr>
		</table>
	</fieldset>
	<br /><br />
<?php
$zonelist = array('UTC', 'Africa/Abidjan', 'Africa/Accra', 'Africa/Addis_Ababa', 'Africa/Algiers', 'Africa/Asmara', 'Africa/Asmera', 'Africa/Bamako', 'Africa/Bangui', 'Africa/Banjul', 'Africa/Bissau', 'Africa/Blantyre', 'Africa/Brazzaville', 'Africa/Bujumbura', 'Africa/Cairo', 'Africa/Casablanca', 'Africa/Ceuta', 'Africa/Conakry', 'Africa/Dakar', 'Africa/Dar_es_Salaam', 'Africa/Djibouti', 'Africa/Douala', 'Africa/El_Aaiun', 'Africa/Freetown', 'Africa/Gaborone', 'Africa/Harare', 'Africa/Johannesburg', 'Africa/Kampala', 'Africa/Khartoum', 'Africa/Kigali', 'Africa/Kinshasa', 'Africa/Lagos', 'Africa/Libreville', 'Africa/Lome', 'Africa/Luanda', 'Africa/Lubumbashi', 'Africa/Lusaka', 'Africa/Malabo', 'Africa/Maputo', 'Africa/Maseru', 'Africa/Mbabane', 'Africa/Mogadishu', 'Africa/Monrovia', 'Africa/Nairobi', 'Africa/Ndjamena', 'Africa/Niamey', 'Africa/Nouakchott', 'Africa/Ouagadougou', 'Africa/Porto-Novo', 'Africa/Sao_Tome', 'Africa/Timbuktu', 'Africa/Tripoli', 'Africa/Tunis', 'Africa/Windhoek', 'America/Adak', 'America/Anchorage', 'America/Anguilla', 'America/Antigua', 'America/Araguaina', 'America/Argentina/Buenos_Aires', 'America/Argentina/Catamarca', 'America/Argentina/ComodRivadavia', 'America/Argentina/Cordoba', 'America/Argentina/Jujuy', 'America/Argentina/La_Rioja', 'America/Argentina/Mendoza', 'America/Argentina/Rio_Gallegos', 'America/Argentina/Salta', 'America/Argentina/San_Juan', 'America/Argentina/San_Luis', 'America/Argentina/Tucuman', 'America/Argentina/Ushuaia', 'America/Aruba', 'America/Asuncion', 'America/Atikokan', 'America/Atka', 'America/Bahia', 'America/Barbados', 'America/Belem', 'America/Belize', 'America/Blanc-Sablon', 'America/Boa_Vista', 'America/Bogota', 'America/Boise', 'America/Buenos_Aires', 'America/Cambridge_Bay', 'America/Campo_Grande', 'America/Cancun', 'America/Caracas', 'America/Catamarca', 'America/Cayenne', 'America/Cayman', 'America/Chicago', 'America/Chihuahua', 'America/Coral_Harbour', 'America/Cordoba', 'America/Costa_Rica', 'America/Cuiaba', 'America/Curacao', 'America/Danmarkshavn', 'America/Dawson', 'America/Dawson_Creek', 'America/Denver', 'America/Detroit', 'America/Dominica', 'America/Edmonton', 'America/Eirunepe', 'America/El_Salvador', 'America/Ensenada', 'America/Fort_Wayne', 'America/Fortaleza', 'America/Glace_Bay', 'America/Godthab', 'America/Goose_Bay', 'America/Grand_Turk', 'America/Grenada', 'America/Guadeloupe', 'America/Guatemala', 'America/Guayaquil', 'America/Guyana', 'America/Halifax', 'America/Havana', 'America/Hermosillo', 'America/Indiana/Indianapolis', 'America/Indiana/Knox', 'America/Indiana/Marengo', 'America/Indiana/Petersburg', 'America/Indiana/Tell_City', 'America/Indiana/Vevay', 'America/Indiana/Vincennes', 'America/Indiana/Winamac', 'America/Indianapolis', 'America/Inuvik', 'America/Iqaluit', 'America/Jamaica', 'America/Jujuy', 'America/Juneau', 'America/Kentucky/Louisville', 'America/Kentucky/Monticello', 'America/Knox_IN', 'America/La_Paz', 'America/Lima', 'America/Los_Angeles', 'America/Louisville', 'America/Maceio', 'America/Managua', 'America/Manaus', 'America/Marigot', 'America/Martinique', 'America/Matamoros', 'America/Mazatlan', 'America/Mendoza', 'America/Menominee', 'America/Merida', 'America/Mexico_City', 'America/Miquelon', 'America/Moncton', 'America/Monterrey', 'America/Montevideo', 'America/Montreal', 'America/Montserrat', 'America/Nassau', 'America/New_York', 'America/Nipigon', 'America/Nome', 'America/Noronha', 'America/North_Dakota/Center', 'America/North_Dakota/New_Salem', 'America/Ojinaga', 'America/Panama', 'America/Pangnirtung', 'America/Paramaribo', 'America/Phoenix', 'America/Port-au-Prince', 'America/Port_of_Spain', 'America/Porto_Acre', 'America/Porto_Velho', 'America/Puerto_Rico', 'America/Rainy_River', 'America/Rankin_Inlet', 'America/Recife', 'America/Regina', 'America/Resolute', 'America/Rio_Branco', 'America/Rosario', 'America/Santa_Isabel', 'America/Santarem', 'America/Santiago', 'America/Santo_Domingo', 'America/Sao_Paulo', 'America/Scoresbysund', 'America/Shiprock', 'America/St_Barthelemy', 'America/St_Johns', 'America/St_Kitts', 'America/St_Lucia', 'America/St_Thomas', 'America/St_Vincent', 'America/Swift_Current', 'America/Tegucigalpa', 'America/Thule', 'America/Thunder_Bay', 'America/Tijuana', 'America/Toronto', 'America/Tortola', 'America/Vancouver', 'America/Virgin', 'America/Whitehorse', 'America/Winnipeg', 'America/Yakutat', 'America/Yellowknife', 'Arctic/Longyearbyen', 'Asia/Aden', 'Asia/Almaty', 'Asia/Amman', 'Asia/Anadyr', 'Asia/Aqtau', 'Asia/Aqtobe', 'Asia/Ashgabat', 'Asia/Ashkhabad', 'Asia/Baghdad', 'Asia/Bahrain', 'Asia/Baku', 'Asia/Bangkok', 'Asia/Beirut', 'Asia/Bishkek', 'Asia/Brunei', 'Asia/Calcutta', 'Asia/Choibalsan', 'Asia/Chongqing', 'Asia/Chungking', 'Asia/Colombo', 'Asia/Dacca', 'Asia/Damascus', 'Asia/Dhaka', 'Asia/Dili', 'Asia/Dubai', 'Asia/Dushanbe', 'Asia/Gaza', 'Asia/Harbin', 'Asia/Ho_Chi_Minh', 'Asia/Hong_Kong', 'Asia/Hovd', 'Asia/Irkutsk', 'Asia/Istanbul', 'Asia/Jakarta', 'Asia/Jayapura', 'Asia/Jerusalem', 'Asia/Kabul', 'Asia/Kamchatka', 'Asia/Karachi', 'Asia/Kashgar', 'Asia/Kathmandu', 'Asia/Katmandu', 'Asia/Kolkata', 'Asia/Krasnoyarsk', 'Asia/Kuala_Lumpur', 'Asia/Kuching', 'Asia/Kuwait', 'Asia/Macao', 'Asia/Macau', 'Asia/Magadan', 'Asia/Makassar', 'Asia/Manila', 'Asia/Muscat', 'Asia/Nicosia', 'Asia/Novokuznetsk', 'Asia/Novosibirsk', 'Asia/Omsk', 'Asia/Oral', 'Asia/Phnom_Penh', 'Asia/Pontianak', 'Asia/Pyongyang', 'Asia/Qatar', 'Asia/Qyzylorda', 'Asia/Rangoon', 'Asia/Riyadh', 'Asia/Saigon', 'Asia/Sakhalin', 'Asia/Samarkand', 'Asia/Seoul', 'Asia/Shanghai', 'Asia/Singapore', 'Asia/Taipei', 'Asia/Tashkent', 'Asia/Tbilisi', 'Asia/Tehran', 'Asia/Tel_Aviv', 'Asia/Thimbu', 'Asia/Thimphu', 'Asia/Tokyo', 'Asia/Ujung_Pandang', 'Asia/Ulaanbaatar', 'Asia/Ulan_Bator', 'Asia/Urumqi', 'Asia/Vientiane', 'Asia/Vladivostok', 'Asia/Yakutsk', 'Asia/Yekaterinburg', 'Asia/Yerevan', 'Atlantic/Azores', 'Atlantic/Bermuda', 'Atlantic/Canary', 'Atlantic/Cape_Verde', 'Atlantic/Faeroe', 'Atlantic/Faroe', 'Atlantic/Jan_Mayen', 'Atlantic/Madeira', 'Atlantic/Reykjavik', 'Atlantic/South_Georgia', 'Atlantic/St_Helena', 'Atlantic/Stanley', 'Australia/ACT', 'Australia/Adelaide', 'Australia/Brisbane', 'Australia/Broken_Hill', 'Australia/Canberra', 'Australia/Currie', 'Australia/Darwin', 'Australia/Eucla', 'Australia/Hobart', 'Australia/LHI', 'Australia/Lindeman', 'Australia/Lord_Howe', 'Australia/Melbourne', 'Australia/NSW', 'Australia/North', 'Australia/Perth', 'Australia/Queensland', 'Australia/South', 'Australia/Sydney', 'Australia/Tasmania', 'Australia/Victoria', 'Australia/West', 'Australia/Yancowinna', 'Europe/Amsterdam', 'Europe/Andorra', 'Europe/Athens', 'Europe/Belfast', 'Europe/Belgrade', 'Europe/Berlin', 'Europe/Bratislava', 'Europe/Brussels', 'Europe/Bucharest', 'Europe/Budapest', 'Europe/Chisinau', 'Europe/Copenhagen', 'Europe/Dublin', 'Europe/Gibraltar', 'Europe/Guernsey', 'Europe/Helsinki', 'Europe/Isle_of_Man', 'Europe/Istanbul', 'Europe/Jersey', 'Europe/Kaliningrad', 'Europe/Kiev', 'Europe/Lisbon', 'Europe/Ljubljana', 'Europe/London', 'Europe/Luxembourg', 'Europe/Madrid', 'Europe/Malta', 'Europe/Mariehamn', 'Europe/Minsk', 'Europe/Monaco', 'Europe/Moscow', 'Europe/Nicosia', 'Europe/Oslo', 'Europe/Paris', 'Europe/Podgorica', 'Europe/Prague', 'Europe/Riga', 'Europe/Rome', 'Europe/Samara', 'Europe/San_Marino', 'Europe/Sarajevo', 'Europe/Simferopol', 'Europe/Skopje', 'Europe/Sofia', 'Europe/Stockholm', 'Europe/Tallinn', 'Europe/Tirane', 'Europe/Tiraspol', 'Europe/Uzhgorod', 'Europe/Vaduz', 'Europe/Vatican', 'Europe/Vienna', 'Europe/Vilnius', 'Europe/Volgograd', 'Europe/Warsaw', 'Europe/Zagreb', 'Europe/Zaporozhye', 'Europe/Zurich', 'Indian/Antananarivo', 'Indian/Chagos', 'Indian/Christmas', 'Indian/Cocos', 'Indian/Comoro', 'Indian/Kerguelen', 'Indian/Mahe', 'Indian/Maldives', 'Indian/Mauritius', 'Indian/Mayotte', 'Indian/Reunion', 'Pacific/Apia', 'Pacific/Auckland', 'Pacific/Chatham', 'Pacific/Easter', 'Pacific/Efate', 'Pacific/Enderbury', 'Pacific/Fakaofo', 'Pacific/Fiji', 'Pacific/Funafuti', 'Pacific/Galapagos', 'Pacific/Gambier', 'Pacific/Guadalcanal', 'Pacific/Guam', 'Pacific/Honolulu', 'Pacific/Johnston', 'Pacific/Kiritimati', 'Pacific/Kosrae', 'Pacific/Kwajalein', 'Pacific/Majuro', 'Pacific/Marquesas', 'Pacific/Midway', 'Pacific/Nauru', 'Pacific/Niue', 'Pacific/Norfolk', 'Pacific/Noumea', 'Pacific/Pago_Pago', 'Pacific/Palau', 'Pacific/Pitcairn', 'Pacific/Ponape', 'Pacific/Port_Moresby', 'Pacific/Rarotonga', 'Pacific/Saipan', 'Pacific/Samoa', 'Pacific/Tahiti', 'Pacific/Tarawa', 'Pacific/Tongatapu', 'Pacific/Truk', 'Pacific/Wake', 'Pacific/Wallis', 'Pacific/Yap');

// Get current timezone :
$current_tz = @date_default_timezone_get();

?>
	<fieldset><legend>&nbsp;<b><?php echo $lang['region'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td width="55%" align="left"><br /><?php echo $lang['timezone'] ?><br />&nbsp;</td>
				<td width="45%" align="left"><br />
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
			<tr>
				<td width="55%" align="left" class="dotted"><br /><?php echo $lang['language'] ?><br />&nbsp;</td>
				<td width="45%" align="left" class="dotted"><br />
					<select name="admin_lang" class="input" style="width:250px">
					<?php
					foreach ($reg as $key => $value) {
						echo '<option value ="' . htmlspecialchars($key) . '"';
						if ( $key == $nfw_options['admin_lang'] ) {
							echo ' selected';
						}
						echo '>' . htmlspecialchars($value['language']) . ' - ' . htmlspecialchars($lang['by']) . ' ' . htmlspecialchars($value['author']) . '</option>';
					}
					?>
					</select><br />&nbsp;
				</td>
			</tr>
		</table>
	</fieldset>
	<br />
	<br />
	<fieldset><legend>&nbsp;<b><?php echo $lang['security'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td width="55%" align="left"><br /><?php echo $lang['login_alert'] ?><br />&nbsp;</td>
				<td width="45%" align="left"><br />
					<label><input type="checkbox" name="admin_login_alert"<?php checked($nfw_options['admin_login_alert'], 1) ?>>&nbsp;<?php echo $lang['yes'] ?></label><br />&nbsp;
				</td>
			</tr>
			<tr>
				<td width="55%" align="left" class="dotted"><br /><?php echo $lang['login_ssl'] ?><br />&nbsp;</td>
				<td width="45%" align="left" class="dotted"><br />
					<label><input type="checkbox" name="admin_ssl"<?php checked($nfw_options['admin_ssl'], 1) ?> onclick="return ssl_warn();">&nbsp;<?php echo $lang['yes'] ?></label><br />&nbsp;
				</td>
			</tr>
		</table>
	</fieldset>
	<br />
	<center><input type="submit" class="button" value="<?php echo $lang['save_conf'] ?>"></center>
	<input type="hidden" name="mid" value="<?php echo $GLOBALS['mid'] ?>">
	<input type="hidden" name="post" value="1">
	<br />
</form>
<?php

html_footer();

/* ------------------------------------------------------------------ */

function save_account_options() {

	global $lang;
	global $nfw_options;

	$err_msg = '';

	// Config file must be writable :
	if (! is_writable('./conf/options.php') ) {
		return $lang['error_conf' ];
	}

	// Password changed :
   $old_admin_pass   = @$_POST['old_admin_pass'];
   $new_admin_pass   = @$_POST['new_admin_pass'];
   $new_admin_pass_2 = @$_POST['new_admin_pass_2'];

   if ( ($old_admin_pass) || ($new_admin_pass_2) || ($new_admin_pass) ) {
		if ( (! $old_admin_pass ) || (! $new_admin_pass_2 ) || (! $new_admin_pass ) ) {
			$err_msg .= $lang['pass_err_1'] . '<br />';
		} else if ( $new_admin_pass_2 !== $new_admin_pass ) {
			$err_msg .= $lang['pass_err_2'] . '<br />';
		} else if ( $new_admin_pass_2 === $new_admin_pass ) {
			$encoded = sha1 ($old_admin_pass);
			if ( $encoded !== $nfw_options['admin_pass'] ) {
				$err_msg .= $lang['pass_err_3'] . '<br />';
			} else if (! preg_match('/^.{6,20}$/', $new_admin_pass) ) {
				$err_msg .= $lang['pass_err_4'] . '<br />';
			} else {
				$nfw_options['admin_pass'] = sha1($new_admin_pass);
			}
		}
	}

	// Contact email :
	if ( empty($_POST['admin_email']) || ! filter_var( $_POST['admin_email'], FILTER_VALIDATE_EMAIL ) ) {
		$err_msg .= $lang['email_err'] . '<br />';
	} else {
		$nfw_options['admin_email'] = $_POST['admin_email'];
	}

	// Regional Settings :
	if (! empty($_POST['timezone']) && @date_default_timezone_set($_POST['timezone']) ) {
		$nfw_options['timezone'] = $_POST['timezone'];
	}
	if (! empty($_POST['admin_lang']) && file_exists('./lib/lang/' . $_POST['admin_lang'] . '/index.php') ) {
		$nfw_options['admin_lang'] = $_POST['admin_lang'];
	}

	// Login security :
	if ( isset($_POST['admin_login_alert']) && ($_POST['admin_login_alert']  == 'on') ) {
		$nfw_options['admin_login_alert'] = 1;
	} else {
		$nfw_options['admin_login_alert'] = 0;
	}
	if ( isset($_POST['admin_ssl']) && ($_POST['admin_ssl']  == 'on') ) {
		$nfw_options['admin_ssl'] = 1;
	} else {
		$nfw_options['admin_ssl'] = 0;
	}

	if (! $fh = fopen('./conf/options.php', 'w') ) {
		return $lang['error_conf' ];
	}
	fwrite($fh, '<?php' . "\n\$nfw_options = <<<'EOT'\n" . serialize( $nfw_options ) . "\nEOT;\n" );
	fclose($fh);

	return $err_msg;

}

/* ------------------------------------------------------------------ */
// EOF
