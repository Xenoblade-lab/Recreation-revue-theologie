<?php
$base = $base ?? '';
$article = $article ?? null;
if (!$article) return;
$canAccessFullArticle = $canAccessFullArticle ?? false;
$auteur = trim(($article['auteur_prenom'] ?? '') . ' ' . ($article['auteur_nom'] ?? ''));
$date = !empty($article['date_soumission']) ? date('j F Y', strtotime($article['date_soumission'])) : '';
$contenu = $article['contenu'] ?? '';
?>
<div class="page-content-compact page-article-detail">
  <div class="article-banner banner">
    <div class="container">
      <a href="<?= $base ?>/publications" class="back">← <?= htmlspecialchars(__('article.back_publications')) ?></a>
      <div class="flex flex-wrap items-center gap-1 mb-1">
        <span class="badge badge-primary"><?= htmlspecialchars(__('common.article')) ?></span>
        <h1 class="font-serif text-lg md:text-xl font-bold leading-tight text-balance max-w-4xl mb-0"><?= htmlspecialchars($article['titre']) ?></h1>
      </div>
    </div>
  </div>
  <div class="container section">
    <div class="flex flex-col lg:flex-row gap-3">
      <div class="flex-1 article-content">
        <?php if ($contenu): ?>
        <section class="mb-3 prose article-prose-compact">
          <h2 class="font-serif text-base font-bold mb-1"><?= $canAccessFullArticle ? htmlspecialchars(__('article.content')) : htmlspecialchars(__('article.resume')); ?></h2>
          <?php if ($canAccessFullArticle): ?>
          <div class="article-content text-muted leading-relaxed"><?= $contenu ?></div>
          <?php else: ?>
          <div class="article-content text-muted leading-relaxed"><?= $contenu ?></div>
          <div class="card p-3 mt-3" style="background: var(--primary); color: var(--primary-foreground);">
            <p class="text-sm mb-2"><?= htmlspecialchars(__('article.become_author_cta')) ?></p>
            <?php
            $loginUrl = $base . '/login?redirect=' . rawurlencode($base . '/article/' . (int)$article['id']);
            $subscribeUrl = $base . '/author/s-abonner';
            $ctaUrl = (class_exists('Service\AuthService') && \Service\AuthService::isLoggedIn()) ? $subscribeUrl : $loginUrl;
            ?>
            <a href="<?= htmlspecialchars($ctaUrl) ?>" class="btn btn-accent btn-sm"><?= htmlspecialchars(__('article.become_author_btn')) ?></a>
          </div>
          <?php endif; ?>
        </section>
        <?php endif; ?>
      </div>
      <aside class="lg:w-64 flex-shrink-0">
        <div class="sticky-top flex flex-col gap-3">
          <div class="card p-3">
            <h3 class="font-serif font-bold mb-1 text-xs"><?= htmlspecialchars(__('article.info')) ?></h3>
            <ul class="flex flex-col gap-1 text-xs" style="list-style: none; padding: 0;">
              <?php if ($auteur): ?>
            <li class="flex gap-2">
                <span class="text-primary"><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#user"/></svg></span>
                <div>
                  <p class="text-muted text-xs mb-0"><?= htmlspecialchars(__('article.author')) ?></p>
                  <p class="font-medium mb-0 text-xs"><?= htmlspecialchars($auteur) ?></p>
                </div>
              </li>
              <?php endif; ?>
              <?php if ($date): ?>
              <li class="flex gap-2">
                <span class="text-primary"><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#calendar"/></svg></span>
                <div>
                  <p class="text-muted text-xs mb-0"><?= htmlspecialchars(__('article.pub_date')) ?></p>
                  <p class="font-medium mb-0 text-xs"><?= $date ?></p>
                </div>
              </li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </aside>
    </div>
    <?php if ($canAccessFullArticle && !empty($article['fichier_path'])): ?>
    <div class="article-pdf-blocks-row grid-2 mt-3">
      <div class="card p-3 text-center">
        <p class="text-xs text-muted mb-1"><?= htmlspecialchars(__('article.read_pdf_intro')) ?></p>
        <a href="<?= $base ?>/download/article/<?= (int) ($article['id'] ?? 0) ?>?inline=1" class="btn btn-accent btn-sm" target="_blank" rel="noopener"><?= htmlspecialchars(__('article.read_pdf')) ?></a>
      </div>
      <div class="card p-3 text-center" style="background: var(--primary); color: var(--primary-foreground);">
        <h3 class="font-serif font-bold mb-0 text-xs"><?= htmlspecialchars(__('article.download')) ?></h3>
        <p class="text-xs mb-1" style="color: rgba(255,255,255,0.7);"><?= htmlspecialchars(__('article.download_pdf_intro')) ?></p>
        <a href="<?= $base ?>/download/article/<?= (int) ($article['id'] ?? 0) ?>" class="btn btn-accent btn-sm" download><?= htmlspecialchars(__('article.download_pdf')) ?></a>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>
