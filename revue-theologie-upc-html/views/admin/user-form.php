<?php
$user = $user ?? null;
$error = $error ?? null;
$old = $old ?? [];
$base = $base ?? '';
$isEdit = $user !== null;

$nom = $isEdit ? ($user['nom'] ?? '') : ($old['nom'] ?? '');
$prenom = $isEdit ? ($user['prenom'] ?? '') : ($old['prenom'] ?? '');
$email = $isEdit ? ($user['email'] ?? '') : ($old['email'] ?? '');
$role = $isEdit ? ($user['role'] ?? 'user') : ($old['role'] ?? 'user');
$statut = $isEdit ? ($user['statut'] ?? 'actif') : 'actif';
?>
<div class="dashboard-header">
  <h1><?= $isEdit ? __('admin.edit_user') : __('admin.create_user') ?></h1>
  <p><a href="<?= $base ?>/admin/users" class="text-primary"><?= htmlspecialchars(__('admin.back_list')) ?></a></p>
</div>
<div class="dashboard-card">
  <?php if ($error): ?>
    <div class="alert alert-error mb-4"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <form method="post" action="<?= $isEdit ? $base . '/admin/users/' . (int) $user['id'] . '/edit' : $base . '/admin/users/create' ?>" class="space-y-4">
    <?= csrf_field() ?>
    <div>
      <label for="nom" class="block text-sm font-medium mb-1"><?= htmlspecialchars(__('admin.label_nom')) ?></label>
      <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($nom) ?>" required class="input w-full">
    </div>
    <div>
      <label for="prenom" class="block text-sm font-medium mb-1"><?= htmlspecialchars(__('admin.label_prenom')) ?></label>
      <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($prenom) ?>" required class="input w-full">
    </div>
    <div>
      <label for="email" class="block text-sm font-medium mb-1"><?= htmlspecialchars(__('admin.label_email')) ?></label>
      <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required class="input w-full" <?= $isEdit ? '' : 'autocomplete="email"' ?>>
    </div>
    <div>
      <label for="role" class="block text-sm font-medium mb-1"><?= htmlspecialchars(__('admin.label_role')) ?></label>
      <select id="role" name="role" class="input w-full">
        <option value="user" <?= $role === 'user' ? 'selected' : '' ?>><?= htmlspecialchars(__('admin.role_user')) ?></option>
        <option value="auteur" <?= $role === 'auteur' ? 'selected' : '' ?>><?= htmlspecialchars(__('admin.role_author')) ?></option>
        <option value="redacteur" <?= $role === 'redacteur' ? 'selected' : '' ?>><?= htmlspecialchars(__('admin.role_redacteur')) ?></option>
        <option value="redacteur en chef" <?= $role === 'redacteur en chef' ? 'selected' : '' ?>><?= htmlspecialchars(__('admin.role_redacteur_chef')) ?></option>
        <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>><?= htmlspecialchars(__('admin.role_admin')) ?></option>
      </select>
    </div>
    <?php if ($isEdit): ?>
    <div>
      <label for="statut" class="block text-sm font-medium mb-1"><?= htmlspecialchars(__('admin.label_statut')) ?></label>
      <select id="statut" name="statut" class="input w-full">
        <option value="actif" <?= $statut === 'actif' ? 'selected' : '' ?>><?= htmlspecialchars(__('admin.status_active')) ?></option>
        <option value="inactif" <?= $statut === 'inactif' ? 'selected' : '' ?>><?= htmlspecialchars(__('admin.status_inactive')) ?></option>
      </select>
    </div>
    <div>
      <label for="password" class="block text-sm font-medium mb-1"><?= htmlspecialchars(__('admin.new_password_optional')) ?></label>
      <input type="password" id="password" name="password" class="input w-full" autocomplete="new-password">
    </div>
    <?php else: ?>
    <div>
      <label for="password" class="block text-sm font-medium mb-1"><?= htmlspecialchars(__('admin.label_password')) ?></label>
      <input type="password" id="password" name="password" required minlength="6" class="input w-full" autocomplete="new-password">
    </div>
    <?php endif; ?>
    <div class="flex gap-2">
      <button type="submit" class="btn btn-primary"><?= $isEdit ? __('admin.save') : __('admin.create') ?></button>
      <a href="<?= $base ?>/admin/users" class="btn btn-outline"><?= htmlspecialchars(__('common.cancel')) ?></a>
    </div>
  </form>
</div>
