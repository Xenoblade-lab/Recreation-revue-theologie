<?php
$article = $article ?? null;
$error = $error ?? null;
$base = $base ?? '';
if (!$article) return;
?>
<div class="dashboard-header">
  <h1><?= htmlspecialchars(__('author.edit_article')) ?></h1>
  <p><?= htmlspecialchars($article['titre']) ?></p>
</div>
<div class="dashboard-card">
  <?php if ($error): ?>
  <p class="text-accent mb-4"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>
  <form action="<?= $base ?>/author/article/<?= (int) $article['id'] ?>/edit" method="post" enctype="multipart/form-data" class="flex flex-col gap-4">
    <?= csrf_field() ?>
    <div class="form-group">
      <label for="titre"><?= htmlspecialchars(__('author.article_title_label')) ?> *</label>
      <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($article['titre']) ?>" required class="h-11">
    </div>
    <div class="form-group">
      <label for="contenu"><?= htmlspecialchars(__('author.summary_content')) ?> *</label>
      <textarea id="contenu" name="contenu" rows="6" required><?= htmlspecialchars($article['contenu'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
      <label for="fichier"><?= htmlspecialchars(__('author.new_file_optional')) ?></label>
      <input type="file" id="fichier" name="fichier" accept=".pdf,.doc,.docx" style="font-size:0.875rem;">
      <?php if (!empty($article['fichier_path'])): ?>
      <p class="text-xs text-muted mt-1"><?= htmlspecialchars(__('author.current_file')) ?> : <?= htmlspecialchars(basename($article['fichier_path'])) ?>. <?= htmlspecialchars(__('author.upload_replace')) ?></p>
      <?php endif; ?>
    </div>
    <div class="wrap-row">
      <button type="submit" class="btn btn-accent"><?= htmlspecialchars(__('author.save_changes')) ?></button>
      <a href="<?= $base ?>/author/article/<?= (int) $article['id'] ?>" class="btn btn-outline"><?= htmlspecialchars(__('common.cancel')) ?></a>
    </div>
  </form>
</div>
