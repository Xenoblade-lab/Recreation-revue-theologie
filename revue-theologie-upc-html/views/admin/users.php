<?php
$users = $users ?? [];
$base = $base ?? '';
$error = $error ?? null;
$success = $success ?? null;

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
<?php if ($error): ?>
  <div class="alert alert-error mb-4"><?= htmlspecialchars(is_string($error) ? $error : __('admin.error')) ?></div>
<?php endif; ?>
<?php if ($success): ?>
  <div class="alert alert-success mb-4"><?= htmlspecialchars(is_string($success) ? $success : __('admin.success')) ?></div>
<?php endif; ?>
<div class="dashboard-card dashboard-card-table">
  <div class="overflow-auto dashboard-table-wrap">
    <table class="dashboard-table">
      <thead>
        <tr>
          <th><?= htmlspecialchars(__('admin.th_name')) ?></th>
          <th><?= htmlspecialchars(__('admin.th_email')) ?></th>
          <th><?= htmlspecialchars(__('admin.th_role')) ?></th>
          <th><?= htmlspecialchars(__('admin.th_status')) ?></th>
          <th><?= htmlspecialchars(__('admin.th_registered')) ?></th>
          <th><?= htmlspecialchars(__('admin.actions')) ?></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($users)): ?>
          <tr><td colspan="6" class="text-muted"><?= htmlspecialchars(__('admin.no_users')) ?></td></tr>
        <?php else: ?>
          <?php foreach ($users as $u):
              $uid = (int) ($u['id'] ?? 0);
              $viewLabel = htmlspecialchars(__('common.read'));
              $modifyLabel = htmlspecialchars(__('admin.modify'));
              $deleteLabel = htmlspecialchars(__('admin.delete'));
              $confirmDeleteUser = htmlspecialchars(__('admin.confirm_delete_user'));
          ?>
            <tr>
              <td data-label="<?= htmlspecialchars(__('admin.th_name'), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars(trim(($u['prenom'] ?? '') . ' ' . ($u['nom'] ?? ''))) ?></td>
              <td data-label="<?= htmlspecialchars(__('admin.th_email'), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($u['email'] ?? '') ?></td>
              <td data-label="<?= htmlspecialchars(__('admin.th_role'), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars(adminRoleLabel($u['role'] ?? 'user')) ?></td>
              <td data-label="<?= htmlspecialchars(__('admin.th_status'), ENT_QUOTES, 'UTF-8') ?>"><span class="badge <?= ($u['statut'] ?? '') === 'actif' ? 'badge green' : 'badge' ?>"><?= htmlspecialchars(($u['statut'] ?? '') === 'actif' ? __('admin.status_active') : __('admin.status_inactive')) ?></span></td>
              <td data-label="<?= htmlspecialchars(__('admin.th_registered'), ENT_QUOTES, 'UTF-8') ?>"><?= !empty($u['created_at']) ? date('d/m/Y', strtotime($u['created_at'])) : '—' ?></td>
              <td class="actions-cell" data-label="<?= htmlspecialchars(__('admin.actions'), ENT_QUOTES, 'UTF-8') ?>">
                <div class="action-buttons">
                  <a href="<?= $base ?>/admin/users/<?= $uid ?>" class="btn-icon" title="<?= $viewLabel ?>" aria-label="<?= $viewLabel ?>">
                    <svg class="icon-svg icon-20" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                  </a>
                  <a href="<?= $base ?>/admin/users/<?= $uid ?>/edit" class="btn-icon" title="<?= $modifyLabel ?>" aria-label="<?= $modifyLabel ?>">
                    <svg class="icon-svg icon-20" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                  </a>
                  <form method="post" action="<?= $base ?>/admin/users/<?= $uid ?>/delete" class="inline-form js-confirm-submit" style="display:inline;" data-confirm-message="<?= htmlspecialchars($confirmDeleteUser, ENT_QUOTES, 'UTF-8') ?>">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn-icon btn-icon-danger" title="<?= $deleteLabel ?>" aria-label="<?= $deleteLabel ?>">
                      <svg class="icon-svg icon-20" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
