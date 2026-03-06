<?php
$evaluations = $evaluations ?? [];
$total = $total ?? 0;
$filterStatut = $filterStatut ?? null;
$base = $base ?? '';

function adminEvalStatutLabel(string $s): string {
    $map = ['en_attente' => 'admin.eval_en_attente', 'en_cours' => 'admin.eval_en_cours', 'termine' => 'admin.eval_termine', 'annule' => 'admin.eval_annule'];
    return function_exists('__') ? __($map[$s] ?? $s) : $s;
}
function adminFormatDate(?string $d): string {
    if (!$d) return '—';
    $t = strtotime($d);
    return $t ? date('d/m/Y', $t) : $d;
}
?>
<div class="dashboard-header">
  <h1><?= htmlspecialchars(__('admin.evaluations_list')) ?></h1>
  <p><?= htmlspecialchars(__('admin.evaluations_intro')) ?></p>
</div>
<div class="dashboard-card mb-4">
  <form method="get" action="<?= $base ?>/admin/evaluations" class="flex flex-wrap items-center gap-3">
    <label for="statut" class="font-medium"><?= htmlspecialchars(__('admin.filter_status')) ?></label>
    <select id="statut" name="statut" class="input" style="width: auto; min-width: 12rem;">
      <option value=""><?= htmlspecialchars(__('admin.filter_all')) ?></option>
      <option value="en_attente" <?= $filterStatut === 'en_attente' ? 'selected' : '' ?>><?= htmlspecialchars(__('admin.eval_en_attente')) ?></option>
      <option value="en_cours" <?= $filterStatut === 'en_cours' ? 'selected' : '' ?>><?= htmlspecialchars(__('admin.eval_en_cours')) ?></option>
      <option value="termine" <?= $filterStatut === 'termine' ? 'selected' : '' ?>><?= htmlspecialchars(__('admin.eval_termine')) ?></option>
      <option value="annule" <?= $filterStatut === 'annule' ? 'selected' : '' ?>><?= htmlspecialchars(__('admin.eval_annule')) ?></option>
    </select>
    <button type="submit" class="btn btn-outline-primary"><?= htmlspecialchars(__('admin.filter_apply')) ?></button>
  </form>
  <p class="text-muted mt-2"><?= (int) $total ?> <?= htmlspecialchars(__('admin.evaluations_count')) ?></p>
</div>
<div class="dashboard-card">
  <div class="overflow-auto">
    <table class="dashboard-table">
      <thead>
        <tr>
          <th><?= htmlspecialchars(__('admin.th_id')) ?></th>
          <th><?= htmlspecialchars(__('admin.th_article')) ?></th>
          <th><?= htmlspecialchars(__('admin.th_evaluator')) ?></th>
          <th><?= htmlspecialchars(__('admin.th_status')) ?></th>
          <th><?= htmlspecialchars(__('admin.th_assignment_date')) ?></th>
          <th><?= htmlspecialchars(__('admin.th_deadline')) ?></th>
          <th><?= htmlspecialchars(__('admin.th_submission_date')) ?></th>
          <th><?= htmlspecialchars(__('admin.th_recommendation')) ?></th>
          <th><?= htmlspecialchars(__('admin.actions')) ?></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($evaluations)): ?>
          <tr><td colspan="9" class="text-muted"><?= htmlspecialchars(__('admin.evaluations_empty')) ?></td></tr>
        <?php else: ?>
          <?php foreach ($evaluations as $e):
              $eid = (int)($e['id'] ?? 0);
              $aid = (int)($e['article_id'] ?? 0);
              $viewLabel = htmlspecialchars(__('common.read'));
              $unassignLabel = htmlspecialchars(__('admin.unassign_evaluator'));
              $confirmUnassign = __('admin.confirm_unassign_evaluator');
          ?>
            <tr>
              <td data-label="<?= htmlspecialchars(__('admin.th_id'), ENT_QUOTES, 'UTF-8') ?>"><?= $eid ?></td>
              <td data-label="<?= htmlspecialchars(__('admin.th_article'), ENT_QUOTES, 'UTF-8') ?>">
                <a href="<?= $base ?>/admin/article/<?= $aid ?>"><?= htmlspecialchars($e['article_titre'] ?? '—') ?></a>
                <span class="text-sm text-muted">(<?= htmlspecialchars($e['article_statut'] ?? '') ?>)</span>
              </td>
              <td data-label="<?= htmlspecialchars(__('admin.th_evaluator'), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars(trim(($e['evaluateur_prenom'] ?? '') . ' ' . ($e['evaluateur_nom'] ?? ''))) ?: '—' ?></td>
              <td data-label="<?= htmlspecialchars(__('admin.th_status'), ENT_QUOTES, 'UTF-8') ?>"><span class="badge"><?= htmlspecialchars(adminEvalStatutLabel($e['statut'] ?? '')) ?></span></td>
              <td data-label="<?= htmlspecialchars(__('admin.th_assignment_date'), ENT_QUOTES, 'UTF-8') ?>"><?= adminFormatDate($e['date_assignation'] ?? null) ?></td>
              <td data-label="<?= htmlspecialchars(__('admin.th_deadline'), ENT_QUOTES, 'UTF-8') ?>"><?= adminFormatDate($e['date_echeance'] ?? null) ?></td>
              <td data-label="<?= htmlspecialchars(__('admin.th_submission_date'), ENT_QUOTES, 'UTF-8') ?>"><?= adminFormatDate($e['date_soumission'] ?? null) ?></td>
              <td data-label="<?= htmlspecialchars(__('admin.th_recommendation'), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($e['recommendation'] ?? '—') ?></td>
              <td class="actions-cell" data-label="<?= htmlspecialchars(__('admin.actions'), ENT_QUOTES, 'UTF-8') ?>">
                <div class="action-buttons">
                  <a href="<?= $base ?>/admin/article/<?= $aid ?>" class="btn-icon" title="<?= $viewLabel ?>" aria-label="<?= $viewLabel ?>">
                    <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#eye"/></svg>
                  </a>
                  <form method="post" action="<?= $base ?>/admin/evaluation/<?= $eid ?>/unassign" class="inline-form js-confirm-submit" style="display:inline;" data-confirm-message="<?= htmlspecialchars($confirmUnassign, ENT_QUOTES, 'UTF-8') ?>">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn-icon btn-icon-danger" title="<?= $unassignLabel ?>" aria-label="<?= $unassignLabel ?>">
                      <svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#trash"/></svg>
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
