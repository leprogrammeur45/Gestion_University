<?php

$title = 'Page introuvable';

ob_start();

?>

<div class="error-page">
    <div class="error-card">


    <div class="error-icon">
        ⚠
    </div>

    <div class="error-code">404</div>

    <h2>Page introuvable</h2>

    <p>
        Désolé, la page que vous recherchez
        n'existe pas ou a été déplacée.
    </p>

    <a href="/salles" class="btn btn-primary">
        ← Retour aux salles
    </a>

</div>


</div>

<?php

$content = ob_get_clean();

require __DIR__ . '/../layout/base.php';
?>
