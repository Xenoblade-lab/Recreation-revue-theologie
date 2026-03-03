<?php
$user = $user ?? null;
$base = $base ?? '';
if (!$user) return;

function adminRoleLabel(string $role): string {
    $roleKeys = [
        'admin' => 'admin.role_admin',
        'auteur' => 'admin.role_author',
        'redacteur' => 'admin.role_redacteur',
        'redacteur en chef' => 'admin.role_redacteur_chef',
        'user' => 'admin.role_user',
    ];
    return function_exists('__') ? __($roleKeys[$role] ?? 'admin.role_user') : $role;
}
$nom = $user['nom'] ?? '';
$prenom = $user['prenom'] ?? '';
$email = $user['email'] ?? '';
$role = $user['role'] ?? 'user';
$statut = $user['statut'] ?? 'actif';
$createdAt = $user['created_at'] ?? null;
?>
<div class="dashboard-header flex flex-wrap items-center justify-between gap-4">
  <div>
    <h1><?= htmlspecialchars(__('admin.user_detail')) ?></h1>
    <p><a href="<?= $base ?>/admin/users" class="text-primary"><?= htmlspecialchars(__('admin.back_list')) ?></a></p>
  </div>
  <a href="<?= $base ?>/admin/users/<?= (int) $user['id'] ?>/edit" class="btn btn-primary"><?= htmlspecialchars(__('admin.modify')) ?></a>
</div>
<div class="dashboard-card">
  <h2><?= htmlspecialchars(trim($prenom . ' ' . $nom)) ?: __('admin.th_name') ?></h2>
  <dl class="space-y-3">
    <div>
      <dt class="text-sm font-medium text-muted"><?= htmlspecialchars(__('admin.label_prenom')) ?></dt>
      <dd><?= htmlspecialchars($prenom) ?></dd>
    </div>
    <div>
      <dt class="text-sm font-medium text-muted"><?= htmlspecialchars(__('admin.label_nom')) ?></dt>
      <dd><?= htmlspecialchars($nom) ?></dd>
    </div>
    <div>
      <dt class="text-sm font-medium text-muted"><?= htmlspecialchars(__('admin.label_email')) ?></dt>
      <dd><?= htmlspecialchars($email) ?></dd>
    </div>
    <div>
      <dt class="text-sm font-medium text-muted"><?= htmlspecialchars(__('admin.label_role')) ?></dt>
      <dd><?= htmlspecialchars(adminRoleLabel($role)) ?></dd>
    </div>
    <div>
      <dt class="text-sm font-medium text-muted"><?= htmlspecialchars(__('admin.label_statut')) ?></dt>
      <dd><span class="badge <?= $statut === 'actif' ? 'badge green' : 'badge' ?>"><?= $statut === 'actif' ? htmlspecialchars(__('admin.status_active')) : htmlspecialchars(__('admin.status_inactive')) ?></span></dd>
    </div>
    <?php if ($createdAt): ?>
    <div>
      <dt class="text-sm font-medium text-muted"><?= htmlspecialchars(__('admin.th_registered')) ?></dt>
      <dd><?= date('d/m/Y H:i', strtotime($createdAt)) ?></dd>
    </div>
    <?php endif; ?>
  </dl>
  <p class="mt-4"><a href="<?= $base ?>/admin/users/<?= (int) $user['id'] ?>/edit" class="btn btn-outline"><?= htmlspecialchars(__('admin.edit_user')) ?></a></p>
</div>
