<?php
$articles = $articles ?? [];
$abonnement = $abonnement ?? null;
$stats = $stats ?? ['total' => 0, 'soumis' => 0, 'valide' => 0, 'rejete' => 0];
$base = $base ?? '';

function authorStatutBadge(string $statut): string {
    $map = [
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
?>
<div class="dashboard-header">
  <h1><?= htmlspecialchars(__('author.my_dashboard')) ?></h1>
  <p><?= htmlspecialchars(__('author.manage_submissions')) ?></p>
</div>
<div class="dashboard-stats">
  <div class="stat-card">
    <div class="stat-icon primary"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#file-text"/></svg></div>
    <div>
      <div class="stat-value"><?= (int) $stats['soumis'] ?></div>
      <div class="stat-label"><?= htmlspecialchars(__('author.articles_submitted')) ?></div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon gold"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#clock"/></svg></div>
    <div>
      <div class="stat-value"><?= (int) ($stats['soumis'] ?? 0) ?></div>
      <div class="stat-label"><?= htmlspecialchars(__('author.in_evaluation')) ?></div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon green"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#check"/></svg></div>
    <div>
      <div class="stat-value"><?= (int) $stats['valide'] ?></div>
      <div class="stat-label"><?= htmlspecialchars(__('author.published')) ?></div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon accent"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#clipboard-check"/></svg></div>
    <div>
      <div class="stat-value"><?= (int) $stats['rejete'] ?></div>
      <div class="stat-label"><?= htmlspecialchars(__('author.rejected')) ?></div>
    </div>
  </div>
</div>
<div class="dashboard-card">
  <h2><?= htmlspecialchars(__('author.submit_new')) ?></h2>
  <p class="text-muted text-sm mb-4"><?= htmlspecialchars(__('author.submit_form_intro')) ?></p>
  <p><a href="<?= $base ?>/author/soumettre" class="btn btn-accent"><?= htmlspecialchars(__('author.submit_article')) ?></a> <a href="<?= $base ?>/instructions-auteurs" class="btn btn-outline"><?= htmlspecialchars(__('nav.instructions')) ?></a></p>
</div>
<div class="dashboard-card" id="articles">
  <h2><?= htmlspecialchars(__('author.my_articles')) ?></h2>
  <div class="overflow-auto">
    <table class="dashboard-table">
      <thead>
        <tr>
          <th><?= htmlspecialchars(__('author.th_title')) ?></th>
          <th><?= htmlspecialchars(__('author.th_date')) ?></th>
          <th><?= htmlspecialchars(__('author.th_status')) ?></th>
          <th><?= htmlspecialchars(__('author.th_actions')) ?></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($articles)): ?>
          <tr><td colspan="4" class="text-muted"><?= htmlspecialchars(__('author.no_articles')) ?></td></tr>
        <?php else: ?>
          <?php foreach ($articles as $a): ?>
          <tr>
            <td><?= htmlspecialchars($a['titre']) ?></td>
            <td><?= authorFormatDate($a['date_soumission'] ?? $a['created_at'] ?? null) ?></td>
            <td><?= authorStatutBadge($a['statut'] ?? 'soumis') ?></td>
            <td class="wrap-row">
              <a href="<?= $base ?>/author/article/<?= (int) $a['id'] ?>" class="btn btn-sm btn-outline"><?= htmlspecialchars(__('common.read')) ?></a>
              <?php if (($a['statut'] ?? '') === 'soumis'): ?>
                <a href="<?= $base ?>/author/article/<?= (int) $a['id'] ?>/edit" class="btn btn-sm btn-outline"><?= htmlspecialchars(__('author.edit_article')) ?></a>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<div class="dashboard-card">
  <h2><?= htmlspecialchars(__('author.subscription')) ?></h2>
  <?php if ($abonnement): ?>
    <p class="text-muted text-sm mb-2"><?= __('author.subscription_active_until') ?> <?= authorFormatDate($abonnement['date_fin'] ?? null) ?>.</p>
  <?php else: ?>
    <p class="text-muted text-sm mb-2"><?= htmlspecialchars(__('author.no_subscription')) ?></p>
  <?php endif; ?>
  <p class="text-sm mb-0"><a href="<?= $base ?>/author/abonnement" class="btn btn-sm btn-outline-primary"><?= htmlspecialchars(__('author.manage_subscription')) ?></a></p>
</div>
