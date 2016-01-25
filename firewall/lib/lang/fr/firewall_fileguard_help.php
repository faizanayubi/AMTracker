<?php
/* 2015-02-25 18:11:39 */
$title = 'Pare-Feu > File Guard';
$close = 'Fermer';
$nfw_help = <<<'EOT'

<h3><strong>File Guard</strong></h3>
File Guard peut détecter, en temps réel, tout accès à un script PHP ayant été créé ou modifié récemment, et vous en alerter par e-mail.

<p>Si un hacker injecte une backdoor dans l'un de vos scripts ou bien en télecharge une sur votre site, dès qu'il tentera d'y accéder avec son navigateur ou toute autre application, NinjaFirewall interceptera la requête et détectera immédiatement que ce script a été créé/modifié récemment. Il vous enverra une alerte par e-mail contenant les informations nécessaires (nom du script, IP, requête HTTP, date et heure). L'alerte sera envoyée à l'adresse indiquée dans le menu "Compte > Options > Contact".</p>

<p>Les modifications détectées par NinjaFirewall incluent <code>mtime</code> (création ou modification du contenu d'un fichier) et <code>ctime</code> (propriétés, permissions etc).</p>

<p>Si vous souhaitez exclure un répertoire, vous pouvez entrer son chemin complet ou une partie de celui-ci (ex. <code>/var/www/public_html/cache/</code>, <code>/cache/</code> etc). NinjaFirewall comparera cette valeur à la variable <code>$_SERVER["SCRIPT_FILENAME"]</code> et, si elle correspond, l'ignorera.</p>

EOT;

