<?php
$base = $base ?? '';
$volumes = $volumes ?? [];
$revuesByVolume = $revuesByVolume ?? [];
$totalRevues = 0;
foreach ($revuesByVolume as $list) { $totalRevues += count($list); }
?>
<div class="banner">
  <div class="container">
    <h1 class="font-serif text-3xl md:text-4xl font-bold text-balance">Archives</h1>
    <div class="divider"></div>
    <p>Consultez l'ensemble des volumes et numéros de la Revue de la Faculté de Théologie.</p>
  </div>
</div>
<div class="container section">
  <div class="grid-3 mb-8" style="grid-template-columns: repeat(3, 1fr); gap: 1rem;">
    <div class="card p-6 text-center"><p class="text-3xl font-bold text-primary mb-0"><?= count($volumes) ?></p><p class="text-sm text-muted mt-1">Volumes</p></div>
    <div class="card p-6 text-center"><p class="text-3xl font-bold text-accent mb-0"><?= $totalRevues ?></p><p class="text-sm text-muted mt-1">Numéros</p></div>
    <div class="card p-6 text-center"><p class="text-3xl font-bold mb-0">—</p><p class="text-sm text-muted mt-1">Articles</p></div>
  </div>
  <div class="flex flex-col gap-8">
    <?php foreach ($volumes as $vol):
      $revues = $revuesByVolume[$vol['id']] ?? [];
    ?>
    <div class="volume-card">
      <div class="head flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
          <h2 class="font-serif text-xl font-bold mb-0">Volume <?= htmlspecialchars($vol['numero_volume'] ?? $vol['annee'] ?? $vol['id']) ?></h2>
          <p class="text-sm mt-1 mb-0" style="color: rgba(255,255,255,0.7);"><svg class="icon-svg icon-16" style="vertical-align: middle;" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#calendar"/></svg> Année <?= htmlspecialchars($vol['annee'] ?? '') ?></p>
        </div>
        <a href="<?= $base ?>/archives" class="btn btn-outline btn-sm" style="border-color: rgba(255,255,255,0.3); color: var(--primary-foreground);">Voir le volume →</a>
      </div>
      <div class="body">
        <?php if (!empty($vol['description'])): ?><p class="text-muted text-sm mb-4"><?= nl2br(htmlspecialchars($vol['description'])) ?></p><?php endif; ?>
        <div class="grid-2 gap-4" style="grid-template-columns: repeat(2, 1fr);">
          <?php foreach ($revues as $r):
            $datePub = !empty($r['date_publication']) ? date('F Y', strtotime($r['date_publication'])) : '';
          ?>
          <a href="<?= $base ?>/numero/<?= (int)$r['id'] ?>" class="card p-4 flex items-center gap-4" style="background: var(--secondary);">
            <div class="card-icon flex-shrink-0"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#file-text"/></svg></div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium mb-0">Numéro <?= htmlspecialchars($r['numero'] ?? '') ?> — <?= htmlspecialchars($r['titre'] ?? $datePub) ?></p>
              <p class="text-xs text-muted mt-1"><?= $datePub ?></p>
            </div>
            <span>→</span>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php if (empty($volumes)): ?>
  <p class="text-muted">Aucun volume ou numéro pour le moment.</p>
  <?php endif; ?>
</div>
