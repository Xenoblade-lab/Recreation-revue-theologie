<?php
$volume = $volume ?? null;
$revues = $revues ?? [];
$error = $error ?? null;
$base = $base ?? '';
if (!$volume) return;
$id = (int) $volume['id'];
?>
<div class="dashboard-header">
  <h1><?= htmlspecialchars(__('admin.volume_detail')) ?> — <?= htmlspecialchars($volume['annee'] ?? '') ?> <?= htmlspecialchars($volume['numero_volume'] ?? '') ?></h1>
  <p><a href="<?= $base ?>/admin/volumes"><?= htmlspecialchars(__('admin.back_list')) ?></a></p>
</div>
<?php if ($error): ?>
<p class="text-accent mb-4"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<div class="dashboard-card">
  <h2><?= htmlspecialchars(__('admin.edit_volume')) ?></h2>
  <form method="post" action="<?= $base ?>/admin/volume/<?= $id ?>" class="space-y-4">
    <?= csrf_field() ?>
    <div>
      <label for="annee" class="block font-medium mb-1"><?= htmlspecialchars(__('admin.volume_year')) ?></label>
      <input type="number" id="annee" name="annee" value="<?= htmlspecialchars($volume['annee'] ?? '') ?>" min="1900" max="2100" class="input w-full">
    </div>
    <div>
      <label for="numero_volume" class="block font-medium mb-1"><?= htmlspecialchars(__('admin.volume_number')) ?></label>
      <input type="text" id="numero_volume" name="numero_volume" value="<?= htmlspecialchars($volume['numero_volume'] ?? '') ?>" class="input w-full">
    </div>
    <div>
      <label for="description" class="block font-medium mb-1"><?= htmlspecialchars(__('admin.volume_description')) ?></label>
      <textarea id="description" name="description" rows="4" class="input w-full"><?= htmlspecialchars($volume['description'] ?? '') ?></textarea>
    </div>
    <div>
      <label for="redacteur_chef" class="block font-medium mb-1"><?= htmlspecialchars(__('admin.chief_editor')) ?></label>
      <input type="text" id="redacteur_chef" name="redacteur_chef" value="<?= htmlspecialchars($volume['redacteur_chef'] ?? '') ?>" class="input w-full">
    </div>
    <button type="submit" class="btn btn-primary"><?= htmlspecialchars(__('admin.save')) ?></button>
  </form>
</div>
<div class="dashboard-card mt-4">
  <h2><?= htmlspecialchars(__('admin.numeros')) ?></h2>
  <?php if (empty($revues)): ?>
  <p class="text-muted"><?= htmlspecialchars(__('admin.no_issue_volume')) ?></p>
  <?php else: ?>
  <ul class="list-none p-0">
    <?php foreach ($revues as $r): ?>
    <li class="mb-2"><a href="<?= $base ?>/admin/numero/<?= (int) $r['id'] ?>"><?= htmlspecialchars($r['numero'] ?? '') ?> — <?= htmlspecialchars($r['titre'] ?? '') ?></a></li>
    <?php endforeach; ?>
  </ul>
  <?php endif; ?>
</div>
