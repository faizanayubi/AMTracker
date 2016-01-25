<?php
/* 2015-03-14 21:26:36 */
$title = 'Firewall > Access Control';
$close = 'Close';
$nfw_help = <<<'EOT'

<h3><strong>Access Control</strong></h3>

Access Control is a powerful set of directives that can be used to allow or restrict access to your website based on many criteria.
<br />
To make better use of them, it is important to understand NinjaFirewall's directives processing order:

<p><img src="static/bullet_off.gif">&nbsp;Incoming HTTP request:</p>
<ol>
	<li><code><a href="http://nintechnet.com/ninjafirewall/pro-edition/help/?htninja" class="links" style="border-bottom:dotted 1px #FDCD25;" target="_blank">.htninja</a></code> file.</li>
	<li>Temporarily blocked IPs ("Firewall > Options > Ban offending IP").</li>

	<li><strong>Access Control :</strong></li>
	<ol>
		<li>Allowed IPs.</li>
		<li>Allowed URLs.</li>
		<li>Blocked IPs.</li>
		<li>Blocked URLs.</li>
		<li>Bot Access Control.</li>
		<li>Geolocation.</li>
		<li>Rate Limiting.</li>
	</ol>

	<li>File Guard.</li>
	<li>NinjaFirewall built-in rules &amp; options</li>
</ol>

<p><img src="static/bullet_off.gif">&nbsp;Response body:</p>
<ol>
	<li>HTTP response headers (Firewall Policies)</li>
	<li>Web Filter</li>
</ol>

<hr class="dotted" size="1">

<h3><strong>Administrator</strong></h3>

<p><img src="static/bullet_off.gif">&nbsp;<strong>Whitelist the Administrator:</strong> when enabling this option, NinjaFirewall will whitelist you so that you will never be blocked by the firewall.
<br />
You will remain whitelisted even if you log out of the administration console.</p>


<hr class="dotted" size="1">

<h3><strong>Source IP</strong></h3>


<p><img src="static/bullet_off.gif">&nbsp;<strong>Retrieve visitors IP address from:</strong> this option should be used if you are behind a reverse proxy, a load balancer or using a CDN, in order to tell NinjaFirewall which IP it should use. By default, it will rely on <code>REMOTE_ADDR</code>. If you want it to use <code>HTTP_X_FORWARDED_FOR</code> or any other similar variable, <font color="red">it is absolutely necessary to ensure that it is reliable</font> (i.e., setup by your own load balancer/reverse proxy) because it <a href="http://blog.nintechnet.com/many-popular-wordpress-security-plugins-vulnerable-to-ip-spoofing/" class="links" style="border-bottom:dotted 1px #FDCD25;" target="_blank">can be easily spoofed</a>. If that variable includes more than one IP, only the left-most (the original client) will be checked. If it does not include any IP, NinjaFirewall will fall back to </code>REMOTE_ADDR</code>.</p>

<p><img src="static/bullet_off.gif">&nbsp;<strong>Scan traffic coming from localhost and private IP address spaces:</strong> this option will allow the firewall to scan traffic from all non-routable private IPs (IPv4 and IPv6) as well as the localhost IP. We recommend to keep it enabled if you have a private network (2 or more servers interconnected).</p>

<hr class="dotted" size="1">

<h3><strong>HTTP Methods</strong></h3>

<p><img src="static/bullet_off.gif">&nbsp;<strong>All Access Control directives below should apply to:</strong> this option lets you select the HTTP method(s). All Access Control directives (Geolocation, IPs, bots and URLs) will only apply to the selected methods. It does not apply to the Firewall Policies options, which use their own ones.</p>


<hr class="dotted" size="1">

<h3><strong>Geolocation Access Control</strong></h3>
You can filter and block traffic coming from specific countries.

<p><img src="static/bullet_off.gif">&nbsp;<strong>Retrieve ISO 3166 country code from:</strong> this is the two-letter country code that is used to define a country (e.g., US, UK, FR, DE etc), based on the visitors IP. NinjaFirewall can either retrieve it from its database, or from a predefined PHP variable added by your HTTP server (e.g., <code>GEOIP_COUNTRY_CODE</code>).</p>

<p><img src="static/bullet_off.gif">&nbsp;<strong>Available/Blocked countries:</strong> you can add/remove any country from the two listboxes. For more information about some specific ISO 3166 codes (A1, A2, AP, EU etc), you may want to consult the <a href="http://dev.maxmind.com/geoip/legacy/codes/iso3166/" class="links" style="border-bottom:dotted 1px #FDCD25;" target="_blank">MaxMind GeoIP online help</a>. By default, no country is blocked.</p>

<p><img src="static/bullet_off.gif">&nbsp;<strong>Add <code>NINJA_COUNTRY_CODE</code> to PHP headers:</strong> after retrieving the two-letter country code, NinjaFirewall can add it to the PHP headers in the <code>$_SERVER["NINJA_COUNTRY_CODE"]</code> variable. If you have an application, theme or a plugin that needs to know your visitors location, simply use that variable.</p>

PHP code example to use in your application to geolocate your visitors&nbsp;:<br />
<center>
	<textarea style="width:100%;height:80px;font-family:monospace;" wrap="off">if (! empty($_SERVER['NINJA_COUNTRY_CODE']) &&
     $_SERVER['NINJA_COUNTRY_CODE'] != '--' ) {
	echo 'Your country code: ' . $_SERVER['NINJA_COUNTRY_CODE'];
}</textarea>
</center>

<p><img src="static/icon_warn.png">&nbsp;If NinjaFirewall cannot find the two-letter country code, it will replace it with 2 hyphens (<code>--</code>).</p>

<hr class="dotted" size="1">

<h3><strong>IP Access Control</strong></h3>
You can permanently allow/block an IP or a part of it. IPv4 and IPv6 are fully supported by NinjaFirewall.
<br />
You must at least enter the first 3 characters of an IP:

<p><img src="static/bullet_off.gif">&nbsp;<strong>Full IPv4 :</strong> <code>1.2.3.123</code> will only match IP <code><font color="red">1.2.3.123</font></code></p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Partial IPv4 :</strong> </p>
<ul><img src="static/bullet_off.gif">&nbsp;<code>1.2.3.</code> will match any IP address <strong>starting with</strong> <code>1.2.3.</code> (from <code><font color="red">1.2.3.</font>0</code> to <code><font color="red">1.2.3.</font>255</code>), but will not match <code>2<font color="red">1.2.3.</font>0</code></ul>
<ul><img src="static/bullet_off.gif">&nbsp;<code>1.2.3</code> will match any IP address <strong>starting with</strong> <code>1.2.3</code> (from <code><font color="red">1.2.3</font>.0</code> to <code><font color="red">1.2.3</font>.255</code>, and also <code><font color="red">1.2.3</font>4.56</code> etc), but will not match <code>4.<font color="red">1.2.3</font></code></ul>
The same rules apply to IPv6 addresses. Subnets notation (e.g, 66.155.0.0/17) are not supported.

<p><img src="static/bullet_off.gif">&nbsp;<strong>Rate limiting:</strong> this option allows you to slow down aggressive bots, crawlers, web scrapers or even small attacks. Any IP reaching the defined threshold will be banned from 1 to 999 seconds. Note that the purpose of this feature is not to permanently block an IP but rather to temporarily prevent it from accessing the site and abusing your system resources. If you want to permanently block an IP, use the blacklist instead. By default, Rate Limiting is turned off.</p>
<p><img src="static/icon_warn.png">&nbsp;IPs temporarily banned by the Rate Limiting option can be unblocked immediately by clicking either the "Save Changes" or "Restore Default Values" buttons at the bottom of this page.</p>

<hr class="dotted" size="1">

<h3><strong>URL Access Control</strong></h3>
You can permanently allow or block any access to one or more PHP scripts based on their path, relative to the web root (<code>SCRIPT_NAME</code>). You can enter either a full or partial path (case-sensitive).


<p><img src="static/bullet_off.gif">&nbsp;<code>/foo/bar.php</code> will allow/block any access to the <code>bar.php</code> script located inside a <code>/foo/</code> directory (<code>http://domain.tld/foo/bar.php</code>, <code>http://domain.tld/another/directory/foo/bar.php</code> etc).</p>
<p><img src="static/bullet_off.gif">&nbsp;<code>/foo/</code> will allow/block access to all PHP scripts located inside a <code>/foo/</code> directory.</p>


<hr class="dotted" size="1">

<h3><strong>Bot Access Control</strong></h3>
You can block bots, scanners and various crawlers based on the <code>HTTP_USER_AGENT</code> variable. You can enter either a full or partial name (case-insensitive).


<hr class="dotted" size="1">

<h3><strong>Log events</strong></h3>
You can enable/disable firewall logging for each access control directive separately.
<br />
<br />
<br />
<br />
<div align="right" style="font-size:11px;color:#999999;">NinjaFirewall includes GeoLite data created by MaxMind, available from <a href="http://www.maxmind.com" style="font-size:11px;color:#999999;">http://www.maxmind.com</a></div>


EOT;

