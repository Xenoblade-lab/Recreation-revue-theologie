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
  <h1><?= htmlspecialchars(__('reviewer.eval_article_title')) ?></h1>
  <p><?= htmlspecialchars($titre) ?> — <?= htmlspecialchars(__('reviewer.deadline_remaining')) ?> <?= $jours !== null ? $jours . ' ' . __('reviewer.days_left') : '—' ?></p>
</div>
<div class="dashboard-card">
  <h2><?= htmlspecialchars(__('reviewer.article_info')) ?></h2>
  <p><strong><?= htmlspecialchars(__('reviewer.th_title_label')) ?></strong> <?= htmlspecialchars($titre) ?></p>
  <p class="mb-0">
    <a href="<?= $base ?>/article/<?= (int) $evaluation['article_id'] ?>" class="btn btn-sm btn-outline-primary"><?= htmlspecialchars(__('reviewer.view_article_page')) ?></a>
    <?php if ($fichierPath): ?>
      <a href="<?= $base ?>/<?= htmlspecialchars($fichierPath) ?>" class="btn btn-sm btn-outline" target="_blank" rel="noopener"><?= htmlspecialchars(__('reviewer.download_manuscript')) ?></a>
    <?php endif; ?>
  </p>
</div>
<div class="dashboard-card">
  <h2><?= htmlspecialchars(__('reviewer.eval_form_title')) ?></h2>
  <p class="text-muted text-sm mb-4"><?= htmlspecialchars(__('reviewer.eval_form_intro')) ?></p>
  <?php if ($error): ?>
  <p class="text-accent mb-4"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>
  <form action="<?= $base ?>/reviewer/evaluation/<?= (int) $evaluation['id'] ?>" method="post" class="flex flex-col gap-4">
    <?= csrf_field() ?>
    <div class="form-group">
      <label for="recommendation"><?= htmlspecialchars(__('reviewer.recommendation_label')) ?></label>
      <select id="recommendation" name="recommendation" class="h-11" style="width:100%;max-width:20rem;padding:0.5rem 0.75rem;border:1px solid var(--input);border-radius:var(--radius);background:var(--background);font-size:0.875rem;">
        <option value=""><?= htmlspecialchars(__('reviewer.choose')) ?></option>
        <option value="accepte" <?= $recommendation === 'accepte' ? 'selected' : '' ?>><?= htmlspecialchars(__('reviewer.reco_accepte')) ?></option>
        <option value="revision_mineure" <?= $recommendation === 'revision_mineure' ? 'selected' : '' ?>><?= htmlspecialchars(__('reviewer.reco_minor')) ?></option>
        <option value="revision_majeure" <?= $recommendation === 'revision_majeure' ? 'selected' : '' ?>><?= htmlspecialchars(__('reviewer.reco_major')) ?></option>
        <option value="rejete" <?= $recommendation === 'rejete' ? 'selected' : '' ?>><?= htmlspecialchars(__('reviewer.reco_rejete')) ?></option>
      </select>
    </div>
    <div class="form-group">
      <label for="commentaires_public"><?= htmlspecialchars(__('reviewer.comments_public')) ?></label>
      <textarea id="commentaires_public" name="commentaires_public" rows="4" placeholder="<?= htmlspecialchars(__('reviewer.comments_public_placeholder')) ?>"><?= htmlspecialchars($evaluation['commentaires_public'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
      <label for="commentaires_prives"><?= htmlspecialchars(__('reviewer.comments_private')) ?></label>
      <textarea id="commentaires_prives" name="commentaires_prives" rows="4" placeholder="<?= htmlspecialchars(__('reviewer.comments_private_placeholder')) ?>"><?= htmlspecialchars($evaluation['commentaires_prives'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
      <label for="suggestions"><?= htmlspecialchars(__('reviewer.suggestions')) ?></label>
      <textarea id="suggestions" name="suggestions" rows="3"><?= htmlspecialchars($evaluation['suggestions'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
      <label><?= htmlspecialchars(__('reviewer.notes_optional')) ?></label>
      <div class="flex flex-wrap gap-4" style="align-items: center;">
        <span><?= htmlspecialchars(__('reviewer.quality')) ?></span>
        <input type="number" name="qualite_scientifique" min="0" max="10" step="1" value="<?= (int) ($evaluation['qualite_scientifique'] ?? '') ?>" style="width:5rem;padding:0.5rem;">
        <span><?= htmlspecialchars(__('reviewer.originality')) ?></span>
        <input type="number" name="originalite" min="0" max="10" step="1" value="<?= (int) ($evaluation['originalite'] ?? '') ?>" style="width:5rem;padding:0.5rem;">
        <span><?= htmlspecialchars(__('reviewer.relevance')) ?></span>
        <input type="number" name="pertinence" min="0" max="10" step="1" value="<?= (int) ($evaluation['pertinence'] ?? '') ?>" style="width:5rem;padding:0.5rem;">
        <span><?= htmlspecialchars(__('reviewer.clarity')) ?></span>
        <input type="number" name="clarte" min="0" max="10" step="1" value="<?= (int) ($evaluation['clarte'] ?? '') ?>" style="width:5rem;padding:0.5rem;">
        <span><?= htmlspecialchars(__('reviewer.final_note')) ?></span>
        <input type="number" name="note_finale" min="0" max="10" step="0.5" value="<?= $evaluation['note_finale'] ?? '' ?>" style="width:5rem;padding:0.5rem;">
      </div>
    </div>
    <div class="wrap-row">
      <button type="submit" name="submit_eval" value="1" class="btn btn-accent"><?= htmlspecialchars(__('reviewer.submit_eval')) ?></button>
      <button type="submit" name="draft" value="1" class="btn btn-outline"><?= htmlspecialchars(__('reviewer.save_draft')) ?></button>
      <a href="<?= $base ?>/reviewer" class="btn btn-outline"><?= htmlspecialchars(__('reviewer.back_dashboard')) ?></a>
    </div>
  </form>
</div>
