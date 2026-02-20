<?php
$user = $_SESSION['user'] ?? null;
$userName = $user ? trim(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '')) : 'Utilisateur';
$userEmail = $user['email'] ?? '';
$initials = 'U';
if ($user) {
    $p = $user['prenom'] ?? '';
    $n = $user['nom'] ?? '';
    $initials = strtoupper(substr($p, 0, 1) . substr($n, 0, 1));
}
$baseUrl = Router\Router::$defaultUri;
?>
<div class="dash-topbar">
    <div class="dash-topbar__brand">
        <img src="<?= $baseUrl ?>assets/logo_upc.png" alt="UPC Logo">
        <div>
            <div class="dash-topbar__title">Espace Auteur</div>
            <div class="dash-topbar__subtitle">Revue de la Faculté de Théologie</div>
        </div>
    </div>

    <div class="dash-topbar__actions">
        <a class="dash-topbar__link" href="<?= Router\Router::route('') ?>">Site public</a>
        <a class="dash-topbar__link" href="<?= Router\Router::route('submit') ?>">Soumettre un article</a>

        <div class="dash-topbar__user">
            <div class="dash-topbar__avatar"><?= htmlspecialchars($initials) ?></div>
            <div class="dash-topbar__user-info">
                <div class="dash-topbar__user-name"><?= htmlspecialchars($userName) ?></div>
                <div class="dash-topbar__user-email"><?= htmlspecialchars($userEmail) ?></div>
            </div>
        </div>

        <a class="dash-topbar__button" href="<?= Router\Router::route('logout') ?>">Se déconnecter</a>
    </div>
</div>

