<?php

$title = 'Liste des réservations';

ob_start();

?>

<div class="page-header">

    <div>

        <h2>📅 Réservations</h2>

        <p>
            Consultez et gérez les réservations des salles.
        </p>

    </div>

    <a
        href="/reservations/create"
        class="btn btn-primary"
    >
        + Créer une réservation
    </a>

</div>


<?php if (empty($reservations)): ?>

    <div class="empty-state">

        <div class="empty-state-icon">
            📅
        </div>

        <h3>
            Aucune réservation
        </h3>

        <p>
            Aucune réservation n'est disponible pour le moment.
        </p>

        <a
            href="/reservations/create"
            class="btn btn-primary"
        >
            + Créer une réservation
        </a>

    </div>

<?php else: ?>

    <div class="card">

        <div class="table-header">

            <div>

                <h3>
                    Toutes les réservations
                </h3>

                <p>
                    Liste des réservations enregistrées.
                </p>

            </div>

            <span class="table-count">

                <?= count($reservations) ?>

                réservation<?= count($reservations) > 1 ? 's' : '' ?>

            </span>

        </div>


        <div class="table-wrapper">

            <table class="data-table">

                <thead>

                    <tr>

                        <th>Salle</th>

                        <th>Responsable</th>

                        <th>Email</th>

                        <th>Motif</th>

                        <th>Début</th>

                        <th>Fin</th>

                        <th>Statut</th>

                        <th>Actions</th>

                    </tr>

                </thead>


                <tbody>

                <?php foreach ($reservations as $reservation): ?>

                    <tr>

                        <td data-label="Salle">

                            <span class="room-reference">

                                #<?= htmlspecialchars((string) $reservation->salle_id) ?>

                            </span>

                        </td>


                        <td data-label="Responsable">

                            <strong>

                                <?= htmlspecialchars($reservation->responsable) ?>

                            </strong>

                        </td>


                        <td data-label="Email">

                            <?= htmlspecialchars($reservation->email) ?>

                        </td>


                        <td data-label="Motif">

                            <span class="reservation-motif">

                                <?= htmlspecialchars($reservation->motif) ?>

                            </span>

                        </td>


                        <td data-label="Début">

                            <?= htmlspecialchars((string) $reservation->date_debut) ?>

                        </td>


                        <td data-label="Fin">

                            <?= htmlspecialchars((string) $reservation->date_fin) ?>

                        </td>


                        <td data-label="Statut">

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

                        </td>


                        <td data-label="Actions">

                            <div class="table-actions">

                                <a
                                    href="/reservations/<?= htmlspecialchars((string) $reservation->id) ?>"
                                    class="btn btn-secondary btn-sm"
                                >
                                    Voir
                                </a>


                                <?php if ($reservation->statut === 'confirmee'): ?>

                                    <form
                                        method="POST"
                                        action="/reservations/<?= htmlspecialchars((string) $reservation->id) ?>/cancel"
                                        class="inline-form"
                                    >

                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm"
                                        >
                                            Annuler
                                        </button>

                                    </form>

                                <?php endif; ?>

                            </div>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

<?php endif; ?>


<?php

$content = ob_get_clean();

require __DIR__ . '/../layout/base.php';

?>