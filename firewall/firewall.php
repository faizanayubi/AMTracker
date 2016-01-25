<?php
// +---------------------------------------------------------------------+
// | NinjaFirewall (Pro edition)                                         |
// |                                                                     |
// | (c) NinTechNet - http://nintechnet.com/                             |
// |                                                                     |
// +---------------------------------------------------------------------+
// | REVISION: 2015-11-07 11:00:51                                       |
// +---------------------------------------------------------------------+
// | This program is free software: you can redistribute it and/or       |
// | modify it under the terms of the GNU General Public License as      |
// | published by the Free Software Foundation, either version 3 of      |
// | the License, or (at your option) any later version.                 |
// |                                                                     |
// | This program is distributed in the hope that it will be useful,     |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of      |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       |
// | GNU General Public License for more details.                        |
// +---------------------------------------------------------------------+

if ( strpos($_SERVER['SCRIPT_NAME'], '/nfwlog/') !== FALSE
	|| $_SERVER['SCRIPT_FILENAME'] == __FILE__ ) { die('Forbidden'); }
if (defined('NFW_STATUS')) { return; }

// Used for benchmarks purpose :
$nfw_['fw_starttime'] = microtime(true);

// Optional NinjaFirewall configuration file
// ( see http://nintechnet.com/ninjafirewall/pro-edition/help/?htninja ) :
if ( @file_exists($nfw_['file'] = dirname($_SERVER['DOCUMENT_ROOT']) .'/.htninja') ||
	@file_exists($nfw_['file'] = $_SERVER['DOCUMENT_ROOT'] .'/.htninja') ) {
	$nfw_['res'] = @include($nfw_['file']);
	// Allow and stop filtering :
	if ( $nfw_['res'] == 'ALLOW' ) {
		define( 'NFW_STATUS', 22 );
		unset($nfw_);
		return;
	}
	// Reject immediately :
	if ( $nfw_['res'] == 'BLOCK' ) {
		header('HTTP/1.1 403 Forbidden');
		header('Status: 403 Forbidden');
		die('403 Forbidden');
	}
}

// Get our options :
if (! @include( __DIR__ . '/conf/options.php') ) {
	// Set the error flag and return :
	define( 'NFW_STATUS', 1 );
	unset($nfw_);
	return;
}

$nfw_['nfw_options'] = @unserialize($nfw_options);
// Ensure we have something or return an error :
if (! isset( $nfw_['nfw_options']['enabled']) ) {
	// Set the error flag and return :
	define( 'NFW_STATUS', 2 );
	unset($nfw_);
	return;
}

// Are we supposed to do anything ?
if ( empty($nfw_['nfw_options']['enabled']) ) {
	define( 'NFW_STATUS', 22 );
	unset($nfw_);
	return;
}

// Response headers hook :
if (! empty($nfw_['nfw_options']['response_headers']) && function_exists('header_register_callback')) {
	define('NFW_RESHEADERS', $nfw_['nfw_options']['response_headers']);
	header_register_callback('nfw_response_headers');
}

// Ensure with have a proper and single IP (a user may wrongly use
// the .htninja file and redirect HTTP_X_FORWARDED_FOR to REMOTE_ADDR):
if (strpos($_SERVER['REMOTE_ADDR'], ',') !== false) {
	$nfw_['match'] = array_map('trim', @explode(',', $_SERVER['REMOTE_ADDR']));
	foreach($nfw_['match'] as $nfw_['m']) {
		if ( filter_var($nfw_['m'], FILTER_VALIDATE_IP) )  {
			// Fix it, so that other apps can use it:
			$_SERVER['REMOTE_ADDR'] = $nfw_['m'];
			break;
		}
	}
}
$nfw_['user_ip'] = $_SERVER['REMOTE_ADDR'];

// Hide PHP notice/error messages ?
if (! empty($nfw_['nfw_options']['php_errors']) ) {
	@error_reporting(0);
	@ini_set('display_errors', 0);
}

// Scan HTTP traffic only... ?
if ( @$nfw_['nfw_options']['scan_protocol'] == 1 && $_SERVER['SERVER_PORT'] == 443 ) {
	define( 'NFW_STATUS', 22 );
	unset($nfw_);
	return;
}
// ...or HTTPS only ?
if ( @$nfw_['nfw_options']['scan_protocol'] == 2 && $_SERVER['SERVER_PORT'] != 443 ) {
	define( 'NFW_STATUS', 22 );
	unset($nfw_);
	return;
}

if ( $_SERVER['SCRIPT_FILENAME'] == __DIR__ .'/index.php' || $_SERVER['SCRIPT_FILENAME'] == __DIR__ .'/login.php' || $_SERVER['SCRIPT_FILENAME'] == __DIR__ .'/install.php' ) {
	// Before returning, let's check if there is any
	// error with the rules, so that we can display it
	// in the administration interface :
	if (! @include( __DIR__ . '/conf/rules.php') ) {
		define( 'NFW_STATUS', 3 );
	} else {
		$nfw_['nfw_rules'] = @unserialize($nfw_rules);
		if (! isset( $nfw_['nfw_rules']['1']) ) {
			// Set the error flag :
			define( 'NFW_STATUS', 4 );
		} else {
			// Everything is fine, let it go :
			define( 'NFW_STATUS', 22 );
		}
	}
	unset($nfw_);
	return;
}

// Check if that IP has been temporarily banned :
if (! empty($nfw_['nfw_options']['ban_ip']) ) {
	$nfw_['ipbk'] = __DIR__ .'/nfwlog/cache/ipbk.'. $_SERVER['SERVER_NAME'] .'_-_'. $nfw_['user_ip'] .'.php';
	if (file_exists($nfw_['ipbk']) ) {
		$nfw_['stat'] = stat($nfw_['ipbk']);
		if ( time() - $nfw_['stat']['mtime'] > $nfw_['nfw_options']['ban_time'] * 60 ) {
			// Too old, clear the banned IP and let it go :
			unlink($nfw_['ipbk']);
		} else {
			// Keep it blocked :
			nfw_block(0);
		}
	}
}

// Is that method allowed ?
if (! empty($nfw_['nfw_options']['request_method']) ) {
	if ( strpos('GETPOSTHEAD', $_SERVER['REQUEST_METHOD']) === false ) {
		nfw_log('REQUEST_METHOD is not allowed by policy', $_SERVER['REQUEST_METHOD'], 2, 0);
		nfw_block(2);
	}
}

// HTTP_HOST is an IP ?
if (! empty($nfw_['nfw_options']['no_host_ip']) && @filter_var(parse_url('http://'.$_SERVER['HTTP_HOST'], PHP_URL_HOST), FILTER_VALIDATE_IP) ) {
	nfw_log('HTTP_HOST is an IP', $_SERVER['HTTP_HOST'], 2, 0);
   nfw_block(2);
}

// Block POST requests...
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// ...without a HTTP_REFERER header ?
	if (! empty($nfw_['nfw_options']['referer_post']) && empty($_SERVER['HTTP_REFERER']) ) {
		nfw_log('POST method without HTTP_REFERER header', 'N/A', 1, 0);
		nfw_block(1);
	}
	$ua = ! empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'N/A';
	// ...without a Mozilla-compatible signature ?
	if (! empty($nfw_['nfw_options']['ua_mozilla']) && stripos($ua, 'Mozilla') === FALSE ) {
		nfw_log('POST method from user-agent without Mozilla-compatible signature', $ua, 1, 0);
		nfw_block(1);
	}
	// ...without a HTTP_ACCEPT header ?
	if (! empty($nfw_['nfw_options']['ua_accept']) && empty($_SERVER['HTTP_ACCEPT']) ) {
		nfw_log('POST method without HTTP_ACCEPT header', 'N/A', 1, 0);
		nfw_block(1);
	}
	// ...without HTTP_ACCEPT_LANGUAGE header ?
	if (! empty($nfw_['nfw_options']['ua_accept_lang']) && empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ) {
		nfw_log('POST method without HTTP_ACCEPT_LANGUAGE header', 'N/A', 1, 0);
		nfw_block(1);
	}
}

// Look for upload:
nfw_check_upload();

// Get our rules :
if (! include( __DIR__ . '/conf/rules.php') ) {
	define( 'NFW_STATUS', 3 );
	unset($nfw_);
	return;
}
$nfw_['nfw_rules'] = @unserialize($nfw_rules);
// Ensure we have something, or return an error :
if (! isset( $nfw_['nfw_rules']['1']) ) {
	define( 'NFW_STATUS', 4 );
	unset($nfw_);
	return;
}

// Parse all requests and server variables :
nfw_check_request( $nfw_['nfw_rules'], $nfw_['nfw_options'] );

// Sanitise requests/variables if needed :
if (! empty($nfw_['nfw_options']['get_sanitise']) && ! empty($_GET) ){
	$_GET = nfw_sanitise( $_GET, 'GET');
}
if (! empty($nfw_['nfw_options']['post_sanitise']) && ! empty($_POST) ){
	$_POST = nfw_sanitise( $_POST, 'POST');
}
if (! empty($nfw_['nfw_options']['request_sanitise']) && ! empty($_REQUEST) ){
	$_REQUEST = nfw_sanitise( $_REQUEST, 'REQUEST');
}
if (! empty($nfw_['nfw_options']['cookies_sanitise']) && ! empty($_COOKIE) ) {
	$_COOKIE = nfw_sanitise( $_COOKIE, 'COOKIE');
}
if (! empty($nfw_['nfw_options']['ua_sanitise']) && ! empty($_SERVER['HTTP_USER_AGENT']) ) {
	$_SERVER['HTTP_USER_AGENT'] = nfw_sanitise( $_SERVER['HTTP_USER_AGENT'], 'HTTP_USER_AGENT');
}
if (! empty($nfw_['nfw_options']['referer_sanitise']) && ! empty($_SERVER['HTTP_REFERER']) ) {
	$_SERVER['HTTP_REFERER'] = nfw_sanitise( $_SERVER['HTTP_REFERER'], 'HTTP_REFERER');
}
if (! empty($nfw_['nfw_options']['php_path_i']) && ! empty($_SERVER['PATH_INFO']) ) {
	$_SERVER['PATH_INFO'] = nfw_sanitise( $_SERVER['PATH_INFO'], 'PATH_INFO');
}
if (! empty($nfw_['nfw_options']['php_path_t']) && ! empty($_SERVER['PATH_TRANSLATED']) ) {
	$_SERVER['PATH_TRANSLATED'] = nfw_sanitise( $_SERVER['PATH_TRANSLATED'], 'PATH_TRANSLATED');
}
if (! empty($nfw_['nfw_options']['php_self']) && ! empty($_SERVER['PHP_SELF']) ) {
	$_SERVER['PHP_SELF'] = nfw_sanitise( $_SERVER['PHP_SELF'], 'PHP_SELF');
}

// If we have a SQL link, close it :
if (! empty($nfw_['dblink']) ) { @$nfw_['dblink']->close(); }
// Clear all our variables :
unset($nfw_);
// Return OK status :
define( 'NFW_STATUS', 22 );

// That's all, folks !
return;

// =====================================================================

function nfw_log($loginfo, $logdata, $loglevel, $ruleid) {

	global $nfw_;

	// Info/sanitise ? Don't block and do not issue any incident number :
	if ( $loglevel == 6) {
		$nfw_['num_incident'] = '0000000';
		$http_ret_code = '200';
	} else {
		// Debugging ? Don't block and do not issue any incident number
		// but set loglevel to 7 (will display 'DEBUG_ON' in log) :
		if (! empty($nfw_['nfw_options']['debug']) ) {
			$nfw_['num_incident'] = '0000000';
			$loglevel = 7;
			$http_ret_code = '200';
		// Create a random incident number :
		} else {
			$nfw_['num_incident'] = mt_rand(1000000, 9000000);
			$http_ret_code = $nfw_['nfw_options']['ret_code'];
		}
	}

	// Return if logging is disabled :
	if (empty($nfw_['nfw_options']['logging']) ) {
		return;
	}

	// Prepare the line to log :
   if (strlen($logdata) > 200) { $logdata = mb_substr($logdata, 0, 200, 'utf-8') . '...'; }
	$res = '';
	$string = str_split($logdata);
	foreach ( $string as $char ) {
		// Allow only ASCII printable characters :
		if ( ord($char) < 32 || ord($char) > 126 ) {
			$res .= '%' . bin2hex($char);
		} else {
			$res .= $char;
		}
	}

	// Set the date timezone (used for log name only) :
	if (empty($nfw_['nfw_options']['tzset']) ) {
		date_default_timezone_set($nfw_['nfw_options']['timezone']);
	}

	$cur_month = date('Y-m');
	$log_file = __DIR__ . '/nfwlog/firewall_' . $cur_month;
	$log_file_ext = $log_file . '.php';

	// Check whether we should rotate the log :
	if (! empty($nfw_['nfw_options']['log_rotate']) && @ctype_digit($nfw_['nfw_options']['log_maxsize']) ) {
		if ( file_exists($log_file_ext) ) {
			$log_stat = stat($log_file_ext);
			if ( $log_stat['size'] > $nfw_['nfw_options']['log_maxsize']) {
				// Rotate it :
				$log_ext = 1;
				while ( file_exists($log_file . '.' . $log_ext . '.php' ) ) {
					$log_ext++;
				}
				rename($log_file_ext, $log_file . '.' . $log_ext . '.php');
			}
		}
	}

	if (! file_exists($log_file_ext) ) {
		$tmp = '<?php exit; ?>' . "\n";
	} else {
		$tmp = '';
	}

   @file_put_contents($log_file_ext,
      $tmp . '[' . time() . '] ' . '[' . round( (microtime(true) - $nfw_['fw_starttime']), 5) . '] ' .
      '[' . $_SERVER['SERVER_NAME'] . '] ' . '[#' . $nfw_['num_incident'] . '] ' .
      '[' . $ruleid . '] ' .
      '[' . $loglevel . '] ' . '[' . $nfw_['user_ip'] . '] ' .
      '[' . $http_ret_code . '] ' . '[' . $_SERVER['REQUEST_METHOD'] . '] ' .
      '[' . $_SERVER['SCRIPT_NAME'] . '] ' . '[' . $loginfo . '] ' .
      '[' . $res . ']' . "\n", FILE_APPEND | LOCK_EX);

}

// =====================================================================

function nfw_block( $lev ) {

	if ( defined('NFW_STATUS') ) { return; }

	global $nfw_;

	// We don't block anyone if we are running in debugging mode :
	if (! empty($nfw_['nfw_options']['debug']) ) {
		return;
	}

	$http_codes = array(
      400 => '400 Bad Request', 403 => '403 Forbidden',
      404 => '404 Not Found', 406 => '406 Not Acceptable',
      500 => '500 Internal Server Error', 503 => '503 Service Unavailable',
   );

	// Prepare the page to display to the blocked user :
	if (empty($nfw_['num_incident']) ) { $nfw_['num_incident'] = '000000'; }
	$tmp = str_replace( '%%NUM_INCIDENT%%', $nfw_['num_incident'],  base64_decode($nfw_['nfw_options']['blocked_msg']) );
	// Add the right IP to the message :
	$tmp = str_replace( '%%REM_ADDRESS%%', $nfw_['user_ip'], $tmp );
	if (! headers_sent() ) {
		header('HTTP/1.0 ' . $http_codes[$nfw_['nfw_options']['ret_code']] );
		header('Status: ' .  $http_codes[$nfw_['nfw_options']['ret_code']] );
		// Prevent caching :
		header('Pragma: no-cache');
		header('Cache-Control: private, no-cache, no-store, max-age=0, must-revalidate, proxy-revalidate');
		header('Expires: Mon, 01 Sep 2014 01:01:01 GMT');
	}
	echo '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">' . "\n" .
		'<html><head><title>' . $http_codes[$nfw_['nfw_options']['ret_code']] .
		'</title><style>body{font-family:sans-serif;font-size:13px;color:#000000;}</style><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body bgcolor="white">' . $tmp . '</body></html>';

	// Check if we must block that IP for a while :
	if ($lev > 0 && $lev < 4 && $nfw_['nfw_options']['ban_ip']) {
		if ( $nfw_['nfw_options']['ban_ip'] == 3 || ($nfw_['nfw_options']['ban_ip'] == 2 && $lev > 1) || ($nfw_['nfw_options']['ban_ip'] == 1 && $lev == 3) ) {
			// Add it to our cache :
			touch( __DIR__ .'/nfwlog/cache/ipbk.'. $_SERVER['SERVER_NAME'] .'_-_'. $nfw_['user_ip'] .'.php');
			// Log it :
			nfw_log('Banning IP for ' . $nfw_['nfw_options']['ban_time'] . ' minute(s)', 'REMOTE_ADDR : '. $nfw_['user_ip'], 6, 0);
		}
	}
	exit;
}

// =====================================================================

function nfw_check_upload() {

	if ( defined('NFW_STATUS') ) { return; }

	global $nfw_;

	// Fetch uploaded files, if any :
	$f_uploaded = nfw_fetch_uploads();
	$tmp = '';
	// Uploads are not allowed :
	if ( empty($nfw_['nfw_options']['uploads']) ) {
		$tmp = '';
		foreach ($f_uploaded as $key => $value) {
			// Empty field ?
			if (! $f_uploaded[$key]['name']) { continue; }
			$tmp .= $f_uploaded[$key]['name'] . ', ' . number_format($f_uploaded[$key]['size']) . ' bytes ';
      }
      if ( $tmp ) {
			// Log and block :
			nfw_log('Blocked file upload attempt', rtrim($tmp, ' '), 3, 0);
			nfw_block(3);
		}
	// Uploads are allowed :
	} else {
		foreach ($f_uploaded as $key => $value) {
			if (! $f_uploaded[$key]['name']) { continue; }
			// Check its size :
			if ( $f_uploaded[$key]['size'] > $nfw_['nfw_options']['upload_maxsize'] ) {
				nfw_log('Attempt to upload a file > ' . ($nfw_['nfw_options']['upload_maxsize'] / 1024) .
					' KB' , $f_uploaded[$key]['name'] . ', ' . number_format($f_uploaded[$key]['size']) . ' bytes', 1, 0);
				nfw_block(1);
			}
			$data = 0;
			// Reject scripts, ELF and system files ?
			if ( $nfw_['nfw_options']['uploads'] == 2 ) {
				// System files :
				if (preg_match('/\.ht(?:access|passwd)|(?:php\d?|\.user)\.ini|\.ph(?:p[345]?|t|tml)\b/', $f_uploaded[$key]['name']) ) {
					nfw_log('Attempt to upload a script or system file', $f_uploaded[$key]['name'] . ', ' . number_format($f_uploaded[$key]['size']) . ' bytes', 3, 0);
					nfw_block(3);
				}
				$data = file_get_contents($f_uploaded[$key]['tmp_name']);
				// ELF :
				if (preg_match('`^\x7F\x45\x4C\x46`', $data) ) {
					nfw_log('Attempt to upload a Linux binary file (ELF)', $f_uploaded[$key]['name'] . ', ' . number_format($f_uploaded[$key]['size']) . ' bytes', 3, 0);
					nfw_block(3);
				}
				// Scripts :
				if (preg_match('`<\?(?i:php)|#!/(?:usr|bin)/.+?\s|\s#include\s+<[\w/.]+?>|\b(?i:array_map|base64_(?:de|en)code|eval|file(?:_get_contents)?|fsockopen|gzinflate|move_uploaded_file|passthru|preg_replace|phpinfo|system|(?:shell_)?exec)\s*\(|\b(?:\$?_(COOKIE|ENV|FILES|(?:GE|POS|REQUES)T|SE(RVER|SSION))|HTTP_(?:(?:POST|GET)_VARS|RAW_POST_DATA)|GLOBALS)\s*[=\[]|\W\$\{\s*[\'"]\w+[\'"]`', $data) ) {
					nfw_log('Attempt to upload a script', $f_uploaded[$key]['name'] . ', ' . number_format($f_uploaded[$key]['size']) . ' bytes', 3, 0);
					nfw_block(3);
				}
			}
			// Look for EICAR test file :
			if (! $data) {
				$data = file_get_contents($f_uploaded[$key]['tmp_name'], NULL, NULL, NULL, 68);
			}
			if ( substr($data, 0, 68) == 'X5O!P%@AP' . '[4\PZX54(P^)7CC)7}$EIC' . 'AR-STANDARD-ANTIVIRUS-TEST-FILE!$H' . '+H*' ) {
				nfw_log('EICAR Standard Anti-Virus Test File blocked', $f_uploaded[$key]['name'] . ', ' . number_format($f_uploaded[$key]['size']) . ' bytes', 3, 0);
				// Always block it, even if we allow uploads:
				nfw_block(3);
			}

			// Sanitise filename ?
			if (! empty($nfw_['nfw_options']['sanitise_fn']) ) {
				$tmp = '';
				$f_uploaded[$key]['name'] = preg_replace('/[^\w\.\-]/i', 'X', $f_uploaded[$key]['name'], -1, $count);
				if ($count) {
					$tmp = ' (sanitising '. $count . ' char. from filename)';
				}
				if ( $tmp ) {
					list ($kn, $is_arr, $kv) = explode('::', $f_uploaded[$key]['where']);
					if ( $is_arr ) {
						$_FILES[$kn]['name'][$kv] = $f_uploaded[$key]['name'];
					} else {
						$_FILES[$kn]['name'] = $f_uploaded[$key]['name'];
					}
				}
			}
			// Log and let it go :
			nfw_log('Uploading file' . $tmp , $f_uploaded[$key]['name'] . ', ' . number_format($f_uploaded[$key]['size']) . ' bytes', 5, 0);
		}
	}
}

// =====================================================================

function nfw_fetch_uploads() {

	$f_uploaded = array();
	$count = 0;
	foreach ($_FILES as $nm => $file) {
		if ( is_array($file['name']) ) {
			foreach($file['name'] as $key => $value) {
				$f_uploaded[$count]['name'] = $file['name'][$key];
				$f_uploaded[$count]['size'] = $file['size'][$key];
				$f_uploaded[$count]['tmp_name'] = $file['tmp_name'][$key];
				$f_uploaded[$count]['where'] = $nm . '::1::' . $key;
				$count++;
			}
		} else {
			$f_uploaded[$count]['name'] = $file['name'];
			$f_uploaded[$count]['size'] = $file['size'];
			$f_uploaded[$count]['tmp_name'] = $file['tmp_name'];
			$f_uploaded[$count]['where'] = $nm . '::0::0' ;
			$count++;
		}
	}
	return $f_uploaded;
}

// =====================================================================

function nfw_check_request( $nfw_rules, $nfw_options ) {

	if ( defined('NFW_STATUS') ) { return; }

	$nf_decode = array();

	foreach ($nfw_rules as $rules_id => $rules_values) {
		// Ignored disabled rules :
		if ( empty( $rules_values['on']) ) { continue; }
		$wherelist = explode('|', $rules_values['where']);
		foreach ($wherelist as $where) {

			// Global GET/POST/COOKIE/SERVER requests :
			if ( ($where == 'POST' && ! empty($nfw_options['post_scan'])) || ($where == 'GET' && ! empty($nfw_options['get_scan'])) || ($where == 'COOKIE' && ! empty($nfw_options['cookies_scan'])) || $where == 'SERVER' ) {
				foreach ($GLOBALS['_' . $where] as $reqkey => $reqvalue) {
					// Look for an array() :
					if ( is_array($reqvalue) ) {
						$res = nfw_flatten( "\n", $reqvalue );
						$reqvalue = $res;
						$rules_values['what'] = '(?m:'. $rules_values['what'] .')';
					}
					if (! empty($nfw_options['post_b64']) && $where == 'POST' && $reqvalue && ! isset($nf_decode[$reqkey]['b64']) ) {
						nfw_check_b64($reqkey, $reqvalue);
						$nf_decode[$reqkey]['b64'] = 1;
					}
					if (! $reqvalue) { continue; }

					// Decode potential double-encoding (applies to XSS and SQLi attempts only):
					if ( ($rules_id > 99 && $rules_id < 150) || ($rules_id > 199 && $rules_id < 250) ) {
						if (! isset($nf_decode[$reqkey]['url']) ) {
							$reqvalue = rawurldecode($reqvalue);
							$nf_decode[$reqkey]['url'] = $reqvalue;
						} else{
							$reqvalue = $nf_decode[$reqkey]['url'];
						}
					}

					if ( preg_match('`'. $rules_values['what'] .'`', $reqvalue) ) {
						// Extra rule :
						if (! empty($rules_values['extra'])) {
							if ( empty($GLOBALS['_' . $rules_values['extra'][1]] [$rules_values['extra'][2]]) || ! preg_match('`'. $rules_values['extra'][3] .'`', $GLOBALS['_' . $rules_values['extra'][1]] [$rules_values['extra'][2]]) ) { continue;	}
						}
						nfw_log($rules_values['why'], $where .':' . $reqkey . ' = ' . $reqvalue, $rules_values['level'], $rules_id);
						nfw_block($rules_values['level']);
               }
				}
				continue;
			}

			// HTTP_USER_AGENT & HTTP_REFERER variables :
			if ( isset($_SERVER[$where]) ) {
				if ( ($where == 'HTTP_USER_AGENT' && empty($nfw_options['ua_scan'])) || ($where == 'HTTP_REFERER' && empty($nfw_options['referer_scan'])) ) { continue; }
				if ( preg_match('`'. $rules_values['what'] .'`', $_SERVER[$where]) ) {
					// Extra rule :
					if (! empty($rules_values['extra'])) {
						if ( empty($GLOBALS['_' . $rules_values['extra'][1]] [$rules_values['extra'][2]]) || ! preg_match('`'. $rules_values['extra'][3] .'`', $GLOBALS['_' . $rules_values['extra'][1]] [$rules_values['extra'][2]]) ) { continue;	}
					}
					nfw_log($rules_values['why'], $where. ' = ' .$_SERVER[$where], $rules_values['level'], $rules_id);
					nfw_block($rules_values['level']);
            }
				continue;
			}

			// Specific POST:xx, GET:xx, COOKIE:xxx, SERVER:xxx requests etc :
			$sub_value = explode(':', $where);
			if ( ($sub_value[0] == 'POST' && empty($nfw_options['post_scan'])) || ($sub_value[0] == 'GET' && empty($nfw_options['get_scan'])) || ($sub_value[0] == 'COOKIE' && empty($nfw_options['cookies_scan'])) ) { continue; }
			if (! empty($sub_value[1]) && @isset($GLOBALS['_' . $sub_value[0]] [$sub_value[1]]) ) {
				if ( is_array($GLOBALS['_' . $sub_value[0]] [$sub_value[1]]) ) {
					$res = nfw_flatten( "\n", $GLOBALS['_' . $sub_value[0]] [$sub_value[1]] );
					$rules_values['what'] = '(?m:'. $rules_values['what'] .')';
				} else {
					$res = $GLOBALS['_' . $sub_value[0]] [$sub_value[1]];
				}
				if (! $res ) { continue; }
				if ( preg_match('`'. $rules_values['what'] .'`', $res) ) {
					// Extra rule :
					if (! empty($rules_values['extra'])) {
						if ( empty($GLOBALS['_' . $rules_values['extra'][1]] [$rules_values['extra'][2]]) || ! preg_match('`'. $rules_values['extra'][3] .'`', $GLOBALS['_' . $rules_values['extra'][1]] [$rules_values['extra'][2]]) ) { continue;	}
					}
					nfw_log($rules_values['why'], $sub_value[0]. ':' .$sub_value[1]. ' = ' . $res, $rules_values['level'], $rules_id);
					nfw_block($rules_values['level']);
				}
			}
		}
	}
}

// =====================================================================

function nfw_flatten( $glue, $pieces ) {

	if ( defined('NFW_STATUS') ) { return; }

	$ret = array();

   foreach ($pieces as $r_pieces) {
      if ( is_array($r_pieces)) {
         $ret[] = nfw_flatten($glue, $r_pieces);
      } else {
         $ret[] = $r_pieces;
      }
   }
   return implode($glue, $ret);
}

// =====================================================================

function nfw_check_b64( $reqkey, $string ) {

	if ( defined('NFW_STATUS') || strlen($string) < 16 ) { return; }

	$decoded = base64_decode($string);
	if ( strlen($decoded) < 16 ) { return; }
	if ( preg_match( '`\b(?:\$?_(COOKIE|ENV|FILES|(?:GE|POS|REQUES)T|SE(RVER|SSION))|HTTP_(?:(?:POST|GET)_VARS|RAW_POST_DATA)|GLOBALS)\s*[=\[)]|\b(?i:array_map|assert|base64_(?:de|en)code|chmod|curl_exec|(?:ex|im)plode|error_reporting|eval|file(?:_get_contents)?|f(?:open|write|close)|fsockopen|function_exists|gzinflate|md5|move_uploaded_file|ob_start|passthru|preg_replace|phpinfo|stripslashes|strrev|(?:shell_)?exec|system|unlink)\s*\(|\becho\s*[\'"]|<\s*(?i:applet|div|embed|i?frame(?:set)?|img|meta|marquee|object|script|textarea)\b|\W\$\{\s*[\'"]\w+[\'"]|<\?(?i:php)|(?i:select\b.+?from\b.+?where|insert\b.+?into\b)`', $decoded) ) {
		nfw_log('BASE64-encoded injection', 'POST:' . $reqkey . ' = ' . $decoded, '3', 0);
		nfw_block(3);
	}
}

// =====================================================================

function nfw_sanitise( $str, $msg ) {

	if ( defined('NFW_STATUS') ) { return; }

	if (! isset($str) ) { return null; }

	global $nfw_;

	// String :
	if (is_string($str) ) {
		// By default, we don't have a SQL connection and cannot use
		// the mysql_real_escape_string function. This could be a problem
		// if the DB is using GBK charset. However, it is possible to connect
		// to the DB from the .htninja file. The DB link should be stored in
		// the $nfw_['dblink'] variable:
		//
		// .htninja example (see http://nintechnet.com/ninjafirewall/pro-edition/help/?htninja ) :
		// ----------------------------- 8< -----------------------------
		// <?php
		//		@$nfw_['dblink'] = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
		//		if ($nfw_['dblink']->connect_error) {
		//			die('MySQL connection error: '. $nfw_['dblink']->connect_error);
		//		}
		// ----------------------------- 8< -----------------------------

		// We sanitise variables **value** either with :
		// -mysql_real_escape_string* to escape [\x00], [\n], [\r], [\],
		//	 ['], ["] and [\x1a] *IF* we have a DB link.
		//	-str_replace to escape backtick [`]
		//	Applies to $_GET, $_POST, $_SERVER['HTTP_USER_AGENT']
		//	and $_SERVER['HTTP_REFERER']
		//
		// Or:
		//
		// -str_replace to escape [<], [>], ["], ['], [`] and , [\]
		//	-str_replace to replace [\n], [\r], [\x1a] and [\x00] with [X]
		//	Applies to $_SERVER['PATH_INFO'], $_SERVER['PATH_TRANSLATED']
		//	and $_SERVER['PHP_SELF']
		//
		// Or:
		//
		// -str_replace to escape ['], [`] and , [\]
		//	-str_replace to replace [\x1a] and [\x00] with [X]
		//	Applies to $_COOKIE only
		//

		// COOKIE ?
		if ($str == 'COOKIE') {
			$str2 = str_replace(	array('\\', "'", "\x00", "\x1a", '`'),
				array('\\\\', "\\'", 'X', 'X', '\\`'),	$str);
		// Use mysql_real_escape_string if we have a DB link :
		} elseif (! empty($nfw_['dblink']) ) {
			$str2 = $nfw_['dblink']->real_escape_string($str);
			$str2 = str_replace('`', '\`', $str2);
		// DIY :
		} else {
			$str2 = str_replace(	array('\\', "'", '"', "\x0d", "\x0a", "\x00", "\x1a", '`', '<', '>'),
				array('\\\\', "\\'", '\\"', 'X', 'X', 'X', 'X', '\\`', '\\<', '\\>'),	$str);
		}
		// Don't sanitised the string if we are running in Debug Mode :
		if (! empty($nfw_['nfw_options']['debug']) ) {
			if ($str2 != $str) {
				nfw_log('Sanitising user input', $msg . ': ' . $str, 7, 0);
			}
			return $str;
		}
		// Log and return the sanitised string :
		if ($str2 != $str) {
			nfw_log('Sanitising user input', $msg . ': ' . $str, 6, 0);
		}
		return $str2;

	// Array :
	} else if (is_array($str) ) {
		foreach($str as $key => $value) {
			// COOKIE ?
			if ($msg == 'COOKIE') {
				$key2 = str_replace(	array('\\', "'", "\x00", "\x1a", '`', '<', '>'),
					array('\\\\', "\\'", 'X', 'X', '\\`', '&lt;', '&gt;'),	$key, $occ);
			} else {
				// We sanitise variables **name** using :
				// -str_replace to escape [\], ['] and ["]
				// -str_replace to replace [\n], [\r], [\x1a] and [\x00] with [X]
				//	-str_replace to replace [`], [<] and [>] with their HTML entities (&#96; &lt; &gt;)
				$key2 = str_replace(	array('\\', "'", '"', "\x0d", "\x0a", "\x00", "\x1a", '`', '<', '>'),
					array('\\\\', "\\'", '\\"', 'X', 'X', 'X', 'X', '&#96;', '&lt;', '&gt;'),	$key, $occ);
			}
			if ($occ) {
				unset($str[$key]);
				nfw_log('Sanitising user input', $msg . ': ' . $key, 6, 0);
			}
			// Sanitise the value :
			$str[$key2] = nfw_sanitise($value, $msg);
		}
		return $str;
	}
}

// =====================================================================

function nfw_response_headers() {

	if (! defined('NFW_RESHEADERS') ) { return; }
	$NFW_RESHEADERS = NFW_RESHEADERS;
	// NFW_RESHEADERS:
	// 000000
	// ||||||_ Strict-Transport-Security (includeSubDomains) [0-1]
	// |||||__ Strict-Transport-Security [0-3]
	// ||||___ X-XSS-Protection [0-1]
	// |||____ X-Frame-Options [0-2]
	// ||_____ X-Content-Type-Options [0-1]
	// |______ HttpOnly cookies [0-1]

	$rewrite = array();

	if ($NFW_RESHEADERS[0] == 1) {
		// Parse all response headers :
		foreach (headers_list() as $header) {
			// Ignore it if it is not a cookie :
			if (strpos($header, 'Set-Cookie:') === false) { continue; }
			// Does it have the HttpOnly flag on ?
			if (stripos($header, '; httponly') !== false) {
				// Save it...
				$rewrite[] = $header;
				// and check next header :
				continue;
			}
			// Append the HttpOnly flag and save it :
			$rewrite[] = $header . '; httponly';
		}
		// Shall we rewrite cookies ?
		if (! empty($rewrite) ) {
			// Remove all original cookies first:
			header_remove('Set-Cookie');
			foreach($rewrite as $cookie) {
				// Inject ours instead :
				header($cookie, false);
			}
		}
	}

	if ($NFW_RESHEADERS[1] == 1) {
		header('X-Content-Type-Options: nosniff');
	}

	if ($NFW_RESHEADERS[2] == 1) {
		header('X-Frame-Options: SAMEORIGIN');
	} elseif ($NFW_RESHEADERS[2] == 2) {
		header('X-Frame-Options: DENY');
	}

	if ($NFW_RESHEADERS[3] == 1) {
		header('X-XSS-Protection: 1; mode=block');
	}

	if ($NFW_RESHEADERS[4] == 0) { return; }
	// We don't send HSTS headers over HTTP :
	if ( $_SERVER['SERVER_PORT'] != 443 &&
	(! isset( $_SERVER['HTTP_X_FORWARDED_PROTO']) ||
	$_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https') ) {
		return;
	}
	if ($NFW_RESHEADERS[4] == 1) {
		// 1 month :
		$max_age = 'max-age=2628000';
	} elseif ($NFW_RESHEADERS[4] == 2) {
		// 6 months :
		$max_age = 'max-age=15768000';
	} elseif ($NFW_RESHEADERS[4] == 3) {
		// 12 months
		$max_age = 'max-age=31536000';
	} elseif ($NFW_RESHEADERS[4] == 4) {
		// Send an empty max-age to signal the UA to
		// cease regarding the host as a known HSTS Host :
		$max_age = 'max-age=0';
	}
	if ($NFW_RESHEADERS[5] == 1) {
		$max_age .= ' ; includeSubDomains';
	}
	header('Strict-Transport-Security: '. $max_age);
}

// =====================================================================
// EOF
