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
  <form action="<?= $base ?>/author/soumettre" method="post" enctype="multipart/form-data" class="flex flex-col gap-4">
    <?= csrf_field() ?>
    <div class="form-group">
      <label for="titre"><?= htmlspecialchars(__('author.article_title_label')) ?> *</label>
      <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($titre) ?>" placeholder="<?= htmlspecialchars(__('author.article_title_placeholder')) ?>" required class="h-11">
    </div>
    <div class="form-group">
      <label for="contenu"><?= htmlspecialchars(__('author.summary_content')) ?> *</label>
      <textarea id="contenu" name="contenu" rows="6" placeholder="<?= htmlspecialchars(__('author.summary_placeholder')) ?>" required><?= htmlspecialchars($contenu) ?></textarea>
    </div>
    <div class="form-group">
      <label for="fichier"><?= htmlspecialchars(__('author.file_label')) ?></label>
      <input type="file" id="fichier" name="fichier" accept=".pdf,.doc,.docx" style="font-size:0.875rem;">
      <p class="text-xs text-muted mt-1"><?= htmlspecialchars(__('author.formats_accepted')) ?></p>
    </div>
    <div class="wrap-row">
      <button type="submit" class="btn btn-accent"><?= htmlspecialchars(__('author.submit_article_btn')) ?></button>
      <a href="<?= $base ?>/instructions-auteurs" class="btn btn-outline"><?= htmlspecialchars(__('nav.instructions')) ?></a>
    </div>
  </form>
</div>
