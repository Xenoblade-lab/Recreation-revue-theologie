<?php
$abonnementActif = $abonnementActif ?? false;
$error = $error ?? null;
$old = $old ?? [];
$base = $base ?? '';
$titre = $old['titre'] ?? '';
$contenu = $old['contenu'] ?? '';
?>
<div class="dashboard-header">
  <h1><?= htmlspecialchars(__('author.submit_article')) ?></h1>
  <p><?= htmlspecialchars(__('author.submit_intro')) ?></p>
</div>
<div class="dashboard-card">
  <?php if (!$abonnementActif): ?>
  <p class="text-muted mb-4"><?= htmlspecialchars(__('author.subscription_may_be_required')) ?></p>
  <?php endif; ?>
  <?php if ($error): ?>
  <p class="text-accent mb-4"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>
  <form action="<?= $base ?>/author/soumettre" method="post" enctype="multipart/form-data" class="space-y-4">
    <?= csrf_field() ?>
    <div>
      <label for="titre" class="block text-sm font-medium mb-1"><?= htmlspecialchars(__('author.article_title_label')) ?> *</label>
      <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($titre) ?>" placeholder="<?= htmlspecialchars(__('author.article_title_placeholder')) ?>" required class="input w-full">
    </div>
    <div>
      <label for="contenu" class="block text-sm font-medium mb-1"><?= htmlspecialchars(__('author.summary_content')) ?> *</label>
      <textarea id="contenu" name="contenu" rows="6" placeholder="<?= htmlspecialchars(__('author.summary_placeholder')) ?>" required class="input w-full"><?= htmlspecialchars($contenu) ?></textarea>
    </div>
    <div>
      <label for="fichier" class="block text-sm font-medium mb-1"><?= htmlspecialchars(__('author.file_label')) ?></label>
      <input type="file" id="fichier" name="fichier" accept=".pdf,.doc,.docx" class="input w-full" style="font-size:0.875rem; max-width:100%;">
      <p class="text-xs text-muted mt-1"><?= htmlspecialchars(__('author.formats_accepted')) ?></p>
    </div>
    <div class="form-actions flex flex-wrap gap-2">
      <button type="submit" class="btn btn-accent"><?= htmlspecialchars(__('author.submit_article_btn')) ?></button>
      <a href="<?= $base ?>/instructions-auteurs" class="btn btn-outline"><?= htmlspecialchars(__('nav.instructions')) ?></a>
    </div>
  </form>
</div>
