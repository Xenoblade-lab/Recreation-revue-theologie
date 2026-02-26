<?php
$article = $article ?? null;
$evaluations = $evaluations ?? [];
$base = $base ?? '';
if (!$article) return;

function authorRevisionsFormatDate(?string $d): string {
    if (!$d) return '—';
    $t = strtotime($d);
    return $t ? date('d/m/Y H:i', $t) : $d;
}
function authorRevisionsRecoLabel(string $reco): string {
    $map = [
        'accepte' => 'author.reco_accepte',
        'accepte_avec_modifications' => 'author.reco_accepte_modif',
        'revision_mineure' => 'author.reco_minor',
        'revision_majeure' => 'author.reco_major',
        'rejete' => 'author.reco_rejete',
    ];
    $key = $map[$reco] ?? null;
    return $key && function_exists('__') ? __($key) : $reco;
}
$statut = $article['statut'] ?? 'soumis';
$statutLabels = [
    'soumis' => 'author.status_soumis',
    'en_lecture' => 'author.status_en_lecture',
    'revision_requise' => 'author.status_revision_requise',
    'valide' => 'author.status_valide',
    'rejete' => 'author.status_rejete',
];
$statutLabel = isset($statutLabels[$statut]) && function_exists('__') ? __($statutLabels[$statut]) : $statut;
?>
<div class="dashboard-header">
  <h1><?= htmlspecialchars(__('author.revisions_title')) ?></h1>
  <p class="text-muted"><a href="<?= $base ?>/author/article/<?= (int) $article['id'] ?>"><?= htmlspecialchars($article['titre']) ?></a> · <?= htmlspecialchars($statutLabel) ?></p>
</div>
<div class="dashboard-card">
  <h2><?= htmlspecialchars(__('author.revisions_timeline')) ?></h2>
  <ul class="space-y-4" style="list-style: none; padding-left: 0;">
    <?php
    $events = [];
    $events[] = ['date' => $article['date_soumission'] ?? $article['created_at'] ?? null, 'type' => 'soumission', 'label' => __('author.revisions_event_submitted') ?: 'Article soumis'];
    foreach ($evaluations as $i => $ev) {
        $events[] = ['date' => $ev['date_assignation'] ?? null, 'type' => 'assignation', 'label' => (function_exists('__') ? __('author.revisions_event_assigned') : 'Évaluation assignée') . ' #' . ($i + 1)];
        if (!empty($ev['date_soumission']) && ($ev['statut'] ?? '') === 'termine') {
            $reco = authorRevisionsRecoLabel($ev['recommendation'] ?? '');
            $events[] = ['date' => $ev['date_soumission'], 'type' => 'eval_received', 'label' => (function_exists('__') ? __('author.revisions_event_eval_received') : 'Évaluation reçue') . ' — ' . $reco, 'comment' => $ev['commentaires_public'] ?? null];
        }
    }
    usort($events, function ($a, $b) {
        $ta = $a['date'] ? strtotime($a['date']) : 0;
        $tb = $b['date'] ? strtotime($b['date']) : 0;
        return $ta <=> $tb;
    });
    foreach ($events as $ev):
        $label = $ev['label'] ?? '';
        $comment = $ev['comment'] ?? null;
    ?>
    <li class="flex gap-3">
      <span class="text-muted shrink-0" style="min-width: 140px;"><?= authorRevisionsFormatDate($ev['date']) ?></span>
      <div>
        <span><?= htmlspecialchars($label) ?></span>
        <?php if (!empty($comment)): ?>
        <div class="mt-2 p-3 rounded border bg-muted" style="background: rgba(0,0,0,0.03);"><?= nl2br(htmlspecialchars($comment)) ?></div>
        <?php endif; ?>
      </div>
    </li>
    <?php endforeach; ?>
  </ul>
</div>
<p class="mt-4"><a href="<?= $base ?>/author/article/<?= (int) $article['id'] ?>" class="btn btn-outline-primary"><?= htmlspecialchars(__('author.back_to_article')) ?></a> <a href="<?= $base ?>/author"><?= htmlspecialchars(__('author.back_to_dashboard')) ?></a></p>
