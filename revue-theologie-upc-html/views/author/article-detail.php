<?php
$article = $article ?? null;
$success = $success ?? null;
$base = $base ?? '';
if (!$article) return;

function authorStatutBadge(string $statut): string {
    $map = [
        'brouillon' => ['label' => function_exists('__') ? __('author.status_brouillon') : 'Brouillon', 'class' => 'badge gold'],
        'soumis' => ['label' => function_exists('__') ? __('author.status_soumis') : 'Soumis', 'class' => 'badge-primary'],
        'valide'  => ['label' => function_exists('__') ? __('author.status_valide') : 'Publié', 'class' => 'badge green'],
        'rejete'  => ['label' => function_exists('__') ? __('author.status_rejete') : 'Rejeté', 'class' => 'badge accent'],
    ];
    $c = $map[$statut] ?? ['label' => $statut, 'class' => 'badge'];
    return '<span class="badge ' . $c['class'] . '">' . htmlspecialchars($c['label']) . '</span>';
}
function authorFormatDate(?string $d): string {
    if (!$d) return '—';
    $t = strtotime($d);
    return $t ? date('j M. Y', $t) : $d;
}
$statut = $article['statut'] ?? 'soumis';
?>
<?php if ($success === 'saved'): ?>
<div class="dashboard-card mb-4 border-l-4 border-green-500 bg-green-50 dark:bg-green-900/20">
  <p class="text-green-700 dark:text-green-300 font-medium"><?= function_exists('__') ? __('author.changes_saved') : 'Modifications enregistrées.' ?></p>
</div>
<?php endif; ?>
<?php if ($success === 'submitted'): ?>
<div class="dashboard-card mb-4 border-l-4 border-green-500 bg-green-50 dark:bg-green-900/20">
  <p class="text-green-700 dark:text-green-300 font-medium"><?= function_exists('__') ? __('author.article_submitted_success') : 'Article soumis avec succès.' ?></p>
</div>
<?php endif; ?>
<div class="dashboard-header flex flex-wrap justify-between items-start gap-2">
  <div style="min-width: 0;">
    <h1><?= htmlspecialchars($article['titre']) ?></h1>
    <p class="text-muted"><?= htmlspecialchars(__('author.submitted_on')) ?> <?= authorFormatDate($article['date_soumission'] ?? $article['created_at'] ?? null) ?> · <?= authorStatutBadge($statut) ?></p>
  </div>
  <?php if (in_array($statut, ['soumis', 'brouillon'], true)): ?>
  <div class="wrap-row flex flex-wrap gap-2">
    <a href="<?= $base ?>/author/article/<?= (int) $article['id'] ?>/edit" class="btn btn-outline"><?= htmlspecialchars(__('author.edit_article')) ?></a>
    <?php if ($statut === 'brouillon'): ?>
    <form method="post" action="<?= $base ?>/author/article/<?= (int) $article['id'] ?>/submit" class="inline-form"><?= csrf_field() ?><button type="submit" class="btn btn-accent"><?= htmlspecialchars(__('author.submit_article_btn')) ?></button></form>
    <?php endif; ?>
  </div>
  <?php endif; ?>
</div>
<div class="dashboard-card">
  <h2><?= htmlspecialchars(__('author.content_summary')) ?></h2>
  <div class="prose"><?= nl2br(htmlspecialchars($article['contenu'] ?? '')) ?></div>
</div>
<?php if (!empty($article['fichier_path'])): ?>
<?php $displayFileName = !empty($article['fichier_nom_original']) ? $article['fichier_nom_original'] : basename($article['fichier_path']); ?>
<div class="dashboard-card">
  <h2><?= htmlspecialchars(__('author.attached_file')) ?></h2>
  <p class="text-sm text-muted mb-2"><?= htmlspecialchars($displayFileName) ?></p>
  <p><a href="<?= $base ?>/download/article/<?= (int) $article['id'] ?>" class="btn btn-outline" target="_blank" rel="noopener" download="<?= htmlspecialchars($displayFileName) ?>"><?= htmlspecialchars(__('author.download_file')) ?></a></p>
</div>
<?php endif; ?>

<?php
$evaluations = $evaluations ?? [];
$articlePublished = ($statut === 'valide');
function authorRecoLabel(string $rec): string {
    $map = ['accepte' => 'author.reco_accepte', 'accepte_avec_modifications' => 'author.reco_accepte_modif', 'revision_mineure' => 'author.reco_accepte_modif', 'revision_majeure' => 'author.reco_revision_majeure', 'rejete' => 'author.reco_rejete'];
    $key = $map[$rec] ?? null;
    return $key && function_exists('__') ? __($key) : $rec;
}
function authorDetailWorkflowSteps(string $statut): array {
    $statut = strtolower(trim($statut));
    $completed = [];
    $current = 0;
    switch ($statut) {
        case 'brouillon': $completed = [0]; $current = 1; break;
        case 'soumis': $completed = [0, 1]; $current = 2; break;
        case 'en_evaluation': $completed = [0, 1, 2]; $current = 3; break;
        case 'revision_requise': $completed = [0, 1, 2, 3]; $current = 4; break;
        case 'accepte': $completed = [0, 1, 2, 3, 4]; $current = 5; break;
        case 'valide': $completed = [0, 1, 2, 3, 4, 5]; $current = 5; break;
        case 'rejete': $completed = [0, 1, 2]; $current = 3; break;
        default: $completed = [0, 1]; $current = 2;
    }
    $steps = [];
    for ($i = 0; $i < 5; $i++) {
        if (in_array($i, $completed, true)) {
            $steps[] = ($i === $current && $statut !== 'valide') ? 'current' : 'completed';
        } else {
            $steps[] = $i === $current ? 'current' : 'pending';
        }
    }
    return $steps;
}
$wfSteps = authorDetailWorkflowSteps($statut);
$wfLabels = [__('author.workflow_recu'), __('author.workflow_en_evaluation'), __('author.workflow_revisions'), __('author.workflow_accepte'), __('author.workflow_publie')];
?>

<?php if (!empty($evaluations)): ?>
<div class="dashboard-card">
  <h2><?= htmlspecialchars(__('author.evaluator_comments_title')) ?></h2>
  <div class="evaluations-list-author">
    <?php foreach ($evaluations as $idx => $ev): ?>
    <div class="evaluation-card">
      <div class="evaluation-card-header">
        <div>
          <strong><?= ($articlePublished && (!empty($ev['evaluateur_nom']) || !empty($ev['evaluateur_prenom']))) ? htmlspecialchars(trim(($ev['evaluateur_prenom'] ?? '') . ' ' . ($ev['evaluateur_nom'] ?? ''))) : (function_exists('__') ? sprintf(__('author.evaluator_n'), $idx + 1) : 'Évaluateur ' . ($idx + 1)) ?></strong>
          <span class="evaluation-date"><?= authorFormatDate($ev['date_soumission'] ?? null) ?></span>
        </div>
        <span class="badge <?= in_array($ev['recommendation'] ?? '', ['accepte', 'accepte_avec_modifications', 'revision_mineure'], true) ? 'badge green' : (($ev['recommendation'] ?? '') === 'rejete' ? 'badge accent' : 'badge') ?>"><?= htmlspecialchars(authorRecoLabel($ev['recommendation'] ?? '')) ?></span>
      </div>
      <?php if (!empty($ev['commentaires_public'])): ?>
      <div class="evaluation-block">
        <h4 class="text-sm font-medium text-muted"><?= htmlspecialchars(__('author.eval_comments')) ?></h4>
        <p><?= nl2br(htmlspecialchars($ev['commentaires_public'])) ?></p>
      </div>
      <?php endif; ?>
      <?php if (!empty($ev['suggestions'])): ?>
      <div class="evaluation-block">
        <h4 class="text-sm font-medium text-muted"><?= htmlspecialchars(__('author.eval_suggestions')) ?></h4>
        <p><?= nl2br(htmlspecialchars($ev['suggestions'])) ?></p>
      </div>
      <?php endif; ?>
      <div class="evaluation-block evaluation-scores">
        <h4 class="text-sm font-medium text-muted"><?= htmlspecialchars(__('author.eval_notes')) ?></h4>
        <div class="scores-grid">
          <span><?= htmlspecialchars(__('author.eval_quality')) ?> <?= (int)($ev['qualite_scientifique'] ?? 0) ?>/10</span>
          <span><?= htmlspecialchars(__('author.eval_originality')) ?> <?= (int)($ev['originalite'] ?? 0) ?>/10</span>
          <span><?= htmlspecialchars(__('author.eval_relevance')) ?> <?= (int)($ev['pertinence'] ?? 0) ?>/10</span>
          <span><?= htmlspecialchars(__('author.eval_clarity')) ?> <?= (int)($ev['clarte'] ?? 0) ?>/10</span>
          <span><strong><?= htmlspecialchars(__('author.eval_final_note')) ?> <?= (int)($ev['note_finale'] ?? 0) ?>/10</strong></span>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<div class="dashboard-card">
  <h2><?= htmlspecialchars(__('author.workflow_title')) ?></h2>
  <div class="workflow-steps workflow-steps-detail" role="status" aria-label="<?= htmlspecialchars(__('author.workflow_aria')) ?>">
    <?php for ($i = 0; $i < 5; $i++):
      $state = $wfSteps[$i] ?? 'pending';
      $label = $wfLabels[$i] ?? '';
    ?>
    <?php if ($i > 0): ?><span class="workflow-arrow">→</span><?php endif; ?>
    <div class="workflow-step workflow-step-<?= $state ?>">
      <?php if ($state === 'completed'): ?>
        <span class="workflow-step-icon workflow-step-icon-done" aria-hidden="true">✓</span>
      <?php elseif ($state === 'current'): ?>
        <span class="workflow-step-icon workflow-step-icon-current" aria-hidden="true">•</span>
      <?php else: ?>
        <span class="workflow-step-icon workflow-step-icon-pending" aria-hidden="true">◦</span>
      <?php endif; ?>
      <span class="workflow-step-label"><?= htmlspecialchars($label) ?></span>
    </div>
    <?php endfor; ?>
  </div>
</div>

<div class="dashboard-card mt-4">
  <h2><?= htmlspecialchars(__('author.revisions_heading')) ?></h2>
  <p><?= htmlspecialchars(__('author.revisions_intro')) ?></p>
  <p><a href="<?= $base ?>/author/article/<?= (int) $article['id'] ?>/revisions" class="btn btn-outline-primary"><?= htmlspecialchars(__('author.view_revisions')) ?></a></p>
</div>
<p class="mt-4"><a href="<?= $base ?>/author"><?= htmlspecialchars(__('author.back_to_dashboard')) ?></a></p>
