<?php
/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (Pro edition)                                         |
 |                                                                     |
 | (c) NinTechNet - http://nintechnet.com/                             |
 |                                                                     |
 +---------------------------------------------------------------------+
 | REVISION: 2015-03-21 16:19:03                                       |
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

if ( version_compare($nfw_options['engine_version'], NFW_ENGINE_VERSION, '<') ) {
	// v2.0.3 update -------------------------------------------------
	if (empty($nfw_options['response_headers']) ) {
		if ( function_exists('header_register_callback') && function_exists('headers_list') && function_exists('header_remove') ) {
			$nfw_options['response_headers'] = '000000';
		}
	}
	// v2.0.5 update -------------------------------------------------
	if ( version_compare( $nfw_options['engine_version'], '2.0.5', '<' ) ) {
		$nfw_options['admin_wl'] = 0;
		$nfw_options['admin_wl_session'] = 0;
		$nfw_options['fg_exclude'] = '';
	}
	// ---------------------------------------------------------------

	// Adjust engine version :
	$nfw_options['engine_version'] = NFW_ENGINE_VERSION;

	$update_options = 1;
}


if ( version_compare($nfw_options['rules_version'], NFW_RULES_VERSION, '<') ) {
	// Get the new set of rules :
	require('./conf/.rules.php');
	$nfw_rules_new = unserialize($nfw_rules_new);

	foreach ( $nfw_rules_new as $new_key => $new_value ) {
		foreach ( $new_value as $key => $value ) {
			// if that rule exists already, we keep its 'on' flag value
			// as it may have been changed by the user with the rules editor :
			if ( ( isset( $nfw_rules[$new_key]['on'] ) ) && ( $key == 'on' ) ) {
				$nfw_rules_new[$new_key]['on'] = $nfw_rules[$new_key]['on'];
			}
		}
	}
	$nfw_rules_new[NFW_DOC_ROOT]['what']= $nfw_rules[NFW_DOC_ROOT]['what'];
	$nfw_rules_new[NFW_DOC_ROOT]['on']	= $nfw_rules[NFW_DOC_ROOT]['on'];

	// v2.0.1 / 20140927 update --------------------------------------
	// We delete rules #151 and #152
	if ( version_compare( $nfw_options['rules_version'], '20140927', '<' ) ) {
		if ( isset($nfw_rules_new[151]) ) {
			unset($nfw_rules_new[151]);
		}
		if ( isset($nfw_rules_new[152]) ) {
			unset($nfw_rules_new[152]);
		}
	}
	// ---------------------------------------------------------------

	// Save rules :
	if ( $fh = fopen('./conf/rules.php', 'w') ) {
		fwrite($fh, '<?php' . "\n\$nfw_rules = <<<'EOT'\n" . serialize( $nfw_rules_new ) . "\nEOT;\n" );
		fclose($fh);
	} else {
		echo '<br /><div class="error"><p>' . $lang['error_rules' ] .'</p></div>';
	}

	// Adjust rules version :
	$nfw_options['rules_version'] = NFW_RULES_VERSION;

	$update_options = 1;
}
// Updates options if needed :
if (! empty($update_options) ) {
	// Save options :
	if ( $fh = fopen('./conf/options.php', 'w') ) {
		fwrite($fh, '<?php' . "\n\$nfw_options = <<<'EOT'\n" . serialize( $nfw_options ) . "\nEOT;\n" );
		fclose($fh);
	} else {
		echo '<br /><div class="error"><p>' . $lang['error_conf' ] .'</p></div>';
	}
}

// Do some housework if needed :
nfw_housework();

/* ------------------------------------------------------------------ */

function nfw_housework() {

	global $nfw_options;

	$cache = './nfwlog/cache/';

	// Flush temporarily blocked IPs :
	if (! empty($nfw_options['ban_ip']) ) {
		if (file_exists( $cache . 'ip_bk_flushed.php' ) ) {
			$stat = stat($cache . 'ip_bk_flushed.php');
			// Flush it if older than 1 hour :
			if ( time() - $stat['mtime'] > 3600 ) {
				$fh = fopen($cache . 'ip_bk_flushed.php' , 'w');
				fclose($fh);
				$glob = glob($cache ."ipbk*.php");
				if (is_array($glob) ) {
					foreach($glob as $file) {
						$stat = stat($file);
						if ( time() - $stat['mtime'] > $nfw_options['ban_time'] * 60 ) {
							unlink($file);
						}
					}
				}
			}
		} else {
			$fh = fopen($cache . 'ip_bk_flushed.php' , 'w');
			fclose($fh);
		}
	}
}

/* ------------------------------------------------------------------ */
// EOF
