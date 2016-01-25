<?php
/* 2015-03-16 16:38:15 */
$lang = array (

	'avail_update'		=> 'Available updates',
	'cur_version'		=>	'Your current version is: %s (%s)',

	'new_version'		=> 'A new version is available: %s',
	'up2date'			=>	'Your version is up to date.',

	'err_zipclass'		=>	'Cannot extract the update: your PHP configuration does not have the ZipArchive class (#%s).',
	'err_curlinit'		=>	'Cannot look for updates: your PHP configuration does not have cURL support (#%s).',
	'err_nozip'			=> 'Downloaded file is not a valid ZIP archive (#%s).',
	'err_zipext'		=> 'Unable to extract the ZIP archive (#%s).',


	'failed_check'		=> 'Unable to retrieve updates information from NinjaFirewall server',
	'curl_connect'		=>	'Unable to connect to NinjaFirewall server: <code>%s</code>.',
	'curl_retcode'		=>	'The server returned the following HTTP code: <code>%s (%s)</code>.',
	'curl_empty'		=>	'The remote server did not return any data (#%s).',
	'curl_wrong'		=>	'The remote server did not return the expected data (#%s).',
	'curl_invlic'		=>	'Your license is not valid (#%s).',
	'curl_err'			=>	'The remote server returned the following error: <code>%s</code>.',
	'nfw_update'		=>	'Connections to NinjaFirewall server have been disabled (<code>NFW_UPDATE</code>).',

	'install_update'	=> 'Download this update',
	'install_update2'	=> 'Install this update',
	'check_update'		=>	'Check for updates',

	'err_cache_rw'		=>	'The cache directory <code>log/cache/</code> is not writable. Aborting update (#%s).',
	'err_root_rw'		=>	'NinjaFirewall\'s <code>%s</code> directory is not writable. Aborting update (#%s).',
	'failed_download'	=>	'Unable to download or save the update (#%s).',
	'err_get_ver'		=>	'Unable to retrieve the downloaded file version. Aborting update (#%s).',
	'err_ver'			=>	'Your current version is %s and downloaded version is %s. Update is not possible (#%s).',
	'err_edn'			=>	'The downloaded update does not match your NinjaFirewall edition. Update is not possible (#%s).',

	'changelog'			=>	'Changelog',
	'review'				=>	'You are about to update from version <b>%s</b> to <b>%s</b>.<br />Please review the changelog below and click the button to proceed.',

	'update_ok'			=>	'The update was successful.',
	'update_ok_bt'		=>	'Click here to finalize',

);
