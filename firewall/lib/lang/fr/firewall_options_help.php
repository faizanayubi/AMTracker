<?php
/* 2015-04-22 19:10:58 */
$title = 'Pare-Feu > Options';
$close = 'Fermer';
$nfw_help = <<<'EOT'

<h3><strong>Options</strong></h3>

<p><img src="static/bullet_off.gif">&nbsp;<strong>Pare-feu :</strong> cette option vous permet de rapidement désactiver le pare-feu. Votre site ne sera plus protégé par NinjaFirewall durant cette période.</p>
<p><img src="static/icon_warn.png">&nbsp;Si vous utilisez le fichier <code>.htninja</code>, celui-ci ne sera pas affecté par cette option. Si vous souhaitez le désactiver aussi, vous devrez soit le renommer, soit le supprimer.</p>

<br />

<p><img src="static/bullet_off.gif">&nbsp;<strong>Mode débogage :</strong> lorsque ce mode est activé, NinjaFirewall ne bloque pas les requêtes mais les enregistre uniquement dans le journal du pare-feu (les lignes correspondantes seront indiquée par la mention <code>DEBUG_ON</code> dans la colonne <code>LEVEL</code> du journal).
<br />
Nous vous conseillons de laisser NinjaFirewall en Mode débogage pendant 24 heures après son installation sur un nouveau site afin de vous assurer que tout fonctionne bien. Vous pourrez pendant cette période consulter le journal du pare-feu pour y voir les éventuels problèmes et, le cas échéant, désactiver les options ou règles pouvant créer des faux-positifs.</p>

<br />

<p><img src="static/bullet_off.gif">&nbsp;<strong>Code HTTP à retourner &amp; Message pour les utilisateurs bloqués&nbsp;:</strong> vous permet de choisir le code HTTP que vous souhaitez que NinjaFirewall retourne lorsqu'il bloque une requête dangereuse ansi que le message à afficher à l'utilisateur bloqué. Vous pouvez utiliser le language HTML ainsi que les 2 variables suivantes&nbsp;:</p>
<ul>
	<p><img src="static/bullet_off.gif">&nbsp;<code>%%REM_ADDRESS%%</code> : l'adresse IP de l'utilisateur.</p>
	<p><img src="static/bullet_off.gif">&nbsp;<code>%%NUM_INCIDENT%%</code> : le numéro d'incident, tel qu'il apparaîtra dans la colonne <code>INCIDENT</code> du journal du pare-feu.</p>
</ul>

<br />

<p><img src="static/bullet_off.gif">&nbsp;<strong>Bannir les IP incriminées :</strong> en plus de rejeter les requêtes dangereuses, NinjaFirewall peut aussi bannir l'adresse IP responsable de l'incident pendant un laps de temps prédéfini, et suivant le niveau de risque estimé (moyen, élevé, critique). Une IP peut être bloquée pendant 1 à 999 minutes.
<br />
Pour débloquer une ou plusieurs adresses IP, sélectionnez-les dans la liste puis cliquez "Sauvegarder les modifications".
<br />
Par défaut, cette option est désactivée.</p>

EOT;

