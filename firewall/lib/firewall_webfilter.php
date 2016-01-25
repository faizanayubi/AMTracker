<?php
/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (Pro edition)                                         |
 |                                                                     |
 | (c) NinTechNet - http://nintechnet.com/                             |
 |                                                                     |
 +---------------------------------------------------------------------+
 | REVISION: 2014-08-21 23:48:33                                       |
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
	<fieldset><legend>&nbsp;<b><?php echo $lang['wf'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td width="55%" align="left"><?php echo $lang['enable_wf'] ?></td>
				<td width="45%">
					<p><label><input type="radio" disabled="disabled" />&nbsp;<?php echo $lang['yes'] ?></label></p>
					<p><label><input type="radio" disabled="disabled" checked="checked"/>&nbsp;<?php echo $lang['no']. $lang['default'] ?></label></p>
				</td>
			</tr>
		</table>

		<table border="0" width="100%" class="smallblack" cellpadding="10" cellspacing="0">
			<tr>
				<td width="28%" align="left" class="dotted"><?php echo $lang['keywords'] ?></td>
				<td width="28%" align="center" class="dotted">
					<input type="text" maxlength="150" disabled="disabled" style="width:200px;" placeholder="<?php echo $lang['eg'] ?> &lt;iframe" class="input" />
					<br />
					<i><?php echo $lang['minmax_char'] ?></i>
					<br /><br />
					<select name="wf_strings" style="width:200px" class="input">
						<option value=""><?php echo $lang['suggested'] ?></option>
						<optgroup label="=== HTML / CSS"></optgroup>
						<option value="<iframe" title="<iframe">&lt;iframe</option>
						<option value="display:none" title="display:none">display:none</option>
						<option value="'hidden'" title="'hidden'">'hidden'</option>
						<option value='http-equiv="refresh"' title='http-equiv="refresh"'>http-equiv="refresh"</option>
						<option value="style.display" title="style.display">style.display</option>
						<option value="multipart/form-data" title="multipart/form-data">multipart/form-data</option>
						<optgroup label="=== JAVASCRIPT"></optgroup>
						<option value="%u00" title="%u00">%u00</option>
						<option value="\u00" title="\u00">\u00</option>
						<option value=".appendChild" title=".appendChild">.appendChild</option>
						<option value="ActiveXObject" title="ActiveXObject">ActiveXObject</option>
						<option value="encodeURIComponent" title="encodeURIComponent">encodeURIComponent</option>
						<option value="eval(" title="eval(">eval(</option>
						<option value=".replace" title=".replace">.replace</option>
						<option value="unescape" title="unescape">unescape</option>
						<optgroup label="=== ERRORS"></optgroup>
						<option value="Fatal error:" title="Fatal error:">Fatal error:</option>
						<option value="Parse error:" title="Parse error:">Parse error:</option>
						<option value="<title>404 Not Found" title="<title>404 Not Found">&lt;title>404 Not Found</option>
						<option value="You have an error in your SQL syntax" title="You have an error in your SQL syntax">You have an error in your SQL syntax</option>
						<optgroup label="=== SHELL SCRIPTS"></optgroup>
						<option value="<?php echo $_SERVER["DOCUMENT_ROOT"] ?>" title="<?php echo $_SERVER["DOCUMENT_ROOT"] ?>"><?php echo $_SERVER["DOCUMENT_ROOT"] ?></option>
						<option value="Hacked by" title="Hacked by">Hacked by</option>
						<option value="<title>phpinfo()" title="<title>phpinfo()">&lt;title>phpinfo()</option>
						<option value="Directory List" title="Directory List">Directory List</option>
						<option value="FTP brute" title="FTP brute">FTP brute</option>
						<option value="Run command" title="Run command">Run command</option>
						<option value="Dump database" title="Dump database">Dump database</option>
						<option value="FilesMan" title="FilesMan">FilesMan</option>
						<option value="Self remove" title="Self remove">Self remove</option>
						<option value="uname -a" title="uname -a">uname -a</option>
						<option value="c99madshell" title="c99madshell">c99madshell</option>
						<option value="r57shell" title="r57shell">r57shell</option>
						<option value="c99shell" title="c99shell">c99shell</option>
						<option value="Open_basedir" title="Open_basedir">Open_basedir</option>
						<option value="phpMiniAdmin" title="phpMiniAdmin">phpMiniAdmin</option>
						<option value="<title>Login - Adminer" title="<title>Login - Adminer">&lt;title>Login - Adminer</option>
					</select>
					<br />
					<br />
					<div style="text-align:left;">
						<label><input type="radio" disabled="disabled" checked="checked" />&nbsp;<?php echo $lang['case_sensit'] .  $lang['default'] ?></label>
						<br />
						<label><input type="radio" disabled="disabled" />&nbsp;<?php echo $lang['case_insensit'] ?></label>
					</div>
				</td>
				<td width="14%" align="center" class="dotted">
					<input type="button" style="width:100px" disabled="disabled" class="button" value="<?php echo $lang['add'] ?> &#187;" />
					<br /><br />
					<input type="button" style="width:100px" disabled="disabled" class="button" value="&#171; <?php echo $lang['remove'] ?>" />
				</td>
				<td width="30%" align="center" class="dotted">
				<?php echo $lang['keywords_search'] ?>
				<br />
				<select multiple="multiple" size="8" style="width:220px;height:200px;"></select>
				</td>
			</tr>
		</table>
		<br />
		<table border="0" width="100%" cellpadding="10" cellspacing="0" class="smallblack">
			<tr>
				<td align="left" width="28%" class="dotted"><?php echo $lang['email_alert'] ?></td>
				<td align="left" width="72%" class="dotted"><br />
				<?php echo $lang['max_email_1'] ?><select class="input">
					<option><?php echo $lang['5_minute'] ?></option>
					<option><?php echo $lang['15_minute'] ?></option>
					<option><?php echo $lang['30_minute'] ?></option>
					<option><?php echo $lang['60_minute'] ?></option>
					<option><?php echo $lang['180_minute'] ?></option>
					<option><?php echo $lang['360_minute'] ?></option>
					<option><?php echo $lang['720_minute'] ?></option>
					<option><?php echo $lang['1440_minute'] ?></option>
				</select><?php echo $lang['max_email_2'] ?>
				<br />
				<i><?php echo $lang['reset_timer'] ?></i>
				<br />
				<br />
				<label><input type="checkbox" disabled="disabled" checked="checked">&nbsp;<?php echo $lang['email_attach'] . $lang['default'] ?>.</label>
				</td>
			</tr>
		</table>
	</fieldset>

	<br />
	<br />
	<center><input type="button" class="button" disabled="disabled" value="<?php echo $lang['save_conf'] ?>"></center>

	<br />
	<br />

<?php

html_footer();

/* ------------------------------------------------------------------ */
// EOF
