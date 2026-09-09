<?php

$title = 'Créer une réservation';

$errors = $errors ?? [];
$data = $data ?? [];
$salles = $salles ?? [];

$fieldError = static function (string $field) use ($errors) {
    return $errors[$field][0] ?? null;
};

ob_start();

?>

<div class="page-header">

    <div>

        <h2>📅 Créer une réservation</h2>

        <p>
            Réservez une salle pour une activité universitaire.
        </p>

    </div>

</div>


<?php if (!empty($errors)): ?>

    <div class="alert alert-danger">

        <span class="alert-icon">⚠</span>

        <div>

            <strong>Impossible de créer la réservation</strong>

            <ul>

                <?php foreach ($errors as $fieldErrors): ?>

                    <?php foreach ($fieldErrors as $error): ?>

                        <li>
                            <?= htmlspecialchars($error) ?>
                        </li>

                    <?php endforeach; ?>

                <?php endforeach; ?>

            </ul>

        </div>

    </div>

<?php endif; ?>


<div class="form-card reservation-form-card">

    <div class="form-card-header">

        <div class="form-card-icon">
            📅
        </div>

        <div>

            <h3>
                Nouvelle réservation
            </h3>

            <p>
                Remplissez les informations ci-dessous.
            </p>

        </div>

    </div>


    <form method="POST" action="/reservations">


        <!-- ================================
             SALLE
        ================================= -->

        <div class="field">

            <label for="salle_id">
                Salle
            </label>

            <select
                id="salle_id"
                name="salle_id"
            >

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
                            -
                            <?= htmlspecialchars($salle->batiment) ?>
                        </option>

                    <?php endif; ?>

                <?php endforeach; ?>

            </select>

            <?php if ($fieldError('salle_id')): ?>

                <div class="field-error">
                    ⚠ <?= htmlspecialchars($fieldError('salle_id')) ?>
                </div>

            <?php endif; ?>

            <div class="field-hint">
                Seules les salles actives peuvent être réservées.
            </div>

        </div>


        <!-- ================================
             RESPONSABLE
        ================================= -->

        <div class="field">

            <label for="responsable">
                Responsable
            </label>

            <input
                type="text"
                id="responsable"
                name="responsable"
                placeholder="Nom du responsable"
                value="<?= htmlspecialchars($data['responsable'] ?? '') ?>"
            >

            <?php if ($fieldError('responsable')): ?>

                <div class="field-error">
                    ⚠ <?= htmlspecialchars($fieldError('responsable')) ?>
                </div>

            <?php endif; ?>

        </div>


        <!-- ================================
             EMAIL
        ================================= -->

        <div class="field">

            <label for="email">
                Email
            </label>

            <input
                type="email"
                id="email"
                name="email"
                placeholder="adresse@email.com"
                value="<?= htmlspecialchars($data['email'] ?? '') ?>"
            >

            <?php if ($fieldError('email')): ?>

                <div class="field-error">
                    ⚠ <?= htmlspecialchars($fieldError('email')) ?>
                </div>

            <?php endif; ?>

        </div>


        <!-- ================================
             MOTIF
        ================================= -->

        <div class="field">

            <label for="motif">
                Motif
            </label>

            <textarea
                id="motif"
                name="motif"
                placeholder="Motif de la réservation"
            ><?= htmlspecialchars($data['motif'] ?? '') ?></textarea>

            <?php if ($fieldError('motif')): ?>

                <div class="field-error">
                    ⚠ <?= htmlspecialchars($fieldError('motif')) ?>
                </div>

            <?php endif; ?>

            <div class="field-hint">
                Indiquez la raison de l'utilisation de la salle.
            </div>

        </div>


        <!-- ================================
             DATES
        ================================= -->

        <div class="reservation-dates">


            <!-- DATE DEBUT -->

            <div class="field">

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


            <!-- DATE FIN -->

            <div class="field">

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


        <!-- ================================
             INFORMATIONS
        ================================= -->

        <div class="reservation-info">

            <span class="reservation-info-icon">
                ℹ
            </span>

            <div>

                <strong>
                    Conditions de réservation
                </strong>

                <p>
                    La réservation doit commencer dans le futur,
                    durer au maximum 4 heures et ne pas chevaucher
                    une réservation existante.
                </p>

            </div>

        </div>


        <!-- ================================
             ACTIONS
        ================================= -->

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