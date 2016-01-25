<?php
/* 2015-10-28 19:05:53 */
$lang = array (
	'error'				=> 'Erreur',
	'yes'					=> 'Oui',
	'no'					=> 'Non',
	'default'			=> ' (defaut)',
	'disabled'			=> 'Désactivé',
	'eg'					=> 'ex:',

	'policies'			=>	'Politique',

	'http_title'		=>	'HTTP / HTTPS',
	'http_enable'		=>	'Activer NinjaFirewall pour le trafic',
	'http_https'		=>	'HTTP et HTTPS',
	'http'				=>	'HTTP seulement',
	'https'				=>	'HTTPS seulement',

	'numbers_only'		=>	'Veuillez entrer des chiffres uniquement.',

	'upload_title'		=>	'Téléchargements',
	'uploads'			=>	'Autoriser les téléchargements (uploads)',
	'disallow_upl'		=>	'Ne pas autoriser',
	'allow_upl'			=>	'Autoriser',
	'allow_but'			=>	'Autoriser, mais bloquer les scripts, fichiers ELF et systèmes',

	'sanit_fn'			=>	'Nettoyer le nom des fichiers',
	'mxsize_fn'			=>	'Taille maximale autorisée',
	'kb'					=>	'Ko',

	'http_get'			=>	'Variable HTTP GET',
	'scan_get'			=>	'Filtrer la variable GET',
	'sanit_get'			=>	'Nettoyer la variable GET',

	'http_post'			=>	'Variable HTTP POST',
	'scan_post'			=>	'Filtrer la variable POST',
	'sanit_post'		=>	'Nettoyer la variable POST',
	'sanit_warn'		=> 'N\'activez pas cette option si vous n\'êtes pas sûr de ce que vous faites&nbsp;!',
	'decode_b64'		=>	'Décoder les chaîne encodées en base64 dans la variable POST',


	'http_request'		=>	'Variable HTTP REQUEST',
	'sanit_request'	=>	'Nettoyer la variable REQUEST',

	'cookies'			=>	'Cookies',
	'scan_cookies'		=>	'Filtrer les cookies',
	'sanit_cookies'	=>	'Nettoyer les cookies',

	'ua'					=>	'Variable HTTP_USER_AGENT',
	'scan_ua'			=>	'Filtrer HTTP_USER_AGENT',
	'sanit_ua'			=>	'Nettoyer HTTP_USER_AGENT',
	'block_ua'			=>	'Bloquer les requêtes POST des navigateurs qui n\'ont pas',
	'mozilla_ua'		=>	'Une signature compatible <i>Mozilla</i>',
	'accept_ua'			=>	'Un en-tête HTTP_ACCEPT',
	'accept_lang_ua'	=>	'Un en-tête HTTP_ACCEPT_LANGUAGE',
	'suspicious_ua'	=>	'Bloquer les bots/scanners suspects',

	'post_warn'			=>	'N\'activez pas cette option si vous utilisez des scripts comme Paypal IPN (sauf si vous avez ajouté son IP ou URL dans le menu <i>Contrôle d\'Accès</i>).',

	'posts_warn'		=>	'N\'activez pas ces options si vous utilisez des scripts comme Paypal IPN (sauf si vous avez ajouté son IP ou URL dans le menu <i>Contrôle d\'Accès</i>).',

	'referer'			=>	'Variable HTTP_REFERER',
	'scan_referer'		=>	'Filtrer HTTP_REFERER',
	'sanit_referer'	=>	'Nettoyer HTTP_REFERER',
	'post_referer'		=>	'Bloquer les requêtes POST qui n\'ont pas d\'en-tête HTTP_REFERER',

	'httponly_warn'	=>	'Si vos scripts PHP envoient des cookies qui doivent être accessibles à partir de JavaScript, vous devez garder cette option désactivée. Continuer ?',
	'httpresponse'		=>	'En-têtes de réponse HTTP',
	'x_c_t_o'			=>	'Activer X-Content-Type-Options pour protéger contre les attaques basées sur la confusion du type MIME',
	'x_f_o'				=>	'Activer X-Frame-Options pour protéger contre les attaques de détournement de clic (clickjacking)',
	'x_x_p'				=>	"Activer X-XSS-Protection pour utiliser les filtres anti-XSS des navigateurs (IE, Chrome et Safari)",
	'httponly'			=>	'Active la propriété HttpOnly pour tous les cookies afin d\'atténuer les menaces XSS qui génèrent des vols de cookies',
	'missing_funct'	=>	'Cette option n\'est pas disponibles parce que la fonction PHP %s n\'est pas disponible sur votre serveur.',
	'hsts'				=>	'Activer Strict-Transport-Security (HSTS) pour forcer les connexions sécurisées vers le serveur',
	'reset'				=>	'Réinitialiser <code>max-age</code> à 0',
	'1_month'			=>	'1 mois',
	'6_months'			=>	'6 mois',
	'1_year'				=>	'1 année',
	'subdomain'			=>	'Appliquer aux sous-domaines',
	'hsts_warn'			=>	'Les en-têtes HSTS ne peuvent être utilisés que lorsque vous vous connectez à votre site en HTTPS (connexion sécurisée).',

	'php'					=>	'PHP',
	'wrapper'			=>	'Bloquer les gestionnaires (wrappers) PHP dangereux',
	'php_error'			=>	'Masquer les messages d\'erreur de PHP',
	'php_self'			=>	'Nettoyer PHP_SELF',
	'php_ptrans'		=>	'Nettoyer PATH_TRANSLATED',
	'php_pinfo'			=>	'Nettoyer PATH_INFO',

	'various'			=> 'Divers',
	'block_docroot'	=>	'Bloquer les requêtes HTTP contenants la variable DOCUMENT_ROOT',
	'block_nullbye'	=>	'Bloquer le caractère ASCII 0x00 (NULL byte)',
	'block_ascii'		=>	'Bloquer les caractères de contrôle ASCII 1 à 8 et 14 à 31',
	'block_lo'			=>	'Bloquer les requêtes GET/POST contenants l\'IP localhost',
	'block_iphost'		=>	'Bloquer les requêtes HTTP dont l\'en-tête HTTP_HOST contient une IP',
	'block_method'		=>	'Accepter les méthodes HTTP suivantes',
	'get_post_head'	=>	'GET, POST et HEAD uniquement',
	'all_method'		=> 'Toutes les méthodes HTTP',

	'disabled_msg'		=> 'Cette option n\'est pas compatible avec la configuration de votre serveur.',

	'default_js'		=>	'Tous les champs vont être réinitialisés avec leur valeur par défaut. Continuer ?',
	'default_button'	=>	'Rétablir les valeurs par defaut',

	'save_conf'			=> 'Sauvegarder les modifications',
	'saved_conf'		=> 'Les modifications ont été enregistrées',
	'error_conf'		=> 'Impossible d\'écrire dans le fichier de configuration <code>/conf/options.php</code>. Assurez-vous que ce fichier n\'est pas en lecture seule.',
	'error_rules'		=> 'Impossible d\'écrire dans le fichier de configuration <code>./conf/rules.php</code>. Assurez-vous que ce fichier n\'est pas en lecture seule.',
);
