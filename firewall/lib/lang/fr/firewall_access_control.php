<?php
/* 2015-08-13 20:07:43 */
$lang = array (
	'error'				=> 'Erreur',
	'yes'					=> 'Oui',
	'no'					=> 'Non',
	'default'			=> ' (défaut)',
	'disabled'			=> 'Désactivé',
	'eg'					=> 'ex:',
	'pro_only'			=>	'Cette fonctionnalité n\'est disponible que dans la version <font color="#FF0000">Pro+</font> Edition de NinjaFirewall',
	'lic_upgrade'		=>	'mise à niveau',

	'admin'				=>	'Administrateur',
	'whitelisttitle'	=>	'Ajouter l\'Administrateur à la liste blanche',
	'whitelisted'		=>	'Vous êtes dans la liste blanche.',
	'notwhitelisted'	=>	'Vous n\'êtes pas dans la liste blanche.',
	'currentstatus'	=>	'Statut actuel pour l\'utilisateur %s',


	'js_empty'			=>	'Votre liste est vide.',
	'js_selecte'		=>	'Sélectionnez une ou plusieurs valeurs à supprimer.',
	'js_emptyfield'	=>	'Le champ est vide !',
	'js_inlist'			=>	'est déjà dans votre liste.',
	'js_allowedchar'	=>	'Les caractères autorisés sont: a-z  0-9  . - _ : et espace.',
	'js_allowedchar2'	=>	'Les caractères autorisés sont: a-z  0-9  . - _ / et espace.',
	'js_ipformat'		=>	'L\'IP doit contenir:' . '\n' .
								'-au moins ses 3 premiers caractères.' . '\n' .
								'-IPv4: chiffres [0-9] and point [.] uniquement.' . '\n' .
								'-IPv6: chiffres [0-9], caractères hexadécimaux [a-f] and deux-points [:] uniquement.' . '\n\n' .
								'Voir Aide contextuelle pour plus d\'informations.',
	'js_blockcn'		=>	'Sélectionner un pays à bloquer.',
	'js_unblockcn'		=>	'Sélectionner un pays à supprimer.',
	'js_bot_default'	=>	'Restaurer les valeurs par défaut de la liste des bots ?',
	'js_method'			=>	'Erreur : vous devez sélectionner au moins une méthode HTTP.',
	'js_sourceip'		=>	'Erreur : vous devez entrer une valeur pour le champ [IP Source > Autre].',
	'js_geoipip'		=>	'Erreur : vous devez entrer une valeur pour le champ [ Contrôle d\'accès par Géolocalisation > Variable PHP ].',
	'js_ratelimit'		=>	'Erreur : vous devez entrer le nombre de connexions autorisées pour l\'option [Limiter le trafic].',
	'js_digit'			=>	'Merci d\'entrer un nombre de 1 à 999.',


	'log_event'			=> 'Journaliser',

	'source_ip'			=> 'IP Source',
	'ip_used'			=> 'Récupérer l\'adresse IP des visiteurs depuis',
	'other'				=> 'Autre',
	'localhost'			=> 'Filtrer les connexions provenant de localhost et d\'adresses IP privées',

	'http_method'		=> 'Méthodes HTTP',
	'methods_txt'		=> 'Toutes les directives ci-dessous s\'appliquent aux méthodes',

	'geoip'				=> 'Contrôle d\'accès par Géolocalisation',
	'geoip_txt'			=> 'Activer la Géolocalisation',
	'geoip_3166'		=> 'Récupérer le code ISO 3166 du pays depuis',
	'geoip_php'			=>	'Variable PHP',
	'geoip_avail'		=> 'Pays disponibles',
	'geoip_blocked'	=> 'Pays bloqués',
	'geoip_ninja'		=> 'Ajouter NINJA_COUNTRY_CODE aux en-têtes PHP',
	'geoip_empy'		=>	'Votre liste est vide ! Si vous n\'utilisez pas la géolocalisation, ' .
								'pensez à désactiver cette option.',
	'geoip_var_err'	=>	'Votre serveur ne semble pas utiliser la variable %s.',
	'geoip_db_err'		=>	'La base de données GeoIP est introuvable&nbsp;!',

	'block'				=>	'Bloquer',
	'unblock'			=>	'Supprimer',
	'allow'				=> 'Accepter',
	'discard'			=>	'Supprimer',

	'ipaccess'			=> 'Contrôle d\'accès par IP',
	'ipallow'			=> 'Accepter les IP suivantes',
	'ipallowed'			=> 'IP acceptées',
	'ipblock'			=> 'Bloquer les IP suivantes',
	'ipblocked'			=> 'IP bloquées',
	'ipv4v6'				=> 'Adresse IPv4 / IPv6<br />complète ou partielle.',

	'ratelimit'			=>	'Limiter le trafic',
	'rate_limit_1'		=>	'Bloquer pendant',
	'rate_limit_2'		=>	'secondes<br />',
	'rate_limit_3'		=>	'les IP ayant plus de',
	'rate_limit_4'		=>	'connexions<br />',
	'rate_limit_5'		=>	'en moins de',
	'rate_limit_6'		=>	'',
	'5_second'			=>	'5 secondes',
	'10_second'			=>	'10 secondes',
	'15_second'			=>	'15 secondes',
	'30_second'			=>	'30 secondes',

	'url_ac'				=> 'Contrôle d\'accès par URL',
	'url_allow'			=> 'Autoriser l\'accès à l\'URL suivante (SCRIPT_NAME)',
	'url_block'			=> 'Bloquer l\'accès à l\'URL suivante (SCRIPT_NAME)',
	'url_note'			=> 'URL complète ou partielle,<br />sensible à la casse.',
	'url_blocked'		=>	'URL bloquées',
	'url_allowed'		=>	'URL autorisées',

	'bot_ac'				=>	'Contrôle d\'accès par Bot',
	'bot_ac_txt'		=> 'Rejeter les bots suivants (HTTP_USER_AGENT)',
	'bot_note'			=> 'Chaîne de caractères complète ou partielle,<br />insensible à la casse.',
	'bot_blocked'		=>	'Bots bloqués',
	'bot_default'		=>	'Restaurer les valeurs par défaut',

	'default_js'		=>	'Tous les champs vont être réinitialisés avec leur valeur par défaut.\nContinuer ?',
	'default_button'	=>	'Rétablir les valeurs par défaut',

	'save_conf'			=> 'Sauvegarder les modifications',
	'saved_conf'		=> 'Les modifications ont été enregistrées',
	'error_conf'		=> 'Impossible d\'écrire dans le fichier de configuration <code>./conf/options.php</code>. ' .
								'Assurez-vous que ce fichier n\'est pas en lecture seule.',
	'error_cache'		=> 'Impossible d\'écrire dans le répertoire du cache <code>./nfwlog/cache</code>. ' .
								'Assurez-vous que ce répertoire n\'est pas en lecture seule.',
);

