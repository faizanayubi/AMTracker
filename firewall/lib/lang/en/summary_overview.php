<?php
/* 2015-01-19 17:50:21 */
$lang = array (
	'error'					=> 'Error',
	'info'					=> 'Info',
	'firewall'				=> 'Firewall',

	'warning'				=> 'Warning',
	'not_working'			=> 'Warning, NinjaFirewall is not working and your site is not protected',
	'err_message'			=> 'Error message',

	'enabled'				=> 'Enabled',

	'license'				=>	'License',
	'lic_expir'				=>	'License expiration',
	'lic_expired'			=> 'Your license has expired',
	'lic_soon_expired'	=> 'Your license will expire soon',
	'lic_renew_click'		=> 'Click here to renew it',
	'lic_free'				=>	'Pro Edition',
	'lic_upgrade'			=>	'upgrade to <font color="#FF0000">Pro+</font> Edition',
	'na'						=>	'N/A',

	'engine_ver'			=> 'Version',

	'failed_connect'		=> 'Unable to retrieve updates information from NinjaFirewall server',

	'new_engine'			=> 'A new version is available, click here to update!',

	'debugging'				=> 'Debugging',

	'debug_warn'			=> 'NinjaFirewall is running in <i>Debug Mode</i>, do not forget to ' . '%s' .
									'disable it</a> before going live.',

	'logging'				=>	'Firewall Log',
	'logging_warn'			=>	'The log is disabled. ' . '%s' . 'Click here to re-enable it',

	'lo_warn'				=>	'You have a private IP:',

	'lo_check'				=>	'If your site is behind a reverse proxy or a load balancer, ' .
									'ensure that the ' . '%s' . 'Source IP</a> is setup accordingly.',

	'source_ip'				=>	'Source IP',

	'cdn_title'				=>	'CDN detection',

	// FREE
	'cdn_clouflare_free'	=>	'<code>HTTP_CF_CONNECTING_IP</code> detected: you seem to be using Cloudflare CDN services. Ensure that you have setup your HTTP server or PHP to forward the correct visitor IP, otherwise use the NinjaFirewall <code>' . '%s' . '.htninja</a></code> configuration file.',
	// PRO
	'cdn_clouflare_pro'	=>	'<code>HTTP_CF_CONNECTING_IP</code> detected: you seem to be using Cloudflare CDN services. Ensure that the ' . '%s' . 'Source IP</a> is setup accordingly.',

	// FREE
	'cdn_incapsula_free'	=>	'<code>HTTP_INCAP_CLIENT_IP</code> detected: you seem to be using Incapsula CDN services. Ensure that you have setup your HTTP server or PHP to forward the correct visitor IP, otherwise use the NinjaFirewall <code>' . '%s' . '.htninja</a></code> configuration file.',
	// PRO
	'cdn_incapsula_pro'	=>	'<code>HTTP_INCAP_CLIENT_IP</code> detected: you seem to be using Incapsula CDN services. Ensure that the ' . '%s' . 'Source IP</a> is setup accordingly.',

	'admin'					=>	'Administrator',
	'whitelisted'			=>	'You are whitelisted.',
	'notwhitelisted'		=>	'You are not whitelisted.',

	'last_login'			=>	'Last login',

	'htninja'				=>	'Optional configuration file',
	'htninja_writable'	=>	'%s is writable. Consider changing its permissions to read-only.',


	'log_dir'				=> 'Log Directory',
	'cache_dir'				=> 'Cache Directory',
	'conf_dir'				=> 'Configuration Directory',

	'dir_readonly'			=>	'Warning, the ' . '<code>%s</code>' . ' directory is not writable',

	'chmod777'				=> 'Please chmod it to 0777 or equivalent.',
);

