<?php
/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (Pro edition)                                         |
 |                                                                     |
 | (c) NinTechNet - http://nintechnet.com/                             |
 |                                                                     |
 +---------------------------------------------------------------------+
 | REVISION: 2015-10-10 18:11:20                                       |
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
$iso_csv = __DIR__ .'/share/iso3166.csv';

html_header();
echo '<br /><div class="warning"><p>' . $lang['pro_only'] . ' (<a class="links" style="border-bottom:1px dotted #FFCC25;" href="http://nintechnet.com/ninjafirewall/pro-edition/">'. $lang['lic_upgrade'] . '</a>).</p></div>';
?>
<br />

	<fieldset><legend>&nbsp;<b><?php echo $lang['admin'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td width="55%" align="left"><?php echo $lang['whitelisttitle'] ?></td>
				<td width="45%">
					<p><label><input type="radio" disabled="disabled">&nbsp;<?php echo $lang['yes'] ?></label></p>
					<p><label><input type="radio" checked="checked" disabled="disabled">&nbsp;<?php echo $lang['no'] . $lang['default'] ?></label></p>
				</td>
			</tr>
			<tr>
				<td width="55%" align="left" class="dotted"><?php printf( $lang['currentstatus'], '<code>' . $_SESSION['nfadmpro'] . '</code>') ?></td>
				<td width="45%" class="dotted">N/A</td>
			</tr>
		</table>
	</fieldset>

	<br />
	<br />

	<fieldset><legend>&nbsp;<b><?php echo $lang['source_ip'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td width="55%" align="left"><?php echo $lang['ip_used'] ?></td>
				<td width="45%" align="left">
					<p><label><input type="radio" disabled="disabled" checked="checked" />&nbsp;REMOTE_ADDR<?php echo $lang['default'] ?></label></p>
					<p><label><input type="radio" disabled="disabled" />&nbsp;HTTP_X_FORWARDED_FOR</label></p>
					<p><label><input type="radio" disabled="disabled" />&nbsp;<?php echo $lang['other'] ?></label>&nbsp;<input class="input" type="text" style="width:200px;" maxlength="30" placeholder="<?php echo $lang['eg'] ?> HTTP_CLIENT_IP" disabled="disabled" /></p>
				</td>
			</tr>

			<tr>
				<td width="55%" align="left" class="dotted"><?php echo $lang['localhost'] ?></td>
				<td width="45%" align="left" class="dotted">
					<p><label><input type="radio" disabled="disabled" checked="checked" />&nbsp;<?php echo $lang['yes'] . $lang['default'] ?></label></p>
					<p><label><input type="radio" disabled="disabled" />&nbsp;<?php echo $lang['no'] ?></label></p>
				</td>
			</tr>
		</table>
	</fieldset>

	<br />
	<br />

	<fieldset><legend>&nbsp;<b><?php echo $lang['http_method'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td width="55%" align="left"><?php echo $lang['methods_txt'] ?></td>
				<td width="45%">
					<p><label><input type="checkbox" disabled="disabled" checked="checked">&nbsp;GET<?php echo $lang['default'] ?></label></p>
					<p><label><input type="checkbox" disabled="disabled" checked="checked">&nbsp;POST<?php echo $lang['default'] ?></label></p>
					<p><label><input type="checkbox" disabled="disabled" checked="checked">&nbsp;HEAD<?php echo  $lang['default'] ?></label></p>
				</td>
			</tr>
		</table>
	</fieldset>

	<br />
	<br />

	<fieldset><legend>&nbsp;<b><?php echo $lang['geoip'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td width="55%" align="left"><?php echo $lang['geoip_txt'] ?></td>
				<td width="40%">
					<p><label><input type="radio" disabled="disabled" />&nbsp;<?php echo $lang['yes'] ?></label></p>
					<p><label><input type="radio" disabled="disabled" checked="checked" />&nbsp;<?php echo $lang['no']. $lang['default'] ?></label></p>

				</td>
				<td width="5%">&nbsp;</td>
			</tr>
		</table>

		<div id="geotable">
			<br />
			<div width="100%" class="dotted"></div>
			<br />
			<table border="0" width="100%">
				<tr>
					<td width="56%" align="left"><?php echo $lang['geoip_3166'] ?></td>
					<td width="44%" style="vertical-align:top;">
						<p><label><input type="radio" disabled="disabled" checked="checked">&nbsp;NinjaFirewall<?php echo $lang['default'] ?></label></p>
						<p><label><input type="radio" disabled="disabled">&nbsp;<?php echo $lang['geoip_php'] ?></label> <input type="text" disabled="disabled" placeholder="<?php echo $lang['eg'] ?> GEOIP_COUNTRY_CODE" style="width:170px;" class="input" /></p>
					</td>
				</tr>
			</table>

			<br />
			<div width="100%" class="dotted"></div>
			<br />

			<table border="0" width="100%">
				<tr>
					<td align="center" valign="top" style="vertical-align:top;"><?php echo $lang['geoip_avail'] ?> :<br />
						<select multiple size="8" name="cn_in" style="width:230px;height:200px;font-family:monospace;">
						<?php
						$csv_array = file($iso_csv, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
						foreach ($csv_array as $line) {
							if (preg_match( '/^(\w\w),"(.+?)"$/', $line, $match) ) {
								echo '<option title="' . $match[2] . '" value="' . $match[1] . '">' . $match[1] . ' ' . $match[2] . '</option>';
							}
						}
						?>
						</select>
					</td>

					<td align="center">
						<input type="button" style="width:150px" disabled="disabled" class="button" value="<?php echo $lang['block'] ?> &#187;" />
						<br />
						<br />
						<input type="button" style="width:150px" disabled="disabled" class="button" value="&#171; <?php echo $lang['unblock'] ?>" />
						<br />
						<br />
						<br />
						<br />
						<label><input type="checkbox" disabled="disabled" checked="checked">&nbsp;<?php echo $lang['log_event'] . $lang['default'] ?></label>
					</td>

					<td align="center" style="vertical-align:top;"><?php echo $lang['geoip_blocked'] ?> :<br />
						<select multiple="multiple" size="8" style="width:230px;height:200px;font-family:monospace;"></select>
					</td>
				</tr>
			</table>

			<br />
			<div width="100%" class="dotted"></div>
			<br />

			<table border="0" width="100%">
				<tr>
					<td width="56%" align="left"><?php echo $lang['geoip_ninja'] ?></td>

					<td width="44%" align="left">
						<p><label><input type="radio" disabled="disabled" />&nbsp;<?php echo $lang['yes'] ?></label></p>
						<p><label><input type="radio" disabled="disabled" checked="checked" />&nbsp; <?php echo $lang['no'] . $lang['default'] ?></label></p>
					</td>
				</tr>
			</table>

		</div>
	</fieldset>

	<br />
	<br />

	<fieldset><legend>&nbsp;<b><?php echo $lang['ipaccess'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td width="25%" align="left"><?php echo $lang['ipallow'] ?></td>
				<td width="35%" align="center">
					<input type="text" disabled="disabled" class="input" style="width:200px;" placeholder="<?php echo $lang['eg'] ?> 1.2.3.4 or 1.2.3" />
					<br />
					<i><?php echo $lang['ipv4v6'] ?></i>
					<br /><br />
					<input type="button" style="width:150px" disabled="disabled" class="button" value="<?php echo $lang['allow'] ?> &#187;" />
					<br /><br />
					<input type="button" style="width:150px" disabled="disabled" class="button" value="&#171; <?php echo $lang['discard'] ?>" />
					<br />
					<br />
					<label><input type="checkbox" disabled="disabled" checked="checked">&nbsp;<?php echo $lang['log_event'] ?></label>
				</td>
				<td width="40%" align="center">
					<?php echo $lang['ipallowed'] ?> :
					<br />
					<select multiple="multiple" size="8" style="width:230px;height:200px;font-family:monospace;"></select>
					<br />&nbsp;
				</td>
			</tr>

			<tr>
				<td width="25%" align="left" class="dotted"><?php echo $lang['ipblock'] ?></td>
				<td width="35%" align="center" class="dotted">
					<input type="text" class="input" disabled="disabled" maxlength="45" style="width:200px;" placeholder="<?php echo $lang['eg'] ?> 1.2.3.4 or 1.2.3" />
					<br />
					<i><?php echo $lang['ipv4v6'] ?></i>
					<br /><br />
					<input type="button" disabled="disabled" style="width:150px" class="button" value="<?php echo $lang['block'] ?> &#187;" />
					<br /><br />
					<input type="button" disabled="disabled" style="width:150px" class="button" value="&#171; <?php echo $lang['unblock'] ?>" />
					<br />
					<br />
					<label><input type="checkbox" disabled="disabled" checked="checked" />&nbsp;<?php echo $lang['log_event'] . $lang['default'] ?></label>
				</td>
				<td width="40%" align="center" class="dotted">
					<?php echo $lang['ipblocked'] ?> :
					<br />
					<select multiple="multiple" size="8" style="width:230px;height:200px;font-family:monospace;"></select><br />&nbsp;
				</td>
			</tr>

			<tr>
				<td width="25%" align="left" class="dotted"><?php echo $lang['ratelimit'] ?></td>
				<td width="35%" align="center" class="dotted">

			<p><label><input type="radio" disabled="disabled" />&nbsp;<?php echo $lang['rate_limit_1'] ?> </label>
				<input type="text" disabled="disabled" class="input" size="2" value="300" maxlength="3" />&nbsp;<?php echo $lang['rate_limit_2'] ?>
				</p>
				<p>
				<?php echo $lang['rate_limit_3'] ?> <input type="text" disabled="disabled" class="input" value="10" size="2" maxlength="3" />&nbsp;<?php echo $lang['rate_limit_4'] ?>
				</p>
				<p>
				<?php echo $lang['rate_limit_5'] ?> <select class="input">
					<option><?php echo $lang['5_second'] ?></option>
					<option><?php echo $lang['10_second'] ?></option>
					<option><?php echo $lang['15_second'] ?></option>
					<option><?php echo $lang['30_second'] ?></option>
					</select>&nbsp;<?php echo $lang['rate_limit_6'] ?>
				</p>
				<p>
				<label><input type="checkbox" disabled="disabled" checked="checked" />&nbsp;<?php echo $lang['log_event'] . ' ' . $lang['default'] ?></label>

				</p>

				</td>
				<td width="40%" align="center" class="dotted">

					<label><input type="radio" disabled="disabled" checked="checked" />&nbsp;<?php echo $lang['disabled'] . ' ' . $lang['default'] ?></label>
				</td>
			</tr>

		</table>
	</fieldset>
	<br />
	<br />

	<fieldset><legend>&nbsp;<b><?php echo $lang['url_ac'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td width="25%" align="left"><?php echo $lang['url_allow'] ?></td>
				<td width="35%" align="center">
					<input type="text" class="input" disabled="disabled" maxlength="45" style="width:200px;" placeholder="<?php echo $lang['eg'] ?> /script.php" />
					<br />
					<i><?php echo $lang['url_note'] ?></i>
					<br /><br />
					<input type="button" disabled="disabled" style="width:150px" class="button" value="<?php echo $lang['allow'] ?> &#187;" />
					<br /><br />
					<input type="button" disabled="disabled" style="width:150px" class="button" value="&#171; <?php echo $lang['discard'] ?>" />
					<br />
					<br />
					<label><input type="checkbox" disabled="disabled" checked="checked" />&nbsp;<?php echo $lang['log_event'] ?></label>
				</td>
				<td width="40%" align="center">
					<?php echo $lang['url_allowed'] ?> :
					<br />
					<select multiple="multiple" size="8" style="width:230px;height:200px;font-family:monospace;"></select>
					<br />&nbsp;
				</td>
			</tr>
			<tr>
				<td width="25%" align="left" class="dotted"><?php echo $lang['url_block'] ?></td>
				<td width="35%" align="center" class="dotted">
					<input type="text" disabled="disabled" class="input" maxlength="45" style="width:200px;" value="" placeholder="<?php echo $lang['eg'] ?> /cache/" />
					<br />
					<i><?php echo $lang['url_note'] ?></i>
					<br /><br />
					<input type="button" disabled="disabled" style="width:150px" class="button" value="<?php echo $lang['block'] ?> &#187;" />
					<br /><br />
					<input type="button" disabled="disabled" style="width:150px" class="button" value="&#171; <?php echo $lang['unblock'] ?>" />
					<br />
					<br />
					<label><input type="checkbox" disabled="disabled" checked="checked" />&nbsp;<?php echo $lang['log_event'] . ' ' . $lang['default'] ?></label>
				</td>
				<td width="40%" align="center" class="dotted">
					<?php echo $lang['url_blocked'] ?> :
					<br />
					<select multiple="multiple" size="8" style="width:230px;height:200px;font-family:monospace;"></select>
					<br />&nbsp;
				</td>
			</tr>

		</table>
	</fieldset>

	<br />
	<br />

	<fieldset><legend>&nbsp;<b><?php echo $lang['bot_ac'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="10" cellspacing="0">
			<tr>
				<td width="25%" align="left"><?php echo $lang['bot_ac_txt'] ?></td>
				<td width="35%" align="center">
					<input type="text" disabled="disabled" class="input" maxlength="45" style="width:200px;" placeholder="<?php echo $lang['eg'] ?> BOT for JCE" />
					<br />
					<i><?php echo $lang['bot_note'] ?></i>
					<br /><br />
					<input type="button" disabled="disabled" style="width:150px" class="button" value="<?php echo $lang['block'] ?> &#187;" />
					<br /><br />
					<input type="button" disabled="disabled" style="width:150px" class="button" value="&#171; <?php echo $lang['unblock'] ?>" />
					<br />
					<br />
					<label><input type="checkbox" disabled="disabled" checked="checked" />&nbsp;<?php echo $lang['log_event'] . ' ' . $lang['default'] ?></label>
				</td>
				<td width="40%" align="center">
					<?php echo $lang['bot_blocked'] ?> :
					<br />
					<select multiple="multiple" size="8" style="width:230px;height:200px;font-family:monospace;">
						<?php
						$bots =  explode('|',  preg_replace( '/\\\([`.\\\+*?\[^\]$(){}=!<>|:-])/', '$1', NFW_BOT_LIST ));
						sort( $bots );
						foreach ($bots as $bot) {
							if ( $bot ) {
								echo '<option title="' . ucwords( $bot ) . '">' . ucwords( $bot ) . '</option>';
							}
						}
						?>
					</select>
					<br />
					<a class="links" style="border-bottom:dotted 1px #FDCD25;" href="javascript:alert('Disabled')"><?php echo $lang['bot_default'] ?></a>
				</td>
			</tr>

		</table>
	</fieldset>

	<br />
	<br />
	<center>
		<input type="button" disabled="disabled" class="button" value="<?php echo $lang['save_conf'] ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" disabled="disabled" class="button" value="<?php echo $lang['default_button'] ?>" />
	</center>

	<br />
	<br />
<?php

html_footer();

/* ------------------------------------------------------------------ */
// EOF
