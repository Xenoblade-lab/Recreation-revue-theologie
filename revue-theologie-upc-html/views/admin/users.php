<?php
$users = $users ?? [];
$base = $base ?? '';

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
?>
<div class="dashboard-header flex flex-wrap items-center justify-between gap-4">
  <div>
    <h1><?= htmlspecialchars(__('admin.users')) ?></h1>
    <p><?= htmlspecialchars(__('admin.users_intro')) ?></p>
  </div>
  <a href="<?= $base ?>/admin/users/create" class="btn btn-primary"><?= htmlspecialchars(__('admin.create_user')) ?></a>
</div>
<div class="dashboard-card">
  <div class="overflow-auto">
    <table class="dashboard-table">
      <thead>
        <tr>
          <th><?= htmlspecialchars(__('admin.th_name')) ?></th>
          <th><?= htmlspecialchars(__('admin.th_email')) ?></th>
          <th><?= htmlspecialchars(__('admin.th_role')) ?></th>
          <th><?= htmlspecialchars(__('admin.th_status')) ?></th>
          <th><?= htmlspecialchars(__('admin.th_registered')) ?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($users)): ?>
          <tr><td colspan="6" class="text-muted"><?= htmlspecialchars(__('admin.no_users')) ?></td></tr>
        <?php else: ?>
          <?php foreach ($users as $u): ?>
            <tr>
              <td><?= htmlspecialchars(trim(($u['prenom'] ?? '') . ' ' . ($u['nom'] ?? ''))) ?></td>
              <td><?= htmlspecialchars($u['email'] ?? '') ?></td>
              <td><?= htmlspecialchars(adminRoleLabel($u['role'] ?? 'user')) ?></td>
              <td><span class="badge <?= ($u['statut'] ?? '') === 'actif' ? 'badge green' : 'badge' ?>"><?= htmlspecialchars(($u['statut'] ?? '') === 'actif' ? __('admin.status_active') : __('admin.status_inactive')) ?></span></td>
              <td><?= !empty($u['created_at']) ? date('d/m/Y', strtotime($u['created_at'])) : '—' ?></td>
              <td><a href="<?= $base ?>/admin/users/<?= (int) ($u['id'] ?? 0) ?>/edit" class="btn btn-sm btn-outline"><?= htmlspecialchars(__('admin.modify')) ?></a></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
