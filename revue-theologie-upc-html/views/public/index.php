<?php
$base = $base ?? '';
$articles = $articles ?? [];
$numeros = $numeros ?? [];
$extrait = function ($html, $len = 180) {
  $t = strip_tags($html);
  return mb_strlen($t) > $len ? mb_substr($t, 0, $len) . '...' : $t;
};
$firstArticle = $articles[0] ?? null;
$firstNumero = $numeros[0] ?? null;
?>
<!-- Hero type template : 2 colonnes, image en forme de livre -->
<section class="hero-template">
  <div class="container hero-template-inner">
    <div class="hero-featured">
      <div class="hero-book-wrap" aria-hidden="true">
        <div class="hero-book-spine"></div>
        <div class="hero-featured-card">
          <div class="hero-featured-img">
            <img src="<?= $base ?>/images/revue-cover.jpg" alt="Revue de Théologie">
            <div class="hero-featured-overlay">
              <h2 class="font-serif"><?= htmlspecialchars(__('home.featured')) ?></h2>
              <p><?= $firstNumero ? htmlspecialchars($firstNumero['numero'] . ' - ' . ($firstNumero['date_publication'] ?? '')) : __('home.volume_num_default') ?></p>
            </div>
          </div>
          <div class="hero-featured-ribbon">
            <span><?= htmlspecialchars(__('home.see_latest_issue')) ?></span>
            <a href="<?= $firstNumero ? $base . '/numero/' . (int)$firstNumero['id'] : $base . '/archives' ?>" class="ribbon-link">→</a>
          </div>
        </div>
      </div>
    </div>
    <div class="hero-welcome">
      <p class="hero-welcome-label"><?= htmlspecialchars(__('home.welcome')) ?></p>
      <h1 class="font-serif hero-welcome-title"><?= htmlspecialchars(__('home.welcome_title')) ?></h1>
      <div class="divider hero-welcome-line"></div>
      <p class="hero-welcome-text"><?= htmlspecialchars(__('home.welcome_text')) ?></p>
      <a href="<?= $base ?>/publications" class="link-read-more"><?= htmlspecialchars(__('common.read_more')) ?></a>
    </div>
  </div>
</section>

<!-- Contenu principal + sidebar -->
<div class="main-wrap">
  <div class="container main-wrap-inner">
    <div class="content-main">
      <!-- Choix de la rédaction -->
      <section class="section-block section-editors-pick">
        <div class="section-block-head flex justify-between items-center flex-wrap gap-2">
          <h2 class="font-serif section-block-title"><?= htmlspecialchars(__('home.editors_pick')) ?></h2>
          <div class="section-block-nav flex items-center gap-2">
            <button type="button" class="btn-carousel btn-prev" aria-label="<?= htmlspecialchars(__('common.prev')) ?>"><span>‹</span></button>
            <a href="<?= $base ?>/publications" class="link-view-all"><?= htmlspecialchars(__('common.view_all')) ?></a>
            <button type="button" class="btn-carousel btn-next" aria-label="<?= htmlspecialchars(__('common.next')) ?>"><span>›</span></button>
          </div>
        </div>
        <div class="search-bar-inline">
          <form action="<?= $base ?>/search" method="get" class="flex gap-2 items-center w-full" role="search">
            <label for="home-search" class="sr-only"><?= htmlspecialchars(__('home.search_label')) ?></label>
            <input type="search" id="home-search" name="q" placeholder="<?= htmlspecialchars(__('home.search_placeholder')) ?>" class="search-input flex-1">
            <button type="submit" class="btn btn-primary btn-search-submit" aria-label="<?= htmlspecialchars(__('nav.search')) ?>"><svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#search"/></svg></button>
          </form>
        </div>
        <?php if ($firstArticle): ?>
        <div class="featured-article-card">
          <div class="featured-article-img">
            <img src="<?= $base ?>/images/revue-cover.jpg" alt="">
          </div>
          <div class="featured-article-body">
            <p class="featured-article-author"><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#user"/></svg> <?= htmlspecialchars(trim(($firstArticle['auteur_prenom'] ?? '') . ' ' . ($firstArticle['auteur_nom'] ?? ''))) ?></p>
            <h3 class="font-serif featured-article-title"><a href="<?= $base ?>/article/<?= (int)$firstArticle['id'] ?>"><?= htmlspecialchars($firstArticle['titre']) ?></a></h3>
            <p class="featured-article-excerpt"><?= htmlspecialchars($extrait($firstArticle['contenu'] ?? '', 220)) ?></p>
            <a href="<?= $base ?>/article/<?= (int)$firstArticle['id'] ?>" class="btn btn-outline btn-view-article"><?= htmlspecialchars(__('common.view_article')) ?></a>
          </div>
        </div>
        <?php else: ?>
        <div class="featured-article-card">
          <div class="featured-article-img"><img src="<?= $base ?>/images/revue-cover.jpg" alt=""></div>
          <div class="featured-article-body">
            <p class="featured-article-author"><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#user"/></svg> Revue de Théologie</p>
            <h3 class="font-serif featured-article-title"><a href="<?= $base ?>/publications"><?= htmlspecialchars(__('home.discover_publications')) ?></a></h3>
            <p class="featured-article-excerpt"><?= htmlspecialchars(__('home.consult_articles')) ?></p>
            <a href="<?= $base ?>/publications" class="btn btn-outline btn-view-article"><?= htmlspecialchars(__('common.view_articles')) ?></a>
          </div>
        </div>
        <?php endif; ?>
      </section>

      <!-- Numéros précédents -->
      <section class="section-block section-previous-issues">
        <div class="section-block-head flex justify-between items-center flex-wrap gap-2">
          <h2 class="font-serif section-block-title"><?= htmlspecialchars(__('home.previous_issues')) ?></h2>
          <div class="section-block-nav flex items-center gap-2">
            <button type="button" class="btn-carousel btn-prev" aria-label="<?= htmlspecialchars(__('common.prev')) ?>"><span>‹</span></button>
            <a href="<?= $base ?>/archives" class="link-view-all"><?= htmlspecialchars(__('common.view_all')) ?></a>
            <button type="button" class="btn-carousel btn-next" aria-label="<?= htmlspecialchars(__('common.next')) ?>"><span>›</span></button>
          </div>
        </div>
        <div class="previous-issues-layout">
          <div class="issues-years">
            <p class="issues-years-title"><?= htmlspecialchars(__('common.by_year')) ?></p>
            <ul>
              <li><a href="<?= $base ?>/archives?year=2025">2025</a></li>
              <li><a href="<?= $base ?>/archives?year=2024">2024</a></li>
              <li><a href="<?= $base ?>/archives?year=2023">2023</a></li>
              <li><a href="<?= $base ?>/archives?year=2022">2022</a></li>
              <li><a href="<?= $base ?>/archives?year=2021">2021</a></li>
              <li><a href="<?= $base ?>/archives"><?= htmlspecialchars(__('common.all_archives')) ?></a></li>
            </ul>
          </div>
          <div class="issues-carousel-wrap">
            <div class="issues-carousel" id="issues-carousel">
              <?php foreach (array_slice($articles, 0, 5) as $a):
                $auteur = trim(($a['auteur_prenom'] ?? '') . ' ' . ($a['auteur_nom'] ?? ''));
              ?>
              <article class="issue-card">
                <div class="issue-card-img"><img src="<?= $base ?>/images/revue-cover.jpg" alt=""></div>
                <p class="issue-card-author"><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#user"/></svg> <?= htmlspecialchars($auteur ?: 'Revue') ?></p>
                <h4 class="font-serif issue-card-title"><a href="<?= $base ?>/article/<?= (int)$a['id'] ?>"><?= htmlspecialchars($a['titre']) ?></a></h4>
              </article>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </section>

      <!-- À paraître -->
      <section class="section-block section-coming-in">
        <div class="section-block-head flex justify-between items-center flex-wrap gap-2">
          <h2 class="font-serif section-block-title"><?= htmlspecialchars(__('home.coming_2026')) ?></h2>
          <div class="section-block-nav flex items-center gap-2">
            <button type="button" class="btn-carousel btn-prev" aria-label="<?= htmlspecialchars(__('common.prev')) ?>"><span>‹</span></button>
            <a href="<?= $base ?>/actualites" class="link-view-all"><?= htmlspecialchars(__('common.view_all')) ?></a>
            <button type="button" class="btn-carousel btn-next" aria-label="<?= htmlspecialchars(__('common.next')) ?>"><span>›</span></button>
          </div>
        </div>
        <div class="coming-blocks">
          <a href="<?= $base ?>/soumettre" class="coming-block coming-block-1">
            <span class="coming-block-label"><?= htmlspecialchars(__('home.call_for_papers')) ?></span>
            <span class="coming-block-title"><?= htmlspecialchars(__('home.vol29_num1')) ?></span>
          </a>
          <a href="<?= $base ?>/actualites" class="coming-block coming-block-2">
            <span class="coming-block-label"><?= htmlspecialchars(__('nav.actualites')) ?></span>
            <span class="coming-block-title"><?= htmlspecialchars(__('home.news_colloque')) ?></span>
          </a>
          <a href="<?= $base ?>/archives" class="coming-block coming-block-3">
            <span class="coming-block-label"><?= htmlspecialchars(__('nav.archives')) ?></span>
            <span class="coming-block-title"><?= htmlspecialchars(__('home.issues_available')) ?></span>
          </a>
        </div>
      </section>

      <!-- Grille des couvertures -->
      <section class="section-block section-covers">
        <h2 class="font-serif section-block-title"><?= htmlspecialchars(__('home.review_issues')) ?></h2>
        <div class="covers-grid">
          <?php foreach (array_slice($numeros, 0, 4) as $nr): ?>
          <a href="<?= $base ?>/numero/<?= (int)$nr['id'] ?>" class="cover-card">
            <div class="cover-card-img"><img src="<?= $base ?>/images/revue-cover.jpg" alt="<?= htmlspecialchars($nr['numero'] ?? '') ?>"></div>
            <p class="cover-card-title"><?= htmlspecialchars($nr['titre'] ?? __('home.issue_num') . ' ' . ($nr['numero'] ?? '')) ?></p>
          </a>
          <?php endforeach; ?>
          <a href="<?= $base ?>/archives" class="cover-card">
            <div class="cover-card-img cover-placeholder"><span>+</span></div>
            <p class="cover-card-title"><?= htmlspecialchars(__('common.all_archives')) ?></p>
          </a>
        </div>
      </section>

      <!-- Actualités & Annonces -->
      <section class="section-block section-news">
        <div class="section-block-head flex justify-between items-center flex-wrap gap-2">
          <h2 class="font-serif section-block-title"><?= htmlspecialchars(__('home.news_announcements')) ?></h2>
          <div class="section-block-nav flex items-center gap-2">
            <button type="button" class="btn-carousel btn-prev" aria-label="<?= htmlspecialchars(__('common.prev')) ?>"><span>‹</span></button>
            <a href="<?= $base ?>/actualites" class="link-view-all"><?= htmlspecialchars(__('common.view_all')) ?></a>
            <button type="button" class="btn-carousel btn-next" aria-label="<?= htmlspecialchars(__('common.next')) ?>"><span>›</span></button>
          </div>
        </div>
        <div class="news-cards">
          <article class="news-card">
            <div class="news-card-img"><img src="<?= $base ?>/images/revue-cover.jpg" alt=""></div>
            <p class="news-card-date"><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#calendar"/></svg> 15 janvier 2026</p>
            <h3 class="font-serif news-card-title"><a href="<?= $base ?>/publications"><?= htmlspecialchars(__('home.pub_last_issues')) ?></a></h3>
            <p class="news-card-excerpt"><?= htmlspecialchars(__('home.consult_issues_online')) ?></p>
            <a href="<?= $base ?>/archives" class="link-read-more"><?= htmlspecialchars(__('common.read_more')) ?></a>
          </article>
          <article class="news-card">
            <div class="news-card-img"><img src="<?= $base ?>/images/revue-cover.jpg" alt=""></div>
            <p class="news-card-date"><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#calendar"/></svg> 10 décembre 2025</p>
            <h3 class="font-serif news-card-title"><a href="<?= $base ?>/actualites"><?= htmlspecialchars(__('home.call_vol29')) ?></a></h3>
            <p class="news-card-excerpt"><?= htmlspecialchars(__('home.editorial_call')) ?></p>
            <a href="<?= $base ?>/soumettre" class="link-read-more"><?= htmlspecialchars(__('common.read_more')) ?></a>
          </article>
          <article class="news-card">
            <div class="news-card-img"><img src="<?= $base ?>/images/revue-cover.jpg" alt=""></div>
            <p class="news-card-date"><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#calendar"/></svg> 1er novembre 2025</p>
            <h3 class="font-serif news-card-title"><a href="<?= $base ?>/actualites"><?= htmlspecialchars(__('home.colloque_africa')) ?></a></h3>
            <p class="news-card-excerpt"><?= htmlspecialchars(__('home.faculty_colloque')) ?></p>
            <a href="<?= $base ?>/actualites" class="link-read-more"><?= htmlspecialchars(__('common.read_more')) ?></a>
          </article>
        </div>
      </section>
    </div>

    <!-- Sidebar droite -->
    <aside class="sidebar-right">
      <div class="widget widget-stats">
        <h3 class="widget-title"><?= htmlspecialchars(__('home.stats_title')) ?></h3>
        <div class="widget-stats-row">
          <span class="widget-stats-value">200+</span>
          <span class="widget-stats-label"><?= htmlspecialchars(__('home.articles_published')) ?></span>
        </div>
        <div class="widget-stats-row">
          <span class="widget-stats-value">28</span>
          <span class="widget-stats-label"><?= htmlspecialchars(__('home.volumes')) ?></span>
        </div>
        <div class="widget-stats-row">
          <span class="widget-stats-value">65+</span>
          <span class="widget-stats-label"><?= htmlspecialchars(__('home.years_publication')) ?></span>
        </div>
      </div>

      <div class="widget widget-notice">
        <h3 class="widget-title"><?= htmlspecialchars(__('home.announcements')) ?></h3>
        <ul class="notice-list">
          <li><a href="<?= $base ?>/archives"><?= htmlspecialchars(__('home.notice_online')) ?></a></li>
          <li><a href="<?= $base ?>/soumettre"><?= htmlspecialchars(__('home.notice_call_vol29')) ?></a></li>
          <li><a href="<?= $base ?>/actualites"><?= htmlspecialchars(__('home.notice_colloque')) ?></a></li>
          <li><a href="<?= $base ?>/instructions-auteurs"><?= htmlspecialchars(__('nav.instructions')) ?></a></li>
        </ul>
      </div>

      <div class="widget widget-cta">
        <div class="widget-cta-icon"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#book"/></svg></div>
        <h3 class="widget-title"><?= htmlspecialchars(__('home.participate')) ?></h3>
        <p class="widget-cta-text"><?= htmlspecialchars(__('home.participate_text')) ?></p>
        <a href="<?= $base ?>/soumettre" class="btn btn-primary btn-sm"><?= htmlspecialchars(__('nav.submit')) ?></a>
      </div>

      <div class="widget widget-question">
        <h3 class="widget-title widget-title-bar"><?= htmlspecialchars(__('home.question_week')) ?></h3>
        <p class="widget-question-text"><?= htmlspecialchars(__('home.question_week_text')) ?></p>
        <form class="widget-poll" id="widget-poll">
          <label class="poll-option"><input type="radio" name="question_week" value="1"> <?= htmlspecialchars(__('home.poll_biblical')) ?></label>
          <label class="poll-option"><input type="radio" name="question_week" value="2"> <?= htmlspecialchars(__('home.poll_systematic')) ?></label>
          <label class="poll-option"><input type="radio" name="question_week" value="3"> <?= htmlspecialchars(__('home.poll_ethics')) ?></label>
          <label class="poll-option"><input type="radio" name="question_week" value="4"> <?= htmlspecialchars(__('home.poll_history')) ?></label>
          <button type="submit" class="btn btn-outline btn-sm mt-4"><?= htmlspecialchars(__('common.send')) ?></button>
        </form>
      </div>

      <div class="widget widget-newsletter">
        <div class="widget-newsletter-bg"></div>
        <h3 class="widget-title widget-title-light"><?= htmlspecialchars(__('home.newsletter_title')) ?></h3>
        <p class="widget-newsletter-text"><?= htmlspecialchars(__('home.newsletter_text')) ?></p>
        <form class="widget-newsletter-form" id="newsletter-form">
          <input type="email" placeholder="<?= htmlspecialchars(__('home.newsletter_placeholder')) ?>" required class="newsletter-input">
          <button type="submit" class="btn btn-accent newsletter-btn"><?= htmlspecialchars(__('home.newsletter_btn')) ?></button>
        </form>
      </div>
    </aside>
  </div>
</div>

<!-- Une plateforme complète pour la recherche théologale -->
<section class="section bg-background">
  <div class="container">
    <div class="section-title text-center mb-6">
      <h2 class="font-serif text-3xl md:text-4xl font-bold text-balance"><?= htmlspecialchars(__('home.platform_title')) ?></h2>
      <div class="divider center"></div>
      <p class="text-muted text-lg" style="max-width: 42rem; margin: 0 auto;"><?= htmlspecialchars(__('home.platform_intro')) ?></p>
    </div>
    <div class="grid-3">
      <div class="card">
        <div class="card-icon"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#upload"/></svg></div>
        <h3 class="font-serif text-lg font-bold mb-2"><?= htmlspecialchars(__('home.submission_online')) ?></h3>
        <p class="text-muted text-sm leading-relaxed"><?= htmlspecialchars(__('home.submission_online_text')) ?></p>
      </div>
      <div class="card">
        <div class="card-icon"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#user"/></svg></div>
        <h3 class="font-serif text-lg font-bold mb-2"><?= htmlspecialchars(__('home.peer_review')) ?></h3>
        <p class="text-muted text-sm leading-relaxed"><?= htmlspecialchars(__('home.peer_review_text')) ?></p>
      </div>
      <div class="card">
        <div class="card-icon"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#book"/></svg></div>
        <h3 class="font-serif text-lg font-bold mb-2"><?= htmlspecialchars(__('home.publication_archiving')) ?></h3>
        <p class="text-muted text-sm leading-relaxed"><?= htmlspecialchars(__('home.publication_archiving_text')) ?></p>
      </div>
      <div class="card">
        <div class="card-icon"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#file-text"/></svg></div>
        <h3 class="font-serif text-lg font-bold mb-2"><?= htmlspecialchars(__('home.pdf_access')) ?></h3>
        <p class="text-muted text-sm leading-relaxed"><?= htmlspecialchars(__('home.pdf_access_text')) ?></p>
      </div>
      <div class="card">
        <div class="card-icon"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#globe"/></svg></div>
        <h3 class="font-serif text-lg font-bold mb-2"><?= htmlspecialchars(__('home.visibility')) ?></h3>
        <p class="text-muted text-sm leading-relaxed"><?= htmlspecialchars(__('home.visibility_text')) ?></p>
      </div>
      <div class="card">
        <div class="card-icon"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#search"/></svg></div>
        <h3 class="font-serif text-lg font-bold mb-2"><?= htmlspecialchars(__('home.advanced_search')) ?></h3>
        <p class="text-muted text-sm leading-relaxed"><?= htmlspecialchars(__('home.advanced_search_text')) ?></p>
      </div>
    </div>
  </div>
</section>

<!-- Derniers articles publiés -->
<section class="section bg-secondary">
  <div class="container">
    <div class="flex flex-col gap-4 mb-8" style="flex-direction: column;">
      <div>
        <h2 class="font-serif text-3xl md:text-4xl font-bold text-balance mb-0"><?= htmlspecialchars(__('home.latest_articles')) ?></h2>
        <div class="divider" style="margin-top: 1rem; margin-bottom: 1rem;"></div>
        <p class="text-muted text-lg" style="max-width: 36rem;"><?= htmlspecialchars(__('home.latest_articles_intro')) ?></p>
      </div>
      <a href="<?= $base ?>/publications" class="btn btn-outline-primary" style="align-self: flex-start;"><?= htmlspecialchars(__('home.view_all_publications')) ?></a>
    </div>
    <div class="grid-3">
      <?php foreach (array_slice($articles, 0, 6) as $a):
        $auteur = trim(($a['auteur_prenom'] ?? '') . ' ' . ($a['auteur_nom'] ?? ''));
        $date = !empty($a['date_soumission']) ? date('j F Y', strtotime($a['date_soumission'])) : '';
        $resume = $extrait($a['contenu'] ?? '', 180);
      ?>
      <article class="article-card">
        <div class="bar"></div>
        <div style="padding: 1.5rem;">
          <span class="badge badge-primary mb-4"><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#tag"/></svg> <?= htmlspecialchars(__('common.article')) ?></span>
          <h3 class="font-serif text-lg font-bold leading-snug mb-3"><a href="<?= $base ?>/article/<?= (int)$a['id'] ?>"><?= htmlspecialchars($a['titre'] ?? '') ?></a></h3>
          <p class="text-muted text-sm leading-relaxed line-clamp-3 mb-4"><?= htmlspecialchars($resume) ?></p>
          <div class="meta">
            <?php if ($auteur): ?><span><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#user"/></svg> <?= htmlspecialchars($auteur) ?></span><?php endif; ?>
            <?php if ($date): ?><span style="margin-left: auto;"><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#clock"/></svg> <?= $date ?></span><?php endif; ?>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Domaines de publication -->
<section class="section bg-secondary">
  <div class="container">
    <div class="section-title">
      <h2 class="font-serif text-3xl md:text-4xl font-bold text-balance"><?= htmlspecialchars(__('home.publication_domains')) ?></h2>
      <div class="divider center"></div>
      <p class="text-muted text-lg"><?= htmlspecialchars(__('home.publication_domains_intro')) ?></p>
    </div>
    <div class="grid-5">
      <a href="<?= $base ?>/publications" class="card text-center">
        <div class="card-icon mx-auto" style="width: 3.5rem; height: 3.5rem; border-radius: 9999px;"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#book"/></svg></div>
        <h3 class="font-serif font-bold text-sm mb-2"><?= htmlspecialchars(__('home.domain_biblical')) ?></h3>
        <p class="text-muted text-xs leading-relaxed mb-3"><?= htmlspecialchars(__('home.domain_biblical_desc')) ?></p>
        <span class="text-xs font-medium text-primary"><?= htmlspecialchars(__('common.view_articles')) ?></span>
      </a>
      <a href="<?= $base ?>/publications" class="card text-center">
        <div class="card-icon mx-auto" style="width: 3.5rem; height: 3.5rem; border-radius: 9999px;"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#book"/></svg></div>
        <h3 class="font-serif font-bold text-sm mb-2"><?= htmlspecialchars(__('home.domain_systematic')) ?></h3>
        <p class="text-muted text-xs leading-relaxed mb-3"><?= htmlspecialchars(__('home.domain_systematic_desc')) ?></p>
        <span class="text-xs font-medium text-primary"><?= htmlspecialchars(__('common.view_articles')) ?></span>
      </a>
      <a href="<?= $base ?>/publications" class="card text-center">
        <div class="card-icon mx-auto" style="width: 3.5rem; height: 3.5rem; border-radius: 9999px;"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#clipboard-check"/></svg></div>
        <h3 class="font-serif font-bold text-sm mb-2"><?= htmlspecialchars(__('home.domain_ethics')) ?></h3>
        <p class="text-muted text-xs leading-relaxed mb-3"><?= htmlspecialchars(__('home.domain_ethics_desc')) ?></p>
        <span class="text-xs font-medium text-primary"><?= htmlspecialchars(__('common.view_articles')) ?></span>
      </a>
      <a href="<?= $base ?>/publications" class="card text-center">
        <div class="card-icon mx-auto" style="width: 3.5rem; height: 3.5rem; border-radius: 9999px;"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#book"/></svg></div>
        <h3 class="font-serif font-bold text-sm mb-2"><?= htmlspecialchars(__('home.domain_history')) ?></h3>
        <p class="text-muted text-xs leading-relaxed mb-3"><?= htmlspecialchars(__('home.domain_history_desc')) ?></p>
        <span class="text-xs font-medium text-primary"><?= htmlspecialchars(__('common.view_articles')) ?></span>
      </a>
      <a href="<?= $base ?>/publications" class="card text-center">
        <div class="card-icon mx-auto" style="width: 3.5rem; height: 3.5rem; border-radius: 9999px;"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#graduation-cap"/></svg></div>
        <h3 class="font-serif font-bold text-sm mb-2"><?= htmlspecialchars(__('home.domain_practical')) ?></h3>
        <p class="text-muted text-xs leading-relaxed mb-3"><?= htmlspecialchars(__('home.domain_practical_desc')) ?></p>
        <span class="text-xs font-medium text-primary"><?= htmlspecialchars(__('common.view_articles')) ?></span>
      </a>
    </div>
  </div>
</section>

<!-- Volumes & Archives -->
<section class="section bg-background">
  <div class="container">
    <div class="section-title">
      <h2 class="font-serif text-3xl md:text-4xl font-bold text-balance"><?= htmlspecialchars(__('home.volumes_archives')) ?></h2>
      <div class="divider center"></div>
      <p class="text-muted text-lg"><?= htmlspecialchars(__('home.volumes_archives_intro')) ?></p>
    </div>
    <div class="grid-3">
      <?php foreach (array_slice($numeros, 0, 6) as $nr): ?>
      <div class="volume-card">
        <div class="head">
          <h3><?= htmlspecialchars(__('common.issue')) ?> <?= htmlspecialchars($nr['numero'] ?? '') ?></h3>
          <p><svg class="icon-svg icon-16" style="vertical-align: middle;" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#calendar"/></svg> <?= htmlspecialchars($nr['date_publication'] ?? '—') ?></p>
        </div>
        <div class="body">
          <ul>
            <li><a href="<?= $base ?>/numero/<?= (int)$nr['id'] ?>"><svg class="icon-svg icon-16" style="vertical-align: middle;" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#file-text"/></svg> <?= htmlspecialchars($nr['titre'] ?? __('home.issue_num') . ' ' . ($nr['numero'] ?? '')) ?></a></li>
          </ul>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-6">
      <a href="<?= $base ?>/archives" class="btn btn-outline-primary"><?= htmlspecialchars(__('home.view_all_archives')) ?></a>
    </div>
  </div>
</section>

<!-- Comment soumettre un article ? -->
<section class="section bg-primary submission-cta">
  <div class="bg-pattern" aria-hidden="true"></div>
  <div class="container relative" style="z-index: 1;">
    <div class="section-title">
      <h2 class="font-serif text-3xl md:text-4xl font-bold text-balance" style="color: var(--primary-foreground);"><?= htmlspecialchars(__('home.how_submit')) ?></h2>
      <div class="divider center" style="background: var(--upc-gold);"></div>
      <p style="color: rgba(255,255,255,0.8);"><?= htmlspecialchars(__('home.how_submit_intro')) ?></p>
    </div>
    <div class="grid-3 mb-8">
      <div class="step">
        <div class="step-num"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#upload"/></svg></div>
        <span class="num">01</span>
        <h3><?= htmlspecialchars(__('home.step_submission')) ?></h3>
        <p><?= htmlspecialchars(__('home.step_submission_text')) ?></p>
      </div>
      <div class="step">
        <div class="step-num"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#clipboard-check"/></svg></div>
        <span class="num">02</span>
        <h3><?= htmlspecialchars(__('home.step_evaluation')) ?></h3>
        <p><?= htmlspecialchars(__('home.step_evaluation_text')) ?></p>
      </div>
      <div class="step">
        <div class="step-num"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#check"/></svg></div>
        <span class="num">03</span>
        <h3><?= htmlspecialchars(__('home.step_publication')) ?></h3>
        <p><?= htmlspecialchars(__('home.step_publication_text')) ?></p>
      </div>
    </div>
    <div class="text-center">
      <a href="<?= $base ?>/soumettre" class="btn btn-lg btn-accent"><?= htmlspecialchars(__('home.start_submission')) ?></a>
    </div>
  </div>
</section>
