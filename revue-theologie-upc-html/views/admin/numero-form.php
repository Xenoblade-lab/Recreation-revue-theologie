<?php
$numero = $numero ?? null;
$volumes = $volumes ?? [];
$isCreate = !empty($isCreate);
$error = $error ?? null;
$base = $base ?? '';
?>
<div class="dashboard-header">
  <h1><?= htmlspecialchars(__('admin.create_numero_title')) ?></h1>
  <p><a href="<?= $base ?>/admin/volumes"><?= htmlspecialchars(__('admin.back_list')) ?></a></p>
</div>
<?php if ($error): ?>
<div class="alert alert-error mb-4"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<div class="dashboard-card">
  <h2><?= htmlspecialchars(__('admin.create_numero_title')) ?></h2>
  <form method="post" action="<?= $base ?>/admin/numeros" class="space-y-4">
    <?= csrf_field() ?>
    <div>
      <label for="volume_id" class="block font-medium mb-1"><?= htmlspecialchars(__('admin.numero_volume')) ?></label>
      <select id="volume_id" name="volume_id" class="input w-full" required>
        <option value="">— <?= htmlspecialchars(__('admin.select_volume')) ?> —</option>
        <?php foreach ($volumes as $v): ?>
          <option value="<?= (int) $v['id'] ?>" <?= (isset($numero['volume_id']) && (int)$numero['volume_id'] === (int)$v['id']) ? 'selected' : '' ?>><?= htmlspecialchars(($v['annee'] ?? '') . ' — ' . ($v['numero_volume'] ?? '')) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label for="numero" class="block font-medium mb-1"><?= htmlspecialchars(__('admin.numero_label')) ?></label>
      <input type="text" id="numero" name="numero" value="<?= htmlspecialchars($numero['numero'] ?? '') ?>" class="input w-full" required>
    </div>
    <div>
      <label for="titre" class="block font-medium mb-1"><?= htmlspecialchars(__('author.th_title')) ?></label>
      <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($numero['titre'] ?? '') ?>" class="input w-full" required>
    </div>
    <div>
      <label for="description" class="block font-medium mb-1"><?= htmlspecialchars(__('admin.volume_description')) ?></label>
      <textarea id="description" name="description" rows="4" class="input w-full"><?= htmlspecialchars($numero['description'] ?? '') ?></textarea>
    </div>
    <div>
      <label for="date_publication" class="block font-medium mb-1"><?= htmlspecialchars(__('admin.th_pub_date')) ?></label>
      <input type="text" id="date_publication" name="date_publication" value="<?= htmlspecialchars($numero['date_publication'] ?? '') ?>" class="input w-full" placeholder="ex. 2024 ou Janvier 2024">
    </div>
    <button type="submit" class="btn btn-primary"><?= htmlspecialchars(__('admin.create_numero_submit')) ?></button>
  </form>
</div>
