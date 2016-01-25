<?php
/* 2015-08-13 19:07:15 */
$title = 'Pare-Feu > Politique';
$close = 'Fermer';
$nfw_help = <<<'EOT'

Parcequ'il agit en amont de votre application, NinjaFirewall peut intercepter, scanner, nettoyer et bloquer les requêtes HTTP envoyées à un script PHP, ainsi que les variables (<code>$_GET</code>, <code>$_POST</code>, <code>$_COOKIES</code>, <code>$_REQUEST</code>, <code>$_FILES</code>, <code>$_SERVER</code>), en-têtes et adresses IP, avant que celles-ci n'atteignent votre application, que ce soit en mode HTTP ou HTTPS.

<p>Utilisez les options ci-dessous pour configurer NinjaFirewall suivant vos besoins.</p>


<hr class="dotted" size="1">

<h3><strong>Filtrer &amp; Nettoyer</strong></h3>
Vous pouvez choisir de filtrer et rejeter les requêtes HTTP dangereuses, mais aussi de les nettoyer. Ces deux actions sont différentes et peuvent être combinées pour plus de sécurité.<br />
<p><img src="static/bullet_off.gif">&nbsp;<strong>Filtrer :</strong> lorsqu'il détecte une requête dangereuse, NinjaFirewall la bloque et retourne un message et code d'erreur HTTP. La requête ne pourra pas aboutir et la connexion sera fermée immédiatement.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Nettoyer :</strong> cette action ne bloque pas la requête mais l'analyse afin d'y trouver des caractères pouvant être dangereux, par exemple pour injecter du code dans la base de données (<code>'</code>, <code>"</code>, <code>\</code>, <code>\n</code>, <code>\r</code>, <code>`</code>, <code>\x1a</code>, <code>\x00</code>) et, le cas échéant, nettoie cette requête en y insérant des caractères d'échappement. S'il s'agit d'une variable et de sa valeur (<code>?variable=valeur</code>), les deux éléments seront nettoyés.<br />
Veuillez noter que cette action est effectuée en dernier, après le filtrage, juste avant que NinjaFirewall fasse suivre la requête à votre application PHP.</p>


<hr class="dotted" size="1">

<h3><strong>HTTP / HTTPS</strong></h3>

<p><img src="static/bullet_off.gif">&nbsp;Sélectionnez le type de trafic filtré par le pare-feu (HTTP et/ou HTTPS).</p>


<hr class="dotted" size="1">

<h3><strong>Téléchargements</strong></h3>

<p><img src="static/bullet_off.gif">&nbsp;<strong>Autoriser les téléchargements :</strong> vous pouvez autoriser ou interdire les téléchargements de fichier vers votre site, ou bien les autoriser sauf s'il s'agit de fichiers potentiellement dangereux&nbsp;: scripts (PHP, CGI, Ruby, Python, bash/shell), code source C/C++, ELF (fichiers exécutables pour Unix/Linux) et certains fichiers systèmes (<code>.htaccess</code>, <code>.htpasswd</code> et PHP INI).</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Nettoyer le nom des fichiers :</strong> si le nom du fichier contient un caractère qui n'est pas une lettre <code>a-zA-Z</code>, un chiffre <code>0-9</code>, un point <code>.</code>, un trait d'union <code>-</code> ou un caractère de soulignement <code>_</code>, celui-si sera remplacé par le caractère <code>X</code>.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Taille maximale autorisée :</strong> vous pouvez sélectionner la taille maximale d'un fichier téléchargé. Tout fichier plus grand que cette taille sera rejeté. Notez que si votre configuration de PHP utilise la directive <code>upload_max_filesize</code>, celle-ci sera prioritaire.</p>

<hr class="dotted" size="1">

<h3><strong>Variable HTTP GET</strong></h3>

<p><img src="static/bullet_off.gif">&nbsp;S'il faut filtrer/nettoyer la variable <code>GET</code>.</p>

<hr class="dotted" size="1">

<h3><strong>Variable HTTP POST</strong></h3>

<p><img src="static/bullet_off.gif">&nbsp;S'il faut filtrer/nettoyer la variable <code>POST</code>.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Décoder les chaîne encodées en base64 dans la variable <code>POST</code>&nbsp;:</strong> NinjaFirewall peut décoder et filtrer les chaînes de caractères encodées en base64 afin d'y détecter du code malveillant caché.</p>

<hr class="dotted" size="1">

<h3><strong>Variable HTTP REQUEST</strong></h3>

<p><img src="static/bullet_off.gif">&nbsp;S'il faut filtrer/nettoyer la variable <code>REQUEST</code>.</p>


<hr class="dotted" size="1">

<h3><strong>Cookies</strong></h3>

<p><img src="static/bullet_off.gif">&nbsp;S'il faut filtrer/nettoyer la variable <code>COOKIE</code>.</p>

<hr class="dotted" size="1">

<h3><strong>Variable HTTP_USER_AGENT</strong></h3>
<p><img src="static/bullet_off.gif">&nbsp;S'il faut filtrer/nettoyer la variable <code>HTTP_USER_AGENT</code>.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Bloquer les requêtes POST des navigateurs qui n'ont pas&nbsp;:</strong> ces 3 options peuvent aider à bloquer les crawlers, spambots et autres scrappers.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Bloquer les bots/scanners suspects (Pro edition uniquement)&nbsp;:</strong> cette option peut bloquer de nombreux crawlers, spambots et autres scrappers.</p>

<hr class="dotted" size="1">

<h3><strong>Variable HTTP_REFERER</strong></h3>

<p><img src="static/bullet_off.gif">&nbsp;S'il faut filtrer/nettoyer la variable <code>HTTP_REFERER</code>.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Bloques les requêtes POST qui n'ont pas d'en-tête <code>HTTP_REFERER</code>&nbsp;:</strong> cette option bloque toutes les requêtes <code>POST</code> qui n'incluent pas le Referrer (adresse de la page -si elle existe- qui a conduit le client à la page courante). Puisque les requêtes <code>POST</code> ne sont pas obligées d'avoir un Referrer, cette option n'est pas activée par défaut.</p>

<hr class="dotted" size="1">

<h3><strong>En-têtes de réponse HTTP</strong></h3>

<p>En plus de filtrer les requêtes entrantes, NinjaFirewall peut aussi intercepter la réponse HTTP afin de modifier ses en-têtes. Ces modifications peuvent aider à atténuer les menaces telles que les attaques XSS, phishing et clickjacking.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Activer <code>X-Content-Type-Options</code> pour protéger contre les attaques basées sur la confusion du type MIME:</strong> les éléments script et styleSheet rejettent les réponses avec des types MIME incorrects si le serveur envoie l’en-tête de réponse <code>X-Content-Type-Options: nosniff</code>. Il s’agit d’une fonctionnalité de sécurité qui facilite la prévention des attaques basées sur la confusion du type MIME.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Activer <code>X-Frame-Options</code> pour protéger contre les attaques de détournement de clic (clickjacking):</strong> cet en-tête informe le navigateur s'il doit autoriser ou non l'affichage d'une page dans une <code>&lt;frame&gt;</code> ou <code>&lt;iframe&gt;</code>. Cela permet d'empêcher les attaques de clickjacking, en veillant à ce que le contenu d'une page ne sont pas intégré dans d'autres pages ou cadres, notamment d'un autre site. NinjaFirewall accepte deux valeurs différentes:</p>
<ul>
	<li><code>SAMEORIGIN</code>: un navigateur ne doit pas afficher le contenu dans une <code>&lt;frame&gt;</code> ou <code>&lt;iframe&gt;</code> d'une page d'origine différente que le contenu lui-même.</li>
	<li><code>DENY</code>: un navigateur ne doit jamais afficher le contenu dans une <code>&lt;frame&gt;</code> ou <code>&lt;iframe&gt;</code>.</li>
</ul>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Activer <code>X-XSS-Protection</code> pour utiliser les filtres anti-XSS des navigateurs (IE, Chrome et Safari):</strong> cet en-tête permet aux navigateurs compatibles d'identifier et bloquer les attaques XSS en empêchant un script malveillant de s'exécuter. NinjaFirewall envoie cet en-tête avec la valeur <code>1; mode=block</code>.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Activer la propriété <code>HttpOnly</code> pour tous les cookies afin d'atténuer les menaces XSS qui génèrent des vols de cookies:</strong> cette protection permet de diminuer les risques d'attaques XSS en empêchant JavaScript d'accéder aux cookies de l'utilisateur. NinjaFirewall peut intercepter les cookies envoyés par vos scripts PHP, activer la propriété <code>HttpOnly</code> si elle est manquante, puis réinjecter les cookies dans la réponse HTTP juste avant que celle-ci ne soit envoyée à vos visiteurs.</p>
<p><img src="static/icon_warn.png">&nbsp;Si vos scripts PHP envoient des cookies qui doivent être accessibles à partir de JavaScript, vous devez garder cette option désactivée.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Activer <code>Strict-Transport-Security</code> (HSTS) pour forcer les connexions sécurisées vers le serveur:</strong> cette politique force les connexions HTTPS sécurisées vers le serveur. Les navigateurs n'accepteront pas de se connecter au site si la connexion n'est pas sécurisée (HTTPS). Cela permet de se défendre contre les détournements de cookies ou les attaques du type "Man-in-the-middle". La plupart des navigateurs récents sont compatibles avec <code>Strict-Transport-Security</code>.</p>

<hr class="dotted" size="1">

<h3><strong>PHP</strong></h3>

<p><img src="static/bullet_off.gif">&nbsp;<strong>Bloquer les gestionnaires (wrappers) PHP dangereux&nbsp;:</strong> PHP dispose de nombreux gestionnaires pour différents types de protocoles de style URL, à utiliser avec les fonctions de manipulation de fichiers. Il est possible pour un hacker de les utiliser afin de passer outre un pare-feu ou plugin de sécurité afin d'exploiter une vulnérabilité dans un script (RFI/LFI etc). Cette option détecte et bloque toute tentative d'utilisation de <code>php://</code> ou <code>data://</code> dans une requête <code>GET</code> ou <code>POST</code>, des cookies, ou dans les variables <code>HTTP_REFERER</code> et <code>HTTP_USER_AGENT</code>.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Masquer les messages d'erreur de PHP&nbsp;:</strong> vous permet de masquer les erreurs retournées par PHP. Ces erreurs peuvent afficher des informations sensibles qui peuvent être exploitées ultérieurement par des pirates.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Nettoyer <code>PHP_SELF</code>, <code>PATH_TRANSLATED</code>, <code>PATH_INFO</code>:</strong> activez ces options si vous souhaitez que le pare-feu nettoie ces trois variables.</p>

<hr class="dotted" size="1">

<h3><strong>Divers</strong></h3>

<p><img src="static/bullet_off.gif">&nbsp;<strong>Bloquer les requêtes HTTP contenant la variable <code>DOCUMENT_ROOT</code>&nbsp;:</strong> cette option bloque toute tentative de passer la variable <code>DOCUMENT_ROOT</code> dans une requête <code>GET</code> ou <code>POST</code>. Les pirates utilisent souvent des scripts qui nécessitent d'utiliser cette variable, mais pas la plupart des applications légitimes (hormis certains scripts d'installation ou de configuration).

<p><img src="static/bullet_off.gif">&nbsp;<strong>Bloquer le caractère ASCII 0x00 (NULL byte) :</strong> toute requête <code>GET</code> ou <code>POST</code>, ainsi que toute variable <code>COOKIE</code>, <code>HTTP_USER_AGENT</code>, <code>REQUEST_URI</code>, <code>PHP_SELF</code>, <code>PATH_INFO</code> contenant le caractère ASCI 0x00 (NULL byte) sera bloquée immédiatement. Ce caractère est dangereux et devrait toujours être rejeté.

<p><img src="static/bullet_off.gif">&nbsp;<strong>Bloquer les caractères de contrôle ASCII 1 à 8 et 14 à 31&nbsp;:</strong> dans la plupart des cas, ces caractères de contrôle de l'ASCII ne sont pas nécessaires et devraient être rejetés.

<p><img src="static/bullet_off.gif">&nbsp;<strong>Bloquer les requêtes <code>GET/POST</code> contenant l'IP localhost&nbsp;:</strong> cette option bloque toute requête <code>GET</code> ou <code>POST</code> contenant l'IP localhost (127.0.0.1). Cela peut s'avérer utile pour bloquer les programmes malveillants. Attention toutefois à ne pas bloquer certains scripts d'installation ou de configuration si vous l'activez.

<p><img src="static/bullet_off.gif">&nbsp;<strong>Bloquer les requêtes HTTP dont l'en-tête <code>HTTP_HOST</code> contient une IP&nbsp;:</strong> cette option rejette toute requête HTTP contenant une adresse IP au lieu d'un nom de domaine dans son en-tête <code>Host</code>. Sauf si vous avez besoin de vous connecter à votre site en utilisant son adresse IP (ex: http://172.16.0.1/index.php), activer cette option bloquera de nombreux scanners de vulnérabilité car ces application trouvent les sites en scannant les plages d'adresses IP plutôt que les noms de domaine.

<p><img src="static/bullet_off.gif">&nbsp;<strong>Accepter les méthodes HTTP :</strong> par défaut, NinjaFirewall n'accepte que les méthodes <code>GET</code>, <code>POST</code> et <code>HEAD</code>.

EOT;

