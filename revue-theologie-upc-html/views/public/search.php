<?php
$q = $q ?? '';
$articles = $articles ?? [];
$numeros = $numeros ?? [];
$base = $base ?? '';
$extrait = function ($html, $len = 180) {
    $t = strip_tags($html);
    return mb_strlen($t) > $len ? mb_substr($t, 0, $len) . '...' : $t;
};
?>
<div class="banner">
  <div class="container">
    <h1 class="font-serif text-3xl md:text-4xl font-bold text-balance">Recherche</h1>
    <div class="divider"></div>
    <form action="<?= $base ?>/search" method="get" class="flex gap-2 flex-wrap items-center max-w-2xl mt-4">
      <label for="search-q" class="sr-only">Rechercher un article ou un numéro</label>
      <input type="search" id="search-q" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Mot-clé, auteur, titre..." class="input flex-1 min-w-[200px]">
      <button type="submit" class="btn btn-primary">Rechercher</button>
    </form>
  </div>
</div>
<div class="container section">
  <?php if ($q === ''): ?>
    <p class="text-muted">Saisissez un mot-clé pour rechercher dans les articles et numéros.</p>
  <?php else: ?>
    <?php if (empty($articles) && empty($numeros)): ?>
      <p class="text-muted">Aucun résultat pour « <?= htmlspecialchars($q) ?> ».</p>
    <?php else: ?>
      <?php if (!empty($articles)): ?>
        <h2 class="font-serif text-xl font-bold mb-4">Articles (<?= count($articles) ?>)</h2>
        <div class="flex flex-col gap-4 mb-8">
          <?php foreach ($articles as $a):
            $auteur = trim(($a['auteur_prenom'] ?? '') . ' ' . ($a['auteur_nom'] ?? ''));
            $date = !empty($a['date_soumission']) ? date('j F Y', strtotime($a['date_soumission'])) : '';
            $resume = $extrait($a['contenu'] ?? '', 180);
          ?>
            <article class="card p-5">
              <h3 class="font-serif font-bold mb-2"><a href="<?= $base ?>/article/<?= (int) $a['id'] ?>" class="text-primary"><?= htmlspecialchars($a['titre'] ?? '') ?></a></h3>
              <p class="text-muted text-sm mb-2"><?= htmlspecialchars($resume) ?></p>
              <div class="flex flex-wrap gap-4 text-xs text-muted">
                <?php if ($auteur): ?><span><?= htmlspecialchars($auteur) ?></span><?php endif; ?>
                <?php if ($date): ?><span><?= $date ?></span><?php endif; ?>
              </div>
              <div class="mt-2">
                <a href="<?= $base ?>/article/<?= (int) $a['id'] ?>" class="btn btn-sm btn-outline-primary">Lire</a>
                <?php if (!empty($a['fichier_path'])): ?>
                  <a href="<?= $base ?>/download/article/<?= (int) $a['id'] ?>" class="btn btn-sm btn-outline">Télécharger PDF</a>
                <?php endif; ?>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <?php if (!empty($numeros)): ?>
        <h2 class="font-serif text-xl font-bold mb-4">Numéros (<?= count($numeros) ?>)</h2>
        <ul class="flex flex-col gap-3">
          <?php foreach ($numeros as $n):
            $dateNum = !empty($n['date_publication']) ? date('j F Y', strtotime($n['date_publication'])) : '';
          ?>
            <li class="card p-4 flex flex-wrap items-center justify-between gap-2">
              <div>
                <strong><a href="<?= $base ?>/numero/<?= (int) $n['id'] ?>" class="text-primary"><?= htmlspecialchars($n['numero'] ?? '') ?> — <?= htmlspecialchars($n['titre'] ?? '') ?></a></strong>
                <?php if ($dateNum): ?><span class="text-muted text-sm ml-2"><?= $dateNum ?></span><?php endif; ?>
              </div>
              <a href="<?= $base ?>/numero/<?= (int) $n['id'] ?>" class="btn btn-sm btn-outline">Voir le numéro</a>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    <?php endif; ?>
  <?php endif; ?>
</div>
