<?php
$numero = $numero ?? null;
$volume = $volume ?? null;
$error = $error ?? null;
$base = $base ?? '';
if (!$numero) return;
$id = (int) $numero['id'];
?>
<div class="dashboard-header">
  <h1><?= htmlspecialchars(__('admin.numero_detail')) ?> — <?= htmlspecialchars($numero['numero'] ?? '') ?></h1>
  <p>
    <?php if ($volume): ?><a href="<?= $base ?>/admin/volume/<?= (int) $volume['id'] ?>"><?= htmlspecialchars(__('common.volume')) ?> <?= htmlspecialchars($volume['annee'] ?? '') ?></a> · <?php endif; ?>
    <a href="<?= $base ?>/admin/volumes"><?= htmlspecialchars(__('admin.back_list')) ?></a>
  </p>
</div>
<?php if ($error): ?>
<p class="text-accent mb-4"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<div class="dashboard-card">
  <h2><?= htmlspecialchars(__('admin.edit_numero')) ?></h2>
  <form method="post" action="<?= $base ?>/admin/numero/<?= $id ?>" class="space-y-4">
    <?= csrf_field() ?>
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
    <button type="submit" class="btn btn-primary"><?= htmlspecialchars(__('admin.save')) ?></button>
  </form>
</div>
