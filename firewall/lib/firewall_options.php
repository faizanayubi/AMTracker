<?php
/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (Pro edition)                                         |
 |                                                                     |
 | (c) NinTechNet - http://nintechnet.com/                             |
 |                                                                     |
 +---------------------------------------------------------------------+
 | REVISION: 2015-09-25 19:47:22                                       |
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
	$err_msg = save_firewall_options();
	if ($err_msg) {
		echo '<br /><div class="error"><p>' . $err_msg .'</p></div>';
   } else {
		echo '<br /><div class="success"><p>'. $lang['saved_conf'] . '</p></div>';
	}
}

?>
<script>
function preview_msg() {
	var t1 = document.option_form.blocked_msg.value.replace('%%REM_ADDRESS%%', '<?php echo $_SERVER['REMOTE_ADDR'] ?>');
	var t2 = t1.replace('%%NUM_INCIDENT%%','1234567');
	var ns;
	if ( t2.match(/<style/i) ) {
		ns = "<?php echo $lang['ns_css'] ?>";
	}
	if ( t2.match(/<script/i) ) {
		ns = "<?php echo $lang['ns_js'] ?>";
	}
	if ( ns ) {
		alert("<?php echo $lang['ns'] ?>"+ ns +". <?php echo $lang['ns2'] ?>");
		return false;
	}
	document.getElementById('out_msg').innerHTML = t2 + '<br /><br /><br />';
	document.getElementById('td_msg').style.display = '';
	document.getElementById('btn_msg').value = '<?php echo $lang['refresh'] ?>';
}
function default_msg() {
	document.option_form.blocked_msg.value = "<?php echo preg_replace( '/[\r\n]/', '\n', NFW_DEFAULT_MSG) ?>";
}
function chktime(){
   if ( (document.option_form.ban_time.value) && (!document.option_form.ban_time.value.match(/^[1-9][0-9]?[0-9]?$/)) ){
      alert("<?php echo $lang['js_999'] ?>");
      document.option_form.ban_time.value = document.option_form.ban_time.value.substring(0, document.option_form.ban_time.value.length-1);
   }
}
function baninput(what) {
	if (what == 0) {
		document.option_form.ban_time.disabled = true;
	} else {
		document.option_form.ban_time.disabled = false;
		document.option_form.ban_time.select();
	}
}
function chkflds(){
   if ( (document.option_form.ban_ip.value > 0) && (! document.option_form.ban_time.value.match(/^[1-9][0-9]?[0-9]?$/)) ){
      alert("<?php echo $lang['js_ban'] ?>");
      document.option_form.ban_time.select();
      return false;
   }
   return true;
}
</script>
<br />
<form method="post" name="option_form" onsubmit="return chkflds();">

	<fieldset><legend>&nbsp;<b><?php echo $lang['options'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td width="55%" align="left"><?php echo $lang['firewall_prot'] ?><br />&nbsp;</td>
				<td width="45%" align="left"><?php
				if ( empty( $nfw_options['enabled']) ) {
					// Disabled :
					echo '<select name="enabled" class="input" style="width:250px">
						<option value="1">' . $lang['enabled'] . '</option>
						<option value="0" selected>' . $lang['disabled'] . '</option>
					</select>&nbsp;&nbsp;<img src="static/icon_error.png" border="0" width="21" height="21">';
				} else {
					// Enabled :
					echo '<select name="enabled" class="input" style="width:250px">
						<option value="1" selected>' . $lang['enabled'] . '</option>
						<option value="0">' . $lang['disabled'] . '</option>
					</select>';
				}
				?><br />&nbsp;
				</td>
			</tr>
			<tr>
				<td width="55%" align="left" class="dotted"><br /><?php echo $lang['debug'] ?><br />&nbsp;</td>
				<td width="45%" align="left" class="dotted"><br /><?php
				if ( empty( $nfw_options['debug']) ) {
					echo '<select name="debug" class="input" style="width:250px">
						<option value="0" selected>' . $lang['disabled'] . $lang['default'] . '</option>
						<option value="1">' . $lang['enabled'] . '</option>
					</select>';
				} else {
					echo '<select name="debug" class="input" style="width:250px">
						<option value="0">' . $lang['disabled'] . $lang['default'] . '</option>
						<option value="1" selected>' . $lang['enabled'] . '</option>
					</select>&nbsp;&nbsp;<img src="static/icon_warn.png" border="0" width="21" height="21">';
				}
				?><br />&nbsp;
				</td>
			</tr>

			<?php
			// Get (if any) the HTTP error code to return :
			if (! @preg_match( '/^(?:40[0346]|50[03])$/', $nfw_options['ret_code']) ) {
				$nfw_options['ret_code'] = '403';
			}
			?>
			<tr>
				<td width="55%" align="left" class="dotted"><br /><?php echo $lang['ret_code'] ?><br />&nbsp;</td>
				<td width="45%" align="left" class="dotted"><br />
					<select name="ret_code" class="input" style="width:250px">
						<option value="400"<?php selected($nfw_options['ret_code'],400) ?>><?php echo $lang['400'] ?></option>
						<option value="403"<?php selected($nfw_options['ret_code'],403) ?>><?php echo $lang['403'] ?></option>
						<option value="404"<?php selected($nfw_options['ret_code'],404) ?>><?php echo $lang['404'] ?></option>
						<option value="406"<?php selected($nfw_options['ret_code'],406) ?>><?php echo $lang['406'] ?></option>
						<option value="500"<?php selected($nfw_options['ret_code'],500) ?>><?php echo $lang['500'] ?></option>
						<option value="503"<?php selected($nfw_options['ret_code'],503) ?>><?php echo $lang['503'] ?></option>
					</select><br />&nbsp;
				</td>
			</tr>

			<tr>
				<td width="55%" align="left" class="dotted"><br /><?php echo $lang['blocked_msg'] ?><br />&nbsp;</td>
				<td width="45%" align="left" class="dotted"><br />
					<textarea name="blocked_msg" rows="8" style="background-color:#ffffff;font-family:monospace;font-size:13px;width:100%;border:1px solid #666666;"><?php
					if (! empty( $nfw_options['blocked_msg']) ) {
						echo htmlentities(base64_decode($nfw_options['blocked_msg']));
					} else {
						echo NFW_DEFAULT_MSG;
					}
					?></textarea>
					<br /><br /><input class="button" type="button" id="btn_msg" value="<?php echo $lang['preview'] ?>" onclick="javascript:preview_msg();" />&nbsp;&nbsp;<input class="button" type="button" id="btn_msg" value="<?php echo $lang['default_msg'] ?>" onclick="javascript:default_msg();" />
				</td>
			</tr>
		</table>
		<table class="smallblack" border="0" width="100%">
			<tr id="td_msg" style="display:none">
				<td id="out_msg" style="border:1px solid #FDCD25;background-color:#ffffff;" width="100%"></td>
			</tr>
		</table>

		<br />

		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<?php
			if ( empty($nfw_options['ban_ip']) || ! preg_match('/^[123]$/', $nfw_options['ban_ip']) ) {
				$nfw_options['ban_ip'] = 0;
			}
			if ( empty($nfw_options['ban_time'])  || ! preg_match('/^[1-9][0-9]?[0-9]?$/', $nfw_options['ban_time']) ) {
				$nfw_options['ban_time'] = 0;
			}
			$ban_disabled = '';
			?>
			<tr>
				<td width="55%" align="left" class="dotted"><br /><?php echo $lang['ban_ip'] ?><br />&nbsp;</td>
				<td width="45%" align="left" class="dotted"><br />
					<select name="ban_ip" class="input" style="width:250px" onChange="baninput(this.value);">
						<option value="0"<?php if (! $nfw_options['ban_ip']) {echo ' selected'; $ban_disabled = ' disabled';} ?>><?php echo $lang['ban_never'] . $lang['default'] ?></option>
						<option value="1"<?php selected($nfw_options['ban_ip'],1) ?>><?php echo $lang['ban_crit'] ?></option>
						<option value="2"<?php selected($nfw_options['ban_ip'],2) ?>><?php echo $lang['ban_high'] ?></option>
						<option value="3"<?php selected($nfw_options['ban_ip'],3) ?>><?php echo $lang['ban_all'] ?></option>
					</select>
					<br />
					<br />
					<?php
					printf($lang['ban_time'], '<input onkeyup="chktime();" type="text" class="input" style="padding-left:3px" name="ban_time" maxlength="3" size="2" value="' . $nfw_options['ban_time'] . '"' . $ban_disabled .'>');

					// Check if we have some banned IPs :
					$glob = glob( dirname( __DIR__ ) . '/nfwlog/cache/ipbk*.php');
					$banned_ips = array();
					if ( is_array($glob) ) {
						foreach ($glob as $file) {
							// Check if the banning period has expired :
							$stat = stat($file);
							if ( time() - $stat['mtime'] > $nfw_options['ban_time'] * 60 ) {
								// Delete it :
								unlink($file);
								continue;
							}
							// Save it :
							if (preg_match( '`_-_(.+)\.php$`', $file, $match) ) {
								$banned_ips[] = $match[1];
							}
						}
					}
					sort($banned_ips);
					?>

					<br />
					<br />
					<?php echo $lang['curr_ban'] ?> <?php echo count($banned_ips) ?>
					<br />
					<select multiple size="8" name="banned_list[]" style="width:100%;height:100px;font-family:monospace;"<?php disabled(count($banned_ips), 0) ?>>
					<?php
					foreach ($banned_ips as $ip) {
						echo '<option value="' . $ip . '">' . htmlentities($ip) . '</option>';
					}
					?>
					</select>
					<br />
					<i class="tinyblack"><?php printf( $lang['remove_ban'], $lang['save_conf']) ?></i>
				</td>
			</tr>
		</table>
	</fieldset>
	<br />
	<br />
	<input type="hidden" name="mid" value="<?php echo $GLOBALS['mid'] ?>">
	<input type="hidden" name="post" value="1">
	<center><input type="submit" name="Save" class="button" value="<?php echo $lang['save_conf'] ?>"></center>
	<br />
	<br />
</form>

<?php

html_footer();

/* ------------------------------------------------------------------ */

function save_firewall_options() {

	global $lang;
	global $nfw_options;

	// Clear selected IPs from the ban list :
	if (! empty($_POST['banned_list']) ) {
		$glob = glob( dirname( __DIR__ ) . '/nfwlog/cache/ipbk*.php');
		$banned_ips = array();
		if ( is_array($glob) ) {
			foreach ($glob as $file) {
				if (preg_match( '`^.+_-_(.+)\.php$`', $file, $match) ) {
					$banned_ips[$match[1]] = $match[0];
				}
			}
		}
		foreach ( $_POST['banned_list'] as $ip ) {
			if (! empty( $banned_ips[$ip] ) && file_exists( $banned_ips[$ip] ) ) {
				unlink( $banned_ips[$ip] );
			}
		}
	}

	// Config file must be writable :
	if (! is_writable('./conf/options.php') ) {
		return $lang['error_conf' ];
	}

	if ( empty( $_POST['enabled']) ) {
		$nfw_options['enabled'] = 0;
	} else {
		$nfw_options['enabled'] = 1;
	}

	if ( empty( $_POST['debug']) ) {
		$nfw_options['debug'] = 0;
	} else {
		$nfw_options['debug'] = 1;
	}

	if ( (isset( $_POST['ret_code'])) &&
		(preg_match( '/^(?:40[0346]|50[03])$/', $_POST['ret_code'])) ) {
		$nfw_options['ret_code'] = $_POST['ret_code'];
	} else {
		$nfw_options['ret_code'] = '403';
	}

	if ( empty( $_POST['blocked_msg']) ) {
		$nfw_options['blocked_msg'] = NFW_DEFAULT_MSG;
	} else {
		$nfw_options['blocked_msg'] = base64_encode($_POST['blocked_msg']);
	}

	if ( empty($_POST['ban_ip']) || ! preg_match('/^[1-3]$/', $_POST['ban_ip']) ) {
		$nfw_options['ban_ip'] = 0;
		$nfw_options['ban_time'] = 0;
	} else {
		$nfw_options['ban_ip'] = $_POST['ban_ip'];
		if ( empty($_POST['ban_time']) || ! preg_match('/^[1-9][0-9]?[0-9]?$/', $_POST['ban_time']) ) {
			$nfw_options['ban_time'] = 10;
		} else {
			$nfw_options['ban_time'] = $_POST['ban_time'];
		}
	}

	// Save changes to 'conf/admin.php' :
	if (! $fh = fopen('./conf/options.php', 'w') ) {
		return $lang['error_conf' ];
	}
	fwrite($fh, '<?php' . "\n\$nfw_options = <<<'EOT'\n" . serialize( $nfw_options ) . "\nEOT;\n" );
	fclose($fh);

	return;
}

/* ------------------------------------------------------------------ */
// EOF
