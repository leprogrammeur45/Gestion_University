<?php

$title = 'Créer une réservation';

ob_start();
?>

<h2>Créer une réservation</h2>

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

<form method="POST" action="/reservations">

    <div>
        <label for="salle_id">Salle</label>

        <select id="salle_id" name="salle_id">

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
    </div>

    <div>
        <label for="responsable">Responsable</label>

        <input
            type="text"
            id="responsable"
            name="responsable"
            value="<?= htmlspecialchars($data['responsable'] ?? '') ?>"
        >
    </div>

    <div>
        <label for="email">Email</label>

        <input
            type="email"
            id="email"
            name="email"
            value="<?= htmlspecialchars($data['email'] ?? '') ?>"
        >
    </div>

    <div>
        <label for="motif">Motif</label>

        <textarea
            id="motif"
            name="motif"
        ><?= htmlspecialchars($data['motif'] ?? '') ?></textarea>
    </div>

    <div>
        <label for="date_debut">Date de début</label>

        <input
            type="datetime-local"
            id="date_debut"
            name="date_debut"
            value="<?= htmlspecialchars($data['date_debut'] ?? '') ?>"
        >
    </div>

    <div>
        <label for="date_fin">Date de fin</label>

        <input
            type="datetime-local"
            id="date_fin"
            name="date_fin"
            value="<?= htmlspecialchars($data['date_fin'] ?? '') ?>"
        >
    </div>

    <button type="submit">
        Créer la réservation
    </button>

</form>

<p>
    <a href="/reservations">Retour à la liste</a>
</p>

<?php

$content = ob_get_clean();

require __DIR__ . '/../layout/base.php';
