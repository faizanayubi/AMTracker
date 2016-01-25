<?php
/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (Pro edition)                                         |
 |                                                                     |
 | (c) NinTechNet - http://nintechnet.com/                             |
 |                                                                     |
 +---------------------------------------------------------------------+
 | REVISION: 2015-09-18 23:36:17                                       |
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

require (__DIR__ .'/lang/' . $nfw_options['admin_lang'] . '/' . basename(__FILE__) );

$critical = $high = $medium = $slow = $benchmark =
$tot_bench = $speed = $upload = $banned_ip = 0;
$fast = 1000;

// Which monthly log should we read ?
$xtr = @$_GET['xtr'];
if ( empty($xtr) || ! preg_match('/^firewall_\d{4}-\d{2}\.php$/', $xtr) ) {
	$xtr = 'firewall_' . date('Y-m') . '.php';
	$fw_log = './nfwlog/' . $xtr;
}
$fw_log = './nfwlog/' . $xtr;

html_header();

if (! file_exists($fw_log) ) {
	goto NO_STATS_FILE;
}

if ($fh = @fopen($fw_log, 'r') ) {
	while (! feof($fh) ) {
		$line = fgets($fh);
		if (preg_match('/^\[.+?(?:\s.\d{4})?\]\s+\[(.+?)\]\s+(?:\[.+?\]\s+){3}\[([1-6])\]/', $line, $match) ) {
			if ($match[2] == 1) {
				$medium++;
			} elseif ($match[2] == 2) {
				$high++;
			} elseif ($match[2] == 3) {
				$critical++;
			} elseif ($match[2] == 5) {
				$upload++;
			} elseif ($match[2] == 6) {
				if (strpos($line, 'Banning IP') !== false) {
					$banned_ip++;
				}
			}
			if ($match[1]) {
				if ( $match[1] > $slow) {
					$slow = $match[1];
				}
				if ( $match[1] < $fast) {
					$fast = $match[1];
				}
				$speed += $match[1];
				$tot_bench++;
			}
		}
	}
	fclose($fh);
} else {
	echo '<br /><div class="error"><p>' . $lang['failed_open'] . ' ['. $fw_log . ']</p></div>';
	summary_stats_combo($xtr);
	html_footer();
	exit;
}

NO_STATS_FILE:

$total = $critical + $high + $medium;
if ($total == 1) {$fast = $slow;}

if (! $total) {
	echo '<br /><div class="warning"><p>' . $lang['no_stats'] . '</p></div>';
	$fast = 0;
} else {
	$coef = 100 / $total;
	$critical = round($critical * $coef, 2);
	$high = round($high * $coef, 2);
	$medium = round($medium * $coef, 2);
	// Avoid divide error :
	if ($tot_bench) {
		$speed = round($speed / $tot_bench, 4);
	} else {
		$fast = 0;
	}
}
// Prepare select box :
$ret = summary_stats_combo($xtr);

?>
<script>
	function stat_redir(where) {
		if (where == '') { return false;}
		document.location.href='?mid=11&token=<?php echo $_REQUEST['token'] ?>&xtr=' + where;
	}
</script>
<br />
<fieldset><legend>&nbsp;<b><?php echo $lang['firewall'] ?></b>&nbsp;</legend>
	<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
		<tr>
			<td width="50%"><?php echo  $lang['stats_period'] ?></td>
			<td width="50%" align="center"><?php echo  $ret ?></td>
		</tr>
		<tr>
			<td width="50%"><?php echo $lang['blocked_hack'] ?></td>
			<td width="50%" align="center"><?php echo $total ?></td>
		</tr>
		<tr>
			<td width="50%"><?php echo $lang['severity'] ?></td>
			<td width="50%" align="center" class="tinyblack">
				<?php echo $lang['critical'] . ' : ' . $critical ?>%<br />
				<table bgcolor="#dddddd" border="0" cellpadding="0" cellspacing="0" height="14" width="252" align="center" style="height:14px;">
					<tr>
						<td width="<?php echo round($critical) ?>%" background="static/bar-critical.png"></td>
						<td width="<?php echo round(100-$critical) ?>%"></td>
					</tr>
				</table>
				<br />
					<?php echo $lang['high'] . ' : ' . $high ?>%<br />
				<table bgcolor="#dddddd" border="0" cellpadding="0" cellspacing="0" height="14" width="252" align="center" style="height:14px;">
					<tr>
						<td width="<?php echo round($high) ?>%" background="static/bar-high.png"></td>
						<td width="<?php echo round(100-$high) ?>%"></td>
					</tr>
				</table>
				<br />
					<?php echo $lang['medium'] . ' : ' . $medium ?>%<br />
				<table bgcolor="#dddddd" border="0" cellpadding="0" cellspacing="0" height="14" width="252" align="center" style="height:14px;">
					<tr>
						<td width="<?php echo round($medium) ?>%" background="static/bar-medium.png"></td>
						<td width="<?php echo round(100-$medium) ?>%"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width="50%"><?php echo $lang['tot_upload'] ?></td>
			<td width="50%" align="center"><?php echo $upload ?></td>
		</tr>
		<tr>
			<td width="50%"><?php echo $lang['ban_ip'] ?></td>
			<td width="50%" align="center"><?php echo $banned_ip ?></td>
		</tr>
	</table>
</fieldset>
<br />
<fieldset><legend>&nbsp;<b><?php echo $lang['benchmarks'] ?></b>&nbsp;</legend>
	<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
		<tr>
			<td width="50%"><?php echo $lang['aver_time'] ?></td>
			<td width="50%" align="center"><?php echo $speed ?>s</td>
		</tr>
		<tr>
			<td width="50%"><?php echo $lang['fast_req'] ?></td>
			<td width="50%" align="center"><?php echo round($fast, 4) ?>s</td>
		</tr>
		<tr>
			<td width="50%"><?php echo $lang['slow_req'] ?></td>
			<td width="50%" align="center"><?php echo round($slow, 4) ?>s</td>
		</tr>
	</table>
</fieldset>
<br />
<?php

html_footer();

/* ------------------------------------------------------------------ */
function summary_stats_combo( $xtr ) {

	// Find all available logs :
	$avail_logs = array();
	if ( is_dir( './nfwlog/' ) ) {
		if ( $dh = opendir( './nfwlog/' ) ) {
			while ( ($file = readdir($dh) ) !== false ) {
				if (preg_match( '/^(firewall_(\d{4})-(\d\d)\.php)$/', $file, $match ) ) {
					$log_stat = stat( './nfwlog/' . $file );
					if ( $log_stat['size'] < 10 ) { continue; }
					$month = date('F', mktime(0, 0, 0, $match[3], 1, 2000) );
					$avail_logs[$match[1] ] = $month . ' ' . $match[2];
				}
			}
			closedir($dh);
		}
	}
	krsort($avail_logs);

	$ret = '<br />
	<center>
		<form>
			<select class="input" name="xtr" onChange="return stat_redir(this.value);">
				<option value="">' . $GLOBALS['lang']['select_stats'] . '</option>';
   foreach ($avail_logs as $file => $text) {
      $ret .= '<option value="' . $file . '"';
      if ($file === $xtr ) {
         $ret .= ' selected';
      }
      $ret .= '>' . $text . '</option>';
   }
   $ret .= '</select>
		</form>
	</center>';
	return $ret;
}

/* ------------------------------------------------------------------ */
// EOF
