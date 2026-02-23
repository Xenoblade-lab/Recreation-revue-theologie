<aside class="dashboard-sidebar" id="sidebar">
    <?php
    $userName = isset($user) ? trim(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '')) : 'Évaluateur';
    $userEmail = $user['email'] ?? '';
    $userInitials = isset($user) ? strtoupper(substr($user['prenom'] ?? '', 0, 1) . substr($user['nom'] ?? '', 0, 1)) : 'EV';
    // Articles assignés = en attente + en cours
    $pendingCount = ($stats['pending'] ?? 0) + ($stats['in_progress'] ?? 0);
    $completedCount = $stats['completed'] ?? 0;
    
    // Vérifier si l'utilisateur est admin (rôle principal)
    $principalRole = strtolower($user['role'] ?? '');
    $activeRole = strtolower($_SESSION['active_role'] ?? $_SESSION['user_role'] ?? $principalRole);
    $isAdmin = ($principalRole === 'admin');
    ?>
    
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <img src="<?= Router\Router::$defaultUri ?>assets/logo_upc.png" alt="UPC Logo" onerror="this.style.display='none'">
            <h2>Évaluateur</h2>
        </div>
        <div class="user-info">
            <div class="user-avatar"><?= htmlspecialchars($userInitials) ?></div>
            <div class="user-details">
                <h3><?= htmlspecialchars($userName) ?></h3>
                <p>Évaluateur</p>
            </div>
        </div>
        <?php if ($isAdmin && $activeRole !== 'admin'): ?>
            <div class="role-switch-container" style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(255, 255, 255, 0.1);">
                <button onclick="switchRole('admin')" class="role-switch-btn" style="width: 100%; padding: 0.75rem; font-size: 0.875rem; background: rgba(59, 130, 246, 0.2); border: 1px solid rgba(59, 130, 246, 0.5); border-radius: 6px; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.2s;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 16px; height: 16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    <span>Revenir à Admin</span>
                </button>
            </div>
        <?php endif; ?>
    </div>

    <nav class="sidebar-nav">

        <div class="nav-section">
            <div class="nav-section-title">Principal</div>
            <a href="<?= \Router\Router::route("reviewer") ?>" class="nav-item <?= ($current_page ?? '') === 'dashboard' ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Tableau de bord</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Évaluations</div>
            <a href="<?= \Router\Router::route("reviewer") ?>#assigned" class="nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span>Articles assignés</span>
                <span class="badge"><?= htmlspecialchars($pendingCount) ?></span>
            </a>
            <a href="<?= \Router\Router::route("reviewer") ?>/terminees" class="nav-item <?= ($current_page ?? '') === 'terminees' ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Évaluations terminées</span>
                <span class="badge"><?= htmlspecialchars($completedCount) ?></span>
            </a>
            <a href="<?= \Router\Router::route("reviewer") ?>/historique" class="nav-item <?= ($current_page ?? '') === 'historique' ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Historique</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Publications</div>
            <a href="<?= \Router\Router::route("reviewer") ?>/publications" class="nav-item <?= ($current_page ?? '') === 'publications' ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Articles publiés</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Compte</div>
            <a href="<?= \Router\Router::route("reviewer") ?>/profil" class="nav-item <?= ($current_page ?? '') === 'profil' ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Mon profil</span>
            </a>
            <a href="<?= \Router\Router::route("logout") ?>" class="nav-item">
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
    background: rgba(59, 130, 246, 0.3) !important;
    border-color: rgba(59, 130, 246, 0.7) !important;
    transform: translateY(-1px);
}
.role-switch-container {
    position: relative;
    z-index: 1;
}
</style>
<script src="<?= \Router\Router::$defaultUri ?>js/author-notify.js"></script>
<script>
function switchRole(targetRole) {
    fetch('<?= \Router\Router::route("switch-role") ?>', {
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

