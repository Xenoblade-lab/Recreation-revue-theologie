<?php
$base = $base ?? '';
$articles = $articles ?? [];
$extrait = function ($html, $len = 220) {
  $t = strip_tags($html);
  return mb_strlen($t) > $len ? mb_substr($t, 0, $len) . '...' : $t;
};
?>
<div class="banner">
  <div class="container">
    <h1 class="font-serif text-3xl md:text-4xl font-bold text-balance">Publications</h1>
    <div class="divider"></div>
    <p>Parcourez l'ensemble des articles publiés dans la Revue de la Faculté de Théologie.</p>
  </div>
</div>
<div class="container section">
  <p class="text-sm text-muted mb-6"><?= count($articles) ?> article(s) trouvé(s)</p>
  <div id="articles-list" class="flex flex-col gap-6">
    <?php foreach ($articles as $a):
      $auteur = trim(($a['auteur_prenom'] ?? '') . ' ' . ($a['auteur_nom'] ?? ''));
      $date = !empty($a['date_soumission']) ? date('j F Y', strtotime($a['date_soumission'])) : '';
      $resume = $extrait($a['contenu'] ?? '', 220);
    ?>
    <article class="card p-6">
      <div class="flex flex-col lg:flex-row gap-4">
        <div class="flex-1">
          <div class="flex flex-wrap gap-2 mb-3">
            <span class="badge badge-primary">Article</span>
          </div>
          <h2 class="font-serif text-xl font-bold mb-2"><a href="<?= $base ?>/article/<?= (int)$a['id'] ?>"><?= htmlspecialchars($a['titre'] ?? '') ?></a></h2>
          <p class="text-muted text-sm leading-relaxed mb-4"><?= htmlspecialchars($resume) ?></p>
          <div class="flex flex-wrap gap-4 text-xs text-muted">
            <?php if ($auteur): ?><span><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#user"/></svg> <?= htmlspecialchars($auteur) ?></span><?php endif; ?>
            <?php if ($date): ?><span><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#clock"/></svg> <?= $date ?></span><?php endif; ?>
          </div>
        </div>
        <div class="flex gap-2 flex-shrink-0">
          <a href="<?= $base ?>/article/<?= (int)$a['id'] ?>" class="btn btn-outline-primary btn-sm">Lire</a>
          <?php if (!empty($a['fichier_path'])): ?><a href="<?= $base ?>/download/article/<?= (int)$a['id'] ?>" class="btn btn-outline btn-sm">PDF</a><?php endif; ?>
        </div>
      </div>
    </article>
    <?php endforeach; ?>
  </div>
  <?php if (empty($articles)): ?>
  <p class="text-muted">Aucun article publié pour le moment.</p>
  <?php endif; ?>
</div>
