<?php

$title = 'Page introuvable';

ob_start();
?>

<h2>404 - Page introuvable</h2>

<p>
    La page demandée n'existe pas.
</p>

<p>
    <a href="/salles">Retour aux salles</a>
</p>

<?php

$content = ob_get_clean();

require __DIR__ . '/../layout/base.php';
