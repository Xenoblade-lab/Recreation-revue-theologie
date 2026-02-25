<?php
$base = $base ?? '';
$volumes = $volumes ?? [];
$revuesByVolume = $revuesByVolume ?? [];
$totalRevues = 0;
foreach ($revuesByVolume as $list) { $totalRevues += count($list); }
?>
<div class="page-content-compact page-archives">
  <div class="banner">
    <div class="container">
      <h1 class="font-serif text-xl md:text-2xl font-bold text-balance"><?= htmlspecialchars(__('archives.title')) ?></h1>
      <div class="divider"></div>
      <p class="text-sm" style="color: rgba(255,255,255,0.85);"><?= htmlspecialchars(__('archives.intro')) ?></p>
    </div>
  </div>
  <div class="container section">
    <div class="archives-stats grid-3 mb-4" style="grid-template-columns: repeat(3, 1fr); gap: 0.75rem;">
      <div class="card p-3 text-center"><p class="text-xl font-bold text-primary mb-0"><?= count($volumes) ?></p><p class="text-xs text-muted mt-0"><?= htmlspecialchars(__('archives.volumes')) ?></p></div>
      <div class="card p-3 text-center"><p class="text-xl font-bold text-accent mb-0"><?= $totalRevues ?></p><p class="text-xs text-muted mt-0"><?= htmlspecialchars(__('archives.issues')) ?></p></div>
      <div class="card p-3 text-center"><p class="text-xl font-bold mb-0">—</p><p class="text-xs text-muted mt-0"><?= htmlspecialchars(__('archives.articles')) ?></p></div>
    </div>
    <div class="flex flex-col gap-4">
      <?php foreach ($volumes as $vol):
        $revues = $revuesByVolume[$vol['id']] ?? [];
        $volId = (int)($vol['id'] ?? 0);
      ?>
      <div id="volume-<?= $volId ?>" class="volume-card volume-card-compact">
        <div class="head flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
          <div>
            <h2 class="font-serif text-base font-bold mb-0"><?= htmlspecialchars(__('common.volume')) ?> <?= htmlspecialchars($vol['numero_volume'] ?? $vol['annee'] ?? $vol['id']) ?></h2>
            <p class="text-xs mt-0 mb-0" style="color: rgba(255,255,255,0.7);"><svg class="icon-svg icon-16" style="vertical-align: middle;" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#calendar"/></svg> <?= htmlspecialchars(__('archives.year')) ?> <?= htmlspecialchars($vol['annee'] ?? '') ?></p>
          </div>
          <a href="#volume-<?= $volId ?>" class="btn btn-outline btn-sm" style="border-color: rgba(255,255,255,0.3); color: var(--primary-foreground);"><?= htmlspecialchars(__('archives.see_volume')) ?></a>
        </div>
        <div class="body">
          <?php if (!empty($vol['description'])): ?><p class="text-muted text-xs mb-3"><?= nl2br(htmlspecialchars($vol['description'])) ?></p><?php endif; ?>
          <div class="grid-2 archives-issues-grid" style="grid-template-columns: repeat(2, 1fr); gap: 0.5rem;">
            <?php foreach ($revues as $r):
              $datePub = !empty($r['date_publication']) ? date('F Y', strtotime($r['date_publication'])) : '';
              $numeroId = (int)($r['id'] ?? 0);
            ?>
            <a href="<?= htmlspecialchars($base) ?>/numero/<?= $numeroId ?>" class="card p-3 flex items-center gap-3 archives-issue-link" style="background: var(--secondary); text-decoration: none; color: inherit;">
              <div class="card-icon flex-shrink-0"><svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#file-text"/></svg></div>
              <div class="flex-1 min-w-0">
                <p class="text-xs font-medium mb-0"><?= htmlspecialchars(__('common.issue')) ?> <?= htmlspecialchars($r['numero'] ?? '') ?> — <?= htmlspecialchars($r['titre'] ?? $datePub) ?></p>
                <p class="text-xs text-muted mt-0"><?= $datePub ?></p>
              </div>
              <span class="text-muted">→</span>
            </a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php if (empty($volumes)): ?>
    <p class="text-muted text-sm"><?= htmlspecialchars(__('archives.none')) ?></p>
    <?php endif; ?>
  </div>
</div>
