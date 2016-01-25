<?php
/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (Pro edition)                                         |
 |                                                                     |
 | (c) NinTechNet - http://nintechnet.com/                             |
 |                                                                     |
 +---------------------------------------------------------------------+
 | REVISION: 2015-01-21 19:18:31                                       |
 +---------------------------------------------------------------------+
 | This program is free software: you can redistribute it and/or       |
 | modify it under the terms of the GNU General Public License as      |
 | published by the Free Software Foundation, either version 3 of      |
 | the License, or (at your option) any later version.                 |
 |                                                                     |
 | This program is distributed in the hope that it will be useful,     |
 | but WITHOUT ANY WARRANTY; without even the implied warranty of      |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       |
 | GNU General Public License for more details.                        |
 +---------------------------------------------------------------------+
*/
if (! defined( 'NFW_ENGINE_VERSION' ) ) { die( 'Forbidden' ); }

// Load current language file :
require (__DIR__ .'/lang/' . $nfw_options['admin_lang'] . '/' . basename(__FILE__) );

html_header();
echo '<br /><div class="warning"><p>' . $lang['pro_only'] . ' (<a class="links" style="border-bottom:1px dotted #FFCC25;" href="http://nintechnet.com/ninjafirewall/pro-edition/">'. $lang['lic_upgrade'] . '</a>).</p></div>';
?>
<br />
	<fieldset><legend>&nbsp;<b><?php echo $lang['fg'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td width="55%" align="left"><?php echo $lang['enable_fg'] ?></td>
				<td width="45%">
					<p><label><input type="radio" disabled="disabled" checked="checked" />&nbsp;<?php echo $lang['yes'] . $lang['default'] ?></label></p>
					<p><label><input type="radio" disabled="disabled" value="0" />&nbsp;<?php echo $lang['no'] ?></label></p>
				</td>
			</tr>
		</table>
		<br />
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td width="55%" align="left" class="dotted"><?php echo $lang['detection'] ?></td>
				<td width="45%" class="dotted">
					<?php printf($lang['monitor'], '<input class="input" disabled="disabled" maxlength="2" size="2" value="10" type="text" />') ?>
				</td>
			</tr>
			<tr>
				<td width="55%" align="left" class="dotted"><?php echo $lang['exclude_title'] ?></td>
				<td width="45%" class="dotted"><p><input class="input" style="width:300px" type="text" value="" placeholder="<?php echo $lang['eg'] ?> /foo/bar/cache/" disabled="disabled"><br /><i><?php echo $lang['exclude_desc'] ?></i></p></td>
			</tr>
		</table>
	</fieldset>
	<br />
	<br />
	<center><input type="button" disabled="disabled" class="button" value="<?php echo $lang['save_conf'] ?>"></center>
	<br />
	<br />
<?php

html_footer();

/* ------------------------------------------------------------------ */
// EOF
