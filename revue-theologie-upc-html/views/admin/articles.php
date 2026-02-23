<?php
$articles = $articles ?? [];
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
?>
<div class="dashboard-header">
  <h1><?= htmlspecialchars(__('admin.articles')) ?></h1>
  <p><?= htmlspecialchars(__('admin.articles_intro')) ?></p>
</div>
<div class="dashboard-card">
  <div class="overflow-auto">
    <table class="dashboard-table">
      <thead>
        <tr>
          <th><?= htmlspecialchars(__('admin.th_id')) ?></th>
          <th><?= htmlspecialchars(__('author.th_title')) ?></th>
          <th><?= htmlspecialchars(__('author.th_date')) ?></th>
          <th><?= htmlspecialchars(__('author.th_status')) ?></th>
          <th><?= htmlspecialchars(__('admin.th_author')) ?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($articles)): ?>
          <tr><td colspan="6" class="text-muted"><?= htmlspecialchars(__('admin.no_articles')) ?></td></tr>
        <?php else: ?>
          <?php foreach ($articles as $a): ?>
            <tr>
              <td><?= (int) ($a['id'] ?? 0) ?></td>
              <td><?= htmlspecialchars($a['titre'] ?? '') ?></td>
              <td><?= adminFormatDate($a['date_soumission'] ?? null) ?></td>
              <td><?= adminStatutBadge($a['statut'] ?? 'soumis') ?></td>
              <td><?= htmlspecialchars(trim(($a['auteur_prenom'] ?? '') . ' ' . ($a['auteur_nom'] ?? ''))) ?></td>
              <td><a href="<?= $base ?>/admin/article/<?= (int) ($a['id'] ?? 0) ?>" class="btn btn-sm btn-outline"><?= htmlspecialchars(__('common.read')) ?></a></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
