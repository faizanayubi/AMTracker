<?php
/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (Pro edition)                                         |
 |                                                                     |
 | (c) NinTechNet - http://nintechnet.com/                             |
 |                                                                     |
 +---------------------------------------------------------------------+
 | REVISION: 2014-11-11 01:53:54                                       |
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

$nfw_update  = 0;
$updafe_file = './nfwlog/cache/nfw_update.php';
$zip_file    = './nfwlog/cache/nfw_update_zip.php';
$extract_path = dirname( __DIR__ );

if (! function_exists('curl_init') ) {
	echo '<br /><div class="error"><p>' . sprintf( $lang['err_curlinit'], 317730 ) . '</p></div>';
	$_POST['nfw_update'] = 0;
}

// Update requested ?
if (! empty($_POST['nfw_update']) ) {

	// Ensure the cache dir is writable :
	if (! is_writable('./nfwlog/cache/') ) {
		echo '<br /><div class="error"><p>' . sprintf( $lang['err_cache_rw'], 106788 ) . '</p></div>';
		goto NFW_AU_END;
	}

	// Ensure NF's root directory is writable :
	if (! is_writable( dirname(__DIR__) ) ) {
		echo '<br /><div class="error"><p>' . sprintf( $lang['err_root_rw'], dirname(__DIR__), 123736 ) .'</p></div>';
		goto NFW_AU_END;
	}

	if (! $res = download_account_update() ) {
		if ( file_exists($updafe_file) ) {
			unlink($updafe_file);
		}
		goto NFW_AU_END;
	}

	// Include the update script :
	if (! file_exists($updafe_file) ) {
		echo '<br /><div class="error"><p>' . sprintf( $lang['failed_download'], 195483 ) .'</p></div>';
		goto NFW_AU_END;
	}
	// Load update script :
	require ($updafe_file);
	// Ensure we get the version :
	if (! defined('NF_UPDATE_VERSION') ) {
		echo '<br /><div class="error"><p>' . sprintf( $lang['err_get_ver'], 114084 ) .'</p></div>';
		unlink($updafe_file);
		goto NFW_AU_END;
	}
	// Double check the new version :
	if ( version_compare( NFW_ENGINE_VERSION, NF_UPDATE_VERSION, '>=' ) ) {
		echo '<br /><div class="error"><p>' . sprintf( $lang['err_ver'], NFW_ENGINE_VERSION, NF_UPDATE_VERSION, 132636 ) .'</p></div>';
		unlink($updafe_file);
		goto NFW_AU_END;
	}

	// Double check edn :
	if ( NFW_EDN != NF_UPDATE_EDN ) {
		echo '<br /><div class="error"><p>' . sprintf( $lang['err_edn'], 118386 ) .'</p></div>';
		unlink($updafe_file);
		goto NFW_AU_END;
	}

	// What are we supposed to do ?
	if ( $_POST['what'] == 1 ) {
		// Display change log :
		$nfw_update = 1;
		?>
		<br />
		<form method="post" name="update_form">
			<fieldset><legend>&nbsp;<b><?php echo $lang['changelog'] ?></b>&nbsp;</legend>
				<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
				<tr>
					<td width="100%" align="center"><div align="left"><?php printf($lang['review'], NFW_ENGINE_VERSION, NF_UPDATE_VERSION) ?></div>
						<br /><textarea style="background-color:#ffffff;width:95%;height:250px;border:1px solid #FDCD25;padding:4px;font-family:monospace;font-size:13px;"><?php echo htmlentities($changelog) ?></textarea>
					</td>
				</tr>
				</table>
			</fieldset>
			<center>
			<input type="hidden" name="what" value="2" />
			<p><input class="button" type="submit" name="nfw_update" value="<?php echo $lang['install_update2'] ?>" /></p>
			</center>
		</form>

		<?php
		html_footer();
		exit;

	} elseif ( $_POST['what'] == 2 ) {
		// Update :
		$nfw_update = 2;

		// Ensure we can extract the ZIP file :
		if (! class_exists( 'ZipArchive' ) ) {
			echo '<br /><div class="error"><p>' . sprintf( $lang['err_zipclass'], 853318 ) . '</p></div>';
			if ( file_exists($updafe_file) ) {
				unlink($updafe_file);
			}
			goto NFW_AU_END;
		}

		$res = unpack_account_update();

		// Delete temp files :
		if ( file_exists($updafe_file) ) {
			unlink($updafe_file);
		}
		if ( file_exists($zip_file) ) {
			unlink($zip_file);
		}

		// Failed ?
		if (! $res) {
			goto NFW_AU_END;
		}

		// Success :
		$_SESSION['ver'] = 0;

		echo '<br /><div class="success"><p>' . $lang['update_ok'] .'</p></div><br />
			<form method="post" action="?token=' . $_REQUEST['token'] . '&rand=' . time() . '">
			<input type="submit" value="' . $lang['update_ok_bt'] . '">
			<input type="hidden" name="mid" value="' . $GLOBALS['mid'] . '">
			<input type="hidden" name="nfw_what" value="check">
			</form>';

		html_footer();
	}

} else {

	// Request to check again for updates ?
	if (! empty($_POST['nfw_checkupdate']) ) {
		$_SESSION['ver'] = 0;
	}

	// Check for update if we haven't done so yet :
	if ($_SESSION['ver'] < 1) {
		$res = check_account_update();
		if (! $res ) {
			// Could not get updates info :
			$connect_err = 1;
			goto NFW_AU_END;
		}
	}
	if (! empty($_POST['nfw_checkupdate']) && version_compare( NFW_ENGINE_VERSION, $_SESSION['vapp'], '>=' ) ) {
		echo '<br /><div class="success"><p>' . $lang['up2date'] .'</p></div>';
	}
}

NFW_AU_END:

$is_update = 0;
$twitter = 'Updates info:<br /><a href="https://twitter.com/nintechnet"><img border="0" src="static/twitter_ntn.png" width="116" height="28" target="_blank"></a>';
?>
<br />
<fieldset><legend>&nbsp;<b><?php echo $lang['avail_update'] ?></b>&nbsp;</legend>
	<form method="post" name="update_form">
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td width="55%" align="left"><?php printf($lang['cur_version'], '<b>'. NFW_ENGINE_VERSION .'</b>', preg_replace('/(\d{4})(\d\d)(\d\d)/', '$1-$2-$3', NFW_RULES_VERSION) ); ?><br /><a href="javascript:popup('?mid=99&token=<?php echo $_REQUEST['token'] ?>',640,480,0);" class="links" style="border-bottom:dotted 1px #FDCD25;"><?php echo $lang['changelog'] ?></a></td>
				<td width="45%">
					<?php
					if ( version_compare( NFW_ENGINE_VERSION, $_SESSION['vapp'], '<' ) ) {
						echo '<img src="static/icon_warn.png" border="0" width="21" height="21">&nbsp;' . sprintf( $lang['new_version'], $_SESSION['vapp']);
						$is_update = 1;
					} else {
						if (! empty($connect_err) ) {
							// Error :
							echo '<img src="static/icon_warn.png" border="0" width="21" height="21">&nbsp;' . $lang['failed_check'];
						} else {
							// OK
							echo '&nbsp;' . $lang['up2date'];
						}
					}
					?>
				</td>
			</tr>
		<?php
		//  If we have an update available, show the 'download' button :
		if ( $is_update ) {
		?>
			<tr>
				<td width="55%" align="left" ><?php echo $twitter ?></td>
				<td width="45%" >
					<input type="hidden" name="what" value="1" />
					<input class="button" type="submit" name="nfw_update" value="<?php echo $lang['install_update'] ?>" />
					<p><input class="button" type="submit" name="nfw_checkupdate" value="<?php echo $lang['check_update'] ?>" /></p>
				</td>
			</tr>
		<?php
		} else {
		// Otherwise, show the 'check update' button only :
		?>
			<tr>
				<td width="55%" align="left" ><?php echo $twitter ?></td>
				<td width="45%" >
					<?php	if ( function_exists('curl_init') ) { ?>
					<input class="button" type="submit" name="nfw_checkupdate" value="<?php echo $lang['check_update'] ?>" />
					<?php } else { ?>
					<input class="button" type="submit" name="-" disabled value="<?php echo $lang['check_update'] ?>" />
					<?php } ?>
				</td>
			</tr>
		<?php
		}
		?>
		</table>
		<input type="hidden" name="mid" value="<?php echo $GLOBALS['mid'] ?>">
		<input type="hidden" name="nfw_what" value="check">
	</form>
</fieldset>
<?php

html_footer();

/* ------------------------------------------------------------------ */

function check_account_update() {

	global $lang;
	require('./lib/misc.php');

	$tmp = '';
	if (! NFW_UPDATE ) {
		echo '<br /><div class="error"><p>' . $lang['nfw_update'] . '</p></div>';
		return 0;
	}
	if (function_exists('curl_init') ) {
		$data  = 'action=checkversion';
		$data .= '&edn=' . urlencode(NFW_EDN);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_USERAGENT, 'NinjaFirewall/' . NFW_ENGINE_VERSION . ':' . NFW_EDN );
		curl_setopt( $ch, CURLOPT_ENCODING, '');
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
		curl_setopt( $ch, CURLOPT_URL, 'http://'. NFW_UPDATE . '/index.php' );
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
		curl_setopt ($ch, CURLOPT_HEADER, 0);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

		if ( ($content = curl_exec($ch)) === FALSE ) {
			// cURL error (connection, timeout etc) :
			$CURL_ERR = curl_error( $ch );
			echo '<br /><div class="error"><p>' . sprintf($lang['curl_connect'], $CURL_ERR)  . '</p></div>';
			@curl_close( $ch );
			return 0;
		}

		$response = curl_getinfo( $ch );
		curl_close($ch);

		// HTTP error ?
		if ( $response['http_code'] != 200 ) {
			echo '<br /><div class="error"><p>' . sprintf( $lang['curl_retcode'], $response['http_code'], $http_err_code[$response['http_code']] ) .'</p></div>';
			return 0;
		}

		$tmp = @unserialize( $content );

		if (! empty( $tmp[NFW_EDN] ) ) {
			$_SESSION['vapp'] = $tmp[NFW_EDN];
		} else {
			$_SESSION['vapp'] = NFW_ENGINE_VERSION;
		}
		$_SESSION['ver'] = 1;
		return 1;
	} else {
		$_SESSION['ver'] = 0;
		echo '<br /><div class="error"><p>' . sprintf($lang['err_curlinit'], 411384 ) .'</p></div>';
		return 0;
	}
}

/* ------------------------------------------------------------------ */
function download_account_update() {

	$tmp = '';
	global $lang;
	global $nfw_options;
	global $updafe_file;
	require('./lib/misc.php');

	$data  = 'action=update';
	$data .= '&edn=' . urlencode( NFW_EDN );
	$data .= '&ver=' . urlencode( NFW_ENGINE_VERSION );

	// pro+ edn only :
	if ( NFW_EDN == 2 ) {
		$domain = strtolower( $_SERVER['SERVER_NAME'] );
		$data .= '&host=' . urlencode( $domain );
		$data .= '&lic=' . urlencode( $nfw_options['lic'] );
	}

	if (! NFW_UPDATE ) {
		echo '<br /><div class="error"><p>' . $lang['nfw_update'] . '</p></div>';
		return 0;
	}

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

	if ( ($content = @unserialize(curl_exec($ch)) ) === FALSE ) {
		// cURL error (connection, timeout etc) :
		$CURL_ERR = curl_error( $ch );
		echo '<br /><div class="error"><p>' . sprintf( $lang['curl_connect'], $CURL_ERR ) . '</p></div>';
		@curl_close( $ch );
		return 0;
	}

	$response = curl_getinfo( $ch );
	curl_close( $ch );

	// HTTP error ?
	if ( $response['http_code'] != 200 ) {
		echo '<br /><div class="error"><p>' . sprintf( $lang['curl_retcode'], $response['http_code'], $http_err_code[$response['http_code']], 714293 ) .'</p></div>';
		return 0;
	}

	if (! empty($content['err']) ) {
		if ( $content['err'] > 10 ) {
			echo '<br /><div class="error"><p>' . sprintf( $lang['curl_invlic'], $content['err'] ) . '</p></div>';
		} else {
			echo '<br /><div class="error"><p>' . sprintf( $lang['curl_err'], $content['err'] ) . '</p></div>';
		}
		return 0;
	}

	if ( empty($content['file']) ) {
		echo '<br /><div class="error"><p>' . sprintf( $lang['curl_empty'], 798330 ) . '</p></div>';
		return 0;
	}

	// Ensure we have a PHP script :
	if (! preg_match('/^<\?php\s/', $content['file']) ) {
		$content = '';
		echo '<br /><div class="error"><p>' . sprintf( $lang['curl_wrong'], 561887 ) . '</p></div>';
		return 0;
	}

	// Save it :
	@file_put_contents( $updafe_file, $content['file'], LOCK_EX);

	// OK :
	return 1;
}

/* ------------------------------------------------------------------ */
function unpack_account_update() {

	global $lang;
	global $nfw_options;
	global $updafe_file;
	global $package;
	global $zip_file;
	global $extract_path;

	// Decode the content :
	$tmp_data = base64_decode( $package );
	// Ensure we have a ZIP header :
	if (! preg_match('/^\x50\x4b\x03\x04/', $tmp_data) ) {
		$tmp_data = '';
		echo '<br /><div class="error"><p>' . sprintf( $lang['err_nozip'], 280543 ) . '</p></div>';
		return 0;
	}
	// Save data to a ZIP file :
	@file_put_contents( $zip_file, $tmp_data, LOCK_EX);

	// Extract it :
	$zip = new ZipArchive;
	if ( $zip->open($zip_file) === TRUE ) {
		$zip->extractTo($extract_path);
		$zip->close();
	} else {
		unlink( $zip_file );
		echo '<br /><div class="error"><p>' . sprintf( $lang['err_zipext'], 712295 ) . '</p></div>';
		return 0;
	}

	// OK
	return 1;
}

/* ------------------------------------------------------------------ */
// EOF
