<?php
/* 2015-02-05 22:59:40 */
$title = 'Firewall > Security Log';
$close = 'Close';
$nfw_help = <<<'EOT'

<h3><strong>View Log</strong></h3>
<p>The firewall log displays blocked and sanitised requests as well as some useful information. It has 6 columns:</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>DATE :</strong> date and time of the incident.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>INCIDENT :</strong> unique incident number/ID as it was displayed to the blocked user.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>LEVEL :</strong> level of severity (medium, high or critical), information (info, error, upload) and debugging mode (DEBUG_ON).</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>RULE :</strong> reference of the NinjaFirewall built-in security rule that triggered the action. A hyphen (<code>-</code>) instead of a number means it was a rule from your own Firewall Policies or Access Control page.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>IP :</strong> the blocked user remote address.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>REQUEST :</strong> the HTTP request including offending variables & values as well as the reason the action was logged.</p>

<hr class="dotted" size="1">

<h3><strong>Log Options</strong></h3>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Enable firewall log:</strong> you can disable/enable the firewall log from this page.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Auto-rotate log:</strong> NinjaFirewall will rotate its log automatically on the very first day of each month. If your site is very busy, you may want to allow it to rotate the log when it reaches a certain size (MB) as well. By default, if will rotate the log each month or earlier, if it reaches 2 megabytes.
</br />
Rotated logs, if any, can be selected and viewed from the dropdown menu.</p>

EOT;

