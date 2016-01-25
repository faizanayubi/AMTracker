<?php
/* 2015-03-13 23:16:11 */
$title = 'Firewall > Live Log';
$close = 'Close';
$nfw_help = <<<'EOT'

<h3><strong>Live Log</strong></h3>
<p>This feature lets you watch your website traffic in real time. It displays connections in a format similar to the one used by most HTTP server logs (e.g., Apache, Nginx). Note that requests sent to static elements like JS/CSS files and images are not managed by NinjaFirewall.</p>
<p>You can enable/disable the monitoring process, change the refresh rate, clear the screen, enable automatic vertical scrolling, change the log format and select which traffic you want to view (HTTP/HTTPS).</p>

<h3><strong>Log Format</strong></h3>
<p>You can easily customize the log format. Possible values are:</p>
<ul>
	<li><code>%time</code>: the server date, time and timezone.</li>
	<li><code>%name</code>: authenticated user (HTTP basic auth), if any.</li>
	<li><code>%client</code>: the client REMOTE_ADDR. If you are behind a load balancer or CDN, this will be its IP.</li>
	<li><code>%method</code>: HTTP method (i.e., GET, POST).</li>
	<li><code>%uri</code>: the URI which was given in order to access the page (REQUEST_URI).</li>
	<li><code>%referrer</code>: the referrer (HTTP_REFERER), if any.</li>
	<li><code>%ua</code>: the user-agent (HTTP_USER_AGENT), if any.</li>
	<li><code>%forward</code>: HTTP_X_FORWARDED_FOR, if any. If you are behind a load balancer or CDN, this will likely be the visitor true IP.</li>
	<li><code>%host</code>: the requested host (HTTP_HOST), if any.</li>
</ul>
Additionally, you can include any of the following characters: <code>"</code>, <code>%</code>, <code>[</code>, <code>]</code>, <code>space</code> and lowercase letters <code>a-z</code>.

<p><img src="static/icon_warn.png">&nbsp;If you are using the optional <code>.htninja</code> configuration file to whitelist your IP, the Live Log feature will not work.</p>

EOT;

