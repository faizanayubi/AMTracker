<?php
/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (Pro edition)                                         |
 |                                                                     |
 | (c) NinTechNet - http://nintechnet.com/                             |
 |                                                                     |
 +---------------------------------------------------------------------+
 | REVISION: 2014-08-21 23:09:10                                       |
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
?>
<br />
<fieldset><legend>&nbsp;<b><?php echo $lang['current_lic'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td width="55%" align="left" ><?php echo $lang['lic_date'] ?></td>
				<td width="45%" ><?php echo $lang['n_a'] ?></td>
			</tr>
			<tr>
				<td width="55%" align="left"><?php echo $lang['lic_number'] ?></td>
				<td width="45%"><?php echo $lang['lic_free'] . ' (<a class="links" style="border-bottom:1px dotted #FFCC25;" href="http://nintechnet.com/ninjafirewall/pro-edition/">'. $lang['lic_upgrade'] . '</a>)' ?></td>
			</tr>
			<tr>
				<td width="55%">&nbsp;</td>
				<td width="45%"><input class="button" type="button" disabled="disabled" value="<?php echo $lang['lic_check'] ?>" />

				</td>
			</tr>
		</table>
</fieldset>
<?php

html_footer();

/* ------------------------------------------------------------------ */
// EOF
