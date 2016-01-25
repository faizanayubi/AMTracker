<?php
/* 2015-01-20 16:28:33 */
$lang = array (

	'error'				=> 'Error',
	'yes'					=> 'Yes',
	'no'					=> 'No',
	'default'			=> ' (default)',
	'disabled'			=> 'Disabled',
	'eg'					=> 'e.g.',
	'pro_only'			=>	'This feature is only available in the <font color="#FF0000">Pro+</font> Edition of NinjaFirewall',
	'lic_upgrade'		=>	'upgrade',

	'admin'				=>	'Administrator',
	'whitelisttitle'	=>	'Whitelist the Administrator',
	'whitelisted'		=>	'You are whitelisted.',
	'notwhitelisted'	=>	'You are not whitelisted.',
	'currentstatus'	=>	'Current status for user %s',

	'js_empty'			=>	'Your list is empty.',
	'js_selecte'		=>	'Select one or more value to delete.',
	'js_emptyfield'	=>	'Field is empty!',
	'js_inlist'			=>	'is already in your list.',
	'js_allowedchar'	=>	'Allowed characters are: a-z  0-9  . - _ : and space.',
	'js_allowedchar2'	=>	'Allowed characters are: a-z  0-9  . - _ / and space.',
	'js_ipformat'		=>	'IPs must contain:' . '\n' .
								'-at least the first 3 characters.' . '\n' .
								'-IPv4: digits [0-9] and dot [.] only.' . '\n' .
								'-IPv6: digit [0-9], hex chars [a-f] and colon [:] only.' . '\n\n' .
								'See contextual Help for more info.',
	'js_blockcn'		=>	'Select a country to block.',
	'js_unblockcn'		=>	'Select a country to unblock.',
	'js_bot_default'	=>	'The default list of bots will be restored. All changes ' .
								'you may have done will be lost.' . '\n' . 'Go ahead ?',

	'js_method'			=>	'Error: you must select at least one HTTP method.',
	'js_sourceip'		=>	'Please enter a value for the [Source IP > Other] field.',
	'js_geoipip'		=>	'Please enter a value for the [ Geolocation Access Control > PHP variable ] field.',
	'js_ratelimit'		=>	'Please enter the number of connections allowed for the [Rate Limiting] directive.',
	'js_digit'			=>	'Please enter a number from 1 to 999.',

	'log_event'			=> 'Log event',

	'source_ip'			=> 'Source IP',
	'ip_used'			=> 'Retrieve visitors IP address from',
	'other'				=> 'Other',

	'localhost'			=> 'Scan traffic coming from localhost and private IP address spaces',

	'http_method'		=> 'HTTP Methods',
	'method'				=>'method',

	'methods_txt'		=> 'All Access Control directives below should apply to',

	'geoip'				=> 'Geolocation Access Control',
	'geoip_txt'			=> 'Enable Geolocation',
	'geoip_3166'		=> 'Retrieve ISO 3166 country code from',
	'geoip_php'			=>	'PHP variable',

	'geoip_avail'		=> 'Available countries',
	'geoip_blocked'	=> 'Blocked countries',
	'geoip_ninja'		=> 'Add NINJA_COUNTRY_CODE to PHP headers',
	'geoip_empy'		=>	'Your list is empty! If you do not use geolocation, ' . '
								do not forget to disable it because it could slow down your site.',
	'geoip_var_err'	=>	'Your server does not seem to support the %s variable.',
	'geoip_db_err'		=>	'GeoIP database not found!',

	'block'				=>	'Block',
	'unblock'			=>	'Unblock',
	'allow'				=> 'Allow',
	'discard'			=>	'Discard',

	'ipaccess'			=> 'IP Access Control',
	'ipallow'			=> 'Allow the following IPs',
	'ipallowed'			=> 'Allowed IPs',
	'ipblock'			=> 'Block the following IPs',
	'ipblocked'			=> 'Blocked IPs',
	'ipv4v6'				=> 'Full or partial IPv4/IPv6 address.',

	'ratelimit'			=>	'Rate limiting',
	'rate_limit_1'		=>	'Block for',
	'rate_limit_2'		=>	'seconds any IP<br />',
	'rate_limit_3'		=>	'with more than',
	'rate_limit_4'		=>	'connections<br />',
	'rate_limit_5'		=>	'within a',
	'rate_limit_6'		=>	'interval.',
	'5_second'			=>	'5-second',
	'10_second'			=>	'10-second',
	'15_second'			=>	'15-second',
	'30_second'			=>	'30-second',

	'url_ac'				=> 'URL Access Control',
	'url_allow'			=> 'Allow access to the following URL (SCRIPT_NAME)',
	'url_block'			=> 'Block access to the following URL (SCRIPT_NAME)',
	'url_note'			=> 'Full or partial case-sensitive URL.',
	'url_blocked'		=>	'Blocked URLs',
	'url_allowed'		=>	'Allowed URLs',

	'bot_ac'				=>	'Bot Access Control',
	'bot_ac_txt'		=> 'Reject the following bots (HTTP_USER_AGENT)',
	'bot_note'			=> 'Full or partial case-insensitive string.',
	'bot_blocked'		=>	'Blocked bots',
	'bot_default'		=>	'Restore default list',

	'default_js'		=>	'All fields will be restored to their default values.\nGo ahead?',
	'default_button'	=>	'Restore Default Values',

	'save_conf'			=> 'Save Changes',
	'saved_conf'		=> 'Your changes were saved',
	'error_conf'		=> 'Cannot write to the <code>./conf/options.php</code> configuration file. ' .
								'Please make this file writable.',
	'error_cache'		=> 'Cannot write to the <code>./nfwlog/cache</code> cache directory. ' .
								'Please make this file writable.',
);

