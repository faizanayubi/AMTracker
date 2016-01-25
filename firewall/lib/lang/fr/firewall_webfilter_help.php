<?php
/* 2015-08-13 19:47:57 */
$title = 'Pare-Feu > Web Filter';
$close = 'Fermer';
$nfw_help = <<<'EOT'

<h3><strong>Web Filter</strong></h3>
Si NinjaFirewall peut filtrer les requêtes HTTP entrantes, il peut aussi intercepter les requêtes sortantes, c'est à dire le contenu de la page HTML juste avant que celui-ci ne soit envoyé au navigateur de l'utilisateur. Ce type de filtre est particulièrement intéressant pour détecter du code malveillant injectés dans vos pages HTML (texte, liens, code JavaScript etc), des scripts utilisés par les hackers (shell, backdoor) et même des erreurs (PHP, MySQL).
<br />
En cas de détection positive, NinjaFirewall ne bloquera pas la requête mais vous enverra immédiatement une alerte par e-mail.

<p><img src="static/bullet_off.gif">&nbsp;<strong>Rechercher les mots suivants dans la page HTML&nbsp;:</strong> vous pouvez entrer de 4 à 150 caractères et sélectionner si la recherche est sensible à la casse.</p>
<p><img src="static/bullet_off.gif">&nbsp;<strong>Alertes E-mail :</strong> l'alerte sera envoyée à l'adresse indiquée dans le menu "Compte > Options > Contact". La page HTML ayant déclenché cette alerte peut être jointe à l'e-mail.</p>

<p><img src="static/icon_warn.png">&nbsp;Filtrer les requêtes sortantes peut être gourmand en ressources. Essayez de limiter le nombre de mots-clés (<10) et/ou, si possible, préférez la recherche sensible à la casse.</p>

EOT;

