<?php
$evaluation = $evaluation ?? null;
$error = $error ?? null;
$base = $base ?? '';
if (!$evaluation) return;

function reviewerFormatDate(?string $d): string {
    if (!$d) return '—';
    $t = strtotime($d);
    return $t ? date('j M. Y', $t) : $d;
}
function reviewerJoursRestants(?string $dateEcheance): ?int {
    if (!$dateEcheance) return null;
    $end = strtotime($dateEcheance . ' 23:59:59');
    $now = time();
    if ($end <= $now) return 0;
    return (int) ceil(($end - $now) / 86400);
}
$titre = $evaluation['article_titre'] ?? '';
$fichierPath = $evaluation['article_fichier_path'] ?? null;
$jours = reviewerJoursRestants($evaluation['date_echeance'] ?? null);
$recommendation = $evaluation['recommendation'] ?? '';
// Afficher "revision_mineure" dans le formulaire si DB = accepte_avec_modifications
if ($recommendation === 'accepte_avec_modifications') {
    $recommendation = 'revision_mineure';
}
?>
<div class="dashboard-header">
  <h1>Évaluation de l'article</h1>
  <p><?= htmlspecialchars($titre) ?> — Délai restant : <?= $jours !== null ? $jours . ' jour(s)' : '—' ?></p>
</div>
<div class="dashboard-card">
  <h2>Informations de l'article</h2>
  <p><strong>Titre :</strong> <?= htmlspecialchars($titre) ?></p>
  <p class="mb-0">
    <a href="<?= $base ?>/article/<?= (int) $evaluation['article_id'] ?>" class="btn btn-sm btn-outline-primary">Voir la page article</a>
    <?php if ($fichierPath): ?>
      <a href="<?= $base ?>/<?= htmlspecialchars($fichierPath) ?>" class="btn btn-sm btn-outline" target="_blank" rel="noopener">Télécharger le manuscrit (PDF)</a>
    <?php endif; ?>
  </p>
</div>
<div class="dashboard-card">
  <h2>Formulaire d'évaluation</h2>
  <p class="text-muted text-sm mb-4">Recommandation obligatoire pour soumettre. Les commentaires pour l'auteur sont visibles par l'auteur ; les commentaires pour l'éditeur restent confidentiels.</p>
  <?php if ($error): ?>
  <p class="text-accent mb-4"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>
  <form action="<?= $base ?>/reviewer/evaluation/<?= (int) $evaluation['id'] ?>" method="post" class="flex flex-col gap-4">
    <div class="form-group">
      <label for="recommendation">Recommandation *</label>
      <select id="recommendation" name="recommendation" class="h-11" style="width:100%;max-width:20rem;padding:0.5rem 0.75rem;border:1px solid var(--input);border-radius:var(--radius);background:var(--background);font-size:0.875rem;">
        <option value="">Choisir</option>
        <option value="accepte" <?= $recommendation === 'accepte' ? 'selected' : '' ?>>Accepté</option>
        <option value="revision_mineure" <?= $recommendation === 'revision_mineure' ? 'selected' : '' ?>>Révisions mineures requises</option>
        <option value="revision_majeure" <?= $recommendation === 'revision_majeure' ? 'selected' : '' ?>>Révisions majeures requises</option>
        <option value="rejete" <?= $recommendation === 'rejete' ? 'selected' : '' ?>>Rejeté</option>
      </select>
    </div>
    <div class="form-group">
      <label for="commentaires_public">Commentaires pour l'auteur (public)</label>
      <textarea id="commentaires_public" name="commentaires_public" rows="4" placeholder="Commentaires visibles par l'auteur"><?= htmlspecialchars($evaluation['commentaires_public'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
      <label for="commentaires_prives">Commentaires pour l'éditeur (privé)</label>
      <textarea id="commentaires_prives" name="commentaires_prives" rows="4" placeholder="Commentaires confidentiels"><?= htmlspecialchars($evaluation['commentaires_prives'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
      <label for="suggestions">Suggestions d'amélioration</label>
      <textarea id="suggestions" name="suggestions" rows="3"><?= htmlspecialchars($evaluation['suggestions'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
      <label>Notes (sur 10, optionnel)</label>
      <div class="flex flex-wrap gap-4" style="align-items: center;">
        <span>Qualité scientifique</span>
        <input type="number" name="qualite_scientifique" min="0" max="10" step="1" value="<?= (int) ($evaluation['qualite_scientifique'] ?? '') ?>" style="width:5rem;padding:0.5rem;">
        <span>Originalité</span>
        <input type="number" name="originalite" min="0" max="10" step="1" value="<?= (int) ($evaluation['originalite'] ?? '') ?>" style="width:5rem;padding:0.5rem;">
        <span>Pertinence</span>
        <input type="number" name="pertinence" min="0" max="10" step="1" value="<?= (int) ($evaluation['pertinence'] ?? '') ?>" style="width:5rem;padding:0.5rem;">
        <span>Clarté</span>
        <input type="number" name="clarte" min="0" max="10" step="1" value="<?= (int) ($evaluation['clarte'] ?? '') ?>" style="width:5rem;padding:0.5rem;">
        <span>Note finale</span>
        <input type="number" name="note_finale" min="0" max="10" step="0.5" value="<?= $evaluation['note_finale'] ?? '' ?>" style="width:5rem;padding:0.5rem;">
      </div>
    </div>
    <div class="wrap-row">
      <button type="submit" name="submit_eval" value="1" class="btn btn-accent">Soumettre l'évaluation</button>
      <button type="submit" name="draft" value="1" class="btn btn-outline">Sauvegarder le brouillon</button>
      <a href="<?= $base ?>/reviewer" class="btn btn-outline">Retour au dashboard</a>
    </div>
  </form>
</div>
