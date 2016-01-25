<?php
/* 2015-12-06 00:30:24 */
$lang = array (

	'next'					=> 'Suivant',
	'ok'						=>	'OK',
	'close'					=>	'Fermer',
	'aborting'				=>	'L\'installation de NinjaFirewall ne peut pas continuer.<br />Veuillez résoudre les problèmes mentionnés ci-dessus.',

	'setup'					=>	'Installation',
	'welcome'				=>	'Bienvenue dans l\'assistant d\'installation de NinjaFirewall %s !',

	'region'					=>	'Paramètres régionaux',
	'timezone'				=> 'Afficher les dates et heures en utilisant le fuseau horaire suivant',
	'language'				=> 'Sélectionnez une langue d\'affichage',

	'by'						=> 'par',

	'sysreq'					=>	'Configuration requise',
	'php_version'			=> 'Version PHP',
	'php_error'				=>	'NinjaFirewall nécessite au minimum PHP v5.3, but votre version actuelle est %s.',
	'php_os'					=>	'Système d\'exploitation',
	'windows'				=>	'NinjaFirewall n\'est pas compatible avec Windows.',
	'safemode'				=>	'safe_mode',
	'safemode_ok'			=>	'Désactivé',
	'safemode_error'		=>	'Vous avez <code>safe_mode</code> activé, veuillez le désactiver. Cette fonctionnalité est devenue OBSOLETE depuis PHP 5.3 et a été SUPPRIMEE depuis PHP 5.4.',
	'prepend'				=>	'auto_prepend_file',
	'prepend_error'		=>	'auto_prepend_file est déjà utilisé: <code>%s</code>.<br />NinjaFirewall a besoin de cette directives interne et va tenter de l\'outrepasser. En cas d\'erreur lors de l\'installation, vérifier votre configuration PHP',
	'prepend_ok'			=>	'Inutilisé',
	'magic_quotes'			=>	'magic_quotes_gpc',
	'magic_quotes_ok'		=>	'Désactivé',
	'magic_quotes_error'	=>	'Vous avez <code>magic_quotes_gpc</code> activé, veuillez le désactiver. Cette fonctionnalité est devenue OBSOLETE depuis PHP 5.3 et a été SUPPRIMEE depuis PHP 5.4.',

	'curl'					=>	'cURL',
	'curl_ok'				=>	'Installé',
	'curl_error'			=>	'Votre configuration PHP ne dispose pas de <code>cURL</code>. Veuillez l\'installer.',

	'ziparchive'			=>	'ZipArchive',
	'ziparchive_ok'		=>	'Installé',
	'ziparchive_error'	=>	'Votre configuration PHP ne dispose pas de la classe <code>ZipArchive</code>. Vous pouvez tout de même installer NinjaFirewall, mais vous ne pourrez pas installer les mises à jour depuis l\'interface d\'administration.',

	'confdir'				=>	'Répertoire de la configuration',
	'confdir_ok'			=>	'Le répertoire <code>conf/</code> est accessible en écriture.',
	'confdir_error'		=>	'Le répertoire <code>conf/</code> est en lecture seule. Veuillez changer ses permissions afin qu\'il soit accessible en écriture.',

	'logdir'					=>	'Répertoire des journaux',
	'logdir_ok'				=>	'Le répertoire <code>nfwlog/</code> est accessible en écriture.',
	'logdir_error'			=>	'Le répertoire <code>nfwlog/</code> est en lecture seule. Veuillez changer ses permissions afin qu\'il soit accessible en écriture.',

	'error_conf'			=> 'Impossible d\'écrire dans le fichier de configuration <code>/conf/options.php</code>. ' .
								'Assurez-vous que ce fichier n\'est pas en lecture seule.',

	'notes'					=>	'Notes de version',

	'license'				=>	'Licence',
	'license_accept'		=>	'Veuillez accepter les termes de la Licence pour poursuivre l\'installation.',
	'license_checkbox'	=>	'J\'accepte les termes de la Licence.',

	'account'				=>	'Administrateur',
	'admin_name'			=>	'Nom du compte de l\'administrateur (6 à 20 caractères)',
	'admin_pass'			=>	'Mot de passe de l\'administrateur (6 à 20 caractères)',
	'admin_pass2'			=>	'Retaper le mot de passe',
	'admin_email'			=>	'Adresse e-mail de l\'administrateur',
	'nf_license'			=>	'Entrez la clé de votre licence NinjaFirewall Pro',


	'js_admin_name'		=>	'Veuillez entrer le nom de compte de l\'administrateur.',
	'js_admin_name_char'	=>	'Le nom de compte de l\'administrateur doit contenir entre 6 et 20 caractères alpha-numeriques ainsi que le caractère (_).',
	'js_admin_pass'		=>	'Veuillez entrer le mot de passe de l\'administrateur.',
	'js_admin_pass_char'	=>	'Le mot de passe de l\'administrateur doit contenir entre 6 et 20 caractères.',
	'js_admin_pass_2'		=>	'Veuillez retaper le mot de passe de l\'administrateur.',
	'js_admin_pass_both'	=>	'Le mot de passe de l\'administrateur doit être le même dans les deux champs.',
	'js_admin_email'		=>	'Veuillez entrer une adresse e-mail valide pour l\'administrateur.',
	'js_license'			=>	'Veuillez entrer la clé votre licence NinjaFirewall Pro.',

	'curl_connect'			=>	'Impossible de se connecter au serveur de NinjaFirewall pour vérifier la licence.',
	'curl_retcode'			=>	'Impossible de se connecter au serveur de NinjaFirewall: erreur HTTP.',
	'curl_empty'			=>	'Impossible de se connecter au serveur de NinjaFirewall: aucune réponse ou réponse vide.',
	'curl_wrong'			=>	'Impossible de se connecter au serveur de NinjaFirewall: les données retournées ne correspondent pas à celles attendues.',
	'err_server'			=>	'Impossible de se connecter au serveur de NinjaFirewall: erreur inattendue.',
	'invalid_lic'			=>	'Votre licence n\'est pas valide (#%s).',

	'integration'			=>	'Intégration',
	'recommended'			=>	' (recommendé)',
	'js_docroot'			=>	'Veuillez entrer le répertoire qui sera protégé par NinjaFirewall.',
	'js_phpini'				=>	'Veuillez sélectionner le type de fichier de configuration PHP utilisé par votre serveur.',
	'docroot'				=>	'Entrez le répertoire qui sera protégé par NinjaFirewall.<br />Par défaut, il s\'agit de <code>%s</code>',
	'httpsapi'				=>	'Sélectionnez votre serveur HTTP et PHP SAPI',
	'other'					=>	'Autre serveur HTTP',
	'phpinfo'				=>	'voir PHPINFO',
	'phpini'					=>	'Sélectionnez le type de fichier de configuration PHP utilisé par votre serveur',
	'ini_1'					=>	'Utilisé par la plupart des hébergements mutualisés',
	'ini_2'					=>	'Utilisé par la plupart des serveurs dédiés et VPS, ainsi que de nombreux hébergements mutualisés n\'utilisant pas les fichiers php.ini',
	'more_info'				=>	'plus d\'info',
	'ini_3'					=>	'Quelques hébergements mutualisés (<a href="https://support.godaddy.com/help/article/8913/what-filename-does-my-php-initialization-file-need-to-use">Godaddy</a>). Rarement utilisé',

	'invalid_docroot'		=>	'<code>%s</code> n\'est pas valide.',
	'ninja_docroot'		=> 'Le répertoire à protéger doit inclure le répertoire où est installé NinjaFirewall.',

	'hhvm_doc'				=> '>Veuillez consulter notre blog afin d\'installer NinjaFirewall avec HHVM.',

	'activation'			=>	'Activation',

	'single_file'			=>	'Afin de protéger votre site, NinjaFirewall nécessite l\'ajout d\'instructions spécifiques au fichier <code>%s</code>.',
	'multi_files'			=>	'Afin de protéger votre site, NinjaFirewall nécessite l\'ajout d\'instructions spécifiques aux fichiers <code>%s</code> et <code>%s</code>.',

	'edit_file'				=>	'Veuillez ajouter les <font color="red">lignes rouges</font> suivantes au fichier <code>%s</code>.<br />Toutes les autres lignes, le cas échéant, sont le contenu actuel du fichier et ne doivent pas être modifiées&nbsp;:',
	'create_file'			=>	'Veuillez créer le fichier <code>%s</code>, et y ajouter les lignes suivantes&nbsp;:',


	'failed'					=>	'Echec !',
	'not_loaded'			=>	'Le pare-feu n\'est pas activé.',
	'suggestions'			=>	'Suggestions:',
	'modphp5tocgi'			=>	'Vous avez sélectionné <code>Apache + module PHP5</code> commer serveur HTTP et PHP SAPI. Peut-être devriez-vous plutôt choisir <code>Apache + CGI/FastCGI</code>&nbsp;?',
	'goback_01'				=>	'Vous pouvez revenir en arrière et essayer de modifier votre sélection',

	'userini_select'		=>	'Vous avez sélectionné <code>.user.ini</code> comme fichier de configuration PHP. Contrairement au fichier <code>php.ini</code>, les changement effectués dans un fichier <code>.user.ini</code> ne sont pas pris en compte immédiatement par PHP, mais environ toutes les 5 minutes. S\'il s\'agit de votre propre serveur, vous pouvez redémarrer Apache (ou PHP-FPM si vous utilisez Nginx) pour forcer PHP a le recharger immédiatement, sinon veuillez patienter quelques minutes avant de cliquer sur le bouton ci-dessous pour relancer le test.',

	'test_it'				=>	'Après avoir effectué ces changements, veuillez cliquer sur le bouton ci-dessous pour tester NinjaFirewall.',
	'test_button'			=>	'Tester NinjaFirewall',

	'cgitomodphp5'			=>	'Vous avez sélectionné <code>Apache + CGI/FastCGI</code> commer serveur HTTP et PHP SAPI. Peut-être devriez-vous plutôt choisir <code>Apache + module PHP5</code>&nbsp;?',
	'wrong_ini'				=>	'Vous avez peut-être sélectionné un mauvais type de fichier de configuration PHP.',
	'try_again'				=>	'Ré-essayer',
	'goback_02'				=>	'Vous pouvez revenir en arrière et essayer de choisir un autre type de fichier INI',
	'need_help'				=>	'Besion d\'aide ? Consultez notre blog :',

	'error'					=>	'Erreur !',
	'error_loaded'			=>	'NinjaFirewall est bien chargé, mais il a retourné une erreur&nbsp;:',
	'error_restart'		=>	'Veuillez recommencer l\'installation.',

	'it_works'				=>	'OK !',

	'congrats'				=>	'Félicitations, NinjaFirewall est installé et fonctionnel&nbsp;!',
	'redir_admin'			=>	'Cliquez sur le bouton ci-dessous afin d\'être redirigé vers la page d\'accueil de son interface d\'administration.',

	'error_conf'			=> 'Impossible d\'écrire dans le fichier de configuration <code>/conf/options.php</code>. ' .
									'Assurez-vous que ce fichier n\'est pas en lecture seule.',
	'error_rules'			=> 'Impossible d\'écrire dans le fichier de configuration <code>/conf/rules.php</code>. ' .
									'Assurez-vous que ce fichier n\'est pas en lecture seule.',


	'mail_subject'			=>	'Guide d\'Utilisation, Installation et Dépannage',
	'hi'						=>	'Bonjour,',
	'hi2'						=>	'Je suis l\'installateur de NinjaFirewall. Voici quelques informations et liens qui pourraient vous être utiles (en langue anglaise).',

	'hi3'						=>	'Dépannage :',
	'hi4'						=>	'-Échec de l\'installation ("Erreur : le pare-feu n\'est pas activé.") ?',
	'hi5'						=>	'-Comment désactiver NinjaFirewall ?',
	'hi6'						=>	'-Vos visiteurs sont bloqués par erreur ?',
	'hi7'						=>	'-Vous avez perdu le mot de passe de votre interface d\'administration ?',

	'hi8'						=>	'Script de dépannage (Pro/Pro+ Edition) :',
	'hi9'						=>	'-Renommez ce fichier en "pro-check.php".',
	'hi10'					=>	'-Téléchargez-le dans le repertoire racine de votre site.',
	'hi11'					=>	'-Rendez-vous sur http://YOUR WEBSITE/pro-check.php.',
	'hi12'					=>	'-Supprimez-le lorsque vous avez fini.',

	'hi13'					=>	'FAQ:',
	'hi14'					=>	'-Ai-je besoin d\'avoir les privilèges root pour installer NinjaFirewall ?',
	'hi15'					=>	'-Est-ce qu\'il fonctionne avec Nginx ?',
	'hi16'					=>	'-Dois-je modifier mes script PHP ?',
	'hi17'					=>	'-Est-ce que NinjaFirewall détectera la bonne adresse IP de mes visiteurs si j\'utilise un service CDN comme Cloudflare ou Incapsula ?',
	'hi18'					=>	'-Est-ce qu\'il va ralentir mon site ?',
	'hi19'					=>	'-Existe-t-il une version pour Windows ?',
	'hi20'					=>	'-Puis-je ajouter / écrire mes propres règles de sécurité ?',
	'hi21'					=>	'-Est-ce que je peux migrer mon site lorsque NinjaFirewall est installé ?',
	'hi22'					=>	'-Comment protéger Joomla avec NinjaFirewall ?',

	'hi23'					=>	'A lire aussi :',
	'hi24'					=>	'-Tester NinjaFirewall sans bloquer vos visiteurs :',
	'hi25'					=>	'-Ajoutez votre code au pare-feu: le fichier ".htninja".',
	'hi25b'					=>	'-Mise à niveau de PHP 5 vers PHP 7 avec NinjaFirewall installé.',

	'hi26'					=>	'Aide & Support :',
	'hi27'					=>	'-Si vous avez besoin d\'aide, cliquez sur l\'onglet "Aide" situé dans le coin supérieur droit de chaque page.',
	'hi28'					=>	'-Les info de mise à jour sont disponible via Twitter :',


);

