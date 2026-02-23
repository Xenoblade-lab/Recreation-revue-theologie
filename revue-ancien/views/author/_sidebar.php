<aside class="dashboard-sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <img src="<?= Router\Router::$defaultUri ?>assets/logo_upc.png" alt="UPC Logo">
            <h2>Espace Auteur</h2>
        </div>
        <div class="user-info">
            <?php
            // Récupérer les données utilisateur depuis les variables passées par le contrôleur
            $userName = isset($user) ? trim(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '')) : 'Utilisateur';
            $userEmail = isset($user) ? ($user['email'] ?? '') : '';
            $userInitials = isset($user) ? strtoupper(substr($user['prenom'] ?? '', 0, 1) . substr($user['nom'] ?? '', 0, 1)) : 'U';
            ?>
            <div class="user-avatar"><?= htmlspecialchars($userInitials) ?></div>
            <div class="user-details">
                <h3><?= htmlspecialchars($userName) ?></h3>
                <p>Auteur</p>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-title">Principal</div>
            <a href="<?= Router\Router::route("author") ?>" class="nav-item <?= (basename($_SERVER['PHP_SELF'], '.php') === 'index' || (isset($currentPage) && $currentPage === 'dashboard')) ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Tableau de bord</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Mes articles</div>
            <a href="<?= Router\Router::route("author") ?>#submit-form" class="nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Nouvelle soumission</span>
            </a>
            <a href="<?= Router\Router::route("author") ?>#submissions-table" class="nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Mes soumissions</span>
                <?php if (isset($stats) && isset($stats['total'])): ?>
                    <span class="badge"><?= $stats['total'] ?></span>
                <?php else: ?>
                    <span class="badge">0</span>
                <?php endif; ?>
            </a>
            <a href="<?= Router\Router::route("author") ?>/articles" class="nav-item <?= (basename($_SERVER['PHP_SELF'], '.php') === 'articles' || (isset($currentPage) && $currentPage === 'articles')) ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Articles publiés</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Compte</div>
            <a href="<?= Router\Router::route("author") ?>/notifications" class="nav-item <?= (basename($_SERVER['PHP_SELF'], '.php') === 'notifications' || (isset($currentPage) && $currentPage === 'notifications')) ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span>Notifications</span>
                <?php if (isset($unreadCount) && $unreadCount > 0): ?>
                    <span class="badge" style="background: var(--color-red);"><?= htmlspecialchars($unreadCount) ?></span>
                <?php endif; ?>
            </a>
            <a href="<?= Router\Router::route("author") ?>/abonnement" class="nav-item <?= (basename($_SERVER['PHP_SELF'], '.php') === 'abonnement' || (isset($currentPage) && $currentPage === 'abonnement')) ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <span>Abonnement & Paiements</span>
            </a>
            <a href="<?= Router\Router::route("author") ?>/profil" class="nav-item <?= (basename($_SERVER['PHP_SELF'], '.php') === 'profil' || (isset($currentPage) && $currentPage === 'profil')) ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Mon profil</span>
            </a>
            <a href="<?= Router\Router::route("logout") ?>" class="nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Déconnexion</span>
            </a>
        </div>
    </nav>
</aside>
