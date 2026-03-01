<?php
$member = $member ?? null;
$candidates = $candidates ?? [];
$error = $error ?? null;
$old = $old ?? [];
$base = $base ?? '';
$isEdit = $member !== null;

$ordre = $isEdit ? (int) ($member['ordre'] ?? 0) : (int) ($old['ordre'] ?? 0);
$titreAffiche = $isEdit ? ($member['titre_affiche'] ?? '') : ($old['titre_affiche'] ?? '');
$actif = $isEdit ? !empty($member['actif']) : (isset($old['actif']) ? (bool) $old['actif'] : true);
$userId = $isEdit ? (int) ($member['user_id'] ?? 0) : (int) ($old['user_id'] ?? 0);
?>
<div class="dashboard-header">
  <h1><?= $isEdit ? htmlspecialchars(__('admin.comite_edit_member')) : htmlspecialchars(__('admin.comite_add_member')) ?></h1>
  <p><a href="<?= $base ?>/admin/comite-editorial" class="text-primary"><?= htmlspecialchars(__('admin.back_list')) ?></a></p>
</div>
<div class="dashboard-card">
  <?php if ($error): ?>
    <div class="alert alert-error mb-4"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <form method="post" action="<?= $isEdit ? $base . '/admin/comite-editorial/' . (int) $member['id'] : $base . '/admin/comite-editorial/create' ?>" class="space-y-4">
    <?= csrf_field() ?>
    <?php if ($isEdit): ?>
      <div>
        <label class="block text-sm font-medium mb-1"><?= htmlspecialchars(__('admin.th_name')) ?></label>
        <p class="input w-full" style="background: var(--muted); cursor: not-allowed;"><?= htmlspecialchars(trim(($member['prenom'] ?? '') . ' ' . ($member['nom'] ?? ''))) ?> (<?= htmlspecialchars($member['email'] ?? '') ?>)</p>
      </div>
    <?php else: ?>
      <div>
        <label for="user_id" class="block text-sm font-medium mb-1"><?= htmlspecialchars(__('admin.comite_select_user')) ?></label>
        <select id="user_id" name="user_id" class="input w-full" required>
          <option value="">— <?= htmlspecialchars(__('admin.comite_choose_user')) ?> —</option>
          <?php foreach ($candidates as $c): ?>
            <option value="<?= (int) $c['id'] ?>" <?= $userId === (int) $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars(trim(($c['prenom'] ?? '') . ' ' . ($c['nom'] ?? ''))) ?> (<?= htmlspecialchars($c['email'] ?? '') ?>)</option>
          <?php endforeach; ?>
        </select>
        <p class="text-sm text-muted mt-1"><?= htmlspecialchars(__('admin.comite_only_reviewers')) ?></p>
      </div>
    <?php endif; ?>
    <div>
      <label for="ordre" class="block text-sm font-medium mb-1"><?= htmlspecialchars(__('admin.comite_th_order')) ?></label>
      <input type="number" id="ordre" name="ordre" value="<?= $ordre ?>" min="0" class="input w-full" style="width: auto;">
    </div>
    <div>
      <label for="titre_affiche" class="block text-sm font-medium mb-1"><?= htmlspecialchars(__('admin.comite_th_titre')) ?></label>
      <input type="text" id="titre_affiche" name="titre_affiche" value="<?= htmlspecialchars($titreAffiche) ?>" class="input w-full" placeholder="<?= htmlspecialchars(__('admin.comite_titre_placeholder')) ?>">
    </div>
    <div>
      <label class="flex items-center gap-2 cursor-pointer">
        <input type="checkbox" name="actif" value="1" <?= $actif ? 'checked' : '' ?>>
        <span><?= htmlspecialchars(__('admin.comite_active_member')) ?></span>
      </label>
      <p class="text-sm text-muted mt-1"><?= htmlspecialchars(__('admin.comite_active_help')) ?></p>
    </div>
    <div class="flex gap-2">
      <button type="submit" class="btn btn-primary"><?= htmlspecialchars(__('admin.save')) ?></button>
      <a href="<?= $base ?>/admin/comite-editorial" class="btn btn-outline"><?= htmlspecialchars(__('common.cancel')) ?></a>
    </div>
  </form>
</div>
