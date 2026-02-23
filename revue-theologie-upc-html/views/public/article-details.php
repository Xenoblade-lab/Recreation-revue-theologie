<?php
$base = $base ?? '';
$article = $article ?? null;
if (!$article) return;
$auteur = trim(($article['auteur_prenom'] ?? '') . ' ' . ($article['auteur_nom'] ?? ''));
$date = !empty($article['date_soumission']) ? date('j F Y', strtotime($article['date_soumission'])) : '';
$contenu = $article['contenu'] ?? '';
?>
<div class="article-banner banner">
  <div class="container">
    <a href="<?= $base ?>/publications" class="back">← <?= htmlspecialchars(__('article.back_publications')) ?></a>
    <div class="flex flex-wrap gap-2 mb-4">
      <span class="badge badge-primary"><?= htmlspecialchars(__('common.article')) ?></span>
    </div>
    <h1 class="font-serif text-2xl md:text-3xl lg:text-4xl font-bold leading-tight text-balance max-w-4xl"><?= htmlspecialchars($article['titre']) ?></h1>
  </div>
</div>
<div class="container section">
  <div class="flex flex-col lg:flex-row gap-8">
    <div class="flex-1 article-content">
      <?php if ($contenu): ?>
      <section class="mb-8 prose">
        <h2 class="font-serif text-xl font-bold mb-4">Contenu</h2>
        <div class="article-content text-muted leading-relaxed"><?= $contenu ?></div>
      </section>
      <?php endif; ?>
      <?php if (!empty($article['fichier_path'])): ?>
      <div class="card p-6 text-center mt-8">
        <p class="text-sm text-muted mb-4"><?= htmlspecialchars(__('article.read_pdf_intro')) ?></p>
        <a href="<?= $base ?>/download/article/<?= (int) ($article['id'] ?? 0) ?>?inline=1" class="btn btn-accent" target="_blank" rel="noopener"><?= htmlspecialchars(__('article.read_pdf')) ?></a>
      </div>
      <?php endif; ?>
    </div>
    <aside class="lg:w-80 flex-shrink-0">
      <div class="sticky-top flex flex-col gap-6">
        <?php if (!empty($article['fichier_path'])): ?>
        <div class="card p-6 text-center" style="background: var(--primary); color: var(--primary-foreground);">
          <h3 class="font-serif font-bold mb-2"><?= htmlspecialchars(__('article.download')) ?></h3>
          <p class="text-sm mb-4" style="color: rgba(255,255,255,0.7);"><?= htmlspecialchars(__('article.download_pdf_intro')) ?></p>
          <a href="<?= $base ?>/download/article/<?= (int) ($article['id'] ?? 0) ?>" class="btn btn-accent w-full" download><?= htmlspecialchars(__('article.download_pdf')) ?></a>
        </div>
        <?php endif; ?>
        <div class="card p-6">
          <h3 class="font-serif font-bold mb-4"><?= htmlspecialchars(__('article.info')) ?></h3>
          <ul class="flex flex-col gap-3 text-sm" style="list-style: none; padding: 0;">
            <?php if ($auteur): ?>
            <li class="flex gap-3">
              <span class="text-primary"><svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#user"/></svg></span>
              <div>
                <p class="text-muted text-xs mb-0"><?= htmlspecialchars(__('article.author')) ?></p>
                <p class="font-medium mb-0"><?= htmlspecialchars($auteur) ?></p>
              </div>
            </li>
            <?php endif; ?>
            <?php if ($date): ?>
            <li class="flex gap-3">
              <span class="text-primary"><svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#calendar"/></svg></span>
              <div>
                <p class="text-muted text-xs mb-0"><?= htmlspecialchars(__('article.pub_date')) ?></p>
                <p class="font-medium mb-0"><?= $date ?></p>
              </div>
            </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </aside>
  </div>
</div>
