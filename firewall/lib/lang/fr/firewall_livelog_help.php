<?php
/* 2015-03-13 23:16:03 */
$title = 'Pare-Feu > Live Log';
$close = 'Fermer';
$nfw_help = <<<'EOT'

<h3><strong>Live Log</strong></h3>
<p>Cette fonctionnalité vous permet de voir, en direct, les connexions en cours en vous les affichant dans un format similaire à celui des journaux des serveurs HTTP (Apache, Nginx etc). Notez que les requêtes concernant les éléments statiques comme les fichiers JS/CSS ou les images ne sont pas traitées par NinjaFirewall.</p>
<p>Vous pouvez activer/stopper le processus, régler l'intervalle de rafraîchissement de la page, activer le défilement vertical automatique, effacer l'écran, changer le format d'affichage et choisir le type de trafic à visualiser (HTTP/HTTPS).</p>

<h3><strong>Format d'affichage</strong></h3>
<p>Vous pouvez facilement changer le format d'affichage en combinant une ou plusieurs des valeurs suivantes :</p>
<ul>
	<li><code>%time</code>: la date, heure et fuseau horaire du serveur.</li>
	<li><code>%name</code>: le nom de l'utilisateur (Authentification HTTP basic), si il existe.</li>
	<li><code>%client</code>: l'adresse IP du client (REMOTE_ADDR). Si votre serveur est derrière un CDN ou un proxy, cette variable retournera l'adresse IP de celui-ci.</li>
	<li><code>%method</code>: la méthode HTTP (i.e., GET, POST).</li>
	<li><code>%uri</code>: l'URI donnée pour accéder à la page (REQUEST_URI).</li>
	<li><code>%referrer</code>: le referrer (HTTP_REFERER), si il existe.</li>
	<li><code>%ua</code>: l'en-tête User_Agent (HTTP_USER_AGENT), si il existe.</li>
	<li><code>%forward</code>: l'en-tête HTTP_X_FORWARDED_FOR, si il existe. Si votre serveur est derrière un CDN ou un proxy, cette variable retournera l'adresse réelle du client.</li>
	<li><code>%host</code>: l'en-tête Host de la requête courante (HTTP_HOST), si elle existe.</li>
</ul>
Vous pouvez aussi utiliser les caractères suivants : <code>"</code>, <code>%</code>, <code>[</code>, <code>]</code>, <code>space</code> et toute lettre minuscule <code>a-z</code>.

<p><img src="static/icon_warn.png">&nbsp;Si vous utilisez le fichier optionnel de configuration <code>.htninja</code> pour toujours accepter les requêtes provenant de votre adresse IP, Live Log ne fonctionnera pas.</p>

EOT;
