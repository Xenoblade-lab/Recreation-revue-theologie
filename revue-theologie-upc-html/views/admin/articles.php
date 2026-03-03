<?php
$articles = $articles ?? [];
$error = $error ?? null;
$success = $success ?? null;
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
<?php if ($error): ?>
<div class="alert alert-error mb-4"><?= htmlspecialchars(is_string($error) ? $error : __('admin.error')) ?></div>
<?php endif; ?>
<?php if ($success): ?>
<div class="alert alert-success mb-4"><?= htmlspecialchars(is_string($success) ? $success : __('admin.success')) ?></div>
<?php endif; ?>
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
          <th><?= htmlspecialchars(__('admin.actions')) ?></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($articles)): ?>
          <tr><td colspan="6" class="text-muted"><?= htmlspecialchars(__('admin.no_articles')) ?></td></tr>
        <?php else: ?>
          <?php foreach ($articles as $a):
              $aid = (int) ($a['id'] ?? 0);
              $viewLabel = htmlspecialchars(__('common.read'));
              $deleteLabel = htmlspecialchars(__('admin.delete'));
              $confirmDelete = __('admin.confirm_delete_article');
          ?>
            <tr>
              <td><?= $aid ?></td>
              <td><?= htmlspecialchars($a['titre'] ?? '') ?></td>
              <td><?= adminFormatDate($a['date_soumission'] ?? null) ?></td>
              <td><?= adminStatutBadge($a['statut'] ?? 'soumis') ?></td>
              <td><?= htmlspecialchars(trim(($a['auteur_prenom'] ?? '') . ' ' . ($a['auteur_nom'] ?? ''))) ?></td>
              <td class="actions-cell">
                <div class="action-buttons">
                  <a href="<?= $base ?>/admin/article/<?= $aid ?>" class="btn-icon" title="<?= $viewLabel ?>" aria-label="<?= $viewLabel ?>">
                    <svg class="icon-svg icon-20" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                  </a>
                  <form method="post" action="<?= $base ?>/admin/article/<?= $aid ?>/delete" class="inline-form js-confirm-submit" style="display:inline;" data-confirm-message="<?= htmlspecialchars($confirmDelete, ENT_QUOTES, 'UTF-8') ?>">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn-icon btn-icon-danger" title="<?= $deleteLabel ?>" aria-label="<?= $deleteLabel ?>">
                      <svg class="icon-svg icon-20" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
