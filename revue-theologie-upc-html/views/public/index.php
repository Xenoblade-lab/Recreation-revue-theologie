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
<!-- Hero type template : 2 colonnes (index.html) -->
<section class="hero-template">
  <div class="container hero-template-inner">
    <div class="hero-featured">
      <div class="hero-featured-card">
        <div class="hero-featured-img">
          <img src="<?= $base ?>/images/revue-cover.jpg" alt="Revue de Théologie">
          <div class="hero-featured-overlay">
            <h2 class="font-serif">Article à la une</h2>
            <p><?= $firstNumero ? htmlspecialchars($firstNumero['numero'] . ' - ' . ($firstNumero['date_publication'] ?? '')) : 'Volume 28 - Numéro 1, 2025' ?></p>
          </div>
        </div>
        <div class="hero-featured-ribbon">
          <span>Voir le dernier numéro</span>
          <a href="<?= $firstNumero ? $base . '/numero/' . (int)$firstNumero['id'] : $base . '/archives' ?>" class="ribbon-link">→</a>
        </div>
      </div>
    </div>
    <div class="hero-welcome">
      <p class="hero-welcome-label">Bienvenue</p>
      <h1 class="font-serif hero-welcome-title">Nous accueillons les derniers articles de recherche en théologie</h1>
      <div class="divider hero-welcome-line"></div>
      <p class="hero-welcome-text">La Revue de la Faculté de Théologie de l'UPC publie des travaux scientifiques en théologie, études bibliques, éthique chrétienne et histoire de l'Église, avec un accent sur les contextes africains.</p>
      <a href="<?= $base ?>/publications" class="link-read-more">Lire la suite →</a>
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
          <h2 class="font-serif section-block-title">Choix de la rédaction</h2>
          <div class="section-block-nav flex items-center gap-2">
            <button type="button" class="btn-carousel btn-prev" aria-label="Précédent"><span>‹</span></button>
            <a href="<?= $base ?>/publications" class="link-view-all">Voir tout</a>
            <button type="button" class="btn-carousel btn-next" aria-label="Suivant"><span>›</span></button>
          </div>
        </div>
        <div class="search-bar-inline">
          <input type="search" placeholder="Rechercher un article..." class="search-input" id="home-search">
          <button type="button" class="btn btn-primary btn-search-submit" aria-label="Rechercher"><svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#search"/></svg></button>
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
            <a href="<?= $base ?>/article/<?= (int)$firstArticle['id'] ?>" class="btn btn-outline btn-view-article">Voir l'article complet</a>
          </div>
        </div>
        <?php else: ?>
        <div class="featured-article-card">
          <div class="featured-article-img"><img src="<?= $base ?>/images/revue-cover.jpg" alt=""></div>
          <div class="featured-article-body">
            <p class="featured-article-author"><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#user"/></svg> Revue de Théologie</p>
            <h3 class="font-serif featured-article-title"><a href="<?= $base ?>/publications">Découvrez les publications</a></h3>
            <p class="featured-article-excerpt">Consultez les articles de la Revue de la Faculté de Théologie.</p>
            <a href="<?= $base ?>/publications" class="btn btn-outline btn-view-article">Voir les articles</a>
          </div>
        </div>
        <?php endif; ?>
      </section>

      <!-- Numéros précédents -->
      <section class="section-block section-previous-issues">
        <div class="section-block-head flex justify-between items-center flex-wrap gap-2">
          <h2 class="font-serif section-block-title">Numéros précédents</h2>
          <div class="section-block-nav flex items-center gap-2">
            <button type="button" class="btn-carousel btn-prev" aria-label="Précédent"><span>‹</span></button>
            <a href="<?= $base ?>/archives" class="link-view-all">Voir tout</a>
            <button type="button" class="btn-carousel btn-next" aria-label="Suivant"><span>›</span></button>
          </div>
        </div>
        <div class="previous-issues-layout">
          <div class="issues-years">
            <p class="issues-years-title">Par année :</p>
            <ul>
              <li><a href="<?= $base ?>/archives?year=2025">2025</a></li>
              <li><a href="<?= $base ?>/archives?year=2024">2024</a></li>
              <li><a href="<?= $base ?>/archives?year=2023">2023</a></li>
              <li><a href="<?= $base ?>/archives?year=2022">2022</a></li>
              <li><a href="<?= $base ?>/archives?year=2021">2021</a></li>
              <li><a href="<?= $base ?>/archives">Toutes les archives</a></li>
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
          <h2 class="font-serif section-block-title">À paraître 2026</h2>
          <div class="section-block-nav flex items-center gap-2">
            <button type="button" class="btn-carousel btn-prev" aria-label="Précédent"><span>‹</span></button>
            <a href="<?= $base ?>/actualites" class="link-view-all">Voir tout</a>
            <button type="button" class="btn-carousel btn-next" aria-label="Suivant"><span>›</span></button>
          </div>
        </div>
        <div class="coming-blocks">
          <a href="<?= $base ?>/soumettre" class="coming-block coming-block-1">
            <span class="coming-block-label">Appel à articles</span>
            <span class="coming-block-title">Volume 29 - Numéro 1</span>
          </a>
          <a href="<?= $base ?>/actualites" class="coming-block coming-block-2">
            <span class="coming-block-label">Actualités</span>
            <span class="coming-block-title">Colloque Théologie & Société</span>
          </a>
          <a href="<?= $base ?>/archives" class="coming-block coming-block-3">
            <span class="coming-block-label">Archives</span>
            <span class="coming-block-title">Numéros disponibles</span>
          </a>
        </div>
      </section>

      <!-- Grille des couvertures -->
      <section class="section-block section-covers">
        <h2 class="font-serif section-block-title">Numéros de la revue</h2>
        <div class="covers-grid">
          <?php foreach (array_slice($numeros, 0, 4) as $nr): ?>
          <a href="<?= $base ?>/numero/<?= (int)$nr['id'] ?>" class="cover-card">
            <div class="cover-card-img"><img src="<?= $base ?>/images/revue-cover.jpg" alt="<?= htmlspecialchars($nr['numero'] ?? '') ?>"></div>
            <p class="cover-card-title"><?= htmlspecialchars($nr['titre'] ?? 'Numéro ' . ($nr['numero'] ?? '')) ?></p>
          </a>
          <?php endforeach; ?>
          <a href="<?= $base ?>/archives" class="cover-card">
            <div class="cover-card-img cover-placeholder"><span>+</span></div>
            <p class="cover-card-title">Toutes les archives</p>
          </a>
        </div>
      </section>

      <!-- Actualités & Annonces -->
      <section class="section-block section-news">
        <div class="section-block-head flex justify-between items-center flex-wrap gap-2">
          <h2 class="font-serif section-block-title">Actualités & Annonces</h2>
          <div class="section-block-nav flex items-center gap-2">
            <button type="button" class="btn-carousel btn-prev" aria-label="Précédent"><span>‹</span></button>
            <a href="<?= $base ?>/actualites" class="link-view-all">Voir tout</a>
            <button type="button" class="btn-carousel btn-next" aria-label="Suivant"><span>›</span></button>
          </div>
        </div>
        <div class="news-cards">
          <article class="news-card">
            <div class="news-card-img"><img src="<?= $base ?>/images/revue-cover.jpg" alt=""></div>
            <p class="news-card-date"><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#calendar"/></svg> 15 janvier 2026</p>
            <h3 class="font-serif news-card-title"><a href="<?= $base ?>/publications">Publication des derniers numéros</a></h3>
            <p class="news-card-excerpt">Consultez les numéros de la revue en ligne.</p>
            <a href="<?= $base ?>/archives" class="link-read-more">Lire la suite →</a>
          </article>
          <article class="news-card">
            <div class="news-card-img"><img src="<?= $base ?>/images/revue-cover.jpg" alt=""></div>
            <p class="news-card-date"><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#calendar"/></svg> 10 décembre 2025</p>
            <h3 class="font-serif news-card-title"><a href="<?= $base ?>/actualites">Appel à contributions pour le Volume 29</a></h3>
            <p class="news-card-excerpt">La rédaction lance l'appel à articles pour le prochain volume.</p>
            <a href="<?= $base ?>/soumettre" class="link-read-more">Lire la suite →</a>
          </article>
          <article class="news-card">
            <div class="news-card-img"><img src="<?= $base ?>/images/revue-cover.jpg" alt=""></div>
            <p class="news-card-date"><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#calendar"/></svg> 1er novembre 2025</p>
            <h3 class="font-serif news-card-title"><a href="<?= $base ?>/actualites">Colloque Théologie et société en Afrique</a></h3>
            <p class="news-card-excerpt">La Faculté de Théologie organise un colloque international.</p>
            <a href="<?= $base ?>/actualites" class="link-read-more">Lire la suite →</a>
          </article>
        </div>
      </section>
    </div>

    <!-- Sidebar droite -->
    <aside class="sidebar-right">
      <div class="widget widget-stats">
        <h3 class="widget-title">La revue en chiffres</h3>
        <div class="widget-stats-row">
          <span class="widget-stats-value">200+</span>
          <span class="widget-stats-label">Articles publiés</span>
        </div>
        <div class="widget-stats-row">
          <span class="widget-stats-value">28</span>
          <span class="widget-stats-label">Volumes</span>
        </div>
        <div class="widget-stats-row">
          <span class="widget-stats-value">65+</span>
          <span class="widget-stats-label">Années de publication</span>
        </div>
      </div>

      <div class="widget widget-notice">
        <h3 class="widget-title">Annonces</h3>
        <ul class="notice-list">
          <li><a href="<?= $base ?>/archives">Publications et numéros en ligne.</a></li>
          <li><a href="<?= $base ?>/soumettre">Appel à articles pour le Volume 29.</a></li>
          <li><a href="<?= $base ?>/actualites">Colloque Théologie et société.</a></li>
          <li><a href="<?= $base ?>/instructions-auteurs">Instructions aux auteurs.</a></li>
        </ul>
      </div>

      <div class="widget widget-cta">
        <div class="widget-cta-icon"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#book"/></svg></div>
        <h3 class="widget-title">Participez à la revue</h3>
        <p class="widget-cta-text">Soumettez vos articles et contribuez au rayonnement de la recherche théologale en Afrique.</p>
        <a href="<?= $base ?>/soumettre" class="btn btn-primary btn-sm">Soumettre un article</a>
      </div>

      <div class="widget widget-question">
        <h3 class="widget-title widget-title-bar">Question de la semaine</h3>
        <p class="widget-question-text">Quel thème souhaiteriez-vous voir davantage traité dans la revue ?</p>
        <form class="widget-poll" id="widget-poll">
          <label class="poll-option"><input type="radio" name="question_week" value="1"> Études bibliques</label>
          <label class="poll-option"><input type="radio" name="question_week" value="2"> Théologie systématique</label>
          <label class="poll-option"><input type="radio" name="question_week" value="3"> Éthique chrétienne</label>
          <label class="poll-option"><input type="radio" name="question_week" value="4"> Histoire de l'Église</label>
          <button type="submit" class="btn btn-outline btn-sm mt-4">Envoyer</button>
        </form>
      </div>

      <div class="widget widget-newsletter">
        <div class="widget-newsletter-bg"></div>
        <h3 class="widget-title widget-title-light">Alertes & actualités</h3>
        <p class="widget-newsletter-text">Recevez les dernières parutions et annonces par email.</p>
        <form class="widget-newsletter-form" id="newsletter-form">
          <input type="email" placeholder="Votre adresse email" required class="newsletter-input">
          <button type="submit" class="btn btn-accent newsletter-btn">S'inscrire</button>
        </form>
      </div>
    </aside>
  </div>
</div>

<!-- Une plateforme complète pour la recherche théologale -->
<section class="section bg-background">
  <div class="container">
    <div class="section-title text-center mb-6">
      <h2 class="font-serif text-3xl md:text-4xl font-bold text-balance">Une plateforme complète pour la recherche théologale</h2>
      <div class="divider center"></div>
      <p class="text-muted text-lg" style="max-width: 42rem; margin: 0 auto;">De la soumission à la publication, notre plateforme accompagne chaque étape du processus éditorial scientifique.</p>
    </div>
    <div class="grid-3">
      <div class="card">
        <div class="card-icon"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#upload"/></svg></div>
        <h3 class="font-serif text-lg font-bold mb-2">Soumission en ligne</h3>
        <p class="text-muted text-sm leading-relaxed">Soumettez vos articles directement via notre plateforme. Formats acceptés : PDF, Word, LaTeX.</p>
      </div>
      <div class="card">
        <div class="card-icon"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#user"/></svg></div>
        <h3 class="font-serif text-lg font-bold mb-2">Évaluation par les pairs</h3>
        <p class="text-muted text-sm leading-relaxed">Processus d'évaluation rigoureux en double aveugle par des experts en théologie.</p>
      </div>
      <div class="card">
        <div class="card-icon"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#book"/></svg></div>
        <h3 class="font-serif text-lg font-bold mb-2">Publication & Archivage</h3>
        <p class="text-muted text-sm leading-relaxed">Organisation en volumes et numéros avec attribution de DOI pour chaque article.</p>
      </div>
      <div class="card">
        <div class="card-icon"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#file-text"/></svg></div>
        <h3 class="font-serif text-lg font-bold mb-2">Accès aux PDFs</h3>
        <p class="text-muted text-sm leading-relaxed">Téléchargez les articles individuels ou les numéros complets au format PDF.</p>
      </div>
      <div class="card">
        <div class="card-icon"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#globe"/></svg></div>
        <h3 class="font-serif text-lg font-bold mb-2">Visibilité internationale</h3>
        <p class="text-muted text-sm leading-relaxed">Indexation académique pour une diffusion maximale de la recherche théologale.</p>
      </div>
      <div class="card">
        <div class="card-icon"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#search"/></svg></div>
        <h3 class="font-serif text-lg font-bold mb-2">Recherche avancée</h3>
        <p class="text-muted text-sm leading-relaxed">Retrouvez facilement les articles par auteur, mot-clé, catégorie ou année.</p>
      </div>
    </div>
  </div>
</section>

<!-- Derniers articles publiés -->
<section class="section bg-secondary">
  <div class="container">
    <div class="flex flex-col gap-4 mb-8" style="flex-direction: column;">
      <div>
        <h2 class="font-serif text-3xl md:text-4xl font-bold text-balance mb-0">Derniers articles publiés</h2>
        <div class="divider" style="margin-top: 1rem; margin-bottom: 1rem;"></div>
        <p class="text-muted text-lg" style="max-width: 36rem;">Découvrez les publications les plus récentes de notre revue.</p>
      </div>
      <a href="<?= $base ?>/publications" class="btn btn-outline-primary" style="align-self: flex-start;">Voir toutes les publications →</a>
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
          <span class="badge badge-primary mb-4"><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#tag"/></svg> Article</span>
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
      <h2 class="font-serif text-3xl md:text-4xl font-bold text-balance">Domaines de publication</h2>
      <div class="divider center"></div>
      <p class="text-muted text-lg">La revue couvre l'ensemble des disciplines théologales, avec un accent particulier sur les perspectives africaines.</p>
    </div>
    <div class="grid-5">
      <a href="<?= $base ?>/publications" class="card text-center">
        <div class="card-icon mx-auto" style="width: 3.5rem; height: 3.5rem; border-radius: 9999px;"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#book"/></svg></div>
        <h3 class="font-serif font-bold text-sm mb-2">Études Bibliques</h3>
        <p class="text-muted text-xs leading-relaxed mb-3">Exégèse, herméneutique, analyse textuelle de l'Ancien et du Nouveau Testament.</p>
        <span class="text-xs font-medium text-primary">Voir les articles</span>
      </a>
      <a href="<?= $base ?>/publications" class="card text-center">
        <div class="card-icon mx-auto" style="width: 3.5rem; height: 3.5rem; border-radius: 9999px;"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#book"/></svg></div>
        <h3 class="font-serif font-bold text-sm mb-2">Théologie Systématique</h3>
        <p class="text-muted text-xs leading-relaxed mb-3">Dogmatique, christologie, pneumatologie et réflexion théologale structurée.</p>
        <span class="text-xs font-medium text-primary">Voir les articles</span>
      </a>
      <a href="<?= $base ?>/publications" class="card text-center">
        <div class="card-icon mx-auto" style="width: 3.5rem; height: 3.5rem; border-radius: 9999px;"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#clipboard-check"/></svg></div>
        <h3 class="font-serif font-bold text-sm mb-2">Éthique Chrétienne</h3>
        <p class="text-muted text-xs leading-relaxed mb-3">Bioéthique, justice sociale, éthique politique et morale chrétienne appliquée.</p>
        <span class="text-xs font-medium text-primary">Voir les articles</span>
      </a>
      <a href="<?= $base ?>/publications" class="card text-center">
        <div class="card-icon mx-auto" style="width: 3.5rem; height: 3.5rem; border-radius: 9999px;"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#book"/></svg></div>
        <h3 class="font-serif font-bold text-sm mb-2">Histoire de l'Église</h3>
        <p class="text-muted text-xs leading-relaxed mb-3">Histoire du christianisme en Afrique, mouvements de réveil, missions.</p>
        <span class="text-xs font-medium text-primary">Voir les articles</span>
      </a>
      <a href="<?= $base ?>/publications" class="card text-center">
        <div class="card-icon mx-auto" style="width: 3.5rem; height: 3.5rem; border-radius: 9999px;"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#graduation-cap"/></svg></div>
        <h3 class="font-serif font-bold text-sm mb-2">Théologie Pratique</h3>
        <p class="text-muted text-xs leading-relaxed mb-3">Pastorale, liturgie, missiologie, éducation chrétienne et formation.</p>
        <span class="text-xs font-medium text-primary">Voir les articles</span>
      </a>
    </div>
  </div>
</section>

<!-- Volumes & Archives -->
<section class="section bg-background">
  <div class="container">
    <div class="section-title">
      <h2 class="font-serif text-3xl md:text-4xl font-bold text-balance">Volumes & Archives</h2>
      <div class="divider center"></div>
      <p class="text-muted text-lg">Consultez et téléchargez l'ensemble des numéros de la revue, organisés par volume.</p>
    </div>
    <div class="grid-3">
      <?php foreach (array_slice($numeros, 0, 6) as $nr): ?>
      <div class="volume-card">
        <div class="head">
          <h3>Numéro <?= htmlspecialchars($nr['numero'] ?? '') ?></h3>
          <p><svg class="icon-svg icon-16" style="vertical-align: middle;" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#calendar"/></svg> <?= htmlspecialchars($nr['date_publication'] ?? '—') ?></p>
        </div>
        <div class="body">
          <ul>
            <li><a href="<?= $base ?>/numero/<?= (int)$nr['id'] ?>"><svg class="icon-svg icon-16" style="vertical-align: middle;" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#file-text"/></svg> <?= htmlspecialchars($nr['titre'] ?? 'Numéro ' . ($nr['numero'] ?? '')) ?></a></li>
          </ul>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-6">
      <a href="<?= $base ?>/archives" class="btn btn-outline-primary">Voir toutes les archives</a>
    </div>
  </div>
</section>

<!-- Comment soumettre un article ? -->
<section class="section bg-primary submission-cta">
  <div class="bg-pattern" aria-hidden="true"></div>
  <div class="container relative" style="z-index: 1;">
    <div class="section-title">
      <h2 class="font-serif text-3xl md:text-4xl font-bold text-balance" style="color: var(--primary-foreground);">Comment soumettre un article ?</h2>
      <div class="divider center" style="background: var(--upc-gold);"></div>
      <p style="color: rgba(255,255,255,0.8);">Un processus simple et transparent en trois étapes pour publier vos travaux de recherche.</p>
    </div>
    <div class="grid-3 mb-8">
      <div class="step">
        <div class="step-num"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#upload"/></svg></div>
        <span class="num">01</span>
        <h3>Soumission</h3>
        <p>Créez votre compte, abonnez-vous et soumettez votre article en ligne avec les métadonnées requises.</p>
      </div>
      <div class="step">
        <div class="step-num"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#clipboard-check"/></svg></div>
        <span class="num">02</span>
        <h3>Évaluation</h3>
        <p>Votre article est évalué en double aveugle par des experts du domaine. Vous recevez les commentaires.</p>
      </div>
      <div class="step">
        <div class="step-num"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#check"/></svg></div>
        <span class="num">03</span>
        <h3>Publication</h3>
        <p>Après acceptation, votre article est publié dans un numéro de la revue avec attribution de DOI.</p>
      </div>
    </div>
    <div class="text-center">
      <a href="<?= $base ?>/soumettre" class="btn btn-lg btn-accent">Commencer la soumission →</a>
    </div>
  </div>
</section>
