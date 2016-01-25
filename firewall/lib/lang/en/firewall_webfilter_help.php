<?php
/* 2014-09-14 19:48:43 */
$title = 'Firewall > Web Filter';
$close = 'Close';
$nfw_help = <<<'EOT'

<h3><strong>Web Filter</strong></h3>
If NinjaFirewall can hook and scan incoming requests, it can also hook the response body (i.e., the output of the HTML page right before it is sent to your visitors browser) and search it for some specific keywords. Such a filter can be useful to detect hacking or malware patterns injected into your HTML code (text strings, spam links, malicious JavaScript code), hackers shell script, redirections and even errors (PHP/MySQL errors). Some suggested keywords are included.
<br />
In the case of a positive detection, NinjaFirewall will not block the response body but will send you an alert by email.

<p><img src="static/bullet_off.gif">&nbsp;<strong>Search HTML page for keywords:</strong> you can enter any keyword from 4 to 150 characters and select whether the search will be case sensitive or not.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Email Alerts:</strong> you can use the notification throttling option to limit the frequency of alerts sent to you (and written to the firewall log) and select whether you want NinjaFirewall to send you the whole HTML source of the page where the keyword was found. Alerts will be sent to the contact email address defined in the "Account &gt; Options" menu.</p>


<p><img src="static/icon_warn.png">&nbsp;Response body filtering can be resource-intensive. Try to limit the number of keywords to what you really need (<10) and/or, if possible, prefer case sensitive to case insensitive filtering.</p>

EOT;

