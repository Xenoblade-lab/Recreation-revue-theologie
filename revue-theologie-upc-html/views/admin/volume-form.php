<?php
$volume = $volume ?? null;
$isCreate = !empty($isCreate);
$error = $error ?? null;
$base = $base ?? '';
?>
<div class="dashboard-header">
  <h1><?= $isCreate ? htmlspecialchars(__('admin.create_volume_title')) : htmlspecialchars(__('admin.edit_volume')) ?></h1>
  <p><a href="<?= $base ?>/admin/volumes"><?= htmlspecialchars(__('admin.back_list')) ?></a></p>
</div>
<?php if ($error): ?>
<div class="alert alert-error mb-4"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<div class="dashboard-card">
  <h2><?= $isCreate ? htmlspecialchars(__('admin.create_volume_title')) : htmlspecialchars(__('admin.edit_volume')) ?></h2>
  <form method="post" action="<?= $isCreate ? $base . '/admin/volumes' : $base . '/admin/volume/' . (int)($volume['id'] ?? 0) ?>" class="space-y-4">
    <?= csrf_field() ?>
    <div>
      <label for="annee" class="block font-medium mb-1"><?= htmlspecialchars(__('admin.volume_year')) ?></label>
      <input type="number" id="annee" name="annee" value="<?= htmlspecialchars($volume['annee'] ?? date('Y')) ?>" min="1900" max="2100" class="input w-full" required>
    </div>
    <div>
      <label for="numero_volume" class="block font-medium mb-1"><?= htmlspecialchars(__('admin.volume_number')) ?></label>
      <input type="text" id="numero_volume" name="numero_volume" value="<?= htmlspecialchars($volume['numero_volume'] ?? '') ?>" class="input w-full" required>
    </div>
    <div>
      <label for="description" class="block font-medium mb-1"><?= htmlspecialchars(__('admin.volume_description')) ?></label>
      <textarea id="description" name="description" rows="4" class="input w-full"><?= htmlspecialchars($volume['description'] ?? '') ?></textarea>
    </div>
    <div>
      <label for="redacteur_chef" class="block font-medium mb-1"><?= htmlspecialchars(__('admin.chief_editor')) ?></label>
      <input type="text" id="redacteur_chef" name="redacteur_chef" value="<?= htmlspecialchars($volume['redacteur_chef'] ?? '') ?>" class="input w-full">
    </div>
    <button type="submit" class="btn btn-primary"><?= $isCreate ? htmlspecialchars(__('admin.create_volume_submit')) : htmlspecialchars(__('admin.save')) ?></button>
  </form>
</div>
