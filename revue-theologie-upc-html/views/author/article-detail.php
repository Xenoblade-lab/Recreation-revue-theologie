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
<div class="dashboard-header flex justify-between items-start">
  <div>
    <h1><?= htmlspecialchars($article['titre']) ?></h1>
    <p class="text-muted"><?= htmlspecialchars(__('author.submitted_on')) ?> <?= authorFormatDate($article['date_soumission'] ?? $article['created_at'] ?? null) ?> · <?= authorStatutBadge($statut) ?></p>
  </div>
  <?php if (in_array($statut, ['soumis', 'brouillon'], true)): ?>
  <div class="wrap-row">
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
<div class="dashboard-card mt-4">
  <h2><?= htmlspecialchars(__('author.revisions_heading')) ?></h2>
  <p><?= htmlspecialchars(__('author.revisions_intro')) ?></p>
  <p><a href="<?= $base ?>/author/article/<?= (int) $article['id'] ?>/revisions" class="btn btn-outline-primary"><?= htmlspecialchars(__('author.view_revisions')) ?></a></p>
</div>
<p class="mt-4"><a href="<?= $base ?>/author"><?= htmlspecialchars(__('author.back_to_dashboard')) ?></a></p>
