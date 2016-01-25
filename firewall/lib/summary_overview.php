<?php
/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (Pro edition)                                         |
 |                                                                     |
 | (c) NinTechNet - http://nintechnet.com/                             |
 |                                                                     |
 +---------------------------------------------------------------------+
 | REVISION: 2015-06-17 22:08:26                                       |
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

// Load current language file :
require (__DIR__ .'/lang/' . $nfw_options['admin_lang'] . '/' . basename(__FILE__) );

html_header();

?>
<br />
<fieldset><legend>&nbsp;<b><?php echo $lang['info'] ?></b>&nbsp;</legend>
	<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
<?php

// Is NF enabled/working ?
if (! defined('NF_DISABLED') ) {
	is_nf_enabled();
}
if (NF_DISABLED) {
	if (! empty($GLOBALS['err_fw'][NF_DISABLED]) ) {
		$msg = $GLOBALS['err_fw'][NF_DISABLED];
	} else {
		$msg = 'Unknown error #' . NF_DISABLED;
	}
	?>
	<tr valign="middle">
		<td width="45%"><?php echo $lang['firewall'] ?></td>
		<td width="10%" align="center"><img src="static/icon_error.png" border="0" width="21" height="21" title="<?php echo $lang['warning'] ?> !"></td>
		<td width="45%"><?php echo $lang['not_working'] . '. ' . $lang['err_message'] . '&nbsp;: ' . $msg ?>
		</td>
	</tr>
	<?php
} else {
	?>
	<tr valign="middle">
		<td width="45%"><?php echo $lang['firewall'] ?></td>
		<td width="10%" align="center">&nbsp;</td>
		<td width="45%"><?php echo $lang['enabled'] ?></td>
	</tr>
	<?php
}

// Check for update :
$connect_err = 0;
if ( $_SESSION['ver'] < 1 && NFW_UPDATE ) {
	$tmp = '';
	if (function_exists('curl_init') ) {
		$data  = 'action=checkversion';
		$data .= '&edn=' . NFW_EDN;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_USERAGENT, 'NinjaFirewall/' . NFW_ENGINE_VERSION . ':' . NFW_EDN );
		curl_setopt( $ch, CURLOPT_ENCODING, '');
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
		curl_setopt( $ch, CURLOPT_URL, 'http://'. NFW_UPDATE .'/index.php' );
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
		curl_setopt ($ch, CURLOPT_HEADER, 0);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		$tmp = @unserialize( curl_exec($ch) );
		$response = curl_getinfo( $ch );
		curl_close($ch);

		if ( empty($tmp[NFW_EDN]) || $response['http_code'] != 200 ) {
			$_SESSION['vapp'] = NFW_ENGINE_VERSION;
			$connect_err = 1;
		} else {
			$_SESSION['vapp'] = $tmp[NFW_EDN];
		}
		$_SESSION['ver'] = 1;
	}
}

?>
	<tr valign="middle">
		<td width="45%"><?php echo $lang['license'] ?></td>
		<td width="10%" align="center">&nbsp;</td>
		<td width="45%"><?php echo $lang['na']  ?></td>
	</tr>

	<tr valign="middle">
		<td width="45%"><?php echo $lang['engine_ver'] ?></td>
		<td width=10% align="center">
<?php

if ($_SESSION['ver'] == 0) {
?>
			<img src="static/icon_warn.png" border="0" width="21" height="21">
		</td>
		<td width="45%"><?php echo $lang['failed_connect'] ?>&nbsp;!</td>
	</tr>
<?php
} else {

	if ( version_compare( NFW_ENGINE_VERSION, $_SESSION['vapp'], '<' ) ) {
		?>
		<img src="static/icon_warn.png" border="0" width="21" height="21"></td>
		<td width="45%"><a class="links" style="border-bottom:1px dotted #FFCC25;" href="?mid=22&token=<?php echo $_REQUEST['token'] ?>"><?php echo $lang['new_engine'] ?></a></td>
		</tr>
		<?php
	} else {
		// HTTP error while checking the version ?
		if ( $connect_err ) {
			?>
			<img src="static/icon_warn.png" border="0" width="21" height="21"></td>
			<td width="45%"><?php echo $lang['failed_connect'] ?></td>
			</tr>
      <?php
		} else {
			?>&nbsp;</td>
			<td width="45%"><?php echo $lang['lic_free'] . ' ' . NFW_ENGINE_VERSION . ' (<a class="links" style="border-bottom:1px dotted #FFCC25;" href="http://nintechnet.com/ninjafirewall/pro-edition/">'. $lang['lic_upgrade'] . '</a>)' ?></td>
			</tr>
			<?php
		}
	}
}

// Is debug mode on ?
if ( $nfw_options['debug'] ) {
	?>
	<tr valign="middle">
		<td width="45%"><?php echo $lang['debugging'] ?></td>
		<td width="10%" align="center">
			<img src="static/icon_warn.png" border="0" width="21" height="21">
		</td>
		<td width="45%">
			<?php printf( $lang['debug_warn'], '<a class="links" style="border-bottom:1px dotted #FFCC25;" href="?mid=30&token='.$_REQUEST['token'].'">') ?>
		</td>
	</tr>
	<?php
}
// Is logging on ?
if (! $nfw_options['logging'] ) {
	?>
	<tr valign="middle">
		<td width="45%"><?php echo $lang['logging'] ?></td>
		<td width="10%" align="center">
			<img src="static/icon_warn.png" border="0" width="21" height="21">
		</td>
		<td width="45%">
			<?php printf( $lang['logging_warn'], '<a class="links" style="border-bottom:1px dotted #FFCC25;" href="?mid=36&token='.$_REQUEST['token'].'">') ?>
		</td>
	</tr>
	<?php
}


if ( NFW_EDN == 2 ) {
	// Pro+ edn :
	$IPlink = '<a class="links" style="border-bottom:1px dotted #FFCC25;" href="?mid=32&token=' . $_REQUEST['token'] . '">';
	// Check IP :
	if ( empty($nfw_options['ac_ip']) || $nfw_options['ac_ip'] == 1 ) {
		$user_ip = $_SERVER['REMOTE_ADDR'];
	} elseif ( $nfw_options['ac_ip'] == 2 && ! empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
		$nfw_match = array_map('trim', @explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
		krsort($nfw_match);
		foreach($nfw_match as $nfw_m) {
			if ( filter_var($nfw_m, FILTER_VALIDATE_IP) )  {
				$user_ip = $nfw_m;
				break;
			}
		}
	} elseif ( $nfw_options['ac_ip'] == 3 && ! empty($nfw_options['ac_ip_2']) && ! empty($_SERVER[$nfw_options['ac_ip_2']]) ) {
		$nfw_match = array_map('trim', @explode(',', $_SERVER[$nfw_options['ac_ip_2']]));
		krsort($nfw_match);
		foreach($nfw_match as $nfw_m) {
			if ( filter_var($nfw_m, FILTER_VALIDATE_IP) )  {
				$user_ip = $nfw_m;
				break;
			}
		}
	}
} else {
	// Pro edn :
	$IPlink = '<a class="links" style="border-bottom:1px dotted #FFCC25;" href="http://nintechnet.com/ninjafirewall/pro-edition/help/?htninja">';
}

if ( empty($user_ip) ) {
	$user_ip = $_SERVER['REMOTE_ADDR'];
}


// Check IP and warn if localhost or private IP :
if (! filter_var($user_ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) ) {
	?>
	<tr valign="middle">
		<td width="45%"><?php echo $lang['source_ip'] ?></td>
		<td width="10%" align="center">
			<img src="static/icon_warn.png" border="0" width="21" height="21">
		</td>
		<td width="45%"><?php echo $lang['lo_warn'] . ' ' . htmlspecialchars($user_ip) . '<br />' ?>
		<?php printf($lang['lo_check'], $IPlink) ?>
		</td>
	</tr>
	<?php
}

// Look for CDN's (Incapsula/Cloudflare) and warn the user about using
// the correct IPs, unless it was added to the access control list :
if (! empty($_SERVER["HTTP_CF_CONNECTING_IP"]) ) {
	if ( $user_ip != $_SERVER["HTTP_CF_CONNECTING_IP"] ) {
		// CloudFlare :
		?>
		<tr valign="middle">
			<td width="45%"><?php echo $lang['cdn_title'] ?></td>
			<td width="10%" align="center">
				<img src="static/icon_warn.png" border="0" height="21" width="21">
			</td>
			<td width="45%">
				<?php
				if ( NFW_EDN == 1 ){
					printf($lang['cdn_clouflare_free'], $IPlink);
				} else {
					printf($lang['cdn_clouflare_pro'], $IPlink);
				}
				?>
			</td>
		</tr>
		<?php
	}
}
if (! empty($_SERVER["HTTP_INCAP_CLIENT_IP"]) ) {
	if ( $user_ip != $_SERVER["HTTP_INCAP_CLIENT_IP"] ) {
		// Incapsula :
		?>
		<tr valign="middle">
			<td width="45%"><?php echo $lang['cdn_title'] ?></td>
			<td width="10%" align="center">
				<img src="static/icon_warn.png" border="0" height="21" width="21">
			</td>
			<td width="45%">
				<?php
				if ( NFW_EDN == 1 ){
					printf($lang['cdn_incapsula_free'], $IPlink);
				} else {
					printf($lang['cdn_incapsula_pro'], $IPlink);
				}
				?>
			</td>
		</tr>
		<?php
	}
}

// Check if the ./nfwlog/ directory is writable :
if (! is_writable('./nfwlog') ) {
	?>
	<tr valign="middle">
		<td width="45%"><?php echo $lang['log_dir'] ?></td>
		<td width="10%" align="center"><img src="static/icon_error.png" border="0" width="21" height="21"></td>
		<td width="45%"><?php printf( $lang['dir_readonly'], '/nfwlog/') ?>&nbsp;! <?php echo $lang['chmod777'] ?></td>
	</tr>
	<?php
}
// Check if the ./nfwlog/cache/ directory is writable :
if (! is_writable('./nfwlog/cache/') ) {
	?>
	<tr valign="middle">
		<td width="45%"><?php echo $lang['cache_dir'] ?></td>
		<td width="10%" align="center"><img src="static/icon_error.png" border="0" width="21" height="21"></td>
		<td width="45%"><?php printf( $lang['dir_readonly'], '/nfwlog/cache/') ?>&nbsp;! <?php echo $lang['chmod777'] ?></td>
	</tr>
	<?php
}

// Check if the ./conf/ directory is writable :
if (! is_writable('./conf') ) {
	?>
	<tr valign="middle">
		<td width="45%"><?php echo $lang['conf_dir'] ?></td>
		<td width="10%" align="center"><img src="static/icon_error.png" border="0" width="21" height="21"></td>
		<td width="45%"><?php printf( $lang['dir_readonly'], '/conf/') ?>&nbsp;! <?php echo $lang['chmod777'] ?></td>
	</tr>
	<?php
}
// Optional NinjaFirewall .htninja configuration file
// ( see http://nintechnet.com/ninjafirewall/pro-edition/help/?htninja ) :
if ( @file_exists( $file = dirname($_SERVER['DOCUMENT_ROOT'] ) . '/.htninja') ||
		@file_exists( $file = $_SERVER['DOCUMENT_ROOT'] . '/.htninja') ) {
	?>
	<tr>
		<td width="45%"><?php echo $lang['htninja'] ?></td>
	<?php
	if ( is_writable($file) ) {
	?>
		<td width="10%" align="center"><img src="static/icon_warn.png" border="0" width="21" height="21"></td>
		<td width="45%"><?php printf( $lang['htninja_writable'], '<code>' . $file . '</code>' ) ?></td>
	</tr>
	<?php
	} else {
	?>
		<td width="10%" align="center">&nbsp;</td>
		<td><code><?php echo $file ?></code></td>
	</tr>
	<?php
	}
}
// Check admin log :
if ( file_exists('./nfwlog/admin.php') ) {
	$nfw_stat = file( './nfwlog/admin.php', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
	// Skip the last line, it is us :
	array_pop($nfw_stat);
	while ($nfw_stat) {
		// Get last connection :
		$line = array_pop($nfw_stat);
		if ( preg_match('/^\[([^\s]+)\s+([^\s]+).+?\]\s+\[(.+?)\]\s+\[(.+?)\]\s+\[OK/', $line, $match) ) {
			break;
		}
	}
	if (! empty($match[1]) ) {
		?>
		<tr valign="middle">
			<td width="45%"><?php echo $lang['last_login'] ?></td>
			<td width="10%" align="center">&nbsp;</td>
			<td width="45%"><a class="links" style="border-bottom:1px dotted #FFCC25;" href="javascript:popup('?mid=90&token=<?php echo $_REQUEST['token'] ?>',640,480,0)"><?php echo htmlspecialchars($match[3]) . ' (' . htmlspecialchars($match[4]) . ')</a> ' . str_replace('/', '-', $match[1]) . ' @ '. $match[2] ?></td>
		</tr>
		<?php
	}
}

?>
	</table>
</fieldset>
<?php

html_footer();

/* ------------------------------------------------------------------ */
// EOF
