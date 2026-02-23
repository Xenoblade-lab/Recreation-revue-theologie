<?php
$assignations = $assignations ?? [];
$enAttente = (int) ($enAttente ?? 0);
$enCours = (int) ($enCours ?? 0);
$terminees = (int) ($terminees ?? 0);
$tauxCompletion = (int) ($tauxCompletion ?? 0);
$base = $base ?? '';

function reviewerStatutBadge(string $statut): string {
    $map = [
        'en_attente' => ['label' => function_exists('__') ? __('reviewer.pending') : 'En attente', 'class' => 'badge-primary'],
        'en_cours'   => ['label' => function_exists('__') ? __('reviewer.in_progress') : 'En cours', 'class' => 'badge-gold'],
        'termine'    => ['label' => function_exists('__') ? __('reviewer.status_termine') : 'Terminé', 'class' => 'badge green'],
        'annule'     => ['label' => function_exists('__') ? __('reviewer.status_annule') : 'Annulé', 'class' => 'badge accent'],
    ];
    $c = $map[$statut] ?? ['label' => $statut, 'class' => 'badge'];
    return '<span class="badge ' . htmlspecialchars($c['class']) . '">' . htmlspecialchars($c['label']) . '</span>';
}
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
?>
<div class="dashboard-header">
  <h1><?= htmlspecialchars(__('reviewer.dashboard_title')) ?></h1>
  <p><?= htmlspecialchars(__('reviewer.dashboard_intro')) ?></p>
</div>
<div class="dashboard-stats">
  <div class="stat-card">
    <div class="stat-icon primary"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#file-text"/></svg></div>
    <div>
      <div class="stat-value"><?= $enAttente ?></div>
      <div class="stat-label"><?= htmlspecialchars(__('reviewer.pending')) ?></div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon gold"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#clock"/></svg></div>
    <div>
      <div class="stat-value"><?= $enCours ?></div>
      <div class="stat-label"><?= htmlspecialchars(__('reviewer.in_progress')) ?></div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon green"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#check"/></svg></div>
    <div>
      <div class="stat-value"><?= $terminees ?></div>
      <div class="stat-label"><?= htmlspecialchars(__('reviewer.done')) ?></div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon accent"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#clipboard-check"/></svg></div>
    <div>
      <div class="stat-value"><?= $tauxCompletion ?> %</div>
      <div class="stat-label"><?= htmlspecialchars(__('reviewer.completion_rate')) ?></div>
    </div>
  </div>
</div>
<div class="dashboard-card">
  <h2><?= htmlspecialchars(__('reviewer.assigned_articles')) ?></h2>
  <p class="text-muted text-sm mb-4"><?= htmlspecialchars(__('reviewer.evaluate_click')) ?></p>
  <div class="overflow-auto">
    <table class="dashboard-table">
      <thead>
        <tr>
          <th><?= htmlspecialchars(__('author.th_title')) ?></th>
          <th><?= htmlspecialchars(__('reviewer.th_assignment_date')) ?></th>
          <th><?= htmlspecialchars(__('reviewer.th_deadline')) ?></th>
          <th><?= htmlspecialchars(__('author.th_status')) ?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($assignations)): ?>
          <tr><td colspan="5" class="text-muted"><?= htmlspecialchars(__('reviewer.no_assignment')) ?></td></tr>
        <?php else: ?>
          <?php foreach ($assignations as $e): ?>
          <?php
            $statut = $e['statut'] ?? 'en_attente';
            $peutEvaluer = in_array($statut, ['en_attente', 'en_cours'], true);
            $jours = reviewerJoursRestants($e['date_echeance'] ?? null);
          ?>
          <tr>
            <td><?= htmlspecialchars($e['article_titre'] ?? '') ?></td>
            <td><?= reviewerFormatDate($e['date_assignation'] ?? null) ?></td>
            <td><?= $jours !== null ? $jours . ' ' . __('reviewer.days_left') : '—' ?></td>
            <td><?= reviewerStatutBadge($statut) ?></td>
            <td>
              <?php if ($peutEvaluer): ?>
                <a href="<?= $base ?>/reviewer/evaluation/<?= (int) $e['id'] ?>" class="btn btn-sm btn-accent"><?= $statut === 'en_cours' ? __('reviewer.resume') : __('reviewer.evaluate') ?></a>
              <?php else: ?>
                —
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <p class="text-sm text-muted mt-4 mb-0"><a href="<?= $base ?>/reviewer/terminees" class="text-primary"><?= htmlspecialchars(__('reviewer.done_list')) ?></a> · <a href="<?= $base ?>/reviewer/historique" class="text-primary"><?= htmlspecialchars(__('reviewer.full_history')) ?></a></p>
</div>
<div class="dashboard-card">
  <h2><?= htmlspecialchars(__('reviewer.reminder_title')) ?></h2>
  <ul class="text-sm text-muted" style="margin: 0; padding-left: 1.25rem;">
    <li><?= htmlspecialchars(__('reviewer.reminder_1')) ?></li>
    <li><?= htmlspecialchars(__('reviewer.reminder_2')) ?></li>
    <li><?= htmlspecialchars(__('reviewer.reminder_3')) ?></li>
    <li><?= htmlspecialchars(__('reviewer.reminder_4')) ?></li>
  </ul>
</div>
