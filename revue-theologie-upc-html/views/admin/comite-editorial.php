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
          <?php foreach ($members as $m): ?>
            <tr>
              <td><?= (int) ($m['ordre'] ?? 0) ?></td>
              <td><?= htmlspecialchars(trim(($m['prenom'] ?? '') . ' ' . ($m['nom'] ?? ''))) ?></td>
              <td><?= htmlspecialchars($m['email'] ?? '') ?></td>
              <td><?= htmlspecialchars($m['titre_affiche'] ?? '—') ?></td>
              <td>
                <span class="badge <?= !empty($m['actif']) ? 'badge green' : 'badge' ?>">
                  <?= !empty($m['actif']) ? htmlspecialchars(__('admin.comite_actif')) : htmlspecialchars(__('admin.comite_inactif')) ?>
                </span>
              </td>
              <td class="wrap-row">
                <a href="<?= $base ?>/admin/comite-editorial/<?= (int) $m['id'] ?>/edit" class="btn btn-sm btn-outline"><?= htmlspecialchars(__('admin.modify')) ?></a>
                <form method="post" action="<?= $base ?>/admin/comite-editorial/<?= (int) $m['id'] ?>/delete" class="inline" onsubmit="return confirm('<?= htmlspecialchars(__('admin.comite_confirm_remove')) ?>');">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-sm" style="color: var(--accent);"><?= htmlspecialchars(__('admin.comite_remove')) ?></button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<p class="text-sm text-muted"><a href="<?= $base ?>/admin"><?= htmlspecialchars(__('admin.back_list')) ?></a></p>
