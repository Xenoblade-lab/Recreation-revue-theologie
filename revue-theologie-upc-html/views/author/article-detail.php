<?php
$article = $article ?? null;
$base = $base ?? '';
if (!$article) return;

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
$statut = $article['statut'] ?? 'soumis';
?>
<div class="dashboard-header flex justify-between items-start">
  <div>
    <h1><?= htmlspecialchars($article['titre']) ?></h1>
    <p class="text-muted"><?= htmlspecialchars(__('author.submitted_on')) ?> <?= authorFormatDate($article['date_soumission'] ?? $article['created_at'] ?? null) ?> · <?= authorStatutBadge($statut) ?></p>
  </div>
  <?php if ($statut === 'soumis'): ?>
  <a href="<?= $base ?>/author/article/<?= (int) $article['id'] ?>/edit" class="btn btn-accent"><?= htmlspecialchars(__('author.edit_article')) ?></a>
  <?php endif; ?>
</div>
<div class="dashboard-card">
  <h2><?= htmlspecialchars(__('author.content_summary')) ?></h2>
  <div class="prose"><?= nl2br(htmlspecialchars($article['contenu'] ?? '')) ?></div>
</div>
<?php if (!empty($article['fichier_path'])): ?>
<div class="dashboard-card">
  <h2><?= htmlspecialchars(__('author.attached_file')) ?></h2>
  <p><a href="<?= $base ?>/<?= htmlspecialchars($article['fichier_path']) ?>" class="btn btn-outline" target="_blank" rel="noopener"><?= htmlspecialchars(__('author.download_file')) ?></a></p>
</div>
<?php endif; ?>
<p class="mt-4"><a href="<?= $base ?>/author"><?= htmlspecialchars(__('author.back_to_dashboard')) ?></a></p>
