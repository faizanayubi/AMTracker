<?php
/* 2015-02-25 18:11:43 */
$title = 'Firewall > File Guard';
$close = 'Close';
$nfw_help = <<<'EOT'

<h3><strong>File Guard</strong></h3>
File Guard can detect, in real-time, any access to a PHP script that was recently modified or created, and alert you about this.

<p>If a hacker uploaded a shell script to your site (or injected a backdoor into an already existing file) and tried to directly access that file using his browser or a script, NinjaFirewall would hook the HTTP request and immediately detect that the file was recently modified/created. It would send you a detailed alert (script name, IP, request, date and time). Alerts will be sent to the contact email address defined in the "Account &gt; Options" menu.</p>

<p>Modifications detected by NinjaFirewall include <code>mtime</code> (saved or updated content of a file) and <code>ctime</code> (permissions, ownership etc).</p>

<p>If you do not want to monitor a folder, you can exclude its full path or a part of it (e.g., <code>/var/www/public_html/cache/</code>, <code>/cache/</code> etc). NinjaFirewall will compare this value to the <code>$_SERVER["SCRIPT_FILENAME"]</code> server variable and, if it matches, will ignore it.</p>

EOT;

