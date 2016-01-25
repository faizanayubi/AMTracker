<?php
/* 2015-02-25 18:11:43 */
$title = 'Firewall > Check';
$close = 'Close';
$nfw_help = <<<'EOT'

<h3><strong>File Check</strong></h3>
<p>File Check lets you perform file integrity monitoring upon request.<br />
You need to create a snapshot of all your files and then, at a later time, you can scan your system to compare it with the previous snapshot. Any modification will be immediately detected: file content, file permissions, file ownership, timestamp as well as file creation and deletion.</p>

<li><strong>Create a snapshot of all files stored in that directory:</strong> by default, the directory is set to NinjaFirewall's parent directory.</li>

<li><strong>Exclude the following files/folders:</strong> you can enter a directory or a file name (e.g., <code>/foo/bar/</code>), or a part of it (e.g., <code>foo</code>). Or you can exclude a file extension (e.g., <code>.css</code>).<br />Multiple values must be comma-separated (e.g., <code>/foo/bar/,.css,.png</code>).</li>

<li><strong>Do not follow symbolic links:</strong> by default, NinjaFirewall will not follow symbolic links.</li>

EOT;
