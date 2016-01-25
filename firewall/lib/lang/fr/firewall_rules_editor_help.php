<?php
/* 2014-09-14 19:56:17 */
$title = 'Pare-Feu > Editeur de Règles';
$close = 'Fermer';
$nfw_help = <<<'EOT'

<h3><strong>Editeur de Règles</strong></h3>
<p>En plus des options configurables dans les menus "Contrôle d'Accès" et "Politique" du pare-feu, NinjaFirewall dispose aussi de règles de sécurité spécifiques aux vulnérabilités les plus communes. Elle sont actives par défaut et vous ne pouvez pas les éditer. Cependant, elles peuvent être désactivées individuellement, notamment dans le cas où l'une d'entre-elles bloquerait certains de vos visiteurs par erreur&nbsp;:</p>
<p><img src="static/bullet_off.gif">&nbsp;Regardez dans le journal du pare-feu et relevez l'identifiant de la règle a désactiver (il est affiché dans la colonne <code>RULE</code>).</p>
<p><img src="static/bullet_off.gif">&nbsp;Selectionnez cette identifiant dans l'editeur de règles et cliquez sur le bouton correspondant pour la désactiver.</p>

<p><img src="static/icon_warn.png">&nbsp;Si la colonne <code>RULE</code> du journal affiche un trait d'union <code>-</code> à la place d'un numéro, cela signifie que la règle provient soit de votre configuration personnelle modifiable dans les menus "Politique" ou "Contrôle d'Accès" du pare-feu.</p>

EOT;

