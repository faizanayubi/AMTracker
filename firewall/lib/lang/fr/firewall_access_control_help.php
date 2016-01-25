<?php
/* 2015-08-13 19:31:22 */
$title = 'Pare-Feu > Contrôle d\'Accès';
$close = 'Fermer';
$nfw_help = <<<'EOT'

<h3><strong>Contrôle d'Accès</strong></h3>

Le Contrôle d'Accès est un puissant ensemble de directives qui peut être utilisé pour autoriser ou restreindre l'accès à votre site selon de nombreux critères.

<br />
Afin de pouvoir optimiser son utilisation, il est important de bien comprendre l'ordre dans lequel NinjaFirewall traite ces directives&nbsp;:
<p><img src="static/bullet_off.gif">&nbsp;Requête HTTP entrante :</p>
<ol>
	<li>Fichier de configuration <code><a href="http://nintechnet.com/ninjafirewall/pro-edition/help/?htninja" class="links" style="border-bottom:dotted 1px #FDCD25;" target="_blank">.htninja</a></code>.</li>
	<li>Adresses IP temporairement bloquées ("Pare-Feu > Options > Bannir les IP incriminées").</li>

	<li><strong>Contrôle d'Accès :</strong></li>
	<ol>
		<li>IP autorisées.</li>
		<li>URL autorisées.</li>
		<li>IP bloquées.</li>
		<li>URL bloquées.</li>
		<li>Bot bloqués.</li>
		<li>Géolocalisation.</li>
		<li>Limitation du trafic.</li>
	</ol>

	<li>File Guard.</li>
	<li>Options, Politiques et règles de pare-feu de NinjaFirewall.</li>
</ol>

<p><img src="static/bullet_off.gif">&nbsp;Requête HTTP sortante :</p>
<ol>
	<li>En-têtes de réponse HTTP (Politiques du pare-feu).</li>
	<li>Web Filter.</li>
</ol>

<hr class="dotted" size="1">

<h3><strong>Administrateur</strong></h3>

<p><img src="static/bullet_off.gif">&nbsp;<strong>Ajouter l'Administrateur à la liste blanche :</strong> lors de l'activation de cette option, NinjaFirewall vous ajoutera  à la liste blanche afin que vous ne soyez jamais bloqué par le pare-feu.
<br />
Notez que même si vous vous déconnectez de la console d'administration, vous resterez dans la liste blanche.</p>


<hr class="dotted" size="1">

<h3><strong>IP Source</strong></h3>


<p><img src="static/bullet_off.gif">&nbsp;<strong>Récupérer l'adresse IP des visiteurs :</strong> cette option doit être utilisée si votre site est derrière un reverse proxy, un load balancer ou si vous utilisez un CDN (Cloudflare, Incapsula etc) afin d'indiquer à NinjaFirewall comment retrouver l'adresse IP de vos visiteurs. Par défaut, le pare-feu utilise <code>REMOTE_ADDR</code>. Si vous voulez utiliser <code>HTTP_X_FORWARDED_FOR</code> ou toute autre variable similaire, <font color="red">il est absolument nécessaire de s'assurer qu'elle est fiable</font> (c-à-d, modifiée par votre reverse proxy/load balancer), car elle peut être <a href="http://blog.nintechnet.com/many-popular-wordpress-security-plugins-vulnerable-to-ip-spoofing/" class="links" style="border-bottom:dotted 1px #FDCD25;" target="_blank">facilement falsifiée</a>. Si cette variable contient plus d'une adresse IP, seule la première insérée sera vérifiée. Si elle ne comprend aucune IP utilisable, NinjaFirewall utilisera <code>REMOTE_ADDR</code>.</p>


<p><img src="static/bullet_off.gif">&nbsp;<strong>Filtrer les connexions provenant de localhost et d'adresses IP privées&nbsp;:</strong> cette option vous permet de filtrer le trafic émanant de votre réseau privé. Nous vous recommandons de le garder activé si vous avez 2 ou plusieurs serveurs reliés entre eux.</p>

<hr class="dotted" size="1">

<h3><strong>Méthodes HTTP</strong></h3>

<p><img src="static/bullet_off.gif">&nbsp;<strong>Toutes les directives ci-dessous s'appliquent aux méthodes&nbsp;:</strong> vous pouvez sélectionner les méthodes HTTP. Toutes les directives du Contrôle d'Accès (Geolocalisation, IP, bots et URL) ne s'appliqueront qu'aux méthodes sélectionnées. Cette option ne concerne pas les Politiques du pare-feu.</p>


<hr class="dotted" size="1">

<h3><strong>Contrôle d'accès par Géolocalisation</strong></h3>
Vous pouvez filtrer et bloquer le trafic en provenance de pays spécifiques.

<p><img src="static/bullet_off.gif">&nbsp;<strong>Récupérer le code ISO 3166 du pays :</strong> il s'agit du code à deux lettres qui est utilisé pour définir un pays (par exemple, US, UK, FR, DE, etc), sur la base de l'adresse IP d'un visiteur. NinjaFirewall peut soit le récupérer à partir de sa base de données, ou à partir d'une variable PHP prédéfinie ajoutée par votre serveur HTTP (par exemple, <code>GEOIP_COUNTRY_CODE</code>).</p>


<p><img src="static/bullet_off.gif">&nbsp;<strong>Pays disponibles/bloqués :</strong> vous pouvez ajouter/supprimer les pays à bloquer depuis ce menu. Pour plus d'information au sujet de certains codes ISO 3166 spécifiques (A1, A2, AP, EU etc),vous pouvez consulter <a href="http://dev.maxmind.com/geoip/legacy/codes/iso3166/" class="links" style="border-bottom:dotted 1px #FDCD25;" target="_blank">l'aide en ligne de MaxMind GeoIP</a>. Aucun pays n'est bloqué par défaut.</p>

<p><img src="static/bullet_off.gif">&nbsp;<strong>Ajouter <code>NINJA_COUNTRY_CODE</code> aux en-têtes PHP&nbsp;:</strong> NinjaFirewall peut ajouter le code à deux lettres du pays aux en-têtes PHP, dans la variable <code>$_SERVER["NINJA_COUNTRY_CODE"]</code>. Si vous avez une application PHP qui nécessite de géolocaliser vos visiteurs, activez cette option.</p>

Exemple de code PHP à ajouter à vos scripts pour géolocaliser vos visiteurs&nbsp;:<br />
<center>
	<textarea style="width:100%;height:80px;font-family:monospace;" wrap="off">if (! empty($_SERVER['NINJA_COUNTRY_CODE']) &&
     $_SERVER['NINJA_COUNTRY_CODE'] != '--' ) {
	echo 'Le code de votre pays est : ' . $_SERVER['NINJA_COUNTRY_CODE'];
}</textarea>
</center>

<p><img src="static/icon_warn.png">&nbsp;Si NinjaFirewall ne peut pas identifier le code à deux lettres du pays, il le remplacera par deux traits d'union (<code>--</code>).</p>

<hr class="dotted" size="1">

<h3><strong>Contrôle d'accès par IP</strong></h3>
Vous pouvez immédiatement accepter ou bloquer une adresse IP ou une partie de celle-ci. NinjaFirewall est compatible avec l'IPv4 et l'IPv6.
<br />
Vous devez entrer au moins les 3 premiers caractères d'une IP&nbsp;:

<p><img src="static/bullet_off.gif">&nbsp;<strong>IPv4 :</strong> <code>1.2.3.123</code> ne s'appliquera qu'à l'IP <code><font color="red">1.2.3.123</font></code></p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>IPv4 partielle :</strong> </p>
<ul><img src="static/bullet_off.gif">&nbsp;<code>1.2.3.</code> s'appliquera à toute adresse IP <strong>commençant par</strong> <code>1.2.3.</code> (de <code><font color="red">1.2.3.</font>0</code> à <code><font color="red">1.2.3.</font>255</code>), mais ne s'appliquera pas à <code>2<font color="red">1.2.3.</font>0</code></ul>
<ul><img src="static/bullet_off.gif">&nbsp;<code>1.2.3</code> s'appliquera à toute adresse IP <strong>commençant par</strong> <code>1.2.3</code> (de <code><font color="red">1.2.3</font>.0</code> à <code><font color="red">1.2.3</font>.255</code>, mais aussi <code><font color="red">1.2.3</font>4.56</code> etc), mais ne s'appliquera pas à <code>4.<font color="red">1.2.3</font></code></ul>
Les mêmes règles s'appliquent aux adresses IPv6. Les masques de sous réseaux (ex: 66.155.0.0/17) ne sont pas pris en charge.

<p><img src="static/bullet_off.gif">&nbsp;<strong>Limiter le trafic :</strong> cette option vous permet de ralentir les bots, crawlers, web scrapers ou même de petites attaques HTTP. Toute IP dépassant la limite fixée sera bannie de 1 à 999 secondes. Notez que le but de cette option est de bloquer temporairement une adresse IP. Si vous souhaitez bloquer définitivement une adresse IP, utilisez le menu "Bloquer les IP" ci-dessus.</p>
<p><img src="static/icon_warn.png">&nbsp;Les adresses IP temporairement bannies par cette option peuvent être débloquées simplement en cliquanr sur les boutons "Sauvegarder les modifications" ou "Rétablir les valeurs par defaut" situés en bas de la page.</p>

<hr class="dotted" size="1">

<h3><strong>Contrôle d'accès par URL</strong></h3>
Vous pouvez immédiatement accepter ou bloquer tout accès à un script PHP en se basant sur son nom ou le(s) répertoire(s) de lequel il se trouve (<code>SCRIPT_NAME</code>). Vous pouvez entrer l'URL complète ou partielle, sensible à la casse.


<p><img src="static/bullet_off.gif">&nbsp;<code>/foo/bar.php</code> autorisera/bloquera tout accès au script PHP <code>bar.php</code> situé dans un répertoire <code>/foo/</code> (<code>http://domaine.tld/foo/bar.php</code>, <code>http://domaine.tld/autre/repertoire/foo/bar.php</code> etc).</p>
<p><img src="static/bullet_off.gif">&nbsp;<code>/foo/</code> autorisera/bloquera l'accès à tous les scripts PHP situés dans un répertoire <code>/foo/</code>.</p>


<hr class="dotted" size="1">

<h3><strong>Contrôle d'accès par Bot </strong></h3>
Vous pouvez bloquer les scanners, bots ou autres crawlers en entrant leur nom (<code>HTTP_USER_AGENT</code>) ou partie de celui-ci. La chaîne de caractères est insensible à la casse.


<hr class="dotted" size="1">

<h3><strong>Journaliser</strong></h3>
Vous pouvez activer/désactiver la journalisation des directives indépendamment les unes des autres.
<br />
<br />
<br />
<br />
<div align="right" style="font-size:11px;color:#999999;">NinjaFirewall includes GeoLite data created by MaxMind, available from <a href="http://www.maxmind.com" style="font-size:11px;color:#999999;">http://www.maxmind.com</a></div>

EOT;

