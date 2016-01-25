<?php
/* 2015-04-22 19:11:03 */
$title = 'Firewall > Options';
$close = 'Close';
$nfw_help = <<<'EOT'

<h3><strong>Options</strong></h3>

<p><img src="static/bullet_off.gif">&nbsp;<strong>Firewall protection:</strong> This option allows you to disable NinjaFirewall. Your site will remain unprotected until you enable it again.
<br />
Note that if you are using the <code>.htninja</code> file, it will not be disabled. If you want to disable it too, you either need to rename or delete it.</p>

<br />

<p><img src="static/bullet_off.gif">&nbsp;<strong>Debug mode:</strong> In Debugging mode, NinjaFirewall will not block or sanitise suspicious requests but will only log them (the firewall log will display <code>DEBUG_ON</code> in the LEVEL column).
<br />
We recommend to run it in Debug Mode for at least 24 hours after installing it on a new site and then to keep an eye on the firewall log during that time. If you notice a false positive in the log, you can simply use NinjaFirewall's Rules Editor to disable the security rule that was wrongly triggered.</p>

<br />

<p><img src="static/bullet_off.gif">&nbsp;<strong>HTTP error code and blocked user message to return:</strong> Lets you customize the HTTP error code returned by NinjaFirewall when blocking a dangerous request and the message to display to the user. You can use any HTML tags and 2 built-in variables:</p>
<ul>
	<li><code>%%REM_ADDRESS%%</code> : the blocked user IP.</li>
	<li><code>%%NUM_INCIDENT%%</code> : the unique incident number as it will appear in the firewall log "INCIDENT" column.</li>
</ul>

<br />

<p><img src="static/bullet_off.gif">&nbsp;<strong>Ban offending IP:</strong> In addition to rejecting the request, NinjaFirewall can also ban the offending IP depending on the level of the severity. If you decide to ban IPs, use the submenu to select the time that IPs will be banned (from 1 to max 999 minutes).
<br />
To unban one or more IPs, select it/them in the listbox and click on the "Save Changes" button.
<br />
By default, IPs are not banned.<p>

EOT;

