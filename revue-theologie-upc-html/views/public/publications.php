<?php
$base = $base ?? '';
$articles = $articles ?? [];
$canAccessFullArticle = $canAccessFullArticle ?? false;
$extrait = function ($html, $len = 50) {
  $t = strip_tags($html);
  return mb_strlen($t) > $len ? mb_substr($t, 0, $len) . '…' : $t;
};
?>
<div class="page-content-compact">
  <div class="banner">
    <div class="container">
      <h1 class="font-serif text-lg md:text-xl font-bold text-balance"><?= htmlspecialchars(__('publications.title')) ?></h1>
      <div class="divider"></div>
      <p class="text-xs" style="color: rgba(255,255,255,0.85);"><?= htmlspecialchars(__('publications.intro')) ?></p>
  </div>
  </div>
  <div class="container section">
    <p class="text-xs text-muted mb-1"><?= count($articles) ?> <?= htmlspecialchars(__('publications.found')) ?></p>
    <div id="articles-list" class="flex flex-col articles-list-dense">
      <?php foreach ($articles as $a):
        $auteur = trim(($a['auteur_prenom'] ?? '') . ' ' . ($a['auteur_nom'] ?? ''));
        $date = !empty($a['date_soumission']) ? date('j F Y', strtotime($a['date_soumission'])) : '';
        $resume = $extrait($a['contenu'] ?? '', 50);
      ?>
      <article class="card card-compact">
        <div class="flex flex-col lg:flex-row gap-1">
          <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-1 mb-0">
              <span class="badge badge-primary"><?= htmlspecialchars(__('common.article')) ?></span>
              <h2 class="font-serif text-sm font-bold mb-0"><a href="<?= $base ?>/article/<?= (int)$a['id'] ?>"><?= htmlspecialchars($a['titre'] ?? '') ?></a></h2>
            </div>
            <p class="text-muted article-card-excerpt mb-0 mt-0"><?= htmlspecialchars($resume) ?></p>
            <div class="flex flex-wrap gap-2 text-xs text-muted">
              <?php if ($auteur): ?><span><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#user"/></svg> <?= htmlspecialchars($auteur) ?></span><?php endif; ?>
              <?php if ($date): ?><span><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#clock"/></svg> <?= $date ?></span><?php endif; ?>
            </div>
          </div>
          <div class="flex gap-2 flex-shrink-0">
            <a href="<?= $base ?>/article/<?= (int)$a['id'] ?>" class="btn btn-outline-primary btn-sm"><?= htmlspecialchars(__('common.read')) ?></a>
            <?php if ($canAccessFullArticle && !empty($a['fichier_path'])): ?><a href="<?= $base ?>/download/article/<?= (int)$a['id'] ?>" class="btn btn-outline btn-sm">PDF</a><?php endif; ?>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
    <?php if (empty($articles)): ?>
    <p class="text-muted"><?= htmlspecialchars(__('publications.none')) ?></p>
    <?php endif; ?>
  </div>
</div>
