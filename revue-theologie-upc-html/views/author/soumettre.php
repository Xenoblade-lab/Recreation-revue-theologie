<?php
$abonnementActif = $abonnementActif ?? false;
$error = $error ?? null;
$old = $old ?? [];
$base = $base ?? '';
$titre = $old['titre'] ?? '';
$contenu = $old['contenu'] ?? '';
?>
<div class="dashboard-header">
  <h1>Soumettre un article</h1>
  <p>Remplissez le formulaire pour soumettre votre manuscrit à la Revue.</p>
</div>
<div class="dashboard-card">
  <?php if (!$abonnementActif): ?>
  <p class="text-muted mb-4">Un abonnement actif peut être requis pour soumettre des articles. Vous pouvez tout de même soumettre.</p>
  <?php endif; ?>
  <?php if ($error): ?>
  <p class="text-accent mb-4"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>
  <form action="<?= $base ?>/author/soumettre" method="post" enctype="multipart/form-data" class="flex flex-col gap-4">
    <div class="form-group">
      <label for="titre">Titre de l'article *</label>
      <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($titre) ?>" placeholder="Titre complet de l'article" required class="h-11">
    </div>
    <div class="form-group">
      <label for="contenu">Résumé / contenu *</label>
      <textarea id="contenu" name="contenu" rows="6" placeholder="Résumé ou contenu de l'article (obligatoire)" required><?= htmlspecialchars($contenu) ?></textarea>
    </div>
    <div class="form-group">
      <label for="fichier">Fichier (PDF, Word)</label>
      <input type="file" id="fichier" name="fichier" accept=".pdf,.doc,.docx" style="font-size:0.875rem;">
      <p class="text-xs text-muted mt-1">Formats acceptés : PDF, DOC, DOCX. Taille max. 10 Mo.</p>
    </div>
    <div class="wrap-row">
      <button type="submit" class="btn btn-accent">Soumettre l'article</button>
      <a href="<?= $base ?>/instructions-auteurs" class="btn btn-outline">Instructions aux auteurs</a>
    </div>
  </form>
</div>
