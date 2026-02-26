<?php
$article = $article ?? null;
$evaluations = $evaluations ?? [];
$reviewers = $reviewers ?? [];
$volumes = $volumes ?? [];
$revues = $revues ?? [];
$error = $error ?? null;
$base = $base ?? '';
if (!$article) return;

function adminStatutBadge(string $statut): string {
    $map = [
        'soumis' => ['label' => function_exists('__') ? __('admin.article_status_soumis') : 'Soumis', 'class' => 'badge-primary'],
        'en_lecture' => ['label' => function_exists('__') ? __('admin.article_status_en_lecture') : 'En évaluation', 'class' => 'badge'],
        'accepte' => ['label' => function_exists('__') ? __('admin.article_status_accepte') : 'Accepté', 'class' => 'badge green'],
        'rejete' => ['label' => function_exists('__') ? __('admin.article_status_rejete') : 'Rejeté', 'class' => 'badge accent'],
        'revision_requise' => ['label' => function_exists('__') ? __('admin.article_status_revision') : 'Révision requise', 'class' => 'badge accent'],
        'valide' => ['label' => function_exists('__') ? __('admin.article_status_publie') : 'Publié', 'class' => 'badge green'],
        'publie' => ['label' => function_exists('__') ? __('admin.article_status_publie') : 'Publié', 'class' => 'badge green'],
    ];
    $c = $map[$statut] ?? ['label' => $statut, 'class' => 'badge'];
    return '<span class="badge ' . $c['class'] . '">' . htmlspecialchars($c['label']) . '</span>';
}
function adminFormatDate(?string $d): string {
    if (!$d) return '—';
    $t = strtotime($d);
    return $t ? date('d/m/Y', $t) : $d;
}
function evalStatutLabel(?string $s): string {
    if (!$s) return '—';
    $map = ['en_attente' => 'En attente', 'en_cours' => 'En cours', 'soumis' => 'Soumis', 'termine' => 'Terminé'];
    return $map[$s] ?? $s;
}
$statut = $article['statut'] ?? 'soumis';
$articleId = (int) $article['id'];
?>
<div class="dashboard-header flex justify-between items-start">
  <div>
    <h1><?= htmlspecialchars($article['titre']) ?></h1>
    <p class="text-muted">
      <?= htmlspecialchars(__('admin.th_author')) ?> : <?= htmlspecialchars(trim(($article['auteur_prenom'] ?? '') . ' ' . ($article['auteur_nom'] ?? ''))) ?>
      · <?= htmlspecialchars(__('author.submitted_on')) ?> <?= adminFormatDate($article['date_soumission'] ?? $article['created_at'] ?? null) ?>
      · <?= adminStatutBadge($statut) ?>
    </p>
  </div>
  <a href="<?= $base ?>/admin/articles" class="btn btn-outline"><?= htmlspecialchars(__('admin.back_list')) ?></a>
</div>
<?php if ($error): ?>
<div class="alert alert-error mb-4"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
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

<div class="dashboard-card">
  <h2><?= htmlspecialchars(__('admin.change_status')) ?></h2>
  <form method="post" action="<?= $base ?>/admin/article/<?= $articleId ?>/statut" class="flex items-center gap-2 flex-wrap">
    <?= csrf_field() ?>
    <select name="statut" class="input" style="width: auto;">
      <option value="soumis" <?= $statut === 'soumis' ? 'selected' : '' ?>><?= htmlspecialchars(__('admin.article_status_soumis')) ?></option>
      <option value="valide" <?= $statut === 'valide' ? 'selected' : '' ?>><?= htmlspecialchars(__('admin.article_status_publie')) ?></option>
      <option value="rejete" <?= $statut === 'rejete' ? 'selected' : '' ?>><?= htmlspecialchars(__('admin.article_status_rejete')) ?></option>
    </select>
    <button type="submit" class="btn btn-primary"><?= htmlspecialchars(__('admin.save')) ?></button>
  </form>
</div>

<div class="dashboard-card">
  <h2><?= htmlspecialchars(__('admin.assign_reviewer')) ?></h2>
  <form method="post" action="<?= $base ?>/admin/article/<?= $articleId ?>/assign" class="flex items-center gap-2 flex-wrap">
    <?= csrf_field() ?>
    <select name="evaluateur_id" class="input" style="width: auto; min-width: 200px;" required>
      <option value="">— <?= htmlspecialchars(__('admin.choose_reviewer')) ?> —</option>
      <?php foreach ($reviewers as $r): ?>
        <option value="<?= (int) $r['id'] ?>"><?= htmlspecialchars(trim(($r['prenom'] ?? '') . ' ' . ($r['nom'] ?? ''))) ?> (<?= htmlspecialchars($r['email'] ?? '') ?>)</option>
      <?php endforeach; ?>
    </select>
    <button type="submit" class="btn btn-primary"><?= htmlspecialchars(__('admin.assign')) ?></button>
  </form>
</div>

<div class="dashboard-card">
  <h2><?= htmlspecialchars(__('admin.assign_issue')) ?></h2>
  <form method="post" action="<?= $base ?>/admin/article/<?= $articleId ?>/issue" class="flex items-center gap-2 flex-wrap">
    <?= csrf_field() ?>
    <select name="issue_id" class="input" style="width: auto; min-width: 220px;">
      <option value="">— <?= htmlspecialchars(__('admin.no_issue')) ?> —</option>
      <?php foreach ($revues as $rev): ?>
        <option value="<?= (int) $rev['id'] ?>" <?= (isset($article['issue_id']) && (int)$article['issue_id'] === (int)$rev['id']) ? 'selected' : '' ?>><?= htmlspecialchars($rev['numero'] ?? '') ?> — <?= htmlspecialchars($rev['titre'] ?? '') ?></option>
      <?php endforeach; ?>
    </select>
    <button type="submit" class="btn btn-primary"><?= htmlspecialchars(__('admin.save')) ?></button>
  </form>
</div>

<div class="dashboard-card">
  <h2><?= htmlspecialchars(__('admin.evaluations_list')) ?></h2>
  <?php if (empty($evaluations)): ?>
    <p class="text-muted"><?= htmlspecialchars(__('admin.no_evaluations')) ?></p>
  <?php else: ?>
    <div class="overflow-auto">
      <table class="dashboard-table">
        <thead>
          <tr>
            <th><?= htmlspecialchars(__('admin.th_user')) ?></th>
            <th><?= htmlspecialchars(__('admin.th_status')) ?></th>
            <th>Recommandation</th>
            <th><?= htmlspecialchars(__('admin.th_date')) ?></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($evaluations as $e): ?>
            <tr>
              <td><?= htmlspecialchars(trim(($e['evaluateur_prenom'] ?? '') . ' ' . ($e['evaluateur_nom'] ?? ''))) ?></td>
              <td><?= htmlspecialchars(evalStatutLabel($e['statut'] ?? null)) ?></td>
              <td><?= htmlspecialchars($e['recommendation'] ?? '—') ?></td>
              <td><?= adminFormatDate($e['date_soumission'] ?? null) ?></td>
              <td><a href="<?= $base ?>/reviewer/evaluation/<?= (int)($e['id'] ?? 0) ?>" class="btn btn-sm btn-outline" target="_blank">Voir</a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<p class="mt-4"><a href="<?= $base ?>/admin/articles"><?= htmlspecialchars(__('admin.back_list')) ?></a></p>
