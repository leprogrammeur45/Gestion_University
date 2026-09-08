<?php

/**
 * Détermine si un lien de navigation correspond à la page courante.
 * Utilisé uniquement pour l'affichage de la classe CSS "active".
 */
$currentPath = $_SERVER['REQUEST_URI'] ?? '';

$isActive = static function (string $prefix) use ($currentPath): bool {
    return str_starts_with($currentPath, $prefix);
};

?>

<!DOCTYPE html>

<html lang="fr">

<head>
    <meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<meta
    name="description"
    content="Gestion University - Gestion des salles et des réservations"
>

<title>
    <?= htmlspecialchars($title ?? 'Gestion University') ?>
    · Gestion University
</title>

<link rel="stylesheet" href="/assets/style.css">

</head>

<body>

<div class="app-shell">

<!-- ================================
     HEADER
================================= -->

<header class="topbar">

    <div class="topbar-left">

        <button
            type="button"
            class="menu-toggle"
            id="menuToggle"
            aria-label="Ouvrir le menu"
            aria-controls="sidebar"
            aria-expanded="false"
        >
            ☰
        </button>

        <a href="/salles" class="topbar-brand">

            <span class="brand-logo">
                🎓
            </span>

            <span class="brand-text">
                <strong>Gestion</strong>
                <span>University</span>
            </span>

        </a>

    </div>

    <div class="topbar-right">

        <div class="topbar-badge">
            <span class="status-dot"></span>
            Administration
        </div>

    </div>

</header>


<!-- ================================
     SIDEBAR
================================= -->

<aside
    class="sidebar"
    id="sidebar"
    aria-label="Navigation principale"
>

    <div class="sidebar-header">

        <div class="sidebar-logo">
            🎓
        </div>

        <div>
            <span class="sidebar-title">
                Gestion University
            </span>

            <span class="sidebar-subtitle">
                Administration
            </span>
        </div>

    </div>


    <nav class="sidebar-navigation">

        <p class="sidebar-section-title">
            MENU PRINCIPAL
        </p>

        <ul class="sidebar-nav">

            <!-- Salles -->

            <li class="sidebar-item">

                <a
                    href="/salles"
                    class="sidebar-link<?= $isActive('/salles') ? ' active' : '' ?>"
                >

                    <span class="sidebar-icon">
                        🏫
                    </span>

                    <span class="sidebar-link-text">
                        Salles
                    </span>

                </a>

            </li>


            <!-- Réservations -->

            <li class="sidebar-item">

                <a
                    href="/reservations"
                    class="sidebar-link<?= $isActive('/reservations') ? ' active' : '' ?>"
                >

                    <span class="sidebar-icon">
                        📅
                    </span>

                    <span class="sidebar-link-text">
                        Réservations
                    </span>

                </a>

            </li>

        </ul>

    </nav>


    <!-- Sidebar footer -->

    <div class="sidebar-footer">

        <div class="sidebar-footer-icon">
            🎓
        </div>

        <div class="sidebar-footer-text">

            <strong>
                Gestion University
            </strong>

            <span>
                Plateforme de gestion
            </span>

        </div>

    </div>

</aside>


<!-- ================================
     CONTENU PRINCIPAL
================================= -->

<main class="content">

    <div class="content-container">

        <?= $content ?? '' ?>

    </div>

</main>

</div>

<!-- ================================
     JAVASCRIPT MENU MOBILE
================================= -->

<script>

    (function () {

        var toggle = document.getElementById('menuToggle');
        var sidebar = document.getElementById('sidebar');

        if (!toggle || !sidebar) {
            return;
        }

        toggle.addEventListener('click', function () {

            var isOpen = document.body.classList.toggle('sidebar-open');

            toggle.setAttribute(
                'aria-expanded',
                isOpen ? 'true' : 'false'
            );

        });

    })();

</script>

</body>

</html>
