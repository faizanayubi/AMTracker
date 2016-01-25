<?php
/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (Pro edition)                                         |
 |                                                                     |
 | (c) NinTechNet - http://nintechnet.com/                             |
 |                                                                     |
 +---------------------------------------------------------------------+
 | REVISION: 2015-10-28 19:24:52                                       |
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

// Saved options ?
if (! empty($_POST['post']) ) {
	if ( $_POST['Save'] == $lang['default_button'] ) {
		// Restore default values:
		$err_msg = restore_firewall_policies();
	} else {
		// Save new configuration:
		$err_msg = save_firewall_policies();
	}
	if ($err_msg) {
		echo '<br /><div class="error"><p>' . $err_msg .'</p></div>';
   } else {
		echo '<br /><div class="success"><p>'. $lang['saved_conf'] . '</p></div>';
   }
}

?>
<script>
function httponly() {
	if (confirm("<?php echo $lang['httponly_warn'] ?>")){
		return true;
	}
	return false;
}
function is_number(id) {
	var e = document.getElementById(id);
	if (! e.value ) { return }
	if (! /^[0-9]+$/.test(e.value) ) {
		alert("<?php echo $lang['numbers_only'] ?>");
		e.value = e.value.substring(0, e.value.length-1);
	}
}
function san_onoff(what) {
	if (what == 0) {
		document.fwrules.sanid.disabled = true;
		document.fwrules.sizeid.disabled = true;
	} else {
		document.fwrules.sanid.disabled = false;
		document.fwrules.sizeid.disabled = false;
	}
}
function restore() {
   if (confirm("<?php echo $lang['default_js'] ?>")){
      return true;
   }else{
		return false;
   }
}
</script>

<?php
if ( empty( $nfw_options['scan_protocol']) || ! preg_match( '/^[123]$/', $nfw_options['scan_protocol']) ) {
	$nfw_options['scan_protocol'] = 3;
}
?>
<br />
<form method="post" name="fwrules">

	<fieldset><legend>&nbsp;<b><?php echo $lang['http_title'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td width="55%" align="left"><?php echo $lang['http_enable'] ?></td>
				<td width="45%">
					<p><label><input type="radio" name="scan_protocol" value="3"<?php checked($nfw_options['scan_protocol'], 3 ) ?>>&nbsp;<?php echo $lang['http_https'] . $lang['default'] ?></label></p>
					<p><label><input type="radio" name="scan_protocol" value="1"<?php checked($nfw_options['scan_protocol'], 1 ) ?>>&nbsp;<?php echo $lang['http'] ?></label></p>
					<p><label><input type="radio" name="scan_protocol" value="2"<?php checked($nfw_options['scan_protocol'], 2 ) ?>>&nbsp;<?php echo $lang['https'] ?></label></p>
				</td>
			</tr>
		</table>
	</fieldset>

	<br />
	<br />
	<?php
	if ( empty( $nfw_options['sanitise_fn']) ) {
		$nfw_options['sanitise_fn'] = 0;
	} else {
		$nfw_options['sanitise_fn'] = 1;
	}
	if ( empty($nfw_options['uploads']) || ! preg_match( '/^[12]$/', $nfw_options['uploads']) ) {
		$nfw_options['uploads'] = 0;
	}
	if ( empty( $nfw_options['upload_maxsize']) ) {
		$upload_maxsize = 1024;
	} else {
		$upload_maxsize = $nfw_options['upload_maxsize'] / 1024;
	}
	?>
	<fieldset><legend>&nbsp;<b><?php echo $lang['upload_title'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td width="55%" align="left"><?php echo $lang['uploads'] ?></td>
				<td width="45%">
				<p><label><input type="radio" name="uploads"<?php checked( $nfw_options['uploads'], 0 ) ?> value="0" id="uf1" onClick="san_onoff(0);">&nbsp;<?php echo $lang['disallow_upl'] . $lang['default'] ?></label></p>
				<p><label><input type="radio" name="uploads"<?php checked( $nfw_options['uploads'], 1 ) ?> value="1" id="uf1" onClick="san_onoff(1);">&nbsp;<?php echo $lang['allow_upl'] ?></label></p>
				<p><label><input type="radio" name="uploads"<?php checked( $nfw_options['uploads'], 2 ) ?> value="2" id="uf2" onClick="san_onoff(1);">&nbsp;<?php echo $lang['allow_but'] ?></label></p>
				<br />
				<p><label><input id="sanid" type="checkbox" name="sanitise_fn"<?php checked( $nfw_options['sanitise_fn'], 1 ); disabled( $nfw_options['uploads'], 0 ) ?>>&nbsp;<?php echo $lang['sanit_fn'] ?></label></p>
				<p>&nbsp;<?php echo $lang['mxsize_fn'] ?> <input class="input" id="sizeid" type="text" name="upload_maxsize"<?php disabled( $nfw_options['uploads'], 0 ) ?> size="5" value="<?php echo $upload_maxsize ?>" onkeyup="is_number('sizeid')">&nbsp;<?php echo $lang['kb'] ?></p>
				</td>
			</tr>
		</table>
	</fieldset>

	<br />
	<br />

	<?php
	if ( empty( $nfw_options['get_scan']) ) {
		$nfw_options['get_scan'] = 0;
	} else {
		$nfw_options['get_scan'] = 1;
	}
	if ( empty( $nfw_options['get_sanitise']) ) {
		$nfw_options['get_sanitise'] = 0;
	} else {
		$nfw_options['get_sanitise'] = 1;
	}
	?>
	<fieldset><legend>&nbsp;<b><?php echo $lang['http_get'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td width="55%" align="left"><?php echo $lang['scan_get'] ?></td>
				<td width="45%">
				<p><label><input type="radio" name="get_scan" value="1"<?php checked( $nfw_options['get_scan'], 1 ) ?>>&nbsp;<?php echo $lang['yes'] . $lang['default'] ?></label></p>
				<p><label><input type="radio" name="get_scan" value="0"<?php checked( $nfw_options['get_scan'], 0 ) ?>>&nbsp;<?php echo $lang['no'] ?></label></p>
				</td>
			</tr>
			<tr>
				<td width="55%" align="left" class="dotted"><?php echo $lang['sanit_get'] ?></td>
				<td width="45%" class="dotted">
				<p><label><input type="radio" name="get_sanitise" value="1"<?php checked( $nfw_options['get_sanitise'], 1 ) ?>>&nbsp;<?php echo $lang['yes'] ?></label></p>
				<p><label><input type="radio" name="get_sanitise" value="0"<?php checked( $nfw_options['get_sanitise'], 0 ) ?>>&nbsp;<?php echo $lang['no'] . $lang['default'] ?></label></p>
				</td>
			</tr>
		</table>
	</fieldset>

	<br />
	<br />

	<?php
	if ( empty( $nfw_options['post_scan']) ) {
		$nfw_options['post_scan'] = 0;
	} else {
		$nfw_options['post_scan'] = 1;
	}
	if ( empty( $nfw_options['post_sanitise']) ) {
		$nfw_options['post_sanitise'] = 0;
	} else {
		$nfw_options['post_sanitise'] = 1;
	}
	if ( empty( $nfw_options['post_b64']) ) {
		$nfw_options['post_b64'] = 0;
	} else {
		$nfw_options['post_b64'] = 1;
	}
	?>
	<fieldset><legend>&nbsp;<b><?php echo $lang['http_post'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td width="55%" align="left"><?php echo $lang['scan_post'] ?></td>
				<td width="45%">
				<p><label><input type="radio" name="post_scan" value="1"<?php checked( $nfw_options['post_scan'], 1 ) ?>>&nbsp;<?php echo $lang['yes'] . $lang['default'] ?></label></p>
				<p><label><input type="radio" name="post_scan" value="0"<?php checked( $nfw_options['post_scan'], 0 ) ?>>&nbsp;<?php echo $lang['no'] ?></label></p>
				</td>
			</tr>
			<tr>
				<td width="55%" align="left" class="dotted"><?php echo $lang['sanit_post'] ?></td>
				<td width="45%" class="dotted">
				<p><label><input type="radio" name="post_sanitise" value="1"<?php checked( $nfw_options['post_sanitise'], 1 ) ?>>&nbsp;<?php echo $lang['yes'] ?></label></p>
				<p><label><input type="radio" name="post_sanitise" value="0"<?php checked( $nfw_options['post_sanitise'], 0 ) ?>>&nbsp;<?php echo $lang['no'] . $lang['default'] ?></label></p>
				<i class="tinyblack">&nbsp;<?php echo $lang['sanit_warn'] ?></i>
				</td>
			</tr>
			<tr>
				<td width="55%" align="left" class="dotted"><?php echo $lang['decode_b64'] ?></td>
				<td width="45%" class="dotted">
				<p><label><input type="radio" name="post_b64" value="1"<?php checked( $nfw_options['post_b64'], 1 ) ?>>&nbsp;<?php echo $lang['yes'] . $lang['default'] ?></label></p>
				<p><label><input type="radio" name="post_b64" value="0"<?php checked( $nfw_options['post_b64'], 0 ) ?>>&nbsp;<?php echo $lang['no'] ?></label></p>
				</td>
			</tr>
		</table>
	</fieldset>

	<br />
	<br />

	<?php
	if ( empty( $nfw_options['request_sanitise']) ) {
		$nfw_options['request_sanitise'] = 0;
	} else {
		$nfw_options['request_sanitise'] = 1;
	}
	?>
	<fieldset><legend>&nbsp;<b><?php echo $lang['http_request'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td width="55%" align="left"><?php echo $lang['sanit_request'] ?></td>
				<td width="45%" align="left">
					<p><label><input type="radio" name="request_sanitise" value="1"<?php checked( $nfw_options['request_sanitise'], 1 ) ?>>&nbsp;<?php echo $lang['yes'] ?></label></p>
					<p><label><input type="radio" name="request_sanitise" value="0"<?php checked( $nfw_options['request_sanitise'], 0 ) ?>>&nbsp;<?php echo $lang['no'] . $lang['default'] ?></label></p>
				</td>
			</tr>
		</table>
	</fieldset>

	<br />
	<br />

	<?php
	if ( empty( $nfw_options['cookies_scan']) ) {
		$nfw_options['cookies_scan'] = 0;
	} else {
		$nfw_options['cookies_scan'] = 1;
	}
	if ( empty( $nfw_options['cookies_sanitise']) ) {
		$nfw_options['cookies_sanitise'] = 0;
	} else {
		$nfw_options['cookies_sanitise'] = 1;
	}
	?>
	<fieldset><legend>&nbsp;<b><?php echo $lang['cookies'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td width="55%" align="left"><?php echo $lang['scan_cookies'] ?></td>
				<td width="45%" align="left">
					<p><label><input type="radio" name="cookies_scan" value="1"<?php checked( $nfw_options['cookies_scan'], 1 ) ?>>&nbsp;<?php echo $lang['yes'] . $lang['default'] ?></label></p>
					<p><label><input type="radio" name="cookies_scan" value="0"<?php checked( $nfw_options['cookies_scan'], 0 ) ?>>&nbsp;<?php echo $lang['no'] ?></label></p>
				</td>
			</tr>
			<tr>
				<td width="55%" align="left" class="dotted"><?php echo $lang['sanit_cookies'] ?></td>
				<td width="45%" align="left" class="dotted">
					<p><label><input type="radio" name="cookies_sanitise" value="1"<?php checked( $nfw_options['cookies_sanitise'], 1 ) ?>>&nbsp;<?php echo $lang['yes'] ?></label></p>
					<p><label><input type="radio" name="cookies_sanitise" value="0"<?php checked( $nfw_options['cookies_sanitise'], 0 ) ?>>&nbsp;<?php echo $lang['no'] . $lang['default'] ?></label></p>
				</td>
			</tr>
		</table>
	</fieldset>

	<br />
	<br />

	<?php
	if ( empty( $nfw_options['ua_scan']) ) {
		$nfw_options['ua_scan'] = 0;
	} else {
		$nfw_options['ua_scan'] = 1;
	}
	if ( empty( $nfw_options['ua_sanitise']) ) {
		$nfw_options['ua_sanitise'] = 0;
	} else {
		$nfw_options['ua_sanitise'] = 1;
	}
	if ( empty( $nfw_options['ua_mozilla']) ) {
		$nfw_options['ua_mozilla'] = 0;
	} else {
		$nfw_options['ua_mozilla'] = 1;
	}
	if ( empty( $nfw_options['ua_accept']) ) {
		$nfw_options['ua_accept'] = 0;
	} else {
		$nfw_options['ua_accept'] = 1;
	}
	if ( empty( $nfw_options['ua_accept_lang']) ) {
		$nfw_options['ua_accept_lang'] = 0;
	} else {
		$nfw_options['ua_accept_lang'] = 1;
	}
	if ( NFW_EDN == 1 ) {
		// Pro Edn only, as this is managed by the
		// Access Control page in the Pro+ Edn. :
		if ( empty( $nfw_rules[NFW_SCAN_BOTS]['on']) ) {
			$block_bots = 0;
		} else {
			$block_bots = 1;
		}
	}
	?>
	<fieldset><legend>&nbsp;<b><?php echo $lang['ua'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td width="55%" align="left"><?php echo $lang['scan_ua'] ?></td>
				<td width="45%" align="left">
					<p><label><input type="radio" name="ua_scan" value="1"<?php checked( $nfw_options['ua_scan'], 1 ) ?>>&nbsp;<?php echo $lang['yes'] . $lang['default'] ?></label></p>
					<p><label><input type="radio" name="ua_scan" value="0"<?php checked( $nfw_options['ua_scan'], 0 ) ?>>&nbsp;<?php echo $lang['no'] ?></label></p>
				</td>
			</tr>
			<tr>
				<td width="55%" align="left" class="dotted"><?php echo $lang['sanit_ua'] ?></td>
				<td width="45%" align="left" class="dotted">
					<p><label><input type="radio" name="ua_sanitise" value="1"<?php checked( $nfw_options['ua_sanitise'], 1 ) ?>>&nbsp;<?php echo $lang['yes'] . $lang['default'] ?></label></p>
					<p><label><input type="radio" name="ua_sanitise" value="0"<?php checked( $nfw_options['ua_sanitise'], 0 ) ?>>&nbsp;<?php echo $lang['no'] ?></label></p>
				</td>
			</tr>
			<tr>
				<td width="55%" align="left" class="dotted"><?php echo $lang['block_ua'] ?></td>
				<td width="45%" align="left" class="dotted">
					<p><label><input type="checkbox" name="ua_mozilla" value="1"<?php checked( $nfw_options['ua_mozilla'], 1 ) ?>>&nbsp;<?php echo $lang['mozilla_ua'] ?></label></p>
					<p><label><input type="checkbox" name="ua_accept" value="1"<?php checked( $nfw_options['ua_accept'], 1 ) ?>>&nbsp;<?php echo $lang['accept_ua'] ?></label></p>
					<p><label><input type="checkbox" name="ua_accept_lang" value="1"<?php checked( $nfw_options['ua_accept_lang'], 1 ) ?>>&nbsp;<?php echo $lang['accept_lang_ua'] ?></label></p>
					<i class="tinyblack">&nbsp;<?php echo $lang['posts_warn'] ?></i>
				</td>
			</tr>

			<?php if ( NFW_EDN == 1 ) { ?>
			<tr>
				<td width="55%" align="left" class="dotted"><?php echo $lang['suspicious_ua'] ?></td>
				<td width="45%" align="left" class="dotted">
					<p><label><input type="radio" name="block_bots" value="1"<?php checked( $block_bots, 1 ) ?>>&nbsp;<?php echo $lang['yes'] . $lang['default'] ?></label></p>
					<p><label><input type="radio" name="block_bots" value="0"<?php checked( $block_bots, 0 ) ?>>&nbsp;<?php echo $lang['no'] ?></label></p>
				</td>
			</tr>
			<?php } ?>

		</table>
	</fieldset>

	<br />
	<br />

	<?php
	if ( empty( $nfw_options['referer_scan']) ) {
		$referer_scan = 0;
	} else {
		$referer_scan = 1;
	}
	if ( empty( $nfw_options['referer_sanitise']) ) {
		$referer_sanitise = 0;
	} else {
		$referer_sanitise = 1;
	}
	if ( empty( $nfw_options['referer_post']) ) {
		$referer_post = 0;
	} else {
		$referer_post = 1;
	}
	?>
	<fieldset><legend>&nbsp;<b><?php echo $lang['referer'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td width="55%" align="left"><?php echo $lang['scan_referer'] ?></td>
				<td width="45%" align="left">
					<p><label><input type="radio" name="referer_scan" value="1"<?php checked( $nfw_options['referer_scan'], 1 ) ?>>&nbsp;<?php echo $lang['yes'] ?></label></p>
					<p><label><input type="radio" name="referer_scan" value="0"<?php checked( $nfw_options['referer_scan'], 0 ) ?>>&nbsp;<?php echo $lang['no'] . $lang['default'] ?></label></p>
				</td>
			</tr>
			<tr>
				<td width="55%" align="left" class="dotted"><?php echo $lang['sanit_referer'] ?></td>
				<td width="45%" align="left" class="dotted">
					<p><label><input type="radio" name="referer_sanitise" value="1"<?php checked( $nfw_options['referer_sanitise'], 1 ) ?>>&nbsp;<?php echo $lang['yes'] . $lang['default'] ?></label></p>
					<p><label><input type="radio" name="referer_sanitise" value="0"<?php checked( $nfw_options['referer_sanitise'], 0 ) ?>>&nbsp;<?php echo $lang['no'] ?></label></p>
				</td>
			</tr>
			<tr>
				<td width="55%" align="left" class="dotted"><?php echo $lang['post_referer'] ?></td>
				<td width="45%" align="left" class="dotted">
					<p><label><input type="radio" name="referer_post" value="1"<?php checked( $nfw_options['referer_post'], 1 ) ?>>&nbsp;<?php echo $lang['yes'] ?></label></p>
					<p><label><input type="radio" name="referer_post" value="0"<?php checked( $nfw_options['referer_post'], 0 ) ?>>&nbsp;<?php echo $lang['no'] . $lang['default'] ?></label></p>
					<i class="tinyblack">&nbsp;<?php echo $lang['post_warn'] ?></i>
				</td>
			</tr>
		</table>
	</fieldset>

	<br />
	<br />

	<?php
	// Some compatibility checks:
	// 1. header_register_callback(): requires PHP >=5.4
	// 2. headers_list() and header_remove(): some hosts may disable them.
	$err_msg = $err = '';
	$err_img = '<img src="static/icon_warn.png" border="0" height="21" width="21">&nbsp;';
	if (! function_exists('header_register_callback') ) {
		$err_msg = $err_img . sprintf($lang['missing_funct'], '<code>header_register_callback()</code>');
		$err = 1;
	} elseif (! function_exists('headers_list') ) {
		$err_msg = $err_img . sprintf($lang['missing_funct'], '<code>headers_list()</code>');
		$err = 1;
	} elseif (! function_exists('header_remove') ) {
		$err_msg = $err_img . sprintf($lang['missing_funct'], '<code>header_remove()</code>');
		$err = 1;
	}
	if ( empty($nfw_options['response_headers']) || strlen($nfw_options['response_headers']) != 6 || $err_msg ) {
		$nfw_options['response_headers'] = '000000';
	}
	?>
	<fieldset><legend>&nbsp;<b><?php echo $lang['httpresponse'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td width="55%" align="left"><?php echo $lang['x_c_t_o'] ?></td>
				<td width="45%" align="left">
					<p><label><input type="radio" name="x_content_type_options" value="1"<?php checked( $nfw_options['response_headers'][1], 1 ); disabled($err, 1); ?>><?php echo $lang['yes']; ?></label></p>
					<p><label><input type="radio" name="x_content_type_options" value="0"<?php checked( $nfw_options['response_headers'][1], 0 ); disabled($err, 1); ?>><?php echo $lang['no'] . $lang['default']; ?></label></p><?php echo $err_msg ?>
				</td>
			</tr>
			<tr>
				<td width="55%" align="left" class="dotted"><?php echo $lang['x_f_o'] ?></td>
				<td width="45%" align="left" class="dotted">
					<p><label><input type="radio" name="x_frame_options" value="1"<?php checked( $nfw_options['response_headers'][2], 1 ); disabled($err, 1); ?>>SAMEORIGIN</label></p>
					<p><label><input type="radio" name="x_frame_options" value="2"<?php checked( $nfw_options['response_headers'][2], 2 ); disabled($err, 1); ?>>DENY</label></p>
					<p><label><input type="radio" name="x_frame_options" value="0"<?php checked( $nfw_options['response_headers'][2], 0 ); disabled($err, 1); ?>><?php echo $lang['no'] . $lang['default']; ?></label></p><?php echo $err_msg ?>
				</td>
			</tr>
			<tr>
				<td width="55%" align="left" class="dotted"><?php echo $lang['x_x_p'] ?></td>
				<td width="45%" align="left" class="dotted">
					<p><label><input type="radio" name="x_xss_protection" value="1"<?php checked( $nfw_options['response_headers'][3], 1 ); disabled($err, 1); ?>><?php echo $lang['yes']; ?></label></p>
					<p><label><input type="radio" name="x_xss_protection" value="0"<?php checked( $nfw_options['response_headers'][3], 0 ); disabled($err, 1); ?>><?php echo $lang['no'] . $lang['default']; ?></label></p><?php echo $err_msg ?>
				</td>
			</tr>
			<tr>
				<td width="55%" align="left" class="dotted"><?php echo $lang['httponly'] ?></td>
				<td width="45%" align="left" class="dotted">
					<p><label><input type="radio" name="cookies_httponly" value="1"<?php checked( $nfw_options['response_headers'][0], 1 ); disabled($err, 1); ?> onclick="return httponly();">&nbsp;<?php echo $lang['yes']; ?></label></p>
					<p><label><input type="radio" name="cookies_httponly" value="0"<?php checked( $nfw_options['response_headers'][0], 0 ); disabled($err, 1); ?>>&nbsp;<?php echo $lang['no'] . $lang['default']; ?></label></p><?php echo $err_msg ?>
				</td>
			</tr>
			<?php
			// We don't send HSTS headers over HTTP (only display this message if there
			// is no other warning to display, $err==0 ):
			if ($_SERVER['SERVER_PORT'] != 443 && ! $err && (! isset( $_SERVER['HTTP_X_FORWARDED_PROTO']) || $_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https') ) {
				$err = 1;
				$hsts_msg = '<p><img src="static/icon_warn.png" border="0" width="21" heigt="21">&nbsp;<i class="tinyblack">' . $lang['hsts_warn'] . '</i>';
			} else {
				$hsts_msg = '';
			}
			?>
			<tr>
				<td width="55%" align="left" class="dotted"><?php echo $lang['hsts'] ?></td>
				<td width="45%" align="left" class="dotted">
					<p><label><input type="radio" name="strict_transport" value="0"<?php checked( $nfw_options['response_headers'][4], 0 ); disabled($err, 1); ?>><?php echo $lang['no'] . $lang['default']; ?></label></p><?php echo $err_msg ?>
					<p><label><input type="radio" name="strict_transport" value="4"<?php checked( $nfw_options['response_headers'][4], 4 ); disabled($err, 1); ?>><?php echo $lang['reset'] ?></label></p>
					<p><label><input type="radio" name="strict_transport" value="1"<?php checked( $nfw_options['response_headers'][4], 1 ); disabled($err, 1); ?>><?php echo $lang['1_month'] ?></label></p>
					<p><label><input type="radio" name="strict_transport" value="2"<?php checked( $nfw_options['response_headers'][4], 2 ); disabled($err, 1); ?>><?php echo $lang['6_months'] ?></label></p>
					<p><label><input type="radio" name="strict_transport" value="3"<?php checked( $nfw_options['response_headers'][4], 3 ); disabled($err, 1); ?>><?php echo $lang['1_year'] ?></label></p>
					<p><label><input type="checkbox" name="strict_transport_sub" value="1"<?php checked( $nfw_options['response_headers'][5], 1 ); disabled($err, 1); ?>><?php echo $lang['subdomain'] ?></label></p>
				<?php echo $hsts_msg; ?>
				</td>
			</tr>
		</table>
	</fieldset>

	<br />
	<br />

	<?php
	if ( empty( $nfw_rules[NFW_WRAPPERS]['on']) ) {
		$nfw_rules[NFW_WRAPPERS]['on'] = 0;
	} else {
		$nfw_rules[NFW_WRAPPERS]['on'] = 1;
	}
	if ( empty( $nfw_options['php_errors']) ) {
		$nfw_options['php_errors'] = 0;
	} else {
		$nfw_options['php_errors'] = 1;
	}
	if ( empty( $nfw_options['php_self']) ) {
		$nfw_options['php_self'] = 0;
	} else {
		$nfw_options['php_self'] = 1;
	}
	if ( empty( $nfw_options['php_path_t']) ) {
		$nfw_options['php_path_t'] = 0;
	} else {
		$nfw_options['php_path_t'] = 1;
	}
	if ( empty( $nfw_options['php_path_i']) ) {
		$nfw_options['php_path_i'] = 0;
	} else {
		$nfw_options['php_path_i'] = 1;
	}
	?>
	<fieldset><legend>&nbsp;<b><?php echo $lang['php'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td width="55%" align="left"><?php echo $lang['wrapper'] ?></td>
				<td width="45%" align="left">
					<p><label><input type="radio" name="php_wrappers" value="1"<?php checked( $nfw_rules[NFW_WRAPPERS]['on'], 1 ) ?>>&nbsp;<?php echo $lang['yes'] . $lang['default'] ?></label></p>
					<p><label><input type="radio" name="php_wrappers" value="0"<?php checked( $nfw_rules[NFW_WRAPPERS]['on'], 0 ) ?>>&nbsp;<?php echo $lang['no'] ?></label></p>
				</td>
			</tr>
			<tr>
				<td width="55%" align="left" class="dotted"><?php echo $lang['php_error'] ?></td>
				<td width="45%" align="left" class="dotted">
					<p><label><input type="radio" name="php_errors" value="1"<?php checked( $nfw_options['php_errors'], 1 ) ?>>&nbsp;<?php echo $lang['yes'] . $lang['default'] ?></label></p>
					<p><label><input type="radio" name="php_errors" value="0"<?php checked( $nfw_options['php_errors'], 0 ) ?>>&nbsp;<?php echo $lang['no'] ?></label></p>
				</td>
			</tr>
			<tr>
				<td width="55%" align="left" class="dotted"><?php echo $lang['php_self'] ?></td>
				<td width="45%" align="left" class="dotted">
					<p><label><input type="radio" name="php_self" value="1"<?php checked( $nfw_options['php_self'], 1 ) ?>>&nbsp;<?php echo $lang['yes'] . $lang['default'] ?></label></p>
					<p><label><input type="radio" name="php_self" value="0"<?php checked( $nfw_options['php_self'], 0 ) ?>>&nbsp;<?php echo $lang['no'] ?></label></p>
				</td>
			</tr>
			<tr>
				<td width="55%" align="left" class="dotted"><?php echo $lang['php_ptrans'] ?></td>
				<td width="45%" align="left" class="dotted">
					<p><label><input type="radio" name="php_path_t" value="1"<?php checked( $nfw_options['php_path_t'], 1 ) ?>>&nbsp;<?php echo $lang['yes'] . $lang['default'] ?></label></p>
					<p><label><input type="radio" name="php_path_t" value="0"<?php checked( $nfw_options['php_path_t'], 0 ) ?>>&nbsp;<?php echo $lang['no'] ?></label></p>
				</td>
			</tr>
			<tr>
				<td width="55%" align="left" class="dotted"><?php echo $lang['php_pinfo'] ?></td>
				<td width="45%" align="left" class="dotted">
					<p><label><input type="radio" name="php_path_i" value="1"<?php checked( $nfw_options['php_path_i'], 1 ) ?>>&nbsp;<?php echo $lang['yes'] . $lang['default'] ?></label></p>
					<p><label><input type="radio" name="php_path_i" value="0"<?php checked( $nfw_options['php_path_i'], 0 ) ?>>&nbsp;<?php echo $lang['no'] ?></label></p>
				</td>
			</tr>
		</table>
	</fieldset>

	<br />
	<br />

	<?php
	// If the document root is < 5 characters, grey out that option:
	if ( strlen( $_SERVER['DOCUMENT_ROOT'] ) < 5 ) {
		$nfw_rules[NFW_DOC_ROOT]['on'] = 0;
		$greyed = 'style="color:#bbbbbb"';
		$disabled = 'disabled ';
		$disabled_msg = '<br /><span class="description">&nbsp;' . $lang['disabled_msg'] . '</span>';
	} else {
		$greyed = '';
		$disabled = '';
		$disabled_msg = '';
	}

	if ( empty( $nfw_rules[NFW_DOC_ROOT]['on']) ) {
		$nfw_rules[NFW_DOC_ROOT]['on'] = 0;
	} else {
		$nfw_rules[NFW_DOC_ROOT]['on'] = 1;
	}
	if ( empty( $nfw_rules[NFW_NULL_BYTE]['on']) ) {
		$nfw_rules[NFW_NULL_BYTE]['on'] = 0;
	} else {
		$nfw_rules[NFW_NULL_BYTE]['on'] = 1;
	}
	if ( empty( $nfw_rules[NFW_ASCII_CTRL]['on']) ) {
		$nfw_rules[NFW_ASCII_CTRL]['on'] = 0;
	} else {
		$nfw_rules[NFW_ASCII_CTRL]['on'] = 1;
	}
	if ( empty( $nfw_rules[NFW_LOOPBACK]['on']) ) {
		$nfw_rules[NFW_LOOPBACK]['on'] = 0;
	} else {
		$nfw_rules[NFW_LOOPBACK]['on'] = 1;
	}
	if ( empty( $nfw_options['no_host_ip']) ) {
		$nfw_options['no_host_ip'] = 0;
	} else {
		$nfw_options['no_host_ip'] = 1;
	}
	if ( empty( $nfw_options['request_method']) ) {
		$nfw_options['request_method'] = 0;
	} else {
		$nfw_options['request_method'] = 1;
	}
	?>
	<fieldset><legend>&nbsp;<b><?php echo $lang['various'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td width="55%" align="left"><?php echo $lang['block_docroot'] ?></td>
				<td width="45%" align="left">
					<p><label><input type="radio" name="block_doc_root" value="1"<?php checked( $nfw_rules[NFW_DOC_ROOT]['on'], 1 ) ?>>&nbsp;<?php echo $lang['yes'] . $lang['default'] ?></label></p>
					<p><label><input type="radio" name="block_doc_root" value="0"<?php checked( $nfw_rules[NFW_DOC_ROOT]['on'], 0 ) ?>>&nbsp;<?php echo $lang['no'] ?></label></p>
				</td>
			</tr>
			<tr>
				<td width="55%" align="left" class="dotted"><?php echo $lang['block_nullbye'] ?></td>
				<td width="45%" align="left" class="dotted">
					<p><label><input type="radio" name="block_null_byte" value="1"<?php checked( $nfw_rules[NFW_NULL_BYTE]['on'], 1 ) ?>>&nbsp;<?php echo $lang['yes'] . $lang['default'] ?></label></p>
					<p><label><input type="radio" name="block_null_byte" value="0"<?php checked( $nfw_rules[NFW_NULL_BYTE]['on'], 0 ) ?>>&nbsp;<?php echo $lang['no'] ?></label></p>
				</td>
			</tr>
			<tr>
				<td width="55%" align="left" class="dotted"><?php echo $lang['block_ascii'] ?></td>
				<td width="45%" align="left" class="dotted">
					<p><label><input type="radio" name="block_ctrl_chars" value="1"<?php checked( $nfw_rules[NFW_ASCII_CTRL]['on'], 1 ) ?>>&nbsp;<?php echo $lang['yes'] . $lang['default'] ?></label></p>
					<p><label><input type="radio" name="block_ctrl_chars" value="0"<?php checked( $nfw_rules[NFW_ASCII_CTRL]['on'], 0 ) ?>>&nbsp;<?php echo $lang['no'] ?></label></p>
				</td>
			</tr>
			<tr>
				<td width="55%" align="left" class="dotted"><?php echo $lang['block_lo'] ?></td>
				<td width="45%" align="left" class="dotted">
					<p><label><input type="radio" name="no_localhost_ip" value="1"<?php checked( $nfw_rules[NFW_LOOPBACK]['on'], 1 ) ?>>&nbsp;<?php echo $lang['yes'] ?></label></p>
					<p><label><input type="radio" name="no_localhost_ip" value="0"<?php checked( $nfw_rules[NFW_LOOPBACK]['on'], 0 ) ?>>&nbsp;<?php echo $lang['no'] . $lang['default'] ?></label></p>
				</td>
			</tr>
			<tr>
				<td width="55%" align="left" class="dotted"><?php echo $lang['block_iphost'] ?></td>
				<td width="45%" align="left" class="dotted">
					<p><label><input type="radio" name="no_host_ip" value="1"<?php checked( $nfw_options['no_host_ip'], 1 ) ?>>&nbsp;<?php echo $lang['yes'] ?></label></p>
					<p><label><input type="radio" name="no_host_ip" value="0"<?php checked( $nfw_options['no_host_ip'], 0 ) ?>>&nbsp;<?php echo $lang['no'] . $lang['default'] ?></label></p>
				</td>
			</tr>
			<tr>
				<td width="55%" align="left" class="dotted"><?php echo $lang['block_method'] ?></td>
				<td width="45%" align="left" class="dotted">
					<p><label><input type="radio" name="request_method" value="1"<?php checked( $nfw_options['request_method'], 1 ) ?>>&nbsp;<?php echo $lang['get_post_head'] ?></label></p>
					<p><label><input type="radio" name="request_method" value="0"<?php checked( $nfw_options['request_method'], 0 ) ?>>&nbsp;<?php echo $lang['all_method'] . $lang['default'] ?></label></p>
				</td>
			</tr>
		</table>
	</fieldset>

	<br />
	<br />

	<input type="hidden" name="mid" value="<?php echo $GLOBALS['mid'] ?>">
	<input type="hidden" name="post" value="1">
	<center>
		<input type="submit" name="Save" class="button" value="<?php echo $lang['save_conf'] ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="submit" name="Save" class="button" value="<?php echo $lang['default_button'] ?>" onclick="return restore();" />
	</center>

	<br />
	<br />

</form>

<?php

html_footer();

/* ------------------------------------------------------------------ */

function restore_firewall_policies() {

	global $lang;
	global $nfw_options;
	global $nfw_rules;

	// Config file must be writable :
	if (! is_writable('./conf/options.php') ) {
		return $lang['error_conf' ];
	}
	// Rules file must be writable as well :
	if (! is_writable('./conf/rules.php') ) {
		return $lang['error_rules'];
	}

	$nfw_options['scan_protocol'] = 3;
	$nfw_options['uploads'] = 0;
	$nfw_options['sanitise_fn'] = 0;
	$nfw_options['upload_maxsize'] = 1048576;
	$nfw_options['get_scan'] = 1;
	$nfw_options['get_sanitise'] = 0;
	$nfw_options['post_scan'] = 1;
	$nfw_options['post_sanitise'] = 0;
	$nfw_options['post_b64'] = 1;
	$nfw_options['request_sanitise'] = 0;
	$nfw_options['cookies_scan'] = 1;
	$nfw_options['cookies_sanitise'] = 0;
	$nfw_options['ua_scan'] = 1;
	$nfw_options['ua_sanitise'] = 1;
	$nfw_options['ua_mozilla'] = 0;
	$nfw_options['ua_accept'] = 0;
	$nfw_options['ua_accept_lang'] = 0;
	if ( NFW_EDN == 1 ) {
		$nfw_rules[NFW_SCAN_BOTS]['on'] = 1;
	}
	$nfw_options['referer_scan'] = 0;
	if ( function_exists('header_register_callback') && function_exists('headers_list') && function_exists('header_remove') ) {
		$nfw_options['response_headers'] = '000000';
	}
	$nfw_options['referer_sanitise'] = 1;
	$nfw_options['referer_post'] = 0;
	$nfw_rules[NFW_WRAPPERS]['on'] = 1;
	$nfw_options['php_errors'] = 1;
	$nfw_options['php_self'] = 1;
	$nfw_options['php_path_t'] = 1;
	$nfw_options['php_path_i'] = 1;

	if ( strlen( $_SERVER['DOCUMENT_ROOT'] ) > 5 ) {
		$nfw_rules[NFW_DOC_ROOT]['what'] = $_SERVER['DOCUMENT_ROOT'];
		$nfw_rules[NFW_DOC_ROOT]['on']	= 1;
	} elseif ( strlen( getenv( 'DOCUMENT_ROOT' ) ) > 5 ) {
		$nfw_rules[NFW_DOC_ROOT]['what'] = getenv( 'DOCUMENT_ROOT' );
		$nfw_rules[NFW_DOC_ROOT]['on']	= 1;
	} else {
		$nfw_rules[NFW_DOC_ROOT]['on']	= 0;
	}

	$nfw_rules[NFW_NULL_BYTE]['on'] = 1;
	$nfw_rules[NFW_ASCII_CTRL]['on'] = 1;
	$nfw_rules[NFW_LOOPBACK]['on'] = 0;
	$nfw_options['no_host_ip'] = 0;
	$nfw_options['request_method'] = 0;

	// Save changes to 'conf/admin.php' :
	if (! $fh = fopen('./conf/options.php', 'w') ) {
		return $lang['error_conf' ];
	}
	fwrite($fh, '<?php' . "\n\$nfw_options = <<<'EOT'\n" . serialize( $nfw_options ) . "\nEOT;\n" );
	fclose($fh);
	// And to the rules.php :
	if (! $fh = fopen('./conf/rules.php', 'w') ) {
		return $lang['error_rules'];
	}
	fwrite($fh, '<?php' . "\n\$nfw_rules = <<<'EOT'\n" . serialize( $nfw_rules ) . "\nEOT;\n" );
	fclose($fh);

	return;
}

/* ------------------------------------------------------------------ */

function save_firewall_policies() {

	global $lang;
	global $nfw_options;
	global $nfw_rules;

	// Config file must be writable :
	if (! is_writable('./conf/options.php') ) {
		return $lang['error_conf' ];
	}
	// Rules file must be writable as well :
	if (! is_writable('./conf/rules.php') ) {
		return $lang['error_rules'];
	}

	// HTTP/S traffic to scan :
	if ( (isset( $_POST['scan_protocol'])) &&
		( preg_match( '/^[123]$/', $_POST['scan_protocol'])) ) {
			$nfw_options['scan_protocol'] = $_POST['scan_protocol'];
	} else {
		// Default : HTTP + HTTPS
		$nfw_options['scan_protocol'] = 3;
	}

	// Allow uploads ?
	if ( isset( $_POST['uploads']) && preg_match( '/^[12]$/', $_POST['uploads']) ) {
			$nfw_options['uploads'] = $_POST['uploads'];
	} else {
		$nfw_options['uploads'] = 0;
	}
	// Sanitise filenames (if uploads are allowed) ?
	if ( isset( $_POST['sanitise_fn']) ) {
		$nfw_options['sanitise_fn'] = 1;
	} else {
		$nfw_options['sanitise_fn'] = 0;
	}
	// Max file size :
	if ( isset($_POST['upload_maxsize']) && preg_match( '/^\d+$/', $_POST['upload_maxsize']) ) {
		$nfw_options['upload_maxsize'] = $_POST['upload_maxsize'] * 1024;
	} else {
		// Default : 1,048,576 bytes (1 MB)
		$nfw_options['upload_maxsize'] = 1048576;
	}

	// Scan GET requests ?
	if ( empty( $_POST['get_scan']) ) {
		$nfw_options['get_scan'] = 0;
	} else {
		// Default: yes
		$nfw_options['get_scan'] = 1;
	}
	// Sanitise GET requests ?
	if ( empty( $_POST['get_sanitise']) ) {
		// Default: no
		$nfw_options['get_sanitise'] = 0;
	} else {
		$nfw_options['get_sanitise'] = 1;
	}

	// Scan POST requests ?
	if ( empty( $_POST['post_scan']) ) {
		$nfw_options['post_scan'] = 0;
	} else {
		// Default: yes
		$nfw_options['post_scan'] = 1;
	}
	// Sanitise POST requests ?
	if ( empty( $_POST['post_sanitise']) ) {
		// Default: no
		$nfw_options['post_sanitise'] = 0;
	} else {
		$nfw_options['post_sanitise'] = 1;
	}
	// Decode base64 values in POST requests ?
	if ( empty( $_POST['post_b64']) ) {
		$nfw_options['post_b64'] = 0;
	} else {
		// Default: yes
		$nfw_options['post_b64'] = 1;
	}

	// HTTP response headers:
	if ( function_exists('header_register_callback') && function_exists('headers_list') && function_exists('header_remove') ) {
		$nfw_options['response_headers'] = '000000';
		// X-Content-Type-Options
		if ( empty( $_POST['x_content_type_options']) ) {
			$nfw_options['response_headers'][1] = 0;
		} else {
			$nfw_options['response_headers'][1] = 1;
		}
		// X-Frame-Options
		if ( empty( $_POST['x_frame_options']) ) {
			$nfw_options['response_headers'][2] = 0;
		} elseif ( $_POST['x_frame_options'] == 1) {
			$nfw_options['response_headers'][2] = 1;
		} else {
			$nfw_options['response_headers'][2] = 2;
		}
		// X-XSS-Protection
		if ( empty( $_POST['x_xss_protection']) ) {
			$nfw_options['response_headers'][3] = 0;
		} else {
			$nfw_options['response_headers'][3] = 1;
		}
		// HttpOnly cookies ?
		if ( empty( $_POST['cookies_httponly']) ) {
			$nfw_options['response_headers'][0] = 0;
		} else {
			$nfw_options['response_headers'][0] = 1;
		}
		// Strict-Transport-Security ?
		if (! isset( $_POST['strict_transport_sub']) ) {
			$nfw_options['response_headers'][5] = 0;
		} else {
			$nfw_options['response_headers'][5] = 1;
		}
		if ( empty( $_POST['strict_transport']) ) {
			$nfw_options['response_headers'][4] = 0;
			$nfw_options['response_headers'][5] = 0;
		} elseif ( $_POST['strict_transport'] == 1) {
			$nfw_options['response_headers'][4] = 1;
		} elseif ( $_POST['strict_transport'] == 2) {
			$nfw_options['response_headers'][4] = 2;
		} elseif ( $_POST['strict_transport'] == 3) {
			$nfw_options['response_headers'][4] = 3;
		} else {
			$nfw_options['response_headers'][4] = 4;
		}
	}

	// Sanitise REQUEST requests ?
	if ( empty( $_POST['request_sanitise']) ) {
		// Default: yes
		$nfw_options['request_sanitise'] = 0;
	} else {
		$nfw_options['request_sanitise'] = 1;
	}

	// Scan COOKIES requests ?
	if ( empty( $_POST['cookies_scan']) ) {
		$nfw_options['cookies_scan'] = 0;
	} else {
		// Default: yes
		$nfw_options['cookies_scan'] = 1;
	}
	// Sanitise COOKIES requests ?
	if ( empty( $_POST['cookies_sanitise']) ) {
		// Default: no
		$nfw_options['cookies_sanitise'] = 0;
	} else {
		$nfw_options['cookies_sanitise'] = 1;
	}

	// Scan HTTP_USER_AGENT requests ?
	if ( empty( $_POST['ua_scan']) ) {
		$nfw_options['ua_scan'] = 0;
	} else {
		// Default: yes
		$nfw_options['ua_scan'] = 1;
	}
	// Sanitise HTTP_USER_AGENT requests ?
	if ( empty( $_POST['ua_sanitise']) ) {
		$nfw_options['ua_sanitise'] = 0;
	} else {
		// Default: yes
		$nfw_options['ua_sanitise'] = 1;
	}

	// Mozilla-compatible signature ?
	if ( isset( $_POST['ua_mozilla']) ) {
		$nfw_options['ua_mozilla'] = 1;
	} else {
		$nfw_options['ua_mozilla'] = 0;
	}
	// HTTP_ACCEPT header ?
	if ( isset( $_POST['ua_accept']) ) {
		$nfw_options['ua_accept'] = 1;
	} else {
		$nfw_options['ua_accept'] = 0;
	}
	// HTTP_ACCEPT_LANGUAGE header  ?
	if ( isset( $_POST['ua_accept_lang']) ) {
		$nfw_options['ua_accept_lang'] = 1;
	} else {
		$nfw_options['ua_accept_lang'] = 0;
	}

	if ( NFW_EDN == 1 ) {
		// Block suspicious bots/scanners ?
		if ( empty( $_POST['block_bots']) ) {
			$nfw_rules[NFW_SCAN_BOTS]['on'] = 0;
		} else {
			// Default: yes
			$nfw_rules[NFW_SCAN_BOTS]['on'] = 1;
		}
	}

	// Scan HTTP_REFERER requests ?
	if ( empty( $_POST['referer_scan']) ) {
		// Default: no
		$nfw_options['referer_scan'] = 0;
	} else {
		$nfw_options['referer_scan'] = 1;
	}
	// Sanitise HTTP_REFERER requests ?
	if ( empty( $_POST['referer_sanitise']) ) {
		$nfw_options['referer_sanitise'] = 0;
	} else {
		// Default: yes
		$nfw_options['referer_sanitise'] = 1;
	}
	// Block POST requests without HTTP_REFERER ?
	if ( empty( $_POST['referer_post']) ) {
		// Default: NO
		$nfw_options['referer_post'] = 0;
	} else {
		$nfw_options['referer_post'] = 1;
	}

	// Block HTTP requests with an IP in the Host header ?
	if ( empty( $_POST['no_host_ip']) ) {
		// Default: NO
		$nfw_options['no_host_ip'] = 0;
	} else {
		$nfw_options['no_host_ip'] = 1;
	}

	// Hide PHP notice & error messages :
	if ( empty( $_POST['php_errors']) ) {
		$nfw_options['php_errors'] = 0;
	} else {
		// Default: yes
		$nfw_options['php_errors'] = 1;
	}

	// Sanitise PHP_SELF ?
	if ( empty( $_POST['php_self']) ) {
		$nfw_options['php_self'] = 0;
	} else {
		// Default: yes
		$nfw_options['php_self'] = 1;
	}
	// Sanitise PATH_TRANSLATED ?
	if ( empty( $_POST['php_path_t']) ) {
		$nfw_options['php_path_t'] = 0;
	} else {
		// Default: yes
		$nfw_options['php_path_t'] = 1;
	}
	// Sanitise PATH_INFO ?
	if ( empty( $_POST['php_path_i']) ) {
		$nfw_options['php_path_i'] = 0;
	} else {
		// Default: yes
		$nfw_options['php_path_i'] = 1;
	}
	// HTTP methods ?
	if ( empty( $_POST['request_method']) ) {
		// Default: no
		$nfw_options['request_method'] = 0;
	} else {
		$nfw_options['request_method'] = 1;
	}

	// Rules

	// Block the DOCUMENT_ROOT server variable in GET/POST requests (#ID 510) :
	if ( empty( $_POST['block_doc_root']) ) {
		$nfw_rules[NFW_DOC_ROOT]['on'] = 0;
	} else {
		// Default: yes
		// We need to ensure that the document root is at least
		// 5 characters, otherwise this option could block a lot
		// of legitimate requests:
		if ( strlen( $_SERVER['DOCUMENT_ROOT'] ) > 5 ) {
			$nfw_rules[NFW_DOC_ROOT]['what'] = $_SERVER['DOCUMENT_ROOT'];
			$nfw_rules[NFW_DOC_ROOT]['on']	= 1;
		} elseif ( strlen( getenv( 'DOCUMENT_ROOT' ) ) > 5 ) {
			$nfw_rules[NFW_DOC_ROOT]['what'] = getenv( 'DOCUMENT_ROOT' );
			$nfw_rules[NFW_DOC_ROOT]['on']	= 1;
		// we must disable that option:
		} else {
			$nfw_rules[NFW_DOC_ROOT]['on']	= 0;
		}
	}

	// Block NULL byte 0x00 (#ID 2) :
	if ( empty( $_POST['block_null_byte']) ) {
		$nfw_rules[NFW_NULL_BYTE]['on'] = 0;
	} else {
		// Default: yes
		$nfw_rules[NFW_NULL_BYTE]['on'] = 1;
	}

	// Block ASCII control characters 1 to 8 and 14 to 31 (#ID 500) :
	if ( empty( $_POST['block_ctrl_chars']) ) {
		$nfw_rules[NFW_ASCII_CTRL]['on'] = 0;
	} else {
		// Default: yes
		$nfw_rules[NFW_ASCII_CTRL]['on'] = 1;
	}

	// Block localhost IP in GET/POST requests (#ID 540) :
	if ( empty( $_POST['no_localhost_ip']) ) {
		// Default: no
		$nfw_rules[NFW_LOOPBACK]['on'] = 0;
	} else {
		$nfw_rules[NFW_LOOPBACK]['on'] = 1;
	}

	// Block PHP built-in wrappers (#ID 520) :
	if ( empty( $_POST['php_wrappers']) ) {
		$nfw_rules[NFW_WRAPPERS]['on'] = 0;
	} else {
		// Default: yes
		$nfw_rules[NFW_WRAPPERS]['on'] = 1;
	}

	// Save changes to 'conf/admin.php' :
	if (! $fh = fopen('./conf/options.php', 'w') ) {
		return $lang['error_conf' ];
	}
	fwrite($fh, '<?php' . "\n\$nfw_options = <<<'EOT'\n" . serialize( $nfw_options ) . "\nEOT;\n" );
	fclose($fh);
	// And to the rules.php :
	if (! $fh = fopen('./conf/rules.php', 'w') ) {
		return $lang['error_rules'];
	}
	fwrite($fh, '<?php' . "\n\$nfw_rules = <<<'EOT'\n" . serialize( $nfw_rules ) . "\nEOT;\n" );
	fclose($fh);

	return;
}

/* ------------------------------------------------------------------ */
// EOF
