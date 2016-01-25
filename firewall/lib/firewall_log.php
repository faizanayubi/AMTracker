<?php
/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (Pro edition)                                         |
 |                                                                     |
 | (c) NinTechNet - http://nintechnet.com/                             |
 |                                                                     |
 +---------------------------------------------------------------------+
 | REVISION: 2015-02-19 01:44:08                                       |
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

$log_dir = './nfwlog/';
// Find all available logs :
$avail_logs = array();
if ( is_dir( $log_dir ) ) {
	if ( $dh = opendir( $log_dir ) ) {
		while ( ($file = readdir($dh) ) !== false ) {
			if (preg_match( '/^(firewall_(\d{4})-(\d\d)(?:\.\d+)?\.php)$/', $file, $match ) ) {
				$avail_logs[$match[1]] = 1;
			}
		}
		closedir($dh);
	}
}
krsort($avail_logs);

if (! empty($_GET['nfw_sort']) && isset( $avail_logs[$_GET['nfw_sort']] ) ) {
	$selected_log = $_GET['nfw_sort'];
} else {
	$selected_log ='firewall_' . date( 'Y-m' ) . '.php';
	// If there is no current log, we try to display the one
	// from the previous month (if any) :
	if (! file_exists( $log_dir . $selected_log ) && ! empty($avail_logs) ) {
		$selected_log = key($avail_logs);
	}
}
// If there isn't any old logs, add the current one to the array :
if (empty( $avail_logs) ) {
	$avail_logs[$selected_log] = 1;
}

// Ensure it exists :
$err_msg = '';
if ( file_exists( $log_dir . $selected_log ) ) {
	if (! is_writable( $log_dir . $selected_log ) ) {
		$err_msg = $lang['log_readonly'];
	}
} else {
	if (! is_writable( $log_dir ) ) {
		$err_msg = $lang['dir_readonly'];
	}
}
if ($err_msg) {
	echo '<br /><div class="error"><p>' . $err_msg .'</p></div>';
}

if ( isset($_POST['save']) ) {
	$err_msg = '';
	// Delete log ?
	if ( $_POST['save'] == $lang['del_log'] ) {
		// Delete the current month log :
		$cur_log = $log_dir . 'firewall_' . date('Y-m') . '.php';
		if ( file_exists( $cur_log ) ) {
			if (! $fh = fopen( $cur_log, 'w') ) {
				$err_msg = $lang['cannot_delete'];
			} else {
				fwrite( $fh,'[' . time() . '] [0] [' . $_SERVER['SERVER_NAME'] .
					'] [#0000000] [0] [6] ' . '[' . $_SERVER['REMOTE_ADDR'] . '] ' .
					'[200 OK] ' . '[' . $_SERVER['REQUEST_METHOD'] . '] ' .
					'[' . $_SERVER['SCRIPT_NAME'] . '] ' . '[Log deleted by admin] ' .
					'[' . $nfw_options['admin_name'] . ': ' . $cur_log . ']' . "\n"
				);
				fclose($fh);
			}
		}

	// Save options ?
	} elseif ( $_POST['save'] == $lang['save_conf'] ) {
		// Update options :
		if (empty( $_POST['logging']) ) {
			$nfw_options['logging'] = 0;
		} else {
			$nfw_options['logging'] = 1;
		}
		if ( empty($_POST['log_rotate']) ) {
			$nfw_options['log_rotate'] = 0;
			$nfw_options['log_maxsize'] = 2 * 1048576;
		} else {
			$nfw_options['log_rotate'] = 1;
			if ( empty($_POST['log_maxsize']) || ! preg_match('/^([1-9]?[0-9])$/', $_POST['log_maxsize']) ) {
				$nfw_options['log_maxsize'] = 2 * 1048576;
			} else {
				$nfw_options['log_maxsize'] = $_POST['log_maxsize'] * 1048576;
			}
		}
		$err_msg = save_firewall_log();
	} else {
		$err_msg = 'Unknown request';
	}
	if ($err_msg) {
		echo '<br /><div class="error"><p>' . $err_msg .'</p></div>';
	} else {
		echo '<br /><div class="success"><p>'. $lang['saved_conf'] . '</p></div>';
	}
}
?>
<script>
function is_number(id) {
	var e = document.getElementById(id);
	if (! e.value ) { return }
	if (! /^[0-9]+$/.test(e.value) ) {
		alert("<?php echo $lang['js_digit'] ?>");
		e.value = e.value.substring(0, e.value.length-1);
	}
}
function del_log() {
   if (confirm("<?php echo $lang['js_del_log'] ?>")){
      return true;
   }else{
		return false;
   }
}
</script>
<?php

// Do we have any log for this month ?
if (! file_exists( $log_dir . $selected_log ) ) {
	echo '<br /><div class="warning"><p>' . $lang['missing_log'] .'</p></div><br />';
	// Display Log option even if there is no log yet :
	firewall_log_options(0);
	html_footer();
	exit;
}
if (! $fh = @fopen( $log_dir . $selected_log, 'r' ) ) {
	echo '<br /><div class="error"><p>' . $lang['cannot_open'] . ' ' . $selected_log .'</p></div><br />';
	firewall_log_options(0);
	html_footer();
	exit;
}

// We will only display the last $max_lines lines,
// and will warn about it if the log is bigger :
$count = 0;
$max_lines = 1500;
while (! feof( $fh ) ) {
	fgets( $fh );
	$count++;
}
// Skip last empty line :
$count--;
fclose( $fh );
if ( $count < $max_lines ) {
	$skip = 0;
} else  {
	echo '<br /><div class="warning"><p>' . sprintf($lang['too_big'], $count, $max_lines) .'</p></div>';
	$skip = $count - $max_lines;
}

$levels = array( '', 'medium', 'high', 'critical', 'error', 'upload', 'info', 'DEBUG_ON' );
?>

<script>
var myToday = '<?php echo date( 'd/M/y') ?>';
var myArray = new Array();
<?php

$fh = fopen( $log_dir . $selected_log, 'r' );
$i = 0;
$logline = '';
$severity = array( 0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0);
while (! feof( $fh ) ) {
	$line = fgets( $fh );
	if ( $skip <= 0 ) {
		// We must use a funky regex to parse the log because NinjaFirewall v1.x
		// and v2.x logs do not have the same format :/
		if ( preg_match( '/^\[(\d{10})\]\s+\[.+?\]\s+\[(.+?)\]\s+\[(#\d{7})(?:-|\]\s+\[)(\d+)\]\s+\[(\d)\]\s+\[([\d.:a-fA-F, ]+?)\]\s+\[.+?\]\s+\[(.+?)\]\s+\[(.+?)\]\s+\[(.+?)\]\s+\[(.+)\]$/', $line, $match ) ) {
			if ( empty( $match[4]) ) { $match[4] = '-'; }
			$res = date( 'd/M/y H:i:s', $match[1] ) . '  ' . $match[3] . '  ' . str_pad( $levels[$match[5]], 8 , ' ', STR_PAD_RIGHT) .'  ' .
			str_pad( $match[4], 4 , ' ', STR_PAD_LEFT) . '  ' . str_pad( $match[6], 15, ' ', STR_PAD_RIGHT) . '  ' .
			$match[7] . ' ' . $match[8] . ' - ' .	$match[9] . ' - [' . $match[10] . '] - ' . $match[2];
			echo 'myArray[' . $i . '] = "' . rawurlencode($res) . '";' . "\n";
			$logline .= htmlentities( $res ."\n" );
			$i++;
			// Keep track of severity levels :
			$severity[$match[5]] = 1;
		}
	}
	$skip--;
}
fclose( $fh );

?>
function filter_log() {
	// Clear the log :
	document.frmlog.txtlog.value = '       DATE         INCIDENT  LEVEL     RULE     IP            REQUEST\n';
	// Prepare the regex :
	var nf_tmp = '';
	if ( document.frmlog.nf_crit.checked == true ) {
		nf_tmp += 'critical|';
	}
	if ( document.frmlog.nf_high.checked == true ) {
		nf_tmp += 'high|';
	}
	if ( document.frmlog.nf_med.checked == true ) {
		nf_tmp += 'medium|';
	}
	if ( document.frmlog.nf_upl.checked == true ) {
		nf_tmp += 'upload|';
	}
	if ( document.frmlog.nf_nfo.checked == true ) {
		nf_tmp += 'info|';
	}
	if ( document.frmlog.nf_dbg.checked == true ) {
		nf_tmp += 'DEBUG_ON|';
	}
	// Return if empty :
	if ( nf_tmp == '' ) {
		document.frmlog.txtlog.value = '\n > <?php echo $lang['js_nomatch'] ?>';
		return true;
	}
	// Put it all together :
	var nf_reg = new RegExp('^[^\\s]+\\s+[^\\s]+\\s+[^\\s]+\\s+' + '(' + nf_tmp.slice(0, - 1) + ')' + '\\s');
	var nb = 0;
	var decodearray;
	for ( i = 0; i < myArray.length; i++ ) {
		decodearray = decodeURIComponent(myArray[i]);
		if ( document.frmlog.nf_today.checked == true ) {
			if (! decodearray.match(myToday) ) { continue;}
		}
		if ( decodearray.match(nf_reg) ) {
			// Display it :
			document.frmlog.txtlog.value += decodearray + '\n';
			nb++;
		}
	}
	if ( nb == 0 ) {
		document.frmlog.txtlog.value = '\n > <?php echo $lang['js_nomatch'] ?>';
	}
}
</script>

<br />
<fieldset><legend>&nbsp;<b><?php echo $lang['log'] ?></b>&nbsp;</legend>
	<center><?php echo $lang['viewing'] ?> <select name="nfw_sort" class="input" onChange='window.location="?mid=<?php echo $GLOBALS['mid'] ?>&token=<?php echo $_REQUEST['token'] ?>&nfw_sort=" + this.value;'>';
<?php
foreach ($avail_logs as $log_name => $tmp) {
	echo '<option value="' . $log_name . '"';
	if ( $selected_log == $log_name ) {
		echo ' selected';
	}
	$log_stat = stat($log_dir . $log_name);
	echo '>' . str_replace('.php', '', $log_name) . ' (' . number_format($log_stat['size']) . ' bytes)</option>';
}
?>
	</select></center>
	<br />
	<form name="frmlog">
		<table width="100%" class="smallblack" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="100%" align="center">
					<textarea name="txtlog" style="background-color:#ffffff;width:95%;height:250px;border:1px solid #FDCD25;padding:4px;font-family:monospace;font-size:13px;" wrap="off"><?php
					echo '       DATE         INCIDENT  LEVEL     RULE     IP            REQUEST' . "\n";
					echo $logline; ?></textarea>
					<br />
					<label><input type="checkbox" name="nf_today" onClick="filter_log();"><?php echo $lang['today'] ?></label>&nbsp;&nbsp;
					<label><input type="checkbox" name="nf_crit" onClick="filter_log();"<?php checked($severity[3], 1) ?>><?php echo $lang['critical'] ?></label>&nbsp;&nbsp;
					<label><input type="checkbox" name="nf_high" onClick="filter_log();"<?php checked($severity[2], 1) ?>><?php echo $lang['high'] ?></label>&nbsp;&nbsp;
					<label><input type="checkbox" name="nf_med" onClick="filter_log();"<?php checked($severity[1], 1) ?>><?php echo $lang['medium'] ?></label>&nbsp;&nbsp;
					<label><input type="checkbox" name="nf_upl" onClick="filter_log();"<?php checked($severity[5], 1) ?>><?php echo $lang['uploads'] ?></label>&nbsp;&nbsp;
					<label><input type="checkbox" name="nf_nfo" onClick="filter_log();"<?php checked($severity[6], 1) ?>><?php echo $lang['info'] ?></label>&nbsp;&nbsp;
					<label><input type="checkbox" name="nf_dbg" onClick="filter_log();"<?php checked($severity[7], 1) ?>><?php echo $lang['debug'] ?></label>
				</td>
			</tr>
		</table>
	</form>
</fieldset>

<br />
<br />
<?php

firewall_log_options(1);
html_footer();

/* ------------------------------------------------------------------ */

function firewall_log_options($is_log) {

	// Need to refresh options :
	global $nfw_options;
	global$lang;

	if ( empty($nfw_options['logging']) ) {
		$nfw_options['logging'] = 0;
		$img = '<img src="static/icon_warn.png" border="0" width="21" heigt="21">';
	} else {
		$nfw_options['logging'] = 1;
		$img = '&nbsp;';
	}
	if ( empty($nfw_options['log_rotate']) ) {
		$nfw_options['log_rotate'] = 0;
		$nfw_options['log_maxsize'] = 2;
	} else {
		// Default : rotate at the end of the month OR if bigger than 5MB
		$nfw_options['log_rotate'] = 1;
		if ( empty($nfw_options['log_maxsize']) || ! ctype_digit($nfw_options['log_maxsize']) ) {
			$nfw_options['log_maxsize'] = 2;
		} else {
			$nfw_options['log_maxsize'] = intval( $nfw_options['log_maxsize'] / 1048576);
			if (empty( $nfw_options['log_maxsize']) ) {
				$nfw_options['log_maxsize'] = 2;
			}
		}
	}
?>
<form method="post">
<fieldset><legend>&nbsp;<b><?php echo $lang['log_options'] ?></b>&nbsp;</legend>
	<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
		<tr>
			<td width="55%" align="left"><?php echo $lang['enable_log'] ?></td>
			<td width="45%" align="left">
				<p><label><input type="radio" name="logging" value="1"<?php checked($nfw_options['logging'], 1) ?>>&nbsp;<?php echo $lang['yes'] . $lang['default'] ?></label></p>
				<p><label><input type="radio" name="logging" value="0"<?php checked($nfw_options['logging'], 0) ?>>&nbsp;<?php echo $lang['no'] ?></label>&nbsp;<?php echo $img ?></p>

			</td>
		</tr>
		<tr>
			<td width="55%" align="left" class="dotted"><?php echo $lang['auto_rotate'] ?></td>
			<td width="45%" align="left" class="dotted">
				<p><label><input type="radio" name="log_rotate" value="1"<?php checked($nfw_options['log_rotate'], 1) ?>>&nbsp;<?php printf($lang['rotate_size'], '</label>&nbsp;<input class="input" id="sizeid" name="log_maxsize" size="2" maxlength="2" value="' . $nfw_options['log_maxsize'] . '" onkeyup="is_number(\'sizeid\')" type="text">') ?> <?php echo $lang['default'] ?></p>
				<p><label><input type="radio" name="log_rotate" value="0"<?php checked($nfw_options['log_rotate'], 0) ?>>&nbsp;<?php echo $lang['rotate'] ?></label></p>
			</td>
		</tr>
	</table>

</fieldset>
	<br />
	<center>
	<input type="submit" class="button" name="save" value="<?php echo $lang['save_conf'] ?>">
	<?php
	if ( $is_log ) {
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" class="button" value="'. $lang['del_log'] . '" name="save" onclick="return del_log();" title="Delete the current log" />';
	}
	?>
	</center>
</form>
<br />

<?php
}

/* ------------------------------------------------------------------ */

function save_firewall_log() {

	global $lang;
	global $nfw_options;

	// Config file must be writable :
	if (! is_writable('./conf/options.php') ) {
		return $lang['error_conf' ];
	}

	// Save changes :
	if (! $fh = fopen('./conf/options.php', 'w') ) {
		return $lang['error_conf' ];
	}
	fwrite($fh, '<?php' . "\n\$nfw_options = <<<'EOT'\n" . serialize( $nfw_options ) . "\nEOT;\n" );
	fclose($fh);

	return;
}

/* ------------------------------------------------------------------ */
// EOF
