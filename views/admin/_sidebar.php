<aside class="dashboard-sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <img src="<?= Router\Router::$defaultUri ?>assets/logo_upc.png" alt="UPC Logo">
            <h2>Admin Panel</h2>
        </div>
        <div class="user-info">
            <?php
            // Récupérer les données utilisateur depuis les variables passées par le contrôleur
            $userName = isset($user) ? trim(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '')) : 'Admin Principal';
            $userEmail = isset($user) ? ($user['email'] ?? '') : '';
            $userInitials = isset($user) ? strtoupper(substr($user['prenom'] ?? '', 0, 1) . substr($user['nom'] ?? '', 0, 1)) : 'AD';
            $userRole = isset($user) && isset($user['role']) ? ucfirst($user['role']) : 'Administrateur';
            
            // Récupérer le rôle actif (pour la bascule)
            $principalRole = strtolower($user['role'] ?? 'admin');
            $activeRole = strtolower($_SESSION['active_role'] ?? $_SESSION['user_role'] ?? $principalRole);
            $isAdmin = ($principalRole === 'admin');
            ?>
            <div class="user-avatar"><?= htmlspecialchars($userInitials) ?></div>
            <div class="user-details">
                <h3><?= htmlspecialchars($userName) ?></h3>
                <p><?= htmlspecialchars($userRole) ?></p>
                <?php if ($activeRole !== $principalRole): ?>
                    <p style="font-size: 0.75rem; color: rgba(255, 255, 255, 0.7); margin-top: 0.25rem;">
                        Mode: <?= htmlspecialchars(ucfirst($activeRole)) ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        <?php if ($isAdmin): ?>
            <div class="role-switch-container" style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(255, 255, 255, 0.1);">
                <?php if ($activeRole === 'admin'): ?>
                    <button onclick="switchRole('reviewer')" class="role-switch-btn" style="width: 100%; padding: 0.75rem; font-size: 0.875rem; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 6px; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.2s;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 16px; height: 16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        <span>Basculer vers Évaluateur</span>
                    </button>
                <?php else: ?>
                    <button onclick="switchRole('admin')" class="role-switch-btn" style="width: 100%; padding: 0.75rem; font-size: 0.875rem; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 6px; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.2s;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 16px; height: 16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        <span>Revenir à Admin</span>
                    </button>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-title">Principal</div>
            <a href="<?= Router\Router::route("admin") ?>" class="nav-item <?= (isset($current_page) && $current_page === 'dashboard') ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Tableau de bord</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Gestion</div>
            <a href="<?= Router\Router::route("admin") ?>/articles" class="nav-item <?= (isset($current_page) && $current_page === 'articles') ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Articles</span>
                <?php if (isset($stats) && isset($stats['articles_total'])): ?>
                    <span class="badge"><?= $stats['articles_total'] ?></span>
                <?php endif; ?>
            </a>
            <a href="<?= Router\Router::route("admin") ?>/evaluations" class="nav-item <?= (isset($current_page) && $current_page === 'evaluations') ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <span>Évaluations</span>
            </a>
            <a href="<?= Router\Router::route("admin") ?>/publications" class="nav-item <?= (isset($current_page) && $current_page === 'publications') ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                <span>Publications</span>
            </a>
            <a href="<?= Router\Router::route("admin") ?>/users" class="nav-item <?= (isset($current_page) && $current_page === 'users') ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>Utilisateurs</span>
            </a>
            <a href="<?= Router\Router::route("admin") ?>/volumes" class="nav-item <?= (isset($current_page) && $current_page === 'volumes') ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span>Numéros/Volumes</span>
            </a>
            <a href="<?= Router\Router::route("admin") ?>/paiements" class="nav-item <?= (isset($current_page) && $current_page === 'paiements') ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <span>Paiements</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Paramètres</div>
            <a href="<?= Router\Router::route("admin") ?>/settings" class="nav-item <?= (isset($current_page) && $current_page === 'settings') ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Configuration</span>
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

<style>
.role-switch-btn:hover {
    background: rgba(255, 255, 255, 0.2) !important;
    border-color: rgba(255, 255, 255, 0.5) !important;
    transform: translateY(-1px);
}
.role-switch-container {
    position: relative;
    z-index: 1;
}
</style>
<script src="<?= Router\Router::$defaultUri ?>js/author-notify.js"></script>
<script>
function switchRole(targetRole) {
    fetch('<?= Router\Router::route("switch-role") ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ role: targetRole })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.redirect) {
            window.location.href = data.redirect;
        } else {
            if (typeof showToast === 'function') showToast(data.error || 'Erreur lors de la bascule de rôle', 'error');
            else alert(data.error || 'Erreur lors de la bascule de rôle');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        if (typeof showToast === 'function') showToast('Erreur lors de la bascule de rôle', 'error');
        else alert('Erreur lors de la bascule de rôle');
    });
}
</script>
