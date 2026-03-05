<?php
$members = $members ?? [];
$base = $base ?? '';
$error = $error ?? null;
$success = $success ?? null;
?>
<div class="dashboard-header flex flex-wrap items-center justify-between gap-4">
  <div>
    <h1><?= htmlspecialchars(__('admin.comite_editorial_title')) ?></h1>
    <p class="text-muted text-sm"><?= htmlspecialchars(__('admin.comite_editorial_intro')) ?></p>
  </div>
  <a href="<?= $base ?>/admin/comite-editorial/create" class="btn btn-primary"><?= htmlspecialchars(__('admin.comite_add_member')) ?></a>
</div>
<?php if ($error): ?>
<div class="alert alert-error mb-4"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if ($success): ?>
<div class="alert alert-success mb-4"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<div class="dashboard-card">
  <div class="overflow-auto">
    <table class="dashboard-table">
      <thead>
        <tr>
          <th><?= htmlspecialchars(__('admin.comite_th_order')) ?></th>
          <th><?= htmlspecialchars(__('admin.th_name')) ?></th>
          <th><?= htmlspecialchars(__('admin.th_email')) ?></th>
          <th><?= htmlspecialchars(__('admin.comite_th_titre')) ?></th>
          <th><?= htmlspecialchars(__('admin.comite_th_active')) ?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($members)): ?>
          <tr><td colspan="6" class="text-muted"><?= htmlspecialchars(__('admin.comite_no_members')) ?></td></tr>
        <?php else: ?>
          <?php foreach ($members as $m):
              $mid = (int) $m['id'];
              $modifyLabel = htmlspecialchars(__('admin.modify'));
              $removeLabel = htmlspecialchars(__('admin.comite_remove'));
              $confirmRemove = __('admin.comite_confirm_remove');
          ?>
            <tr>
              <td data-label="<?= htmlspecialchars(__('admin.comite_th_order'), ENT_QUOTES, 'UTF-8') ?>"><?= (int) ($m['ordre'] ?? 0) ?></td>
              <td data-label="<?= htmlspecialchars(__('admin.th_name'), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars(trim(($m['prenom'] ?? '') . ' ' . ($m['nom'] ?? ''))) ?></td>
              <td data-label="<?= htmlspecialchars(__('admin.th_email'), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($m['email'] ?? '') ?></td>
              <td data-label="<?= htmlspecialchars(__('admin.comite_th_titre'), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($m['titre_affiche'] ?? '—') ?></td>
              <td data-label="<?= htmlspecialchars(__('admin.comite_th_active'), ENT_QUOTES, 'UTF-8') ?>">
                <span class="badge <?= !empty($m['actif']) ? 'badge green' : 'badge' ?>">
                  <?= !empty($m['actif']) ? htmlspecialchars(__('admin.comite_actif')) : htmlspecialchars(__('admin.comite_inactif')) ?>
                </span>
              </td>
              <td class="actions-cell" data-label="<?= htmlspecialchars(__('admin.actions'), ENT_QUOTES, 'UTF-8') ?>">
                <div class="action-buttons">
                  <a href="<?= $base ?>/admin/comite-editorial/<?= $mid ?>/edit" class="btn-icon" title="<?= $modifyLabel ?>" aria-label="<?= $modifyLabel ?>">
                    <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#pencil"/></svg>
                  </a>
                  <form method="post" action="<?= $base ?>/admin/comite-editorial/<?= $mid ?>/delete" class="inline-form js-confirm-submit" style="display:inline;" data-confirm-message="<?= htmlspecialchars($confirmRemove, ENT_QUOTES, 'UTF-8') ?>">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn-icon btn-icon-danger" title="<?= $removeLabel ?>" aria-label="<?= $removeLabel ?>">
                      <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#trash"/></svg>
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
<p class="text-sm text-muted"><a href="<?= $base ?>/admin"><?= htmlspecialchars(__('admin.back_list')) ?></a></p>
