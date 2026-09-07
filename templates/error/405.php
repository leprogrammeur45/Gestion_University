<?php

$title = 'Méthode non autorisée';

ob_start();
?>

<h2>405 - Méthode non autorisée</h2>

<p>
    La méthode HTTP utilisée n'est pas autorisée pour cette ressource.
</p>

<p>
    <a href="/salles">Retour aux salles</a>
</p>

<?php

$content = ob_get_clean();

require __DIR__ . '/../layout/base.php';
