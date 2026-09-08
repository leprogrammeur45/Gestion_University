<?php

$title = isset($salle) ? 'Modifier une salle' : 'Ajouter une salle';

ob_start();

$errors = $errors ?? [];
$formData = $data ?? [];

$fieldError = static function (string $field) use ($errors) {
    return $errors[$field][0] ?? null;
};

?>

<div class="page-header">

    <h2>
        <?= isset($salle) ? '✏️ Modifier une salle' : '➕ Ajouter une salle' ?>
    </h2>

</div>

<div class="form-card">

    <form
        method="POST"
        action="<?= isset($salle)
            ? '/salles/' . htmlspecialchars((string) $salle->id) . '/edit'
            : '/salles'
        ?>"
    >

        <div class="field<?= $fieldError('nom') ? ' has-error' : '' ?>">

            <label for="nom">Nom</label>

            <input
                type="text"
                id="nom"
                name="nom"
                placeholder="Ex. B12"
                value="<?= htmlspecialchars($formData['nom'] ?? $salle->nom ?? '') ?>"
            >

            <?php if ($fieldError('nom')): ?>

                <div class="field-error">
                    ⚠ <?= htmlspecialchars($fieldError('nom')) ?>
                </div>

            <?php endif; ?>

        </div>

        <div class="field<?= $fieldError('batiment') ? ' has-error' : '' ?>">

            <label for="batiment">Bâtiment</label>

            <input
                type="text"
                id="batiment"
                name="batiment"
                placeholder="Ex. B"
                value="<?= htmlspecialchars($formData['batiment'] ?? $salle->batiment ?? '') ?>"
            >

            <?php if ($fieldError('batiment')): ?>

                <div class="field-error">
                    ⚠ <?= htmlspecialchars($fieldError('batiment')) ?>
                </div>

            <?php endif; ?>

        </div>

        <div class="field<?= $fieldError('capacite') ? ' has-error' : '' ?>">

            <label for="capacite">Capacité</label>

            <input
                type="number"
                id="capacite"
                name="capacite"
                placeholder="Ex. 40"
                value="<?= htmlspecialchars((string) ($formData['capacite'] ?? $salle->capacite ?? '')) ?>"
            >

            <?php if ($fieldError('capacite')): ?>

                <div class="field-error">
                    ⚠ <?= htmlspecialchars($fieldError('capacite')) ?>
                </div>

            <?php endif; ?>

        </div>

        <div class="field<?= $fieldError('type') ? ' has-error' : '' ?>">

            <label for="type">Type</label>

            <select id="type" name="type">

                <option
                    value="cours"
                      <?= (($formData['type'] ?? $salle->type ?? '') === 'cours') ? 'selected' : '' ?>
                >
                    Cours
                </option>

                <option
                    value="informatique"
                      <?= (($formData['type'] ?? $salle->type ?? '') === 'informatique') ? 'selected' : '' ?>
                >
                    Informatique
                </option>

                <option
                    value="laboratoire"
                      <?= (($formData['type'] ?? $salle->type ?? '') === 'laboratoire') ? 'selected' : '' ?>
                >
                    Laboratoire
                </option>

                <option
                    value="amphitheatre"
                      <?= (($formData['type'] ?? $salle->type ?? '') === 'amphitheatre') ? 'selected' : '' ?>
                >
                    Amphithéâtre
                </option>

                <option
                    value="reunion"
                      <?= (($formData['type'] ?? $salle->type ?? '') === 'reunion') ? 'selected' : '' ?>
                >
                    Réunion
                </option>

            </select>

            <?php if ($fieldError('type')): ?>

                <div class="field-error">
                    ⚠ <?= htmlspecialchars($fieldError('type')) ?>
                </div>

            <?php endif; ?>

        </div>

        <div class="field">

            <label for="active">Statut</label>

            <input
                type="hidden"
                name="active"
                value="0"
            >

            <div class="checkbox-row">

                <input
                    type="checkbox"
                    id="active"
                    name="active"
                    value="1"
                      <?= in_array($formData['active'] ?? ($salle->active ?? true), [true, 1, '1'], true) ? 'checked' : '' ?>
                >

                <label for="active">
                    Salle active
                </label>

            </div>

        </div>

        <div class="form-actions">

            <button
                type="submit"
                class="btn btn-primary"
            >
                <?= isset($salle)
                    ? 'Enregistrer les modifications'
                    : 'Créer la salle'
                ?>
            </button>

            <a
                href="/salles"
                class="btn btn-secondary"
            >
                Annuler
            </a>

        </div>

    </form>

</div>

<?php

$content = ob_get_clean();

require __DIR__ . '/../layout/base.php';
