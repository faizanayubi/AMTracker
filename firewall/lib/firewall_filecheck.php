<?php
/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (Pro edition)                                         |
 |                                                                     |
 | (c) NinTechNet - http://nintechnet.com/                             |
 |                                                                     |
 +---------------------------------------------------------------------+
 | REVISION: 2015-11-24 18:11:20                                       |
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

$log_dir = dirname(__DIR__) . '/nfwlog/cache/';
$nfmon_snapshot = $log_dir . 'nfilecheck_snapshot.php';
$nfmon_diff = $log_dir . 'nfilecheck_diff.php';
$err = $success = '';

// Load current language file :
require (__DIR__ .'/lang/' . $nfw_options['admin_lang'] . '/' . basename(__FILE__) );

// Download File Check snapshot :
if ( isset($_POST['dlsnap']) ) {
	if (file_exists( $nfmon_snapshot ) ) {
		$stat = stat( $nfmon_snapshot );
		$data = '== NinjaFirewall File Check (snapshot)'. "\n";
		$data.= '== ' . $_SERVER['HTTP_HOST'] . "\n";
		$data.= '== ' . date('M d, Y @ H:i:s O', $stat['ctime']) . "\n\n";
		$fh = fopen( $nfmon_snapshot, 'r');
		while (! feof($fh) ) {
			$res = explode('::', fgets($fh) );
			if (! empty($res[0][0]) && $res[0][0] == '/') {
				$data .= $res[0] . "\n";
			}
		}
		fclose($fh);
		$data .= "\n== EOF\n";
		header('Content-Type: application/txt');
		header('Content-Length: '. strlen( $data ) );
		header('Content-Disposition: attachment; filename="'. $_SERVER['HTTP_HOST'] .'_snapshot.txt"');
		echo $data;
		exit;
	}
}
// Download File Check modified files list :
if ( isset($_POST['dlmods']) ) {
	if (file_exists( $nfmon_diff ) ) {
		$download_file = $nfmon_diff;
	} elseif (file_exists( $nfmon_diff .'.php') ) {
		$download_file = $nfmon_diff .'.php';
	} else {
		exit;
	}
	$stat = stat($download_file);
	$data = '== NinjaFirewall File Check (diff)'. "\n";
	$data.= '== ' . $_SERVER['HTTP_HOST'] . "\n";
	$data.= '== ' . date('M d, Y @ H:i:s O', $stat['ctime']) . "\n\n";
	$data.= '[+] = ' . $lang['download_new'] .
				'      [-] = ' . $lang['download_del'] .
				'      [!] = ' . $lang['download_mod'] .
				"\n\n";
	$fh = fopen($download_file, 'r');
	while (! feof($fh) ) {
		$res = explode('::', fgets($fh) );
		if ( empty($res[1]) ) { continue; }
		// New file :
		if ($res[1] == 'N') {
			$data .= '[+] ' . $res[0] . "\n";
		// Deleted file :
		} elseif ($res[1] == 'D') {
			$data .= '[-] ' . $res[0] . "\n";
		// Modified file:
		} elseif ($res[1] == 'M') {
			$data .= '[!] ' . $res[0] . "\n";
		}
	}
	fclose($fh);
	$data .= "\n== EOF\n";
	header('Content-Type: application/txt');
	header('Content-Length: '. strlen( $data ) );
	header('Content-Disposition: attachment; filename="'. $_SERVER['HTTP_HOST'] .'_diff.txt"');
	echo $data;
	exit;
}

html_header();

// Ensure cache folder is writable :
if (! is_writable('./nfwlog/cache/') ) {
	echo '<br /><div class="error" style="width: 90%; text-align: left;" id="error_table"><p>'. $lang['error'] .': ' . $lang['error_cache'] .'</p></div>';
}

// Saved options ?
if (! empty($_REQUEST['nfw_act'])) {
	if ( $_REQUEST['nfw_act'] == 'delete') {
		// Delete de current snapshot file :
		if (file_exists($nfmon_snapshot) ) {
			unlink ($nfmon_snapshot);
			$success = $lang['deleted_ok'];
			// Remove old diff file as well :
			if ( file_exists($nfmon_diff . '.php') ) {
				unlink($nfmon_diff . '.php');
			}
		} else {
			$err =  $lang['deleted_err'];
		}
	} elseif ( $_REQUEST['nfw_act'] == 'create') {
		if (! $err = nf_sub_monitoring_create($nfmon_snapshot) ) {
			$success = $lang['created_ok'];
			if (file_exists($nfmon_diff) ) {
				unlink($nfmon_diff);
			}
		}
	} elseif ( $_REQUEST['nfw_act'] == 'scan') {
		// Scan disk for changes :
		if (! file_exists($nfmon_snapshot) ) {
			$err = $lang['not_created'];
		} else {

			$snapproc = microtime(true);
			$err = nf_sub_monitoring_scan($nfmon_snapshot, $nfmon_diff);

			$nfw_options['snapproc'] = round( microtime(true) - $snapproc, 2);
			// Save processing time :
			if ( $fh = fopen('./conf/options.php', 'w') ) {
				fwrite($fh, '<?php' . "\n\$nfw_options = <<<'EOT'\n" . serialize( $nfw_options ) . "\nEOT;\n" );
				fclose($fh);
			}

			if (! $err) {
				if (file_exists($nfmon_diff) ) {
					$err =  $lang['changes'];
					$changes = 1;
				} else {
					$success =  $lang['no_changes'];
				}
			}
		}
	}
}

if ( $err ) {
	echo '<br /><div class="error"><p>' . $err .'</p></div>';
} elseif ( $success ) {
	echo '<br /><div class="success"><p>'. $success . '</p></div>';
}

if ( empty($nfw_options['snapdir']) ) {
	$nfw_options['snapdir'] = '';
	if ( file_exists($nfmon_snapshot) ) {
		unlink($nfmon_snapshot);
	}
}

// If we don't have a snapshopt, offer to create one :
if (! file_exists($nfmon_snapshot) ) {
	$nfw_options['snapexclude'] = dirname(__DIR__);
	?>
	<br />
	<form method="post" name="monitor_form">
		<fieldset><legend>&nbsp;<b><?php echo $lang['fwl_fc'] ?></b>&nbsp;</legend>
			<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
				<tr>
					<td width="35%" align="left"><?php echo $lang['create_snap'] ?></td>
					<td width="65%"><p><input class="input" type="text" style="width:100%" name="snapdir" value="<?php
					if (! empty($nfw_options['snapdir']) ) {
						echo htmlspecialchars($nfw_options['snapdir']);
					} else {
						echo htmlspecialchars( dirname(dirname(__DIR__)) );
					}
					?>" required /></p>
					</td>
				</tr>
				<tr>
					<td width="35%" align="left" class="dotted"><?php echo $lang['excl_dirs'] ?></td>
					<td width="65%" class="dotted"><p><input class="input" style="width:100%" type="text" name="snapexclude" value="<?php echo htmlspecialchars($nfw_options['snapexclude']); ?>" placeholder="<?php echo $lang['eg'] .' '. htmlspecialchars( dirname(__DIR__) ) ?>" /><br /><i><?php echo $lang['excl_dirs_help'] ?>.</i></p></td>
				</tr>
				<tr>
					<td width="35%" align="left">&nbsp;</td>
					<td width="65%"><label><input type="checkbox" name="snapnoslink" value="1" checked="checked" /><?php echo $lang['no_follow'] ?></label></td>
				</tr>
			</table>
		</fieldset>
		<br />
		<br />
		<input type="hidden" name="mid" value="<?php echo $GLOBALS['mid'] ?>" />
		<input type="hidden" name="nfw_act" value="create" />
		<center><input type="submit" name="Save" class="button" value="<?php echo $lang['create_btn'] ?>" /></center>
		<br />
		<br />
	</form>
	<br />
	<?php
	html_footer();
	exit;
}


// We have a snapshot :
$stat = stat($nfmon_snapshot);
$count = -2;
$fh = fopen($nfmon_snapshot, 'r');
while (! feof($fh) ) {
	fgets($fh);
	$count++;
}
fclose($fh);
// Look for new/mod/del files :
$res = $new_file = $del_file = $mod_file = array();
// If no changes were detected, we display the last ones (if any) :
if (! file_exists($nfmon_diff) && file_exists($nfmon_diff . '.php') ) {
	$nfmon_diff = $nfmon_diff . '.php';
}
if (file_exists($nfmon_diff) ) {
	$fh = fopen($nfmon_diff, 'r');
	while (! feof($fh) ) {
		$res = explode('::', fgets($fh) );
		if ( empty($res[1]) ) { continue; }
		// New file :
		if ($res[1] == 'N') {
			$s_tmp = explode(':', rtrim($res[2]));
			$new_file[$res[0]] = $s_tmp[0] .':'.
				$s_tmp[1] .':'.
				$s_tmp[2] .':'.
				$s_tmp[3] .':'.
				date('Y-m-d H~i~s O', $s_tmp[4]) .':'.
				date('Y-m-d H~i~s O', $s_tmp[5]);
		// Deleted file :
		} elseif ($res[1] == 'D') {
			$del_file[$res[0]] = 1;
		// Modified file:
		} elseif ($res[1] == 'M') {
			$s_tmp = explode(':', $res[2]);
			$mod_file[$res[0]] = $s_tmp[0] .':'.
				$s_tmp[1] .':'.
				$s_tmp[2] .':'.
				$s_tmp[3] .':'.
				date('Y-m-d H~i~s O', $s_tmp[4]) .':'.
				date('Y-m-d H~i~s O', $s_tmp[5]) .'::';
				$s_tmp = explode(':', rtrim($res[3]));
			$mod_file[$res[0]] .= $s_tmp[0] .':'.
				$s_tmp[1] .':'.
				$s_tmp[2] .':'.
				$s_tmp[3] .':'.
				date('Y-m-d H~i~s O', $s_tmp[4]) .':'.
				date('Y-m-d H~i~s O', $s_tmp[5]);
		}
	}
	fclose($fh);
	$mod = 1;
} else {
	$mod = 0;
}
	?>
	<script>
	<?php if ($mod) { ?>
	function file_info(what, where) {
		// New file :
		if (where == 1) {
			<?php if ($new_file) { ?>
			var nfo = what.split(':');
			document.getElementById('new_size').innerHTML = nfo[3];
			document.getElementById('new_chmod').innerHTML = nfo[0];
			document.getElementById('new_uidgid').innerHTML = nfo[1] + ' / ' + nfo[2];
			document.getElementById('new_mtime').innerHTML = nfo[4].replace(/~/g, ':');
			document.getElementById('new_ctime').innerHTML = nfo[5].replace(/~/g, ':');
			document.getElementById('table_new').style.display = '';
			<?php } ?>
		// Modified file :
		} else if (where == 2) {
			<?php if ($mod_file) { ?>
			var all = what.split('::');
			var nfo = all[0].split(':');
			var nfo2 = all[1].split(':');
			document.getElementById('mod_size').innerHTML = nfo[3];
			if (nfo[3] != nfo2[3]) {
				document.getElementById('mod_size2').innerHTML = '<font color="red">'+ nfo2[3] +'</font>';
			} else {
				document.getElementById('mod_size2').innerHTML = nfo2[3];
			}
			document.getElementById('mod_chmod').innerHTML = nfo[0];
			if (nfo[0] != nfo2[0]) {
				document.getElementById('mod_chmod2').innerHTML = '<font color="red">'+ nfo2[0] +'</font>';
			} else {
				document.getElementById('mod_chmod2').innerHTML = nfo2[0];
			}
			document.getElementById('mod_uidgid').innerHTML = nfo[1] + ' / ' + nfo[2];
			if ( (nfo[1] != nfo2[1]) || (nfo[2] != nfo2[2]) ) {
				document.getElementById('mod_uidgid2').innerHTML = '<font color="red">'+ nfo2[1] + '/' + nfo2[2] +'</font>';
			} else {
				document.getElementById('mod_uidgid2').innerHTML = nfo2[1] + ' / ' + nfo2[2];
			}
			document.getElementById('mod_mtime').innerHTML = nfo[4].replace(/~/g, ':');
			if (nfo[4] != nfo2[4]) {
				document.getElementById('mod_mtime2').innerHTML = '<font color="red">'+ nfo2[4].replace(/~/g, ':') +'</font>';
			} else {
				document.getElementById('mod_mtime2').innerHTML = nfo2[4].replace(/~/g, ':');
			}
			document.getElementById('mod_ctime').innerHTML = nfo[5].replace(/~/g, ':');
			if (nfo[5] != nfo2[5]) {
				document.getElementById('mod_ctime2').innerHTML = '<font color="red">'+ nfo2[5].replace(/~/g, ':') +'</font>';
			} else {
				document.getElementById('mod_ctime2').innerHTML = nfo2[5].replace(/~/g, ':');
			}
			document.getElementById('table_mod').style.display = '';
			<?php } ?>
		}
	}
	<?php } ?>
	function delit() {
		if (confirm("<?php echo $lang['ask_delete'] ?>") ) {
			return true;
		}
		return false;
	}
	function nftoogle() {
		document.getElementById('changes_table').style.display = '';
		document.getElementById('vcbtn').disabled = true;
	}
	</script>
	<br />

<fieldset><legend>&nbsp;<b><?php echo $lang['fwl_fc'] ?></b>&nbsp;</legend>
	<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
		<tr>
			<td width="35%" align="left"><?php echo $lang['last_sshot'] ?></td>
			<td width="65%"><p>
				<p><?php printf( $lang['created_on'], date('M d, Y @ H:i:s O', $stat['ctime']) ); ?></p>
				<p><?php printf( $lang['total_files'], number_format($count) ); ?></p>

				<p><?php echo $lang['directory'] ?> <code><?php echo htmlspecialchars($nfw_options['snapdir']) ?></code></p>
				<?php
				if (! empty($nfw_options['snapexclude']) ) {
					$res = @explode(',', $nfw_options['snapexclude']);
					echo '<p>' .  $lang['exclusion'] . ' ';
					foreach ($res as $exc) {
						echo '<code>' . htmlspecialchars($exc) . '</code>&nbsp;';
					}
					echo '</p>';
				}
				echo	'<p>' .  $lang['symlinks'] . ' ';
				if ( empty($nfw_options['snapnoslink']) ) {
					echo $lang['follow'];
				} else {
					echo $lang['dont_follow'];
				}
				echo '</p>';
				if (! empty($nfw_options['snapproc']) ) {
					echo '<p>' . sprintf( $lang['proc_time'], $nfw_options['snapproc']) . '</p>';
				}
				?>
				<form method="post">
					<p>
						<input type="submit" name="dlsnap" value="<?php echo $lang['down_sshot'] ?>" class="button-secondary" />&nbsp;&nbsp;&nbsp;<input type="submit" class="button-secondary" onClick="return delit();" value="<?php echo $lang['del_sshot'] ?>" />
						<input type="hidden" name="nfw_act" value="delete" />
						<input type="hidden" name="mid" value="<?php echo $GLOBALS['mid'] ?>">
					</p>
				</form>
			</td>
		</tr>
		<tr>
			<td width="35%" align="left" class="dotted"><?php echo $lang['last_changes'] ?></td>
			<td width="65%" class="dotted"><p>

			<?php
			// Show info about last changes, if any :
			if ($mod) {
			?>
				<p><?php printf( $lang['new_files_'], count($new_file) ) ?></p>
				<p><?php printf( $lang['del_files_'], count($del_file) ) ?></p>
				<p><?php printf( $lang['mod_files_'], count($mod_file) ) ?></p>

				<form method="post">
					<p><input type="button" value="<?php echo $lang['view_changes'] ?>" onClick="nftoogle();" class="button-secondary" id="vcbtn" <?php
					if (! empty($changes)) {
						echo 'disabled="disabled" ';
					}
					?>/>&nbsp;&nbsp;&nbsp;<input type="submit" name="dlmods" value="<?php echo $lang['down_changes'] ?>" class="button-secondary" /></p>
				</form>
				<br />
			<?php
				if (empty($changes)) {
					echo '<table border="0" width="100%" id="changes_table" style="display:none">';
				} else {
					echo '<table border="0" width="100%" id="changes_table">';
				}

				$more_info = $lang['click_info'];
				if ($new_file) {
					echo '<tr><td><br />';
					echo $lang['new_files'] . ' ' . count($new_file). '<br />';
					echo '<select name="sometext" multiple="multiple" style="width:100%;height:150px" onClick="file_info(this.value, 1);">';
					foreach($new_file as $k => $v) {
						echo '<option value="' . htmlspecialchars($v) . '" title="' . htmlspecialchars($k) . '">' . htmlspecialchars($k) . '</option>';
					}
					echo '</select>
					<br /><i class="tinyblack">'. $more_info . '</i><br />
					<table id="table_new" style="width:100%;background-color:#F7F7F7;border:solid 1px #DFDFDF;display:none;text-align:left;">
						<tr>
							<th style="padding:0;width:25%;">' . $lang['size'] .'</th>
							<td style="padding:0" id="new_size"></td>
						</tr>
						<tr>
							<th style="padding:0;width:25%;">' . $lang['access'] .'</th>
							<td style="padding:0" id="new_chmod"></td>
						</tr>
						<tr>
							<th style="padding:0;width:25%;">' . $lang['uidgid'] .'</th>
							<td style="padding:0" id="new_uidgid"></td>
						</tr>
						<tr>
							<th style="padding:0;width:25%;">' . $lang['mtime'] .'</th>
							<td style="padding:0" id="new_mtime"></td>
						</tr>
						<tr>
							<th style="padding:0;width:25%;">' . $lang['ctime'] .'</th>
							<td style="padding:0" id="new_ctime"></td>
						</tr>
					</table>
				</td>
			</tr>';
				}
				if ($del_file) {
					echo '
			<tr>
				<td><br />' . $lang['del_files'] .' '. count($del_file). '<br />' .
					'<select name="sometext" multiple="multiple" style="width:100%;height:150px">';
					foreach($del_file as $k => $v) {
						echo '<option title="' . htmlspecialchars($k) . '">' . htmlspecialchars($k) . '</option>';
					}
					echo'</select>
				</td>
			</tr>';
				}
				if ($mod_file) {
					echo '
			<tr>
				<td><br />' . $lang['mod_files'] .' '. count($mod_file). '<br />'.
					'<select name="sometext" multiple="multiple" style="width:100%;height:150px" onClick="file_info(this.value, 2);">';
					foreach($mod_file as $k => $v) {
						echo '<option value="' . htmlspecialchars($v) . '" title="' . htmlspecialchars($k) . '">' . htmlspecialchars($k) . '</option>';
					}
					echo'</select>
					<br /><i class="tinyblack">'. $more_info . '</i><br />
					<table id="table_mod" style="width:100%;background-color:#F7F7F7;border:solid 1px #DFDFDF;display:none;text-align:left;">
						<tr>
							<th style="padding:0;width:25%;">&nbsp;</th>
							<td style="padding:0"><b>' .$lang['old'] .'</b></td>
							<td style="padding:0"><b>' . $lang['new'] .'</b></td>
						</tr>
						<tr>
							<th style="padding:0;width:25%;">' . $lang['size'] .'</th>
							<td style="padding:0" id="mod_size"></td>
							<td style="padding:0" id="mod_size2"></td>
						</tr>
						<tr>
							<th style="padding:0;width:25%;">' . $lang['access'] .'</th>
							<td style="padding:0" id="mod_chmod"></td>
							<td style="padding:0" id="mod_chmod2"></td>
						</tr>
						<tr>
							<th style="padding:0;width:25%;">' . $lang['uidgid'] .'</th>
							<td style="padding:0" id="mod_uidgid"></td>
							<td style="padding:0" id="mod_uidgid2"></td>
						</tr>
						<tr>
							<th style="padding:0;width:25%;">' . $lang['mtime'] .'</th>
							<td style="padding:0" id="mod_mtime"></td>
							<td style="padding:0" id="mod_mtime2"></td>
						</tr>
						<tr>
							<th style="padding:0;width:25%;">' . $lang['ctime'] .'</th>
							<td style="padding:0" id="mod_ctime"></td>
							<td style="padding:0" id="mod_ctime2"></td>
						</tr>
					</table>
				</td>
			</tr>';
				}
			echo '</table>';
			} else {
				echo $lang['none'];
			}
			?>
			</td>
		</tr>
	</table>
</fieldset>
<br />

<form method="post">
	<input type="hidden" name="mid" value="<?php echo $GLOBALS['mid'] ?>">
	<input type="hidden" name="nfw_act" value="scan" />
	<p style="text-align:center;"><input type="submit" class="button-primary" value="<?php echo $lang['scan now'] ?> &#187;" /></p>
</form>
<br />
<?php

html_footer();
exit;

/* ------------------------------------------------------------------ */

function nf_sub_monitoring_create($nfmon_snapshot) {

	global $lang;
	global $nfw_options;

	// Check POST data:
	if ( empty($_POST['snapdir']) ) {
		return $lang['enter_path'];
	}
	if ( strlen($_POST['snapdir']) > 1 ) {
		$_POST['snapdir'] = rtrim($_POST['snapdir'], '/');
	}
	if (! file_exists($_POST['snapdir']) ) {
		return sprintf( $lang['dir_not_found'], '<code>'. htmlspecialchars($_POST['snapdir']) .'</code>');
	}
	if (! is_readable($_POST['snapdir']) ) {
		return sprintf( $lang['dir_not_read'], '<code>'. htmlspecialchars($_POST['snapdir']) .'</code>');
	}
	if ( isset($_POST['snapnoslink']) ) {
		$snapnoslink = 1;
	} else {
		$snapnoslink = 0;
	}

	$snapexclude = '';
	if (! empty($_POST['snapexclude']) ) {
		$_POST['snapexclude'] = trim($_POST['snapexclude']);
		$tmp = preg_quote($_POST['snapexclude'], '/');
		$snapexclude = str_replace(',', '|', $tmp);
	}

	$snapproc = microtime(true);

	if ($fh = fopen($nfmon_snapshot, 'w') ) {
		fwrite($fh, '<?php die("Forbidden"); ?>' . "\n");
		$res = scd($_POST['snapdir'], $snapexclude, $fh, $snapnoslink);
		fclose($fh);

		// Error ?
		if ($res) {
			if (file_exists($nfmon_snapshot) ) {
				unlink($nfmon_snapshot);
			}
			return $res;
		}
		$stat = stat($nfmon_snapshot);
		if ($stat['size'] < 30 ) {
			unlink($nfmon_snapshot);
			return sprintf( $lang['cannot_create'], '<code>'. $nfmon_snapshot .'</code>');
		}

		// Save scan dir :
		$nfw_options['snapproc'] = round( microtime(true) - $snapproc, 2);
		$nfw_options['snapexclude'] = $_POST['snapexclude'];
		$nfw_options['snapdir'] = $_POST['snapdir'];
		$nfw_options['snapnoslink'] = $snapnoslink;
		if (! $fh = fopen('./conf/options.php', 'w') ) {
			return $lang['error_conf' ];
		}
		fwrite($fh, '<?php' . "\n\$nfw_options = <<<'EOT'\n" . serialize( $nfw_options ) . "\nEOT;\n" );
		fclose($fh);

	} else {
		return sprintf( $lang['cannot_write'], '<code>'. $nfmon_snapshot .'</code>');
	}
}

/* ------------------------------------------------------------------ */

function scd($snapdir, $snapexclude, $fh, $snapnoslink) {

	if (is_readable($snapdir) ) {
		if ($dh = opendir($snapdir) ) {
			while ( FALSE !== ($file = readdir($dh)) ) {
				if ( $file == '.' || $file == '..') { continue; }
				$full_path = $snapdir . '/' . $file;
				if ( $snapexclude ) {
					if ( preg_match("/$snapexclude/", $full_path) ) { continue; }
				}
				if (is_readable($full_path)) {
					if ( $snapnoslink && is_link($full_path)) { continue; }
					if ( is_dir($full_path) ) {
						scd($full_path, $snapexclude, $fh, $snapnoslink);
					} elseif (is_file($full_path) ) {
						$file_stat = stat($full_path);
						fwrite($fh, $full_path . '::' . sprintf ("%04o", $file_stat['mode'] & 0777) . ':' . $file_stat['uid'] . ':' .
							$file_stat['gid'] . ':' . $file_stat['size'] . ':' . $file_stat['mtime'] . ':' .
							$file_stat['ctime'] . "\n");
					}
				}
			}
			closedir($dh);
		} else {
			return sprintf( $lang['cannot_open'], '<code>'. htmlspecialchars($snapdir) .'</code>');
		}
	} else {
		return sprintf( $lang['dir_not_read'], '<code>'. htmlspecialchars($snapdir) .'</code>');
	}
}

/* ------------------------------------------------------------------ */

function nf_sub_monitoring_scan($nfmon_snapshot, $nfmon_diff) {

	global $lang;
	global $nfw_options;

	if (empty($nfw_options['enabled']) ) { return; }

	if (! isset($nfw_options['snapexclude']) || ! isset($nfw_options['snapdir']) || ! isset($nfw_options['snapnoslink']) ) {
		return sprintf( $lang['missing_option'], __LINE__ );
	}
	$tmp = preg_quote($nfw_options['snapexclude'], '/');
	$snapexclude = str_replace(',', '|', $tmp);

	if ($fh = fopen($nfmon_snapshot . '_tmp', 'w') ) {
		fwrite($fh, '<?php die("Forbidden"); ?>' . "\n");
		$res = scd($nfw_options['snapdir'], $snapexclude, $fh, $nfw_options['snapnoslink']);
		fclose($fh);
	} else {
		return sprintf( $lang['cannot_create'], '<code>'. $nfmon_snapshot . '_tmp</code>');
	}

	// Error ?
	if ($res) {
		if (file_exists($nfmon_snapshot . '_tmp') ) {
			unlink($nfmon_snapshot . '_tmp');
		}
		return $res;
	}

	// Compare both snapshots :

	$old_files = $file = $new_files =  array();
	$modified_files = $match = array();

	if (! $fh = fopen($nfmon_snapshot, 'r') ) {
		return sprintf(  $lang['err_old_ss'], __LINE__ );
	}
	while (! feof($fh) ) {
		$match = explode('::', rtrim(fgets($fh)) . '::' );
		if (! empty($match[1]) ) {
			$old_files[$match[0]] = $match[1];
		}
	}
	fclose($fh);

	if (! $fh = fopen($nfmon_snapshot . '_tmp', 'r') ) {
		return sprintf(  $lang['err_new_ss'], __LINE__ );
	}
	while (! feof($fh) ) {
		$match = explode('::', rtrim(fgets($fh)) . '::' );

		if ( empty($match[1]) ) {
			continue;
		}
		// New file ?
		if ( empty( $old_files[$match[0]] ) ) {
			$new_files[$match[0]] = $match[1];
			continue;
		}
		// Modified file ?
		if ( $old_files[$match[0]] !=	$match[1] ) {
			 $modified_files[$match[0]] = $old_files[$match[0]] . '::' . $match[1];
		}
		// Delete it from old files list :
		unset( $old_files[$match[0]] );
	}
	fclose ($fh);

	// Write changes to file, if any :
	if ($new_files || $modified_files || $old_files) {

		$fh = fopen($nfmon_diff, 'w');
		fwrite($fh, '<?php die("Forbidden"); ?>' . "\n");

		if ( $new_files ) {
			foreach ( $new_files as $fkey => $fvalue ) {
				fwrite($fh, $fkey . '::N::' . $fvalue . "\n");
			}
		}
		if ( $modified_files ) {
			foreach ( $modified_files as $fkey => $fvalue ) {
				fwrite($fh, $fkey . '::M::' . $fvalue . "\n");
			}
		}
		if ( $old_files ) {
			foreach ( $old_files as $fkey => $fvalue ) {
				fwrite($fh, $fkey . '::D::' . $fvalue . "\n");
			}
		}
		fclose($fh);
		rename( $nfmon_snapshot . '_tmp', $nfmon_snapshot);

	} else {
		if (file_exists($nfmon_diff) ) {
			// Keep last changes :
			rename($nfmon_diff, $nfmon_diff. '.php');
		}
		unlink( $nfmon_snapshot . '_tmp');
	}
}

/* ------------------------------------------------------------------ */
// EOF
