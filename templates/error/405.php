<?php

$title = 'Méthode non autorisée';

ob_start();

?>

<div class="error-page">
    <div class="error-card">


    <div class="error-icon">
        ⚠
    </div>

    <div class="error-code">405</div>

    <h2>Méthode non autorisée</h2>

    <p>
        Cette action HTTP n'est pas autorisée
        pour cette ressource.
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
