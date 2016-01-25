<?php
/*
 +---------------------------------------------------------------------+
 | NinjaFirewall (Pro edition)                                         |
 |                                                                     |
 | (c) NinTechNet - http://nintechnet.com/                             |
 |                                                                     |
 +---------------------------------------------------------------------+
 | REVISION: 2015-07-31 15:56:14                                       |
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

// Saved options ?
if (! empty($_POST) ) {
	$err_msg = $is_update = $ok_msg = '';
	if ( isset($_POST['sel_e_r']) ) {
		if ( $_POST['sel_e_r'] < 1 ) {
			$err_msg = $lang['select_disable'];
		} else if ( ( $_POST['sel_e_r'] == 2 ) || ( $_POST['sel_e_r'] > 499 ) && ( $_POST['sel_e_r'] < 600 ) ) {
			$err_msg = $lang['policy_rule'];
		} else if (! isset( $nfw_rules[$_POST['sel_e_r']] ) ) {
			$err_msg = $lang['no_exist'];
		} else {
			$nfw_rules[$_POST['sel_e_r']]['on'] = 0;
			$is_update = 1;
			$ok_msg = sprintf($lang['ok_disabled'], htmlspecialchars($_POST['sel_e_r']));
		}
	} else if ( isset($_POST['sel_d_r']) ) {
		if ( $_POST['sel_d_r'] < 1 ) {
			$err_msg = $lang['select_enable'];
		} else if ( ( $_POST['sel_d_r'] == 2 ) || ( $_POST['sel_d_r'] > 499 ) && ( $_POST['sel_d_r'] < 600 ) ) {
			$err_msg = $lang['policy_rule'];
		} else if (! isset( $nfw_rules[$_POST['sel_d_r']] ) ) {
			$err_msg = $lang['no_exist'];
		} else {
			$nfw_rules[$_POST['sel_d_r']]['on'] = 1;
			$is_update = 1;
			$ok_msg = sprintf($lang['ok_enabled'], htmlspecialchars($_POST['sel_d_r']));
		}
	}
   if ( $is_update ) {
		$err_msg = save_firewall_rules_editor();
	}
	if ($err_msg) {
      echo '<br /><div class="error"><p>' . $err_msg .'</p></div>';
   } else {
      echo '<br /><div class="success"><p>'. $ok_msg . '</p></div>';
   }
}

$disabled_rules = $enabled_rules = array();
foreach ( $nfw_rules as $rule_key => $rule_value ) {
	if (! empty( $nfw_rules[$rule_key]['on'] ) ) {
		$enabled_rules[] =  $rule_key;
	} else {
		$disabled_rules[] = $rule_key;
	}
}
?>
<br />
	<fieldset><legend>&nbsp;<b><?php echo $lang['ruleseditor'] ?></b>&nbsp;</legend>
		<table width="100%" class="smallblack" border="0" cellpadding="5" cellspacing="0">
			<tr>
				<td width="55%" align="left"><?php echo $lang['select'] ?></td>
				<td width="45%">
					<form method="post">
						<select name="sel_e_r" class="input" style="width:180px;">
						<option value="0"><?php echo $lang['enabled'] . ' ' . count( $enabled_rules ) ?></option>
						<?php
						sort($enabled_rules);
						$count = 0;
						$desr = '';
						foreach ( $enabled_rules as $key ) {
							// grey-out those ones, they can be changed in the Firewall Policies section:
							if ( ( $key == 2 ) || ( $key > 499 ) && ( $key < 600 ) ) {
								echo '<option value="0" disabled="disabled">' . $lang['rule'] . htmlspecialchars($key) . ' Firewall policy</option>';
							} else {
								if ( $key < 100 ) {
									$desc = ' Remote/local file inclusion';
								} elseif ( $key < 150 ) {
									$desc = ' Cross-site scripting';
								} elseif ( $key < 200 ) {
									$desc = ' Code injection';
								} elseif ( $key < 250 ) {
									$desc = ' SQL injection';
								} elseif ( $key < 350 ) {
									$desc = ' Various vulnerability';
								} elseif ( $key < 400 ) {
									$desc = ' Backdoor/shell';
								}
								echo '<option value="' . htmlspecialchars($key) . '">' . $lang['rule'] . htmlspecialchars($key) . $desc . '</option>';
								$count++;
							}
						}
						?>
						</select>&nbsp;&nbsp;<input class="button" style="width:100px;" type="submit" value="<?php echo $lang['disable_it'] ?>"<?php disabled( $count, 0) ?>>
					</form>
					<br />
					<br />
					<form method="post">
						<select name="sel_d_r" class="input" style="width:180px;">
						<option value="0"><?php echo $lang['disabled'] . ' ' . count( $disabled_rules ) ?></option>
						<?php
						$count = 0;
						sort($disabled_rules);
						foreach ( $disabled_rules as $key ) {
							// grey-out those ones, they can be changed in the Firewall Policies section:
							if ( ( $key == 2 ) || ( $key > 499 ) && ( $key < 600 ) ) {
								echo '<option value="0" disabled="disabled">' . $lang['rule'] . htmlspecialchars($key) . ' Firewall policy</option>';
							} else {
								if ( $key < 100 ) {
									$desc = ' Remote/local file inclusion';
								} elseif ( $key < 150 ) {
									$desc = ' Cross-site scripting';
								} elseif ( $key < 200 ) {
									$desc = ' Code injection';
								} elseif ( $key < 250 ) {
									$desc = ' SQL injection';
								} elseif ( $key < 350 ) {
									$desc = ' Various vulnerability';
								} elseif ( $key < 400 ) {
									$desc = ' Backdoor/shell';
								}
								echo '<option value="' . htmlspecialchars($key) . '">' . $lang['rule'] . htmlspecialchars($key) . $desc . '</option>';
								$count++;
							}
						}
						?>
						</select>&nbsp;&nbsp;<input class="button" style="width:100px;" type="submit" value="<?php echo $lang['enable_it'] ?>"<?php disabled( $count, 0) ?>>
					</form>
					<br /><i class="tinyblack"><?php echo $lang['greyed_out'] ?></i>
				</td>
			</tr>
		</table>
	</fieldset>

	<br />
	<br />

<?php

html_footer();

/* ------------------------------------------------------------------ */

function save_firewall_rules_editor() {

	global $lang;
	global $nfw_rules;

	// Config file must be writable :
	if (! is_writable('./conf/rules.php') ) {
		return $lang['error_rules' ];
	}

	// Save changes :
	if (! $fh = fopen('./conf/rules.php', 'w') ) {
		return $lang['error_rules' ];
	}
	fwrite($fh, '<?php' . "\n\$nfw_rules = <<<'EOT'\n" . serialize( $nfw_rules ) . "\nEOT;\n" );
	fclose($fh);

	return;
}

/* ------------------------------------------------------------------ */
// EOF
