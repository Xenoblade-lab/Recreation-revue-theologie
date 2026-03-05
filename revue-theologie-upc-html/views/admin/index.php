<?php
$totalArticles = (int) ($totalArticles ?? 0);
$publishedArticles = (int) ($publishedArticles ?? 0);
$reviewersCount = (int) ($reviewersCount ?? 0);
$monthlyRevenue = (float) ($monthlyRevenue ?? 0);
$totalRevenue = (float) ($totalRevenue ?? 0);
$lastSubmissions = $lastSubmissions ?? [];
$recentActivities = $recentActivities ?? [];
$base = $base ?? '';

function adminStatutBadge(string $statut): string {
    $map = [
        'soumis' => ['label' => function_exists('__') ? __('admin.article_status_soumis') : 'Soumis', 'class' => 'badge-primary'],
        'en_lecture' => ['label' => function_exists('__') ? __('admin.article_status_en_lecture') : 'En évaluation', 'class' => 'badge'],
        'accepte' => ['label' => function_exists('__') ? __('admin.article_status_accepte') : 'Accepté', 'class' => 'badge green'],
        'rejete' => ['label' => function_exists('__') ? __('admin.article_status_rejete') : 'Rejeté', 'class' => 'badge-accent'],
        'revision_requise' => ['label' => function_exists('__') ? __('admin.article_status_revision') : 'Révision requise', 'class' => 'badge-accent'],
        'valide' => ['label' => function_exists('__') ? __('admin.article_status_publie') : 'Publié', 'class' => 'badge green'],
        'publie' => ['label' => function_exists('__') ? __('admin.article_status_publie') : 'Publié', 'class' => 'badge green'],
    ];
    $c = $map[$statut] ?? ['label' => $statut, 'class' => 'badge'];
    return '<span class="badge ' . $c['class'] . '">' . htmlspecialchars($c['label']) . '</span>';
}

function adminFormatDate(?string $d): string {
    if (!$d) return '—';
    $t = strtotime($d);
    return $t ? date('j M. Y', $t) : $d;
}

function adminActivityDate(?string $d): string {
    if (!$d) return '—';
    $t = strtotime($d);
    return $t ? date('d/m/Y H:i', $t) : $d;
}
?>
<div class="dashboard-header">
  <h1><?= htmlspecialchars(__('admin.dashboard_title')) ?></h1>
  <p><?= htmlspecialchars(__('admin.dashboard_intro')) ?></p>
</div>
<div class="dashboard-stats">
  <div class="stat-card">
    <div class="stat-icon primary"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#file-text"/></svg></div>
    <div>
      <div class="stat-value"><?= $totalArticles ?></div>
      <div class="stat-label"><?= htmlspecialchars(__('admin.articles_total')) ?></div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon green"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#check"/></svg></div>
    <div>
      <div class="stat-value"><?= $publishedArticles ?></div>
      <div class="stat-label"><?= htmlspecialchars(__('admin.articles_published')) ?></div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon gold"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#user"/></svg></div>
    <div>
      <div class="stat-value"><?= $reviewersCount ?></div>
      <div class="stat-label"><?= htmlspecialchars(__('admin.reviewers_active')) ?></div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon accent"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#award"/></svg></div>
    <div>
      <div class="stat-value"><?= number_format($monthlyRevenue, 0, ',', ' ') ?> $</div>
      <div class="stat-label"><?= htmlspecialchars(__('admin.monthly_revenue')) ?></div>
      <?php if ($totalRevenue > 0): ?>
      <div class="text-muted text-sm mt-1"><?= htmlspecialchars(__('admin.total_validated')) ?> : <?= number_format($totalRevenue, 0, ',', ' ') ?> $</div>
      <?php endif; ?>
    </div>
  </div>
</div>
<div class="dashboard-card">
  <h2><?= htmlspecialchars(__('admin.last_submissions')) ?></h2>
  <div class="overflow-auto">
    <table class="dashboard-table">
      <thead>
        <tr>
          <th><?= htmlspecialchars(__('author.th_title')) ?></th>
          <th><?= htmlspecialchars(__('author.th_date')) ?></th>
          <th><?= htmlspecialchars(__('author.th_status')) ?></th>
          <th><?= htmlspecialchars(__('admin.th_author')) ?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($lastSubmissions)): ?>
          <tr><td colspan="5" class="text-muted"><?= htmlspecialchars(__('admin.no_articles')) ?></td></tr>
        <?php else: ?>
          <?php foreach ($lastSubmissions as $a): ?>
          <tr>
            <td data-label="<?= htmlspecialchars(__('author.th_title'), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($a['titre'] ?? '') ?></td>
            <td data-label="<?= htmlspecialchars(__('author.th_date'), ENT_QUOTES, 'UTF-8') ?>"><?= adminFormatDate($a['date_soumission'] ?? null) ?></td>
            <td data-label="<?= htmlspecialchars(__('author.th_status'), ENT_QUOTES, 'UTF-8') ?>"><?= adminStatutBadge($a['statut'] ?? 'soumis') ?></td>
            <td data-label="<?= htmlspecialchars(__('admin.th_author'), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars(trim(($a['auteur_prenom'] ?? '') . ' ' . ($a['auteur_nom'] ?? ''))) ?></td>
            <td data-label="<?= htmlspecialchars(__('admin.actions'), ENT_QUOTES, 'UTF-8') ?>"><a href="<?= $base ?>/admin/article/<?= (int) ($a['id'] ?? 0) ?>" class="btn btn-sm btn-outline"><?= htmlspecialchars(__('common.read')) ?></a></td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <p class="text-sm text-muted mt-4 mb-0"><a href="<?= $base ?>/admin/articles" class="text-primary"><?= htmlspecialchars(__('admin.view_all_articles')) ?></a></p>
</div>
<div class="dashboard-card">
  <h2><?= htmlspecialchars(__('admin.recent_activities')) ?></h2>
  <?php if (empty($recentActivities)): ?>
  <p class="text-muted"><?= htmlspecialchars(__('admin.no_recent_activities')) ?></p>
  <?php else: ?>
  <ul class="activity-list" style="list-style: none; padding: 0; margin: 0;">
    <?php foreach ($recentActivities as $n):
      $data = [];
      if (!empty($n['data'])) {
        $decoded = json_decode($n['data'], true);
        $data = is_array($decoded) ? $decoded : [];
      }
      $message = $data['message'] ?? $n['type'] ?? '';
      $link = $data['link'] ?? $data['url'] ?? null;
      $readUrl = $link ? ((strpos($link, 'http') === 0) ? $link : $base . (strpos($link, '/') === 0 ? $link : '/' . $link)) : null;
    ?>
    <li style="padding: 0.6rem 0; border-bottom: 1px solid var(--border, #eee); display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem;">
      <span class="text-muted text-sm" style="flex: 0 0 5.5rem;"><?= adminActivityDate($n['created_at'] ?? null) ?></span>
      <span style="flex: 1; min-width: 0;"><?= htmlspecialchars($message) ?></span>
      <?php if ($readUrl): ?>
      <a href="<?= htmlspecialchars($readUrl) ?>" class="btn btn-sm btn-outline"><?= htmlspecialchars(__('common.read')) ?></a>
      <?php endif; ?>
    </li>
    <?php endforeach; ?>
  </ul>
  <p class="text-sm text-muted mt-3 mb-0"><a href="<?= $base ?>/admin/notifications" class="text-primary"><?= htmlspecialchars(__('admin.view_all_notifications')) ?></a></p>
  <?php endif; ?>
</div>
<div class="dashboard-card">
  <h2><?= htmlspecialchars(__('admin.quick_actions')) ?></h2>
  <div class="quick-actions-wrap">
    <a href="<?= $base ?>/admin/users/create" class="btn btn-outline-primary"><?= htmlspecialchars(__('admin.create_user')) ?></a>
    <a href="<?= $base ?>/admin/articles" class="btn btn-outline-primary"><?= htmlspecialchars(__('admin.manage_articles')) ?></a>
    <a href="<?= $base ?>/admin/paiements" class="btn btn-outline-primary"><?= htmlspecialchars(__('admin.payments')) ?></a>
    <a href="<?= $base ?>/admin/parametres" class="btn btn-outline-primary"><?= htmlspecialchars(__('admin.review_params')) ?></a>
  </div>
</div>
