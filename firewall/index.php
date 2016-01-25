<?php
/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (Pro edition)                                         |
 |                                                                     |
 | (c) NinTechNet - http://nintechnet.com/                             |
 |                                                                     |
 +---------------------------------------------------------------------+
 | REVISION: 2015-11-24 23:33:00                                       |
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

// Required constants :
require(__DIR__ . '/lib/constants.php');

if (! @include(__DIR__ . '/conf/options.php') ) {
	header('Location: login.php?1');
	exit;
}
$nfw_options = unserialize($nfw_options);

date_default_timezone_set( $nfw_options['timezone'] );

// Start session :
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

if ( $_SERVER['QUERY_STRING'] == 'logout') {
	session_destroy();
   header('Location: login.php?logout');
   exit;
}

if ( empty($_SESSION['timeout']) || empty($_SESSION['nfadmpro']) ||
	empty($_SESSION['nftoken']) || empty($_REQUEST['token']) ) {
	if ( empty($_SESSION['first_run']) ) {
		session_destroy();
	}
   header('Location: login.php?2');
   exit;
}

if ($_SESSION['nftoken'] != sha1($_REQUEST['token']) ) {
	session_destroy();
   header('Location: login.php?3');
   exit;
}

if ( ($_SESSION['timeout'] + 7200) < time() ) {
	session_destroy();
   header('Location: login.php?expired');
   exit;
}
$_SESSION['timeout'] = time();

if ($_SESSION['nfadmpro'] != $nfw_options['admin_name']) {
	session_destroy();
   header('Location: login.php?4');
   exit;
}

// Should we force SSL login ?
if ( $nfw_options['admin_ssl'] && $_SERVER['SERVER_PORT'] != 443 ) {
	// Force it:
   header('Location: https://' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'] );
   exit;
}

require(__DIR__ . '/conf/rules.php');
$nfw_rules = unserialize($nfw_rules);

// Used for updates :
require(__DIR__ . '/lib/nfw_init.php');

if (! isset($_SESSION['ver']) ) {
	$_SESSION['vapp'] = $_SESSION['ver'] = 0;
}

if (@strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') === FALSE) {
	define('OPA','opacity:0.4');
	define('OPA_OUT','this.style.opacity=0.4');
	define('OPA_OVER','this.style.opacity=1');
} else {
	if (preg_match('/MSIE [678]/', $_SERVER['HTTP_USER_AGENT']) ) {
		define('OPA',''); define('OPA_OUT',''); define('OPA_OVER','');
	} else {
		define('OPA','filter:alpha(opacity=60)');
		define('OPA_OUT','this.filters.alpha.opacity=60');
		define('OPA_OVER','this.filters.alpha.opacity=100');
	}
}

if ( empty($_REQUEST['mid']) || ! ctype_digit($_REQUEST['mid']) ) {
	$mid = 10;
} else {
	$mid = $_REQUEST['mid'];
}

// Changelog pop-up window :
if ($mid == 99) {
	nfw_changelog();

//	menu Summary > Overview :
} elseif ($mid == 11) {
	if (! empty($_GET['help']) ) {
		nfw_help(__DIR__ . '/lib/lang/' . $nfw_options['admin_lang'] . '/summary_stats_help.php');
	}
	require(__DIR__ . '/lib/summary_stats.php');

//	menu Account > Options :
} elseif ($mid == 20) {
	if (! empty($_GET['help']) ) {
		nfw_help(__DIR__ . '/lib/lang/' . $nfw_options['admin_lang'] . '/account_options_help.php');
	}
	require(__DIR__ . '/lib/account_options.php');

//	menu Account > License :
} elseif ($mid == 21) {
	if (! empty($_GET['help']) ) {
		nfw_help(__DIR__ . '/lib/lang/' . $nfw_options['admin_lang'] . '/account_license_help.php');
	}
	require(__DIR__ . '/lib/account_license.php');

//	menu Account > Updates :
} elseif ($mid == 22) {
	if (! empty($_GET['help']) ) {
		nfw_help(__DIR__ . '/lib/lang/' . $nfw_options['admin_lang'] . '/account_updates_help.php');
	}
	require(__DIR__ . '/lib/account_updates.php');

// menu Firewall > Otions
} elseif ($mid == 30) {
	if (! empty($_GET['help']) ) {
		nfw_help(__DIR__ . '/lib/lang/' . $nfw_options['admin_lang'] . '/firewall_options_help.php');
	}
	require(__DIR__ . '/lib/firewall_options.php');

// menu Firewall > Policies
} elseif ($mid == 31) {
	if (! empty($_GET['help']) ) {
		nfw_help(__DIR__ . '/lib/lang/' . $nfw_options['admin_lang'] . '/firewall_policies_help.php');
	}
	require(__DIR__ . '/lib/firewall_policies.php');

// menu Firewall > Access Control
} elseif ($mid == 32) {
	if (! empty($_GET['help']) ) {
		nfw_help(__DIR__ . '/lib/lang/' . $nfw_options['admin_lang'] . '/firewall_access_control_help.php');
	}
	require(__DIR__ . '/lib/firewall_access_control.php');

// menu Firewall > File Guard
} elseif ($mid == 33) {
	if (! empty($_GET['help']) ) {
		nfw_help(__DIR__ . '/lib/lang/' . $nfw_options['admin_lang'] . '/firewall_fileguard_help.php');
	}
	require(__DIR__ . '/lib/firewall_fileguard.php');

// menu Firewall > Web Filter
} elseif ($mid == 34) {
	if (! empty($_GET['help']) ) {
		nfw_help(__DIR__ . '/lib/lang/' . $nfw_options['admin_lang'] . '/firewall_webfilter_help.php');
	}
	require(__DIR__ . '/lib/firewall_webfilter.php');

// menu Firewall > Rules Editor
} elseif ($mid == 35) {
	if (! empty($_GET['help']) ) {
		nfw_help(__DIR__ . '/lib/lang/' . $nfw_options['admin_lang'] . '/firewall_rules_editor_help.php');
	}
	require(__DIR__ . '/lib/firewall_rules_editor.php');

// menu Firewall > Security Log
} elseif ($mid == 36) {
	if (! empty($_GET['help']) ) {
		nfw_help(__DIR__ . '/lib/lang/' . $nfw_options['admin_lang'] . '/firewall_log_help.php');
	}
	require(__DIR__ . '/lib/firewall_log.php');

// menu Firewall > Live Log
} elseif ($mid == 37) {
	if (! empty($_GET['help']) ) {
		nfw_help(__DIR__ . '/lib/lang/' . $nfw_options['admin_lang'] . '/firewall_livelog_help.php');
	}
	require(__DIR__ . '/lib/firewall_livelog.php');

// menu Firewall > File Check
} elseif ($mid == 38) {
	if (! empty($_GET['help']) ) {
		nfw_help(__DIR__ . '/lib/lang/' . $nfw_options['admin_lang'] . '/firewall_filecheck_help.php');
	}
	require(__DIR__ . '/lib/firewall_filecheck.php');

} elseif ($mid == 90) {
   raw_admin_log();
} elseif ($mid == 91) {
	flush_admin_log();
   raw_admin_log();

//	menu Summary > Overview :
} else {
	if (! empty($_GET['help']) ) {
		nfw_help(__DIR__ . '/lib/lang/' . $nfw_options['admin_lang'] . '/summary_overview_help.php');
	}
	require(__DIR__ . '/lib/summary_overview.php');
}

exit;

/* ================================================================== */

function is_nf_enabled() {

	global $nfw_options;

	// Check whether NF is running.

	// No communication from the firewall :
	if (! defined('NFW_STATUS') ) {
		define('NF_DISABLED', 10);
		return;
	}

	// NF was disabled by the admin :
	if ( isset($nfw_options['enabled']) && $nfw_options['enabled'] == '0' ) {
		define('NF_DISABLED', 5);
		return;
	}

	// There is another instance of NinjaFirewall firewall running,
	// maybe in the parent directory:
	if (NFW_STATUS == 20 || NFW_STATUS == 21 || NFW_STATUS == 23) {
		define('NF_DISABLED', 10);
		return;
	}

	// OK :
	if (NFW_STATUS == 22) {
		define('NF_DISABLED', 0);
		return;
	}

	// Err :
	define('NF_DISABLED', NFW_STATUS);
	return;

}

/* ================================================================== */

function checked( $var, $val) {

	if ( $var == $val ) {
		echo " checked='checked'";
	}

}

/* ================================================================== */

function selected( $var, $val) {

	if ( $var == $val ) {
		echo " selected='selected'";
	}

}

/* ================================================================== */

function disabled( $var, $val) {

	if ( $var == $val ) {
		echo " disabled='disabled'";
	}

}

/* ================================================================== */

function raw_admin_log() {

	// turn off debugging (if enabled) for that window :
	define('NF_NODBG', true);

	global $nfw_options;

	require (__DIR__ . '/lib/lang/' . $nfw_options['admin_lang'] . '/' . basename(__FILE__) );

   echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>NinjaFirewall</title><link href="static/styles.css" rel="stylesheet" type="text/css"><script>function dellog(){if (confirm("' . $lang['del_log'] . ' ?")){return true;}else{return false;}}</script></head><body bgcolor="white" class="smallblack"><fieldset style="height:400px;width:600px;padding:5px">';

	if ( $fh = fopen(__DIR__ . '/nfwlog/admin.php', 'r') ) {
		$st = stat(__DIR__ . '/nfwlog/admin.php');
		echo '<legend><b>&nbsp;admin log [/nfwlog/admin.php - ' . number_format($st['size']) . ' bytes]</b>&nbsp;</legend><center><textarea style="background-color:#ffffff;width:590px;height:380px;border:none;font-family:Consolas,Monaco,monospace;font-size:13px;" wrap="off">';

		if ($st['size'] < 5) {
			fclose($fh);
			echo '<center>' . $lang['empty_log'] . '.</center></textarea></center></fieldset><br /></body></html>';
			exit;
		}
		while (! feof($fh) ) {
			$line = fgets($fh);
			echo htmlspecialchars($line);
		}
		fclose($fh);
		echo '</textarea></center></fieldset><br /><center><form method="post" onsubmit="return dellog();"><input type="button" value="' . $lang['close'] . '" onClick="window.close();">&nbsp;&nbsp;&nbsp;<input type="hidden" name="mid" value="91"><input type="submit" value="' . $lang['del_log'] . '"></form></center><br /></body></html>';
	} else {
		echo '<font color="red">' . $lang['err_open_log'] . ' (/nfwlog/admin.php) !</font></fieldset><br /></body></html>';
	}
	exit;

}

/* ================================================================== */

function flush_admin_log() {

	global $nfw_options;

	if ($fh = fopen(__DIR__ . '/nfwlog/admin.php', 'w') ) {
		fwrite($fh, date('[d/M/Y H:i:s O] ') . '[' . $nfw_options['admin_name'] . '] ' .
		'[' . $_SERVER['REMOTE_ADDR'] . '] ' . '[OK] ' .  "\n" );
		fclose($fh);
	}
}

/* ================================================================== */

function nfw_help($what) {

require($what);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>NinjaFirewall : <?php echo $title ?></title>
	<link href="static/styles.css" rel="stylesheet" type="text/css">
	<link rel="Shortcut Icon" type="image/gif" href="static/favicon.ico">
</head>
<body bgcolor="white" class="smallblack">
	<fieldset><legend>&nbsp;<b><?php echo $title ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td>
				<?php echo $nfw_help ?>
				</td>
			</tr>
		</table>
	</fieldset>
	<br />
	<center><input type="button" value="<?php echo $close ?>" onclick="window.close()" /></center>
</body>
</html>
<?php

exit;

}

/* ================================================================== */

function nfw_changelog() {

require('changelog.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>NinjaFirewall : changelog</title>
	<link href="static/styles.css" rel="stylesheet" type="text/css">
	<link rel="Shortcut Icon" type="image/gif" href="static/favicon.ico">
</head>
<body bgcolor="white" class="smallblack">
	<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
		<tr>
			<td width="100%" align="center"><textarea style="background-color:#ffffff;width:95%;height:380px;border:1px solid #FDCD25;padding:4px;font-family:monospace;font-size:13px;"><?php echo htmlentities($changelog) ?></textarea></td>
		</tr>
	</table>
	<br />
	<center><input type="button" value="Close" onclick="window.close()" /></center>
</body>
</html>
<?php
exit;

}

/* ================================================================== */

function html_header() {

	global $nfw_options;

	require (__DIR__ . '/lib/lang/' . $nfw_options['admin_lang'] . '/' . basename(__FILE__) );

	$menu = array(
		10 => $lang['summ_main'] . ' &gt; ' . $lang['summ_over'],
		11 => $lang['summ_main'] . ' &gt; ' . $lang['summ_stat'],
		20 => $lang['acc_main'] . ' &gt; ' . $lang['acc_opt'],
		21 => $lang['acc_main'] . ' &gt; ' . $lang['acc_lic'],
		22 => $lang['acc_main'] . ' &gt; ' . $lang['acc_upd'],
		30 => $lang['fwl_main'] . ' &gt; ' . $lang['fwl_opt'],
		31 => $lang['fwl_main'] . ' &gt; ' . $lang['fwl_pol'],
		32 => $lang['fwl_main'] . ' &gt; ' . $lang['fwl_ac'],
		33 => $lang['fwl_main'] . ' &gt; ' . $lang['fwl_fg'],
		34 => $lang['fwl_main'] . ' &gt; ' . $lang['fwl_wf'],
		35 => $lang['fwl_main'] . ' &gt; ' . $lang['fwl_edit'],
		36 => $lang['fwl_main'] . ' &gt; ' . $lang['fwl_log'],
		37 => $lang['fwl_main'] . ' &gt; ' . $lang['fwl_livelog'],
		38 => $lang['fwl_main'] . ' &gt; ' . $lang['fwl_fc']
	);
	$m10 = $m11 = $m12 =
	$m20 = $m21 = $m22 =
	$m30 = $m31 = $m32 =
	$m33 = $m34 = $m35 =
	$m36 = $m37 = $m38 = 'static/bullet_off.gif';

	if    ( $GLOBALS['mid'] == 10 ) $m10 = 'static/bullet_on.gif';
	elseif( $GLOBALS['mid'] == 11 ) $m11 = 'static/bullet_on.gif';
	elseif( $GLOBALS['mid'] == 20 ) $m20 = 'static/bullet_on.gif';
	elseif( $GLOBALS['mid'] == 21 ) $m21 = 'static/bullet_on.gif';
	elseif( $GLOBALS['mid'] == 22 ) $m22 = 'static/bullet_on.gif';
	elseif( $GLOBALS['mid'] == 30 ) $m30 = 'static/bullet_on.gif';
	elseif( $GLOBALS['mid'] == 31 ) $m31 = 'static/bullet_on.gif';
	elseif( $GLOBALS['mid'] == 32 ) $m32 = 'static/bullet_on.gif';
	elseif( $GLOBALS['mid'] == 33 ) $m33 = 'static/bullet_on.gif';
	elseif( $GLOBALS['mid'] == 34 ) $m34 = 'static/bullet_on.gif';
	elseif( $GLOBALS['mid'] == 35 ) $m35 = 'static/bullet_on.gif';
	elseif( $GLOBALS['mid'] == 36 ) $m36 = 'static/bullet_on.gif';
	elseif( $GLOBALS['mid'] == 37 ) $m37 = 'static/bullet_on.gif';
	elseif( $GLOBALS['mid'] == 38 ) $m38 = 'static/bullet_on.gif';

	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>NinjaFirewall : '. $menu[$GLOBALS['mid']] .'</title>
	<link href="static/styles.css" rel="stylesheet" type="text/css">
	<link rel="Shortcut Icon" type="image/gif" href="static/favicon.ico">
	<script>function disconnect(who){if (confirm("' . $lang['close_sess'] . ' ["+who+"] ?")){return true};return false;}function popup(url,width,height,scroll_bar) {height=height+20;width=width+20;var str = "height=" + height + ",innerHeight=" + height;str += ",width=" + width + ",innerWidth=" + width;if (window.screen){var ah = screen.availHeight - 30;var aw = screen.availWidth -10;var xc = (aw - width) / 2;var yc = (ah - height) / 2;str += ",left=" + xc + ",screenX=" + xc;str += ",top=" + yc + ",screenY=" + yc;if (scroll_bar) {str += ",scrollbars=no";}else {str += ",scrollbars=yes";}str += ",status=no,location=no,resizable=yes";}win = open(url, "nfpop", str);setTimeout("win.window.focus()",300);}</script>
</head>
<body bgcolor="white" class="smallblack">

<table style="border:0px solid #666666;" width="100%">
	<tr>
		<td align="left" width="250">
			<img src="static/logo.png" width="192" height="62">
		</td>
		<td align="center">
			<div class="error" style="display:none;width:90%;text-align:left" id="error_table"><p>' . $lang['not_working'] . '.<br />' . $lang['err_message'] . '&nbsp;: <font id="error_msg">' . @$GLOBALS['err_fw'][NF_DISABLED] . '</font></p></div>
		</td>
		<td align="right">
			<a href="?logout" onclick="return disconnect(\''. $nfw_options['admin_name'] .'\');"><img border="0" src="static/logout.png" width="52" height="52" title="Logout" alt="Logout" style="'.OPA.'" onmouseover="'.OPA_OVER.'" onmouseout="'.OPA_OUT.'"></a>
		</td>
	</tr>
</table>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td width="150" align="left"><br />
			<table border="0" width="150" height="400" cellpadding="0" cellspacing="7">
				<tr valign="top">
					<td class="tinyblack">
						<center style="border:1px solid #FDCD25;">' . $lang['summ_main'] . '</center>
					</td>
				</tr>
				<tr>
					<td class="tinyblack">
						<img src="'. $m10 .'" width="10" height="10">&nbsp;<a class="links" href="?mid=10&token='.$_REQUEST['token'].'">' . $lang['summ_over'] . '</a>
					</td>
				</tr>
				<tr>
					<td class="tinyblack">
						<img src="'. $m11 .'" width="10" height="10">&nbsp;<a class="links" href="?mid=11&token='.$_REQUEST['token'].'">' . $lang['summ_stat'] . '</a>
					</td>
				</tr>
				<tr>
					<td class="tinyblack">
						<center style="border:1px solid #FDCD25;">' . $lang['acc_main'] . '</center>
					</td>
				</tr>
				<tr>
					<td class="tinyblack">
						<img src="'. $m20 .'" width="10" height="10">&nbsp;<a class="links" href="?mid=20&token='.$_REQUEST['token'].'">' . $lang['acc_opt'] . '</a>
					</td>
				</tr>
				<tr>
					<td class="tinyblack">
						<img src="'. $m21 .'" width="10" height="10">&nbsp;<a class="links" href="?mid=21&token='.$_REQUEST['token'].'">' . $lang['acc_lic'] . '</a>
					</td>
				</tr>
				<tr>
					<td class="tinyblack">
						<img src="'. $m22 .'" width="10" height="10">&nbsp;<a class="links" href="?mid=22&token='.$_REQUEST['token'].'">' . $lang['acc_upd'] . '</a>
					</td>
				</tr>
				<tr>
					<td class="tinyblack">
						<center style="border:1px solid #FDCD25;">' . $lang['fwl_main'] . '</center>
					</td>
				</tr>
				<tr>
					<td class="tinyblack">
						<img src="'. $m30 .'" width="10" height="10">&nbsp;<a class="links" href="?mid=30&token='.$_REQUEST['token'].'">' . $lang['fwl_opt'] . '</a>
					</td>
				</tr>
				<tr>
					<td class="tinyblack">
						<img src="'. $m31 .'" width="10" height="10">&nbsp;<a class="links" href="?mid=31&token='.$_REQUEST['token'].'">' . $lang['fwl_pol'] . '</a>
					</td>
				</tr>
				<tr>
					<td class="tinyblack">
						<img src="'. $m32 .'" width="10" height="10">&nbsp;<a class="links" href="?mid=32&token='.$_REQUEST['token'].'">' . $lang['fwl_ac'] . ' (<font color="#FF0000">Pro+</font>)</a>
					</td>
				</tr>
				<tr>
					<td class="tinyblack">
						<img src="'. $m33 .'" width="10" height="10">&nbsp;<a class="links" href="?mid=33&token='.$_REQUEST['token'].'">' . $lang['fwl_fg'] . ' (<font color="#FF0000">Pro+</font>)</a>
					</td>
				</tr>
				<tr>
					<td class="tinyblack">
						<img src="'. $m38 .'" width="10" height="10">&nbsp;<a class="links" href="?mid=38&token='.$_REQUEST['token'].'">' . $lang['fwl_fc'] . '</a>
					</td>
				</tr>
				<tr>
					<td class="tinyblack">
						<img src="'. $m34 .'" width="10" height="10">&nbsp;<a class="links" href="?mid=34&token='.$_REQUEST['token'].'">' . $lang['fwl_wf'] . ' (<font color="#FF0000">Pro+</font>)</a>
					</td>
				</tr>
				<tr>
					<td class="tinyblack">
						<img src="'. $m35 .'" width="10" height="10">&nbsp;<a class="links" href="?mid=35&token='.$_REQUEST['token'].'">' . $lang['fwl_edit'] . '</a>
					</td>
				</tr>
				<tr>
					<td class="tinyblack">
						<img src="'. $m36 .'" width="10" height="10">&nbsp;<a class="links" href="?mid=36&token='.$_REQUEST['token'].'">' . $lang['fwl_log'] . '</a>
					</td>
				</tr>
				<tr>
					<td class="tinyblack">
						<img src="'. $m37 .'" width="10" height="10">&nbsp;<a class="links" href="?mid=37&token='.$_REQUEST['token'].'">' . $lang['fwl_livelog'] . ' (<font color="#FF0000">Pro+</font>)</a>
					</td>
				</tr>
				<tr>
					<td style="text-align:center;"><p><img src="static/logopro_60.png" width="60" height="60"></p>
					</td>
				</tr>
			</table>
		</td>
		<td width="20">&nbsp;</td>
		<td>
			<table style="border:0px solid #666666;" width="100%" cellpadding=6>
				<tr>
					<td class=smallblack><div style="float:left;"><b>'. $menu[$GLOBALS['mid']] .'</b></div><div style="float:right;border:1px solid #FDCD25;padding:2px"><a href="javascript:popup(\'?mid='. $GLOBALS['mid'] .'&help=1&token=' . $_REQUEST['token'] . '\',640,480,0);" class="links"><strong>' . $lang['help'] . '</strong></a></div><br />';

}

/* ================================================================== */

function html_footer() {

	// Check if NF is enabled :
	if (! defined('NF_DISABLED') ) {
		is_nf_enabled();
	}
   echo'
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<center class=tinygrey><a href="http://ninjamonitoring.com/" title="NinjaMonitoring : monitor your website for suspicious activities"><img src="static/p_icon_nm.png" height="21" width="21" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://ninjafirewall.com/" title="NinjaFirewall : advanced firewall software for all your PHP applications"><img src="static/p_icon_nf.png" height="21" width="21" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://ninjarecovery.com/" title="NinjaRecovery : incident response, malware removal &amp; hacking recovery" ><img src="static/p_icon_nr.png" height="21" width="21" border="0"></a><br />&copy; 2011-'. date('Y') .' <a style="border-bottom:dotted 1px #FDCD25;color:#999999;" href="http://nintechnet.com/" target="_blank" title="The Ninja Technologies Network">NinTechNet</a><br />The Ninja Technologies Network</center>';

	// Show error message:
	if ( NF_DISABLED && $GLOBALS['mid'] != 10 ) {
		if (! empty($GLOBALS['err_fw'][NF_DISABLED]) ) {
			$msg = $GLOBALS['err_fw'][NF_DISABLED];
		} else {
			$msg = 'Unknown error #' . NF_DISABLED;
		}
		echo '<script>	function show_err(){document.getElementById("error_table").style.display = "";document.getElementById("error_msg").innerHTML = \'' . $msg . '\';}	window.onload = show_err;</script>';
	}

	echo '
</body>
</html>';
   exit;
}

/* ================================================================== */
// EOF
