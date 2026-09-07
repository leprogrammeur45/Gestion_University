<?php

$title = 'Liste des réservations';

ob_start();
?>

<h2>Liste des réservations</h2>

<a href="/reservations/create">Créer une réservation</a>

<?php if (empty($reservations)): ?>

    <p>Aucune réservation disponible.</p>

<?php else: ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Salle</th>
                <th>Responsable</th>
                <th>Email</th>
                <th>Motif</th>
                <th>Date de début</th>
                <th>Date de fin</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>

        <?php foreach ($reservations as $reservation): ?>

            <tr>
                <td>
                    <?= htmlspecialchars((string) $reservation->id) ?>
                </td>

                <td>
                    <?= htmlspecialchars((string) $reservation->salle_id) ?>
                </td>

                <td>
                    <?= htmlspecialchars($reservation->responsable) ?>
                </td>

                <td>
                    <?= htmlspecialchars($reservation->email) ?>
                </td>

                <td>
                    <?= htmlspecialchars($reservation->motif) ?>
                </td>

                <td>
                    <?= htmlspecialchars((string) $reservation->date_debut) ?>
                </td>

                <td>
                    <?= htmlspecialchars((string) $reservation->date_fin) ?>
                </td>

                <td>
                    <?= htmlspecialchars($reservation->statut) ?>
                </td>

                <td>
                    <a href="/reservations/<?= htmlspecialchars((string) $reservation->id) ?>">
                        Voir
                    </a>

                    <?php if ($reservation->statut === 'confirmée'): ?>

                        <form
                            method="POST"
                            action="/reservations/<?= htmlspecialchars((string) $reservation->id) ?>/cancel"
                            style="display: inline;"
                        >
                            <button type="submit">
                                Annuler
                            </button>
                        </form>

                    <?php endif; ?>
                </td>
            </tr>

        <?php endforeach; ?>

        </tbody>
    </table>

<?php endif; ?>

<?php

$content = ob_get_clean();

require __DIR__ . '/../layout/base.php';
