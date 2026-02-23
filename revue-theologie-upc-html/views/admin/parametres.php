<?php
$info = $info ?? null;
$success = !empty($success);
$base = $base ?? '';

$nom = $info['nom_officiel'] ?? 'Revue de Théologie de l\'UPC';
$description = $info['description'] ?? '';
$ligne_editoriale = $info['ligne_editoriale'] ?? '';
$objectifs = $info['objectifs'] ?? '';
$domaines_couverts = $info['domaines_couverts'] ?? '';
$issn = $info['issn'] ?? '';
$comite_scientifique = $info['comite_scientifique'] ?? '';
$comite_redaction = $info['comite_redaction'] ?? '';
?>
<div class="dashboard-header">
  <h1><?= htmlspecialchars(__('admin.params_title')) ?></h1>
  <p><?= htmlspecialchars(__('admin.params_intro')) ?></p>
</div>
<?php if ($success): ?>
  <div class="alert green mb-4"><?= htmlspecialchars(__('admin.params_saved')) ?></div>
<?php endif; ?>
<div class="dashboard-card">
  <form method="post" action="<?= $base ?>/admin/parametres" class="space-y-4">
    <div>
      <label for="nom_officiel" class="block text-sm font-medium mb-1"><?= htmlspecialchars(__('admin.official_name')) ?></label>
      <input type="text" id="nom_officiel" name="nom_officiel" value="<?= htmlspecialchars($nom) ?>" class="input w-full">
    </div>
    <div>
      <label for="description" class="block text-sm font-medium mb-1"><?= htmlspecialchars(__('admin.description')) ?></label>
      <textarea id="description" name="description" rows="4" class="input w-full"><?= htmlspecialchars($description) ?></textarea>
    </div>
    <div>
      <label for="ligne_editoriale" class="block text-sm font-medium mb-1"><?= htmlspecialchars(__('admin.editorial_line')) ?></label>
      <textarea id="ligne_editoriale" name="ligne_editoriale" rows="4" class="input w-full"><?= htmlspecialchars($ligne_editoriale) ?></textarea>
    </div>
    <div>
      <label for="objectifs" class="block text-sm font-medium mb-1"><?= htmlspecialchars(__('admin.objectives')) ?></label>
      <textarea id="objectifs" name="objectifs" rows="3" class="input w-full"><?= htmlspecialchars($objectifs) ?></textarea>
    </div>
    <div>
      <label for="domaines_couverts" class="block text-sm font-medium mb-1"><?= htmlspecialchars(__('admin.domains_covered')) ?></label>
      <textarea id="domaines_couverts" name="domaines_couverts" rows="3" class="input w-full"><?= htmlspecialchars($domaines_couverts) ?></textarea>
    </div>
    <div>
      <label for="issn" class="block text-sm font-medium mb-1"><?= htmlspecialchars(__('admin.issn')) ?></label>
      <input type="text" id="issn" name="issn" value="<?= htmlspecialchars($issn) ?>" class="input w-full">
    </div>
    <div>
      <label for="comite_scientifique" class="block text-sm font-medium mb-1"><?= htmlspecialchars(__('admin.scientific_committee')) ?></label>
      <textarea id="comite_scientifique" name="comite_scientifique" rows="4" class="input w-full"><?= htmlspecialchars($comite_scientifique) ?></textarea>
    </div>
    <div>
      <label for="comite_redaction" class="block text-sm font-medium mb-1"><?= htmlspecialchars(__('admin.editorial_committee')) ?></label>
      <textarea id="comite_redaction" name="comite_redaction" rows="4" class="input w-full"><?= htmlspecialchars($comite_redaction) ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary"><?= htmlspecialchars(__('admin.save_params')) ?></button>
  </form>
</div>
