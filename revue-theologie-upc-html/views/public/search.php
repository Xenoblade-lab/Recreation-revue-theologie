<?php
$q = $q ?? '';
$articles = $articles ?? [];
$numeros = $numeros ?? [];
$base = $base ?? '';
$extrait = function ($html, $len = 80) {
    $t = strip_tags($html);
    return mb_strlen($t) > $len ? mb_substr($t, 0, $len) . '...' : $t;
};
?>
<div class="page-content-compact page-search">
<div class="banner">
  <div class="container">
    <h1 class="font-serif text-lg md:text-xl font-bold text-balance"><?= htmlspecialchars(__('search.title')) ?></h1>
    <div class="divider"></div>
    <form action="<?= $base ?>/search" method="get" class="flex gap-2 flex-wrap items-center max-w-2xl mt-1">
      <label for="search-q" class="sr-only"><?= htmlspecialchars(__('search.label')) ?></label>
      <input type="search" id="search-q" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="<?= htmlspecialchars(__('search.placeholder')) ?>" class="input flex-1 min-w-[200px]">
      <button type="submit" class="btn btn-sm btn-primary"><?= htmlspecialchars(__('nav.search')) ?></button>
    </form>
  </div>
</div>
<div class="container section">
  <?php if ($q === ''): ?>
    <p class="text-muted text-xs"><?= htmlspecialchars(function_exists('__') ? __('search.hint') : 'Saisissez un mot-clé pour rechercher dans les articles et numéros.') ?></p>
  <?php else:
    $nbArticles = count($articles);
    $nbNumeros = count($numeros);
    $hasResults = $nbArticles > 0 || $nbNumeros > 0;
  ?>
    <div class="search-results-intro mb-2">
      <?php if ($hasResults): ?>
        <p class="text-xs font-medium">
          <?= htmlspecialchars(function_exists('__') ? sprintf(__('search.results_for'), $q) : 'Résultats pour « ' . $q . ' »') ?> :
          <?= htmlspecialchars(function_exists('__') ? sprintf(__('search.results_summary'), $nbArticles, $nbNumeros) : sprintf('%d article(s), %d numéro(s)', $nbArticles, $nbNumeros)) ?>.
        </p>
      <?php else: ?>
        <p class="text-xs font-medium text-foreground">
          <?= htmlspecialchars(function_exists('__') ? sprintf(__('search.no_results_full'), $q) : 'Aucun résultat trouvé pour « ' . $q . ' ». Essayez d\'autres mots-clés, un titre ou un nom d\'auteur.') ?>
        </p>
      <?php endif; ?>
    </div>
    <?php if ($hasResults): ?>
      <?php if (!empty($articles)): ?>
        <h2 class="font-serif text-sm font-bold mb-1"><?= htmlspecialchars(__('search.articles')) ?> (<?= count($articles) ?>)</h2>
        <div class="flex flex-col gap-2 search-articles-list mb-3">
          <?php foreach ($articles as $a):
            $auteur = trim(($a['auteur_prenom'] ?? '') . ' ' . ($a['auteur_nom'] ?? ''));
            $date = !empty($a['date_soumission']) ? date('j F Y', strtotime($a['date_soumission'])) : '';
            $resume = $extrait($a['contenu'] ?? '', 80);
          ?>
            <article class="card search-article-card">
              <h3 class="font-serif font-bold text-xs mb-0"><a href="<?= $base ?>/article/<?= (int) $a['id'] ?>" class="text-primary"><?= htmlspecialchars($a['titre'] ?? '') ?></a></h3>
              <p class="text-muted text-xs mb-0 mt-0 search-article-excerpt"><?= htmlspecialchars($resume) ?></p>
              <div class="flex flex-wrap gap-2 text-xs text-muted search-article-meta">
                <?php if ($auteur): ?><span><?= htmlspecialchars($auteur) ?></span><?php endif; ?>
                <?php if ($date): ?><span><?= $date ?></span><?php endif; ?>
              </div>
              <div class="search-article-actions">
                <a href="<?= $base ?>/article/<?= (int) $a['id'] ?>" class="btn btn-sm btn-outline-primary"><?= htmlspecialchars(__('common.read')) ?></a>
                <?php if (!empty($a['fichier_path'])): ?>
                  <a href="<?= $base ?>/download/article/<?= (int) $a['id'] ?>" class="btn btn-sm btn-outline"><?= htmlspecialchars(__('article.download_pdf')) ?></a>
                <?php endif; ?>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <?php if (!empty($numeros)): ?>
        <h2 class="font-serif text-sm font-bold mb-1"><?= htmlspecialchars(__('search.issues')) ?> (<?= count($numeros) ?>)</h2>
        <ul class="flex flex-col gap-1 search-numeros-list">
          <?php foreach ($numeros as $n):
            $dateNum = !empty($n['date_publication']) ? date('j F Y', strtotime($n['date_publication'])) : '';
          ?>
            <li class="card search-numero-card flex flex-wrap items-center justify-between gap-1">
              <div class="search-numero-text">
                <strong class="text-xs"><a href="<?= $base ?>/numero/<?= (int) $n['id'] ?>" class="text-primary"><?= htmlspecialchars($n['numero'] ?? '') ?> — <?= htmlspecialchars($n['titre'] ?? '') ?></a></strong>
                <?php if ($dateNum): ?><span class="text-muted text-xs ml-1"><?= $dateNum ?></span><?php endif; ?>
              </div>
              <a href="<?= $base ?>/numero/<?= (int) $n['id'] ?>" class="btn btn-sm btn-outline"><?= htmlspecialchars(__('numero.see_issue')) ?></a>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    <?php endif; ?>
  <?php endif; ?>
</div>
</div>
