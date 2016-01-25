<?php
/* 2015-02-25 18:11:39 */
$title = 'Pare-Feu > File Check';
$close = 'Fermer';
$nfw_help = <<<'EOT'

<h3><strong>FileCheck</strong></h3>

<p>File Check vous permet d'effectuer une analyse de l'intégrité de vos fichiers sur demande.<br />
Vous devez créer un instantané de tous vos fichiers, puis, ultérieurement, vous pouvez scanner votre système pour le comparer avec l'instantané précédent. Toute modification sera immédiatement détectée&nbsp;: contenu, permissions et propriétés des fichiers, leur création, suppression ainsi que l'horodatage.</p>

<li><strong>Créer un instantané de tous les fichiers se trouvant dans ce répertoire&nbsp;:</strong> par défaut, il s'agit du répertoire parent de NinjaFirewall.</li>

<li><strong>Exclure les fichiers / dossiers suivants&nbsp;:</strong> vous pouvez entrer un répertoire ou un nom de fichier (par ex. <code>/foo/bar/</code>), ou une partie de celui-ci (par ex. <code>foo</code>), ou même exclure une extension de fichier (par ex. <code>.css</code>).<br />Plusieurs valeurs doivent être séparées par des virgules (par ex. <code>/foo/bar/,.css,.png</code>).</li>

<li><strong>Ne pas suivre les liens symboliques&nbsp;:</strong> par défaut, NinjaFirewall ignore les liens symboliques lors de son analyse des fichiers.</li>

EOT;

