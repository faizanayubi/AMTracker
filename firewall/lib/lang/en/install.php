<?php
/* 2015-12-06 00:30:27 */
$lang = array (

	'next'					=> 'Next',
	'ok'						=>	'OK',
	'close'					=>	'Close',
	'aborting'				=>	'NinjaFirewall installation cannot proceed.<br />Please change your system settings to match the  requirements.',

	'setup'					=>	'Setup',
	'welcome'				=>	'Welcome to NinjaFirewall %s installation!',

	'region'					=>	'Regional Settings',
	'timezone'				=> 'Display dates and times using the following timezone',
	'language'				=> 'Select a display language',
	'by'						=> 'by',

	'sysreq'					=>	'System requirements',
	'php_version'			=> 'PHP version',
	'php_error'				=>	'NinjaFirewall requires PHP v5.3 or greater, but your current version is %s.',

	'php_os'					=>	'Operating system',
	'windows'				=>	'NinjaFirewall is not compatible with Windows.',

	'safemode'				=>	'safe_mode',
	'safemode_ok'			=>	'Disabled',
	'safemode_error'		=>	'You have <code>safe_mode</code> enabled, please disable it. This feature has been DEPRECATED as of PHP 5.3 and REMOVED as of PHP 5.4.',

	'prepend'				=>	'auto_prepend_file',
	'prepend_error'		=>	'auto_prepend_file is already in use: <code>%s</code><br />NinjaFirewall needs to use this directive and will orverride your current one.',
	'prepend_ok'			=>	'Unused',

	'magic_quotes'			=>	'magic_quotes_gpc',
	'magic_quotes_ok'		=>	'Disabled',
	'magic_quotes_error'	=>	'You have <code>magic_quotes_gpc</code> enabled, please disable it, this feature has been DEPRECATED as of PHP 5.3 and REMOVED as of PHP 5.4.',

	'curl'					=>	'cURL',
	'curl_ok'				=>	'Installed',
	'curl_error'			=>	'Your PHP configuration does not have <code>cURL</code> support. Please install it.',

	'ziparchive'			=>	'ZipArchive',
	'ziparchive_ok'		=>	'Installed',
	'ziparchive_error'	=>	'Your PHP configuration does not have the <code>ZipArchive</code> class. You can still install NinjaFirewall, but you will not be able to use one-click updates.',

	'confdir'				=>	'Configuration folder',
	'confdir_ok'			=>	'The <code>conf/</code> folder is writable.',
	'confdir_error'		=>	'The <code>conf/</code> folder is not writable. Please change its permissions accordingly.',

	'logdir'					=>	'Log folder',
	'logdir_ok'				=>	'The <code>nfwlog/</code> folder is writable.',
	'logdir_error'			=>	'The <code>nfwlog/</code> folder is not writable. Please change its permissions accordingly.',

	'notes'					=>	'Release notes',

	'license'				=>	'License',
	'license_accept'		=>	'Please accept the terms of the License to proceed.',
	'license_checkbox'	=>	'I accept the terms of this License.',

	'account'				=>	'Administrator',
	'admin_name'			=>	'Administrator name (6 to 20 characters)',
	'admin_pass'			=>	'Administrator password (6 to 20 characters)',
	'admin_pass2'			=>	'Retype administrator password',
	'admin_email'			=>	'Administrator email',
	'nf_license'			=>	'Enter your NinjaFirewall <font color="#FF0000">Pro+</font> license key',


	'js_admin_name'		=>	'Please enter the administrator name.',
	'js_admin_name_char'	=>	'The administrator name can only contain from 6 to 20 alpha-numeric characters and underscore (_).',
	'js_admin_pass'		=>	'Please enter the administrator password.',
	'js_admin_pass_char'	=>	'The administrator password must contain from 6 to 20 characters.',
	'js_admin_pass_2'		=>	'Please retype the administrator password.',
	'js_admin_pass_both'	=>	'The administrator password must be the same in both fields.',
	'js_admin_email'		=>	'Please enter a valid administrator email address.',
	'js_license'			=>	'Please enter your NinjaFirewall Pro+ license key.',

	'curl_connect'			=>	'Unable to connect to NinjaFirewall server to check the license.',
	'curl_retcode'			=>	'Unable to connect to NinjaFirewall server: HTTP error.',
	'curl_empty'			=>	'Unable to connect to NinjaFirewall server: no response or empty response.',
	'curl_wrong'			=>	'Unable to connect to NinjaFirewall server: unexpected response.',
	'err_server'			=>	'Unable to connect to NinjaFirewall server: unknown error.',
	'invalid_lic'			=>	'Your license is not valid (#%s).',


	'integration'			=>	'Integration',
	'recommended'			=>	' (recommended)',
	'js_docroot'			=>	'Please enter the directory to be protected by NinjaFirewall.',
	'js_phpini'				=>	'Please select the PHP initialization file supported by your server.',
	'docroot'				=>	'Enter the directory to be protected by NinjaFirewall.<br />By default, it is <code>%s</code>',
	'httpsapi'				=>	'Select your HTTP server and PHP SAPI',
	'other'					=>	'Other webserver',
	'phpinfo'				=>	'view PHPINFO',
	'phpini'					=>	'Select the PHP initialization file supported by your server',
	'ini_1'					=>	'Used by most shared hosting accounts',
	'ini_2'					=>	'Used by most dedicated/VPS servers, as well as shared hosting accounts that do not support php.ini',
	'more_info'				=>	'more info',
	'ini_3'					=>	'A few shared hosting accounts (some <a href="https://support.godaddy.com/help/article/8913/what-filename-does-my-php-initialization-file-need-to-use">Godaddy hosting plans</a>). Seldom used',
	'invalid_docroot'		=>	'<code>%s</code> is not a valid directory.',
	'ninja_docroot'		=> 'The directory to protect must include NinjaFirewall installation folder.',

	'hhvm_doc'				=> 'Please check our blog if you want to install NinjaFirewall on HHVM.',

	'activation'			=>	'Activation',

	'single_file'			=>	'In order to protect your site, NinjaFirewall needs some specific directives to be added to your <code>%s</code> file.',
	'multi_files'			=>	'In order to protect your site, NinjaFirewall needs some specific directives to be added to your <code>%s</code> and <code>%s</code> files.',

	'edit_file'				=>	'Please add the following <font color="red">red lines</font> of code to your <code>%s</code> file.<br />All other lines, if any, are the actual content of the file and should not be changed:',
	'create_file'			=>	'Please create a <code>%s</code> file, and add the following lines of code to it:',

	'failed'					=>	'Failed !',
	'not_loaded'			=>	'The firewall is not loaded.',
	'suggestions'			=>	'Suggestions:',
	'modphp5tocgi'			=>	'You selected <code>Apache + PHP5 module</code> as your HTTP server and PHP SAPI. Maybe your HTTP server is <code>Apache + CGI/FastCGI</code>?',
	'goback_01'				=>	'You can go back back and try to select another HTTP server type',

	'userini_select'		=>	'You have selected <code>.user.ini</code> as your PHP initialization file. Unlike <code>php.ini</code>, <code>.user.ini</code> files are not reloaded immediately by PHP, but every 5 minutes. If this is your own server, restart Apache (or PHP-FPM if you are running nginx) to force PHP to reload it, otherwise please wait a few minutes and then, click the button below to run the firewall test again.',

	'test_it'				=>	'After making those changes, please click on the button below to test NinjaFirewall.',
	'test_button'			=>	'Test NinjaFirewall',

	'cgitomodphp5'			=>	'You selected <code>Apache + CGI/FastCGI</code> as your HTTP server &amp; PHP SAPI. Maybe your HTTP server is <code>Apache + PHP5 module</code>?',
	'wrong_ini'				=>	'You may have selected the wrong PHP initialization file.',
	'try_again'				=>	'Test again',
	'goback_02'				=>	'You can go back and try to select another INI file',
	'need_help'				=>	'Need help ? Check our blog:',

	'error'					=>	'Error!',
	'error_loaded'			=>	'NinjaFirewall is loaded but returned the following error:',
	'error_restart'		=>	'You may need to restart the installer to fix that error.',

	'it_works'				=>	'It works!',

	'congrats'				=>	'Congratulations, NinjaFirewall is up and running!',
	'redir_admin'			=>	'Click the button below to be redirected to the administration console.',

	'error_conf'			=> 'Cannot write to the <code>/conf/options.php</code> configuration file. ' .
									'Please make this file writable.',
	'error_rules'			=> 'Cannot write to the <code>/conf/rules.php</code> configuration file. ' .
									'Please make this file writable.',

	'mail_subject'			=>	'Quick Start, FAQ & Troubleshooting Guide',
	'hi'						=>	'Hi,',
	'hi2'						=>	'This is NinjaFirewall\'s installer. Below are some helpful info and links you may consider reading before using NinjaFirewall.',

	'hi3'						=>	'Troubleshooting:',
	'hi4'						=>	'-Failed installation ("Error: the firewall is not loaded")?',
	'hi5'						=>	'-How to disable NinjaFirewall?',
	'hi6'						=>	'-Blocked visitors?',
	'hi7'						=>	'-I lost my administrator password. How can I recover it?',

	'hi8'						=>	'NinjaFirewall (Pro/Pro+ Edition) troubleshooter script:',
	'hi9'						=>	'-Rename this file to "pro-check.php".',
	'hi10'					=>	'-Upload it into yourwebsite root folder.',
	'hi11'					=>	'-Goto http://YOUR WEBSITE/pro-check.php.',
	'hi12'					=>	'-Delete it afterwards.',

	'hi13'					=>	'FAQ:',
	'hi14'					=>	'-Do I need root privileges to install NinjaFirewall?',
	'hi15'					=>	'-Does it work with Nginx?',
	'hi16'					=>	'-Do I need to alter my PHP scripts?',
	'hi17'					=>	'-Will NinjaFirewall detect the correct IP of my visitors if I am behind a CDN service like Cloudflare or Incapsula?',
	'hi18'					=>	'-Will it slow down my site?',
	'hi19'					=>	'-Is there any Windows version?',
	'hi20'					=>	'-Can I add/write my own security rules?',
	'hi21'					=>	'-Can I migrate my site(s) with NinjaFirewall installed?',
	'hi22'					=>	'-How can I protect Joomla with NinjaFirewall?',

	'hi23'					=>	'Must Read:',
	'hi24'					=>	'-Testing NinjaFirewall without blocking your visitors.',
	'hi25'					=>	'-Add your own code to the firewall: the ".htninja" file.',
	'hi25b'					=>	'-Upgrading to PHP 7 with NinjaFirewall installed.',

	'hi26'					=>	'Help & Support Links:',
	'hi27'					=>	'-Each page of NinjaFirewall includes a contextual help: click on the "Help" menu tab located in the upper right corner of the corresponding page.',
	'hi28'					=>	'-Updates info are available via Twitter:',


);

