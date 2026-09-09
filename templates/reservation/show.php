<?php

$title = 'Détail de la réservation';

if (!isset($reservation)) {
    throw new \LogicException('Une réservation est requise pour afficher cette page.');
}

ob_start();

?>

<div class="page-header">

    <div>

        <h2>
            📅 Réservation #<?= htmlspecialchars((string) $reservation->id) ?>
        </h2>

        <p>
            Consultez les informations de cette réservation.
        </p>

    </div>

    <?php if ($reservation->statut === 'confirmee'): ?>

        <span class="badge badge-success">

            <span class="badge-dot"></span>

            Confirmée

        </span>

    <?php elseif ($reservation->statut === 'annulee'): ?>

        <span class="badge badge-danger">

            <span class="badge-dot"></span>

            Annulée

        </span>

    <?php else: ?>

        <span class="badge badge-neutral">

            <span class="badge-dot"></span>

            <?= htmlspecialchars($reservation->statut) ?>

        </span>

    <?php endif; ?>

</div>


<div class="detail-card">

    <div class="detail-card-header">

        <div class="detail-card-icon">
            📅
        </div>

        <div>

            <h3>
                Informations de la réservation
            </h3>

            <p>
                Détails concernant l'utilisation de la salle.
            </p>

        </div>

    </div>


    <div class="detail-row">

        <span class="label">
            Salle
        </span>

        <span class="value">
            #<?= htmlspecialchars((string) $reservation->salle_id) ?>
        </span>

    </div>


    <div class="detail-row">

        <span class="label">
            Responsable
        </span>

        <span class="value">
            <?= htmlspecialchars($reservation->responsable) ?>
        </span>

    </div>


    <div class="detail-row">

        <span class="label">
            Email
        </span>

        <span class="value">
            <?= htmlspecialchars($reservation->email) ?>
        </span>

    </div>


    <div class="detail-row">

        <span class="label">
            Motif
        </span>

        <span class="value">
            <?= htmlspecialchars($reservation->motif) ?>
        </span>

    </div>


    <div class="detail-row">

        <span class="label">
            Date de début
        </span>

        <span class="value">
            <?= htmlspecialchars((string) $reservation->date_debut) ?>
        </span>

    </div>


    <div class="detail-row">

        <span class="label">
            Date de fin
        </span>

        <span class="value">
            <?= htmlspecialchars((string) $reservation->date_fin) ?>
        </span>

    </div>

</div>


<div class="actions-row">

    <?php if ($reservation->statut === 'confirmee'): ?>

        <form
            method="POST"
            action="/reservations/<?= htmlspecialchars((string) $reservation->id) ?>/cancel"
        >

            <button
                type="submit"
                class="btn btn-danger"
            >
                Annuler la réservation
            </button>

        </form>

    <?php endif; ?>


    <a
        href="/reservations"
        class="btn btn-secondary"
    >
        ← Retour à la liste
    </a>

</div>


<?php

$content = ob_get_clean();

require __DIR__ . '/../layout/base.php';

?>