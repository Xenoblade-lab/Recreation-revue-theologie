<?php
$evaluations = $evaluations ?? [];
$base = $base ?? '';

function reviewerStatutBadge(string $statut): string {
    $map = [
        'en_attente' => ['label' => function_exists('__') ? __('reviewer.pending') : 'En attente', 'class' => 'badge-primary'],
        'en_cours'   => ['label' => function_exists('__') ? __('reviewer.in_progress') : 'En cours', 'class' => 'badge-gold'],
        'termine'    => ['label' => function_exists('__') ? __('reviewer.status_termine') : 'Terminé', 'class' => 'badge green'],
        'annule'     => ['label' => function_exists('__') ? __('reviewer.status_annule') : 'Annulé', 'class' => 'badge accent'],
    ];
    $c = $map[$statut] ?? ['label' => $statut, 'class' => 'badge'];
    return '<span class="badge ' . $c['class'] . '">' . htmlspecialchars($c['label']) . '</span>';
}
function reviewerFormatDate(?string $d): string {
    if (!$d) return '—';
    $t = strtotime($d);
    return $t ? date('j M. Y', $t) : $d;
}
function reviewerRecommendationLabel(?string $r): string {
    if ($r === null || $r === '') return '—';
    $map = [
        'accepte'                    => function_exists('__') ? __('reviewer.reco_accepte') : 'Accepté',
        'accepte_avec_modifications' => function_exists('__') ? __('reviewer.reco_accepte_modif') : 'Accepté avec modifications',
        'revision_mineure'           => function_exists('__') ? __('reviewer.reco_minor') : 'Révisions mineures requises',
        'revision_majeure'           => function_exists('__') ? __('reviewer.reco_revision_majeure') : 'Révisions majeures',
        'rejete'                    => function_exists('__') ? __('reviewer.reco_rejete') : 'Rejeté',
    ];
    return $map[$r] ?? $r;
}
?>
<div class="dashboard-header">
  <h1><?= htmlspecialchars(__('reviewer.history_title')) ?></h1>
  <p><?= htmlspecialchars(__('reviewer.history_intro')) ?></p>
</div>
<div class="dashboard-card">
  <table class="dashboard-table">
    <thead>
      <tr>
        <th><?= htmlspecialchars(__('author.th_title')) ?></th>
        <th><?= htmlspecialchars(__('reviewer.th_date_assign')) ?></th>
        <th><?= htmlspecialchars(__('author.th_status')) ?></th>
        <th><?= htmlspecialchars(__('reviewer.th_recommendation')) ?></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($evaluations)): ?>
        <tr><td colspan="5" class="text-muted"><?= htmlspecialchars(__('reviewer.no_eval')) ?></td></tr>
      <?php else: ?>
        <?php foreach ($evaluations as $e): ?>
        <?php $statut = $e['statut'] ?? 'en_attente'; ?>
        <tr>
          <td><?= htmlspecialchars($e['article_titre'] ?? '') ?></td>
          <td><?= reviewerFormatDate($e['date_assignation'] ?? null) ?></td>
          <td><?= reviewerStatutBadge($statut) ?></td>
          <td><?= htmlspecialchars(reviewerRecommendationLabel($e['recommendation'] ?? null)) ?></td>
          <td>
            <?php if (in_array($statut, ['en_attente', 'en_cours'], true)): ?>
              <a href="<?= $base ?>/reviewer/evaluation/<?= (int) $e['id'] ?>" class="btn btn-sm btn-outline"><?= htmlspecialchars(__('reviewer.evaluate')) ?></a>
            <?php else: ?>
              <a href="<?= $base ?>/article/<?= (int) ($e['article_id'] ?? 0) ?>" class="btn btn-sm btn-outline"><?= htmlspecialchars(__('reviewer.view_article')) ?></a>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
