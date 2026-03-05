<?php
$articles = $articles ?? [];
$abonnement = $abonnement ?? null;
$stats = $stats ?? ['total' => 0, 'soumis' => 0, 'valide' => 0, 'rejete' => 0];
$base = $base ?? '';
$isAuthor = $isAuthor ?? false;

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

/** Étapes du workflow auteur : Reçu, Soumis, En évaluation, Révisions, Accepté, Publié. Retourne pour chaque étape 'completed'|'current'|'pending'. */
function authorWorkflowSteps(string $statut): array {
    $statut = strtolower(trim($statut));
    $steps = ['recu', 'soumis', 'en_evaluation', 'revisions', 'accepte', 'publie'];
    $completed = [];
    $current = 0;
    switch ($statut) {
        case 'brouillon':
            $completed = [0];      // Reçu
            $current = 1;         // Soumis
            break;
        case 'soumis':
            $completed = [0, 1];
            $current = 2;
            break;
        case 'en_evaluation':
            $completed = [0, 1, 2];
            $current = 3;
            break;
        case 'revision_requise':
            $completed = [0, 1, 2, 3];
            $current = 4;
            break;
        case 'accepte':
            $completed = [0, 1, 2, 3, 4];
            $current = 5;
            break;
        case 'valide':
            $completed = [0, 1, 2, 3, 4, 5];
            $current = 5;
            break;
        case 'rejete':
            $completed = [0, 1, 2];
            $current = 3;
            break;
        default:
            $completed = [0];
            $current = 1;
    }
    $result = [];
    for ($i = 0; $i < count($steps); $i++) {
        if (in_array($i, $completed, true)) {
            $result[] = ($i === $current && $statut !== 'valide') ? 'current' : 'completed';
        } else {
            $result[] = $i === $current ? 'current' : 'pending';
        }
    }
    return $result;
}
?>
<?php if (!$isAuthor): ?>
<div class="dashboard-header">
  <h1><?= htmlspecialchars(__('author.my_dashboard')) ?></h1>
  <p class="text-muted"><?= htmlspecialchars(__('author.dashboard_subscribe_cta')) ?></p>
</div>
<div class="dashboard-card">
  <p><?= htmlspecialchars(__('author.dashboard_subscribe_intro')) ?></p>
  <p class="mt-4 mb-0 flex flex-wrap gap-2">
    <a href="<?= $base ?>/author/s-abonner" class="btn btn-accent"><?= htmlspecialchars(__('author.subscribe_btn')) ?></a>
    <a href="<?= $base ?>/author/abonnement" class="btn btn-outline-primary"><?= htmlspecialchars(__('author.manage_subscription')) ?></a>
  </p>
</div>
<?php else: ?>
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
  <p class="flex flex-wrap gap-2"><a href="<?= $base ?>/author/soumettre" class="btn btn-accent"><?= htmlspecialchars(__('author.submit_article')) ?></a> <a href="<?= $base ?>/instructions-auteurs" class="btn btn-outline"><?= htmlspecialchars(__('nav.instructions')) ?></a></p>
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
          <th><?= htmlspecialchars(__('author.th_workflow')) ?></th>
          <th><?= htmlspecialchars(__('author.th_actions')) ?></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($articles)): ?>
          <tr><td colspan="5" class="text-muted"><?= htmlspecialchars(__('author.no_articles')) ?></td></tr>
        <?php else: ?>
          <?php foreach ($articles as $a):
            $wf = authorWorkflowSteps($a['statut'] ?? 'soumis');
            $wfLabels = [
                __('author.workflow_recu'),
                __('author.workflow_soumis'),
                __('author.workflow_en_evaluation'),
                __('author.workflow_revisions'),
                __('author.workflow_accepte'),
                __('author.workflow_publie'),
            ];
            $aid = (int) $a['id'];
            $readLabel = htmlspecialchars(__('common.read'));
            $editLabel = htmlspecialchars(__('author.edit_article'));
            $submitLabel = htmlspecialchars(__('author.submit_article_btn'));
          ?>
          <tr>
            <td data-label="<?= htmlspecialchars(__('author.th_title'), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($a['titre']) ?></td>
            <td data-label="<?= htmlspecialchars(__('author.th_date'), ENT_QUOTES, 'UTF-8') ?>"><?= authorFormatDate($a['date_soumission'] ?? $a['created_at'] ?? null) ?></td>
            <td data-label="<?= htmlspecialchars(__('author.th_status'), ENT_QUOTES, 'UTF-8') ?>"><?= authorStatutBadge($a['statut'] ?? 'soumis') ?></td>
            <td class="workflow-col-cell" data-label="<?= htmlspecialchars(__('author.th_workflow'), ENT_QUOTES, 'UTF-8') ?>">
              <div class="workflow-col" role="status" aria-label="<?= htmlspecialchars(__('author.workflow_aria')) ?>">
                <?php for ($i = 0; $i < 6; $i++):
                  $state = $wf[$i] ?? 'pending';
                  $label = $wfLabels[$i] ?? '';
                ?>
                  <?php if ($i > 0): ?><span class="workflow-arrow">→</span><?php endif; ?>
                  <span class="workflow-step-mini workflow-step-<?= $state ?>">
                    <?php if ($state === 'completed'): ?>
                      <span class="workflow-icon workflow-icon-done" aria-hidden="true">✓</span>
                    <?php elseif ($state === 'current'): ?>
                      <span class="workflow-icon workflow-icon-current" aria-hidden="true">•</span>
                    <?php else: ?>
                      <span class="workflow-icon workflow-icon-pending" aria-hidden="true">◦</span>
                    <?php endif; ?>
                    <span class="workflow-label"><?= htmlspecialchars($label) ?></span>
                  </span>
                <?php endfor; ?>
              </div>
            </td>
            <td class="actions-cell" data-label="<?= htmlspecialchars(__('author.th_actions'), ENT_QUOTES, 'UTF-8') ?>">
              <div class="action-buttons">
                <a href="<?= $base ?>/author/article/<?= $aid ?>" class="btn-icon" title="<?= $readLabel ?>" aria-label="<?= $readLabel ?>">
                  <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#eye"/></svg>
                </a>
                <?php if (in_array($a['statut'] ?? '', ['soumis', 'brouillon'], true)): ?>
                  <a href="<?= $base ?>/author/article/<?= $aid ?>/edit" class="btn-icon" title="<?= $editLabel ?>" aria-label="<?= $editLabel ?>">
                    <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#pencil"/></svg>
                  </a>
                <?php endif; ?>
                <?php if (($a['statut'] ?? '') === 'brouillon'): ?>
                  <form method="post" action="<?= $base ?>/author/article/<?= $aid ?>/submit" class="inline-form" style="display:inline;">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn-icon" title="<?= $submitLabel ?>" aria-label="<?= $submitLabel ?>">
                      <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#check"/></svg>
                    </button>
                  </form>
                <?php endif; ?>
              </div>
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
<?php endif; ?>
