<?php
/* 2015-01-19 17:50:25 */
$lang = array (
	'error'					=> 'Erreur',
	'info'					=> 'Info',
	'firewall'				=> 'Pare-Feu',

	'warning'				=> 'Attention',
	'not_working'			=> 'Attention, NinjaFirewall ne fonctionne pas et votre site n\'est pas protégé',
	'err_message'			=> 'Message d\'erreur',

	'enabled'				=> 'Activé',
	'license'				=>	'License',
	'lic_expir'				=>	'Expiration licence',
	'lic_expired'			=> 'Votre licence a expiré',
	'lic_soon_expired'	=> 'Votre licence va bientôt expirer',
	'lic_renew_click'		=> 'Cliquez ici pour la renouveler',
	'lic_free'				=>	'Pro Edition',
	'lic_upgrade'			=>	'mise à niveau vers <font color="#FF0000">Pro+</font> Edition',
	'na'						=>	'N/A',

	'engine_ver'			=> 'Version',

	'failed_connect'		=> 'Impossible de récupérer les informations de mise à jour ' .
									'depuis les serveurs de NinjaFirewall',

	'new_engine'			=> 'Une nouvelle version est disponible, cliquez ici pour l\'installer&nbsp;!',

	'debugging'				=> 'Débogage',

	'debug_warn'			=> 'NinjaFirewall est en mode <i>Débogage</i>, n\'oubliez pas de ' . '%s' .
									'désactiver celui-ci</a> avant de mettre votre serveur en production.',

	'logging'				=>	'Journal',
	'logging_warn'			=>	'Le journal du pare-feu est désactivé. ' . '%s' . 'Cliquez ici pour le réactiver',

	'lo_warn'				=>	'Vous avez l\'adresse IP d\'un réseau privé :',

	'lo_check'				=>	'Si votre site est derrière un <i>reverse proxy</i> ou <i>load balancer</i>, ' .
									'pensez à configurer correctement l\'option ' . '%s' . 'IP Source</a>.',

	'source_ip'				=>	'IP source',

	'cdn_title'				=>	'Détection CDN',

	// FREE
	'cdn_clouflare_free'	=>	'<code>HTTP_CF_CONNECTING_IP</code> detecté&nbsp;: vous semblez utiliser les services de Cloudflare. Assurez-vous d\'avoir configuré votre serveur HTTP pour qu\'il utilise la bonne adresse IP, sinon, utilisez le fichier <code>' . '%s' . '.htninja</a></code> prévu à cet effet.',
	// PRO
	'cdn_clouflare_pro'	=>	'<code>HTTP_CF_CONNECTING_IP</code> detecté&nbsp;: vous semblez utiliser les services de Cloudflare. Assurez-vous que l\'option ' . '%s' . 'IP source</a> a bien été configurée correctement.',

	// FREE
	'cdn_incapsula_free'	=>	'<code>HTTP_INCAP_CLIENT_IP</code> detecté&nbsp;: vous semblez utiliser les services de Incapsula. Assurez-vous d\'avoir configuré votre serveur HTTP pour qu\'il utilise la bonne adresse IP, sinon, utilisez le fichier <code>' . '%s' . '.htninja</a></code> prévu à cet effet.',
	// PRO
	'cdn_incapsula_pro'	=>	'<code>HTTP_INCAP_CLIENT_IP</code> detecté&nbsp;: vous semblez utiliser les services de Incapsula. Assurez-vous que l\'option ' . '%s' . 'IP source</a> a bien été configurée correctement.',


	'admin'					=>	'Administrateur',
	'whitelisted'			=>	'Vous êtes dans la liste blanche.',
	'notwhitelisted'		=>	'Vous n\'êtes pas dans la liste blanche.',

	'last_login'			=>	'Dernière Connexion',

	'htninja'				=>	'Fichier de configuration',
	'htninja_writable'	=>	'%s est accessible en écriture. Pensez à changer ses permissions en lecture seule.',

	'log_dir'				=> 'Répertoire des Fichier Journaux',
	'cache_dir'				=> 'Répertoire du Cache',
	'conf_dir'				=> 'Répertoire de la Configuration',

	'dir_readonly'			=>	'Attention, le répertoire ' . '<code>%s</code>' . ' est en lecture seule',
	'chmod777'				=> 'Changez ses permissions (chmod) en 0777 ou équivalent.',
);

