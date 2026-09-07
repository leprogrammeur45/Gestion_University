<?php

$title = isset($salle) ? 'Modifier une salle' : 'Ajouter une salle';

ob_start();
?>

<h2><?= isset($salle) ? 'Modifier une salle' : 'Ajouter une salle' ?></h2>

<?php if (!empty($errors)): ?>

    <div>
        <h3>Erreurs :</h3>

        <ul>
            <?php foreach ($errors as $fieldErrors): ?>
                <?php foreach ($fieldErrors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </ul>
    </div>

<?php endif; ?>

<form
    method="POST"
    action="<?= isset($salle)
        ? '/salles/' . htmlspecialchars((string) $salle->id) . '/edit'
        : '/salles'
    ?>"
>

    <div>
        <label for="nom">Nom</label>

        <input
            type="text"
            id="nom"
            name="nom"
            value="<?= htmlspecialchars($salle->nom ?? '') ?>"
        >
    </div>

    <div>
        <label for="batiment">Bâtiment</label>

        <input
            type="text"
            id="batiment"
            name="batiment"
            value="<?= htmlspecialchars($salle->batiment ?? '') ?>"
        >
    </div>

    <div>
        <label for="capacite">Capacité</label>

        <input
            type="number"
            id="capacite"
            name="capacite"
            value="<?= htmlspecialchars((string) ($salle->capacite ?? '')) ?>"
        >
    </div>

    <div>
        <label for="type">Type</label>

        <select id="type" name="type">

            <option value="cours"
                <?= (($salle->type ?? '') === 'cours') ? 'selected' : '' ?>>
                Cours
            </option>

            <option value="informatique"
                <?= (($salle->type ?? '') === 'informatique') ? 'selected' : '' ?>>
                Informatique
            </option>

            <option value="laboratoire"
                <?= (($salle->type ?? '') === 'laboratoire') ? 'selected' : '' ?>>
                Laboratoire
            </option>

            <option value="amphitheatre"
                <?= (($salle->type ?? '') === 'amphitheatre') ? 'selected' : '' ?>>
                Amphithéâtre
            </option>

            <option value="reunion"
                <?= (($salle->type ?? '') === 'reunion') ? 'selected' : '' ?>>
                Réunion
            </option>

        </select>
    </div>

    <div>
        <label for="active">Active</label>

        <input
            type="hidden"
            name="active"
            value="0"
        >

        <input
            type="checkbox"
            id="active"
            name="active"
            value="1"
            <?= (($salle->active ?? true) ? 'checked' : '') ?>
        >
    </div>

    <button type="submit">
        <?= isset($salle) ? 'Modifier' : 'Ajouter' ?>
    </button>

</form>

<p>
    <a href="/salles">Retour à la liste</a>
</p>

<?php

$content = ob_get_clean();

require __DIR__ . '/../layout/base.php';
