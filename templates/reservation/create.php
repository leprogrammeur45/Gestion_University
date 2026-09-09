<?php

$title = 'Créer une réservation';
$salles = $salles ?? [];

ob_start();

$errors = $errors ?? [];

$fieldError = static function (string $field) use ($errors) {
    return $errors[$field][0] ?? null;
};

?>

<div class="page-header">
    <div>
        <h2>📅 Créer une réservation</h2>
        <p>Planifiez l'utilisation d'une salle universitaire.</p>
    </div>
</div>

<div class="form-card reservation-form-card">

    <div class="form-card-header">
        <div class="form-card-icon">
            📅
        </div>

        <div>
            <h3>Nouvelle réservation</h3>
            <p>
                Renseignez les informations nécessaires pour réserver une salle.
            </p>
        </div>
    </div>

    <form method="POST" action="/reservations">

        <div class="field<?= $fieldError('salle_id') ? ' has-error' : '' ?>">

            <label for="salle_id">
                Salle
            </label>

            <select id="salle_id" name="salle_id">

                <option value="">
                    Sélectionnez une salle
                </option>

                <?php foreach ($salles as $salle): ?>

                    <?php if ($salle->active): ?>

                        <option
                            value="<?= htmlspecialchars((string) $salle->id) ?>"
                            <?= ((string) ($data['salle_id'] ?? '') === (string) $salle->id) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($salle->nom) ?>
                            —
                            <?= htmlspecialchars($salle->batiment) ?>
                        </option>

                    <?php endif; ?>

                <?php endforeach; ?>

            </select>

            <div class="field-hint">
                Seules les salles actuellement actives sont disponibles.
            </div>

            <?php if ($fieldError('salle_id')): ?>

                <div class="field-error">
                    ⚠ <?= htmlspecialchars($fieldError('salle_id')) ?>
                </div>

            <?php endif; ?>

        </div>


        <div class="field<?= $fieldError('responsable') ? ' has-error' : '' ?>">

            <label for="responsable">
                Responsable
            </label>

            <input
                type="text"
                id="responsable"
                name="responsable"
                placeholder="Nom et prénom du responsable"
                value="<?= htmlspecialchars($data['responsable'] ?? '') ?>"
            >

            <?php if ($fieldError('responsable')): ?>

                <div class="field-error">
                    ⚠ <?= htmlspecialchars($fieldError('responsable')) ?>
                </div>

            <?php endif; ?>

        </div>


        <div class="field<?= $fieldError('email') ? ' has-error' : '' ?>">

            <label for="email">
                Adresse email
            </label>

            <input
                type="email"
                id="email"
                name="email"
                placeholder="adresse@email.com"
                value="<?= htmlspecialchars($data['email'] ?? '') ?>"
            >

            <div class="field-hint">
                Cette adresse sera associée à la réservation.
            </div>

            <?php if ($fieldError('email')): ?>

                <div class="field-error">
                    ⚠ <?= htmlspecialchars($fieldError('email')) ?>
                </div>

            <?php endif; ?>

        </div>


        <div class="field<?= $fieldError('motif') ? ' has-error' : '' ?>">

            <label for="motif">
                Motif de la réservation
            </label>

            <textarea
                id="motif"
                name="motif"
                placeholder="Exemple : réunion pédagogique, cours, soutenance..."
            ><?= htmlspecialchars($data['motif'] ?? '') ?></textarea>

            <div class="field-hint">
                Le motif doit contenir entre 5 et 255 caractères.
            </div>

            <?php if ($fieldError('motif')): ?>

                <div class="field-error">
                    ⚠ <?= htmlspecialchars($fieldError('motif')) ?>
                </div>

            <?php endif; ?>

        </div>


        <div class="reservation-dates">

            <div class="field<?= $fieldError('date_debut') ? ' has-error' : '' ?>">

                <label for="date_debut">
                    Date de début
                </label>

                <input
                    type="datetime-local"
                    id="date_debut"
                    name="date_debut"
                    value="<?= htmlspecialchars($data['date_debut'] ?? '') ?>"
                >

                <?php if ($fieldError('date_debut')): ?>

                    <div class="field-error">
                        ⚠ <?= htmlspecialchars($fieldError('date_debut')) ?>
                    </div>

                <?php endif; ?>

            </div>


            <div class="field<?= $fieldError('date_fin') ? ' has-error' : '' ?>">

                <label for="date_fin">
                    Date de fin
                </label>

                <input
                    type="datetime-local"
                    id="date_fin"
                    name="date_fin"
                    value="<?= htmlspecialchars($data['date_fin'] ?? '') ?>"
                >

                <?php if ($fieldError('date_fin')): ?>

                    <div class="field-error">
                        ⚠ <?= htmlspecialchars($fieldError('date_fin')) ?>
                    </div>

                <?php endif; ?>

            </div>

        </div>


        <div class="reservation-info">
            <span class="reservation-info-icon">ℹ</span>

            <div>
                <strong>Informations importantes</strong>

                <p>
                    La réservation doit commencer dans le futur,
                    ne pas dépasser 4 heures et ne pas chevaucher
                    une réservation existante.
                </p>
            </div>
        </div>


        <div class="form-actions">

            <button
                type="submit"
                class="btn btn-primary"
            >
                📅 Créer la réservation
            </button>

            <a
                href="/reservations"
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
?>