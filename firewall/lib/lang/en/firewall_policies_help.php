<?php
/* 2015-02-20 02:04:34 */
$title = 'Firewall > Policies';
$close = 'Close';
$nfw_help = <<<'EOT'

Because NinjaFirewall sits in front of your application, it can hook, scan and sanitise all PHP requests, HTTP variables, headers and IPs before they reach your site: <code>$_GET</code>, <code>$_POST</code>, <code>$_COOKIES</code>, <code>$_REQUEST</code>, <code>$_FILES</code>, <code>$_SERVER</code> in either or both HTTP & HTTPS mode.
<br />
Use the options below to enable, disable or to tweak these rules according to your needs.


<hr class="dotted" size="1">

<h3><strong>Scan &amp; Sanitise</strong></h3>
You can choose to scan and reject dangerous content but also to sanitise requests and variables. Those 2 actions are different and can be combined together for better security.<br />
<p><img src="static/bullet_off.gif">&nbsp;<strong>Scan :</strong> if anything suspicious is detected, NinjaFirewall will block the request and return an HTTP error code and message. The user request will fail and the connection will be closed immediately.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Sanitise :</strong> this option will not block but sanitise the user request by escaping characters that can be used to perform code or SQL injections (<code>'</code>, <code>"</code>, <code>\</code>, <code>\n</code>, <code>\r</code>, <code>`</code>, <code>\x1a</code>, <code>\x00</code>) and various exploits (XSS etc). If it is a variable, i.e. <code>?name=value</code>, both its name and value will be sanitised.<br />
This action will be performed when the filtering process is over, right before NinjaFirewall forwards the request to your PHP script.</p>


<hr class="dotted" size="1">

<h3><strong>HTTP / HTTPS</strong></h3>

<p><img src="static/bullet_off.gif">&nbsp;Whether to filter HTTP and/or HTTPS traffic.</p>


<hr class="dotted" size="1">

<h3><strong>Uploads</strong></h3>

<p><img src="static/bullet_off.gif">&nbsp;<strong>File Uploads :</strong> you can allow/disallow uploads, or allow uploads but block scripts (PHP, CGI, Ruby, Python, bash/shell, C/C++ source code), ELF (Unix/Linux binary files) and system files (<code>.htaccess</code>, <code>.htpasswd</code> and PHP INI).</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Sanitise filenames :</strong> any character that is not a letter <code>a-zA-Z</code>, a digit <code>0-9</code>, a dot <code>.</code>, a hyphen <code>-</code> or an underscore <code>_</code> will be removed from the filename and replaced with the <code>X</code> character.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Maximum allowed file size :</strong> if you allow uploads, you can select the maximum size of an uploaded file. Any file bigger than this value will be rejected. Note that if your PHP configuration uses the <code>upload_max_filesize</code> directive, it will be used before NinjaFirewall.</p>


<hr class="dotted" size="1">

<h3><strong>HTTP GET variable</strong></h3>

<p><img src="static/bullet_off.gif">&nbsp;Whether to scan and/or sanitise the <code>GET</code> variable.</p>

<hr class="dotted" size="1">

<h3><strong>HTTP POST variable</strong></h3>

<p><img src="static/bullet_off.gif">&nbsp;Whether to scan and/or sanitise the <code>POST</code> variable.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Decode base64-encoded <code>POST</code> variable:</strong> NinjaFirewall will decode and scan base64 encoded values in order to detect obfuscated malicious code. This option is only available for the <code>POST</code> variable.</p>

<hr class="dotted" size="1">

<h3><strong>HTTP REQUEST variable</strong></h3>

<p><img src="static/bullet_off.gif">&nbsp;Whether to scan and/or sanitise the <code>REQUEST</code> variable.</p>


<hr class="dotted" size="1">

<h3><strong>Cookies</strong></h3>

<p><img src="static/bullet_off.gif">&nbsp;Whether to scan and/or sanitise the <code>COOKIE</code> variable.</p>

<hr class="dotted" size="1">

<h3><strong>HTTP_USER_AGENT server variable</strong></h3>

<p><img src="static/bullet_off.gif">&nbsp;Whether to scan and/or sanitise the <code>HTTP_USER_AGENT</code> variable.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Block <code>POST</code> requests from User-Agents that do not have:</strong> those 3 options can help to block crawlers, scrappers and spambots.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Block suspicious bots/scanners (Pro Edition only):</strong> this option will block crawlers, scrappers and spambots.</p>

<hr class="dotted" size="1">

<h3><strong>HTTP_REFERER server variable</strong></h3>

<p><img src="static/bullet_off.gif">&nbsp;Whether to scan and/or sanitise the <code>HTTP_REFERER</code> variable.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Block POST requests that do not have an <code>HTTP_REFERER</code> header:</strong> this option will block any <code>POST</code> request that does not have a Referrer header (<code>HTTP_REFERER</code> variable). If you need external applications to post to your scripts (e.g., Paypal IPN etc), you are advised to keep this option disabled otherwise they will likely be blocked (unless you added them to your IP or URL Access Control whitelist). Note that <code>POST</code> requests are not required to have a Referrer header and, for that reason, this option is disabled by default.</p>

<hr class="dotted" size="1">

<h3><strong>HTTP response headers</strong></h3>

<p>In addition to filtering incoming requests, NinjaFirewall can also hook the HTTP response in order to alter its headers. Those modifications can help to mitigate threats such as XSS, phishing and clickjacking attacks.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Set <code>X-Content-Type-Options</code> to protect against MIME type confusion attacks:</strong> sending this response header with the <code>nosniff</code> value will prevent compatible browsers from MIME-sniffing a response away from the declared content-type.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Set <code>X-Frame-Options</code> to protect against clickjacking attempts:</strong> this header indicates a policy whether a browser must not allow to render a page in a <code>&lt;frame&gt;</code> or <code>&lt;iframe&gt;</code>. Hosts can declare this policy in the header of their HTTP responses to prevent clickjacking attacks, by ensuring that their content is not embedded into other pages or frames. NinjaFirewall accepts two different values:</p>
<ul>
	<li><code>SAMEORIGIN</code>: a browser receiving content with this header must not display this content in any frame from a page of different origin than the content itself.</li>
	<li><code>DENY</code>: a browser receiving content with this header must not display this content in any frame.</li>
</ul>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Set <code>X-XSS-Protection</code> to enable browser's built-in XSS filter (IE, Chrome and Safari):</strong> this header allows compatible browsers to identify and block XSS attack by preventing the malicious script from executing. NinjaFirewall will set its value to <code>1; mode=block</code>.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Force <code>HttpOnly</code> flag on all cookies to mitigate XSS attacks:</strong> adding this flag to cookies helps to mitigate the risk of cross-site scripting by preventing them from being accessed through client-side script. NinjaFirewall can hook all cookies sent by your blog, its plugins or any other PHP script, add the <code>HttpOnly</code> flag if it is missing, and re-inject those cookies back into your server HTTP response headers right before they are sent to your visitors.</p>
<p><img src="static/icon_warn.png">&nbsp;If your PHP scripts send cookies that need to be accessed from JavaScript, you should keep that option disabled.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Set <code>Strict-Transport-Security</code> (HSTS) to enforce secure connections to the server:</strong> this policy enforces secure HTTPS connections to the server. Web browsers will not allow the user to access the web application over insecure HTTP protocol. It helps to defend against cookie hijacking and Man-in-the-middle attacks. Most recent browsers support HSTS headers.</p>

<hr class="dotted" size="1">

<h3><strong>PHP</strong></h3>

<p><img src="static/bullet_off.gif">&nbsp;<strong>Block PHP built-in wrappers:</strong> PHP has several wrappers for use with the filesystem functions. It is possible for an attacker to use them to bypass firewalls and various IDS to exploit remote and local file inclusions. This option lets you block any script attempting to pass a <code>php://</code> or a <code>data://</code> stream inside a <code>GET</code> or <code>POST</code> request, cookies, user agent and referrer variables.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Hide PHP notice & error messages:</strong> this option lets you hide errors returned by your scripts. Such errors can leak sensitive informations which can be exploited by hackers.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Sanitise <code>PHP_SELF</code>, <code>PATH_TRANSLATED</code>, <code>PATH_INFO</code>:</strong> this option can sanitise any dangerous characters found in those 3 server variables to prevent various XSS and database injection attempts.</p>

<hr class="dotted" size="1">

<h3><strong>Various</strong></h3>

<p><img src="static/bullet_off.gif">&nbsp;<strong>Block the <code>DOCUMENT_ROOT</code> server variable in HTTP requests:</strong> this option will block scripts attempting to pass the <code>DOCUMENT_ROOT</code> server variable in a <code>GET</code> or <code>POST</code> request. Hackers use shell scripts that often need to pass this value, but most legitimate programs do not.

<p><img src="static/bullet_off.gif">&nbsp;<strong>Block ASCII character 0x00 (NULL byte):</strong> this option will reject any <code>GET</code> or <code>POST</code> request, <code>COOKIE</code>, <code>HTTP_USER_AGENT</code>, <code>REQUEST_URI</code>, <code>PHP_SELF</code>, <code>PATH_INFO</code> variables containing the ASCII character 0x00 (NULL byte). Such a character is dangerous and should always be rejected.

<p><img src="static/bullet_off.gif">&nbsp;<strong>Block ASCII control characters 1 to 8 and 14 to 31:</strong> in most cases, those control characters are not needed and should be rejected as well.

<p><img src="static/bullet_off.gif">&nbsp;<strong>Block localhost IP in <code>GET/POST</code> requests:</strong> this option will block any <code>GET</code> or <code>POST</code> request containing the localhost IP (127.0.0.1). It can be useful to block SQL dumpers and various hacker's shell scripts.

<p><img src="static/bullet_off.gif">&nbsp;<strong>Block HTTP requests with an IP in the <code>HTTP_HOST</code> header:</strong> this option will reject any request using an IP instead of a domain name in the <code>Host</code> header of the HTTP request. Unless you need to connect to your site using its IP address, (e.g. http://172.16.0.1/index.php), enabling this option will block a lot of hackers scanners because such applications scan IPs rather than domain names.

<p><img src="static/bullet_off.gif">&nbsp;<strong>Accept the following HTTP methods:</strong> by default, NinjaFirewall will only accept <code>GET</code>, <code>POST</code> and <code>HEAD</code> methods.

EOT;

