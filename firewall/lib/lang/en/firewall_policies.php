<?php
/* 2015-10-28 19:05:58 */
$lang = array (

	'error'				=> 'Error',
	'yes'					=> 'Yes',
	'no'					=> 'No',
	'default'			=> ' (default)',
	'disabled'			=> 'Disabled',
	'eg'					=> 'e.g.',

	'policies'			=>	'Policies',

	'http_title'		=>	'HTTP / HTTPS',
	'http_enable'		=>	'Enable NinjaFirewall for',
	'http_https'		=>	'HTTP and HTTPS traffic',
	'http'				=>	'HTTP traffic only',
	'https'				=>	'HTTPS traffic only',

	'numbers_only'		=>	'Please enter numbers only.',

	'upload_title'		=>	'Uploads',
	'uploads'			=>	'Allow uploads',
	'disallow_upl'		=>	'Disallow uploads',
	'allow_upl'			=>	'Allow uploads',
	'allow_but'			=>	'Allow, but block scripts, ELF and system files',
	'sanit_fn'			=>	'Sanitise filenames',
	'mxsize_fn'			=>	'Maximum allowed file size',
	'kb'					=>	'KB',

	'http_get'			=>	'HTTP GET variable',
	'scan_get'			=>	'Scan GET variable',
	'sanit_get'			=>	'Sanitise GET variable',

	'http_post'			=>	'HTTP POST variable',
	'scan_post'			=>	'Scan POST variable',
	'sanit_post'		=>	'Sanitise POST variable',
	'sanit_warn'		=> 'Do not enable this option unless you know what you are doing!',
	'decode_b64'		=>	'Decode base64-encoded POST variables',

	'http_request'		=>	'HTTP REQUEST variable',
	'sanit_request'	=>	'Sanitise REQUEST variable',

	'cookies'			=>	'Cookies',
	'scan_cookies'		=>	'Scan cookies',
	'sanit_cookies'	=>	'Sanitise cookies',

	'ua'					=>	'HTTP_USER_AGENT server variable',
	'scan_ua'			=>	'Scan HTTP_USER_AGENT',
	'sanit_ua'			=>	'Sanitise HTTP_USER_AGENT',
	'block_ua'			=>	'Block POST requests from User-Agents that do not have',
	'mozilla_ua'		=>	'A Mozilla-compatible signature',
	'accept_ua'			=>	'An HTTP_ACCEPT header',
	'accept_lang_ua'	=>	'An HTTP_ACCEPT_LANGUAGE header ',
	'suspicious_ua' 	=>	'Block suspicious bots/scanners',

	'post_warn'			=>	'Keep this option disabled if you are using scripts like Paypal IPN (unless you added them to your IP or URL Access Control whitelist).',
	'posts_warn'		=>	'Keep these options disabled if you are using scripts like Paypal IPN (unless you added them to your IP or URL Access Control whitelist).',


	'referer'			=>	'HTTP_REFERER server variable',
	'scan_referer'		=>	'Scan HTTP_REFERER',
	'sanit_referer'	=>	'Sanitise HTTP_REFERER',
	'post_referer'		=>	'Block POST requests that do not have an HTTP_REFERER header',

	'httponly_warn'	=>	'If your PHP scripts send cookies that need to be accessed from JavaScript, you should keep this option disabled. Go ahead?',
	'httpresponse'		=>	'HTTP response headers',
	'x_c_t_o'			=>	'Set X-Content-Type-Options to protect against MIME type confusion attacks',
	'x_f_o'				=>	'Set X-Frame-Options to protect against clickjacking attempts',
	'x_x_p'				=>	"Set X-XSS-Protection to enable browser's built-in XSS filter (IE, Chrome and Safari)",
	'httponly'			=>	'Force HttpOnly flag on all cookies to mitigate XSS attacks',
	'missing_funct'	=>	'This option is disabled because the %s PHP function is not available on your server.',
	'hsts'				=>	'Set Strict-Transport-Security (HSTS) to enforce secure connections to the server',
	'reset'				=>	'Set <code>max-age</code> to 0',
	'1_month'			=>	'1 month',
	'6_months'			=>	'6 months',
	'1_year'				=>	'1 year',
	'subdomain'			=>	'Apply to all subdomains',
	'hsts_warn'			=>	'HSTS headers can only be set when you are accessing your site over HTTPS.',


	'php'					=>	'PHP',
	'wrapper'			=>	'Block PHP built-in wrappers',
	'php_error'			=>	'Hide PHP notice and error messages',
	'php_self'			=>	'Sanitise PHP_SELF',
	'php_ptrans'		=>	'Sanitise PATH_TRANSLATED',
	'php_pinfo'			=>	'Sanitise PATH_INFO',

	'various'			=> 'Various',
	'block_docroot'	=>	'Block the DOCUMENT_ROOT server variable in HTTP request',
	'block_nullbye'	=>	'Block ASCII character 0x00 (NULL byte)',
	'block_ascii'		=>	'Block ASCII control characters 1 to 8 and 14 to 31',
	'block_lo'			=>	'Block localhost IP in GET/POST request',
	'block_iphost'		=>	'Block HTTP requests with an IP in the HTTP_HOST header',
	'block_method'		=>	'Accept the following HTTP methods',
	'get_post_head'	=>	'GET, POST and HEAD only',
	'all_method'		=> 'Any HTTP methods',

	'disabled_msg'		=> 'This option is not compatible with your actual server configuration.',

	'default_js'		=>	'All fields will be restored to their default values. Go ahead?',
	'default_button'	=>	'Restore Default Values',

	'save_conf'			=> 'Save Changes',
	'saved_conf'		=> 'Your changes were saved',
	'error_conf'		=> 'Cannot write to the <code>./conf/options.php</code> configuration file. ' .
								'Please make this file writable.',
	'error_rules'		=> 'Cannot write to the <code>./conf/rules.php</code> configuration file. ' .
								'Please make this file writable.',

);

