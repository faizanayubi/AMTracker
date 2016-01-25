<?php
/* 2014-09-14 19:48:24 */
$title = 'Firewall > Rules Editor';
$close = 'Close';
$nfw_help = <<<'EOT'

<h3><strong>Rules Editor</strong></h3>
<p>Besides the Firewall Policies and Access Control directives, NinjaFirewall includes also a large set of built-in rules used to protect your site against the most common vulnerabilities and hacking attempts. They are always enabled and you cannot edit them, but if you notice that your visitors are wrongly blocked by some of those rules, you can use the Rules Editor to disable them individually:</p>
<p><img src="static/bullet_off.gif">&nbsp;Check your firewall log and find the rule ID you want to disable (it is displayed in the <code>RULE</code> column).</p>
<p><img src="static/bullet_off.gif">&nbsp;Select its ID from the enabled rules list below and click the "Disable it" button.</p>

<p><img src="static/icon_warn.png">&nbsp;If the <code>RULE</code> column from your log shows a hyphen <code>-</code> instead of a number, that means that the rule can be changed in your Firewall Policies page.</p>

EOT;

