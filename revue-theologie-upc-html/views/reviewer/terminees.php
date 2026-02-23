<?php
$evaluations = $evaluations ?? [];
$base = $base ?? '';

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
        'rejete'                     => function_exists('__') ? __('reviewer.reco_rejete') : 'Rejeté',
    ];
    return $map[$r] ?? $r;
}
?>
<div class="dashboard-header">
  <h1><?= htmlspecialchars(__('reviewer.terminees_title')) ?></h1>
  <p><?= htmlspecialchars(__('reviewer.terminees_intro')) ?></p>
</div>
<div class="dashboard-card">
  <table class="dashboard-table">
    <thead>
      <tr>
        <th><?= htmlspecialchars(__('reviewer.th_article_title')) ?></th>
        <th><?= htmlspecialchars(__('reviewer.th_submission_date')) ?></th>
        <th><?= htmlspecialchars(__('reviewer.th_recommendation')) ?></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($evaluations)): ?>
        <tr><td colspan="4" class="text-muted"><?= htmlspecialchars(__('reviewer.no_done')) ?></td></tr>
      <?php else: ?>
        <?php foreach ($evaluations as $e): ?>
        <tr>
          <td><?= htmlspecialchars($e['article_titre'] ?? '') ?></td>
          <td><?= reviewerFormatDate($e['date_soumission'] ?? null) ?></td>
          <td><span class="badge"><?= htmlspecialchars(reviewerRecommendationLabel($e['recommendation'] ?? null)) ?></span></td>
          <td><a href="<?= $base ?>/article/<?= (int) ($e['article_id'] ?? 0) ?>" class="btn btn-sm btn-outline"><?= htmlspecialchars(__('reviewer.view_article')) ?></a></td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
