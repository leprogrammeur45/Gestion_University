<?php

$title = 'Détail de la réservation';

ob_start();
?>

<h2>Détail de la réservation</h2>

<p>
    <strong>ID :</strong>
    <?= htmlspecialchars((string) $reservation->id) ?>
</p>

<p>
    <strong>Salle :</strong>
    <?= htmlspecialchars((string) $reservation->salle_id) ?>
</p>

<p>
    <strong>Responsable :</strong>
    <?= htmlspecialchars($reservation->responsable) ?>
</p>

<p>
    <strong>Email :</strong>
    <?= htmlspecialchars($reservation->email) ?>
</p>

<p>
    <strong>Motif :</strong>
    <?= htmlspecialchars($reservation->motif) ?>
</p>

<p>
    <strong>Date de début :</strong>
    <?= htmlspecialchars((string) $reservation->date_debut) ?>
</p>

<p>
    <strong>Date de fin :</strong>
    <?= htmlspecialchars((string) $reservation->date_fin) ?>
</p>

<p>
    <strong>Statut :</strong>
    <?= htmlspecialchars($reservation->statut) ?>
</p>

<?php if ($reservation->statut === 'confirmée'): ?>

    <form
        method="POST"
        action="/reservations/<?= htmlspecialchars((string) $reservation->id) ?>/cancel"
    >
        <button type="submit">
            Annuler la réservation
        </button>
    </form>

<?php endif; ?>

<p>
    <a href="/reservations">Retour à la liste</a>
</p>

<?php

$content = ob_get_clean();

require __DIR__ . '/../layout/base.php';
