<?php
$base = $base ?? '';
$revue = $revue ?? null;
$parts = $parts ?? [];
$volume = $volume ?? null;
$articlesNumero = $articlesNumero ?? [];
if (!$revue) return;
$volLabel = $volume ? (function_exists('__') ? htmlspecialchars(__('common.volume')) : 'Volume') . ' ' . ($volume['numero_volume'] ?? $volume['annee'] ?? '') . ' — ' : '';
$datePub = !empty($revue['date_publication']) ? date('F Y', strtotime($revue['date_publication'])) : '';
?>
<div class="page-content-compact page-numero-detail">
  <div class="banner">
    <div class="container">
      <h1 class="font-serif text-xl md:text-2xl font-bold"><?= $volLabel ?><?= function_exists('__') ? htmlspecialchars(__('common.issue')) : 'Numéro' ?> <?= htmlspecialchars($revue['numero']) ?></h1>
      <div class="divider"></div>
      <p class="text-sm" style="color: rgba(255,255,255,0.85);"><?= $datePub ?> — <?= count($articlesNumero) ?> <?= htmlspecialchars(__('numero.articles_in_issue')) ?></p>
    </div>
  </div>
  <div class="container section">
    <?php if (!empty($revue['description'])): ?>
    <p class="text-muted text-sm mb-3"><?= nl2br(htmlspecialchars($revue['description'])) ?></p>
    <?php endif; ?>
    <div class="card numero-detail-card p-4 max-w-3xl mx-auto">
      <?php if (!empty($articlesNumero)): ?>
      <p class="text-muted text-xs mb-2"><?= htmlspecialchars(__('numero.contains')) ?></p>
      <ul class="flex flex-col gap-1 text-sm">
        <?php foreach ($articlesNumero as $a):
          $auteur = trim(($a['auteur_prenom'] ?? '') . ' ' . ($a['auteur_nom'] ?? ''));
        ?>
        <li><a href="<?= $base ?>/article/<?= (int)$a['id'] ?>"><?= htmlspecialchars($a['titre']) ?></a><?= $auteur ? ' — ' . htmlspecialchars($auteur) : '' ?></li>
        <?php endforeach; ?>
      </ul>
      <?php elseif (!empty($parts)): ?>
      <p class="text-muted text-xs mb-2"><?= htmlspecialchars(__('numero.contents')) ?></p>
      <ul class="flex flex-col gap-1 text-sm">
        <?php foreach ($parts as $p): ?>
        <li>
          <strong><?= htmlspecialchars($p['titre'] ?? '') ?></strong>
          <?php if (!empty($p['auteurs'])): ?> — <?= htmlspecialchars($p['auteurs']) ?><?php endif; ?>
          <?php if (!empty($p['pages'])): ?> (pp. <?= htmlspecialchars($p['pages']) ?>)<?php endif; ?>
          <?php if (!empty($p['file_path'])): ?> <a href="<?= $base ?>/<?= htmlspecialchars(ltrim($p['file_path'], '/')) ?>" class="btn btn-outline btn-sm" target="_blank" rel="noopener">PDF</a><?php endif; ?>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php else: ?>
      <p class="text-muted text-sm"><?= htmlspecialchars(__('numero.none')) ?></p>
      <?php endif; ?>
      <p class="mt-4 flex flex-wrap gap-2">
        <a href="<?= $base ?>/archives" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars(__('numero.back_archives')) ?></a>
        <a href="<?= $base ?>/publications" class="btn btn-primary btn-sm"><?= htmlspecialchars(__('numero.all_publications')) ?></a>
      </p>
    </div>
  </div>
</div>
