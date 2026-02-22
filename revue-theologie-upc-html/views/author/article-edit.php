<?php
$article = $article ?? null;
$error = $error ?? null;
$base = $base ?? '';
if (!$article) return;
?>
<div class="dashboard-header">
  <h1>Modifier l'article</h1>
  <p><?= htmlspecialchars($article['titre']) ?></p>
</div>
<div class="dashboard-card">
  <?php if ($error): ?>
  <p class="text-accent mb-4"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>
  <form action="<?= $base ?>/author/article/<?= (int) $article['id'] ?>/edit" method="post" enctype="multipart/form-data" class="flex flex-col gap-4">
    <div class="form-group">
      <label for="titre">Titre de l'article *</label>
      <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($article['titre']) ?>" required class="h-11">
    </div>
    <div class="form-group">
      <label for="contenu">Résumé / contenu *</label>
      <textarea id="contenu" name="contenu" rows="6" required><?= htmlspecialchars($article['contenu'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
      <label for="fichier">Nouveau fichier (optionnel)</label>
      <input type="file" id="fichier" name="fichier" accept=".pdf,.doc,.docx" style="font-size:0.875rem;">
      <?php if (!empty($article['fichier_path'])): ?>
      <p class="text-xs text-muted mt-1">Fichier actuel : <?= htmlspecialchars(basename($article['fichier_path'])) ?>. En uploadant un nouveau fichier, il sera remplacé.</p>
      <?php endif; ?>
    </div>
    <div class="wrap-row">
      <button type="submit" class="btn btn-accent">Enregistrer les modifications</button>
      <a href="<?= $base ?>/author/article/<?= (int) $article['id'] ?>" class="btn btn-outline">Annuler</a>
    </div>
  </form>
</div>
