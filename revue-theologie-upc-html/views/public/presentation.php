<?php
$base = $base ?? '';
$revueInfo = $revueInfo ?? null;
$desc = $revueInfo['description'] ?? '';
$objectifs = $revueInfo['objectifs'] ?? null;
$domaines = $revueInfo['domaines_couverts'] ?? null;
?>
<div class="banner">
  <div class="container">
    <h1 class="font-serif text-3xl md:text-4xl font-bold text-balance">Présentation de la Revue</h1>
    <div class="divider"></div>
    <p>Découvrez la mission, les objectifs et les domaines couverts par la Revue de la Faculté de Théologie.</p>
  </div>
</div>
<section class="container presentation-intro">
  <div>
    <h2 class="font-serif text-2xl md:text-3xl font-bold mb-6 text-balance">Une tradition de recherche théologale depuis plus de 60 ans</h2>
    <?php if ($desc): ?>
    <p class="text-muted leading-relaxed"><?= nl2br(htmlspecialchars($desc)) ?></p>
    <?php else: ?>
    <p class="text-muted leading-relaxed">La Revue de la Faculté de Théologie de l'Université Protestante au Congo (UPC) est une publication scientifique à comité de lecture, fondée dans les années 1960. Elle constitue l'un des plus anciens périodiques théologiques en Afrique francophone.</p>
    <p class="text-muted leading-relaxed mt-4">Publiée sous l'égide de la Faculté de Théologie de l'UPC à Kinshasa, la revue accueille des contributions originales de chercheurs, enseignants et doctorants en théologie et disciplines connexes provenant de la RDC et d'ailleurs.</p>
    <?php endif; ?>
    <p class="text-muted leading-relaxed mt-4">Fidèle à la devise de l'UPC — <strong class="text-primary italic">"Vérité, Foi, Liberté"</strong> — la revue encourage la rigueur scientifique, l'ouverture au dialogue et la liberté académique.</p>
  </div>
  <div class="image-wrap">
    <img src="<?= $base ?>/images/revue-cover.jpg" alt="Couverture Revue de Théologie" width="500" height="600">
    <div class="floating-badge">Depuis 1960</div>
  </div>
</section>
<section class="section bg-secondary">
  <div class="container">
    <div class="grid-3" style="grid-template-columns: repeat(4, 1fr);">
      <div class="text-center"><p class="text-3xl font-bold text-primary mb-0">65+</p><p class="text-sm text-muted mt-1">Années de publication</p></div>
      <div class="text-center"><p class="text-3xl font-bold text-primary mb-0">200+</p><p class="text-sm text-muted mt-1">Articles publiés</p></div>
      <div class="text-center"><p class="text-3xl font-bold text-primary mb-0">150+</p><p class="text-sm text-muted mt-1">Contributeurs</p></div>
      <div class="text-center"><p class="text-3xl font-bold text-primary mb-0">12+</p><p class="text-sm text-muted mt-1">Pays représentés</p></div>
    </div>
  </div>
</section>
<section class="container section">
  <h2 class="font-serif text-2xl md:text-3xl font-bold text-center mb-2">Objectifs de la Revue</h2>
  <div class="divider center"></div>
  <?php if ($objectifs): ?>
  <div class="card max-w-3xl mx-auto p-6"><div class="text-muted leading-relaxed"><?= nl2br(htmlspecialchars($objectifs)) ?></div></div>
  <?php else: ?>
  <div class="grid-2 gap-4 max-w-4xl mx-auto">
    <div class="card flex" style="align-items: flex-start; gap: 0.75rem;"><span class="text-primary font-bold"><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#check"/></svg></span><p class="mb-0 text-sm">Promouvoir la recherche scientifique en théologie en Afrique francophone</p></div>
    <div class="card flex" style="align-items: flex-start; gap: 0.75rem;"><span class="text-primary font-bold"><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#check"/></svg></span><p class="mb-0 text-sm">Offrir une plateforme de publication aux chercheurs et enseignants</p></div>
    <div class="card flex" style="align-items: flex-start; gap: 0.75rem;"><span class="text-primary font-bold"><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#check"/></svg></span><p class="mb-0 text-sm">Favoriser le dialogue interconfessionnel et interdisciplinaire</p></div>
    <div class="card flex" style="align-items: flex-start; gap: 0.75rem;"><span class="text-primary font-bold"><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#check"/></svg></span><p class="mb-0 text-sm">Contribuer au développement de la pensée théologale contextuelle</p></div>
  </div>
  <?php endif; ?>
</section>
<section class="section bg-primary">
  <div class="container">
    <h2 class="font-serif text-2xl md:text-3xl font-bold text-center mb-2" style="color: var(--primary-foreground);">Domaines de Publication</h2>
    <div class="divider center" style="background: var(--upc-gold);"></div>
    <?php if ($domaines): ?>
    <div class="card max-w-3xl mx-auto p-6" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2);"><div style="color: rgba(255,255,255,0.9);"><?= nl2br(htmlspecialchars($domaines)) ?></div></div>
    <?php else: ?>
    <div class="grid-3 gap-6">
      <div class="card" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2);"><h3 class="font-serif font-bold mb-2" style="color: var(--primary-foreground);">Études Bibliques</h3><p class="text-sm leading-relaxed mb-0" style="color: rgba(255,255,255,0.7);">Exégèse de l'Ancien et du Nouveau Testament, herméneutique biblique, théologie biblique dans le contexte africain.</p></div>
      <div class="card" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2);"><h3 class="font-serif font-bold mb-2" style="color: var(--primary-foreground);">Théologie Systématique</h3><p class="text-sm leading-relaxed mb-0" style="color: rgba(255,255,255,0.7);">Dogmatique, christologie, pneumatologie, ecclésiologie et questions fondamentales de la foi chrétienne.</p></div>
      <div class="card" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2);"><h3 class="font-serif font-bold mb-2" style="color: var(--primary-foreground);">Éthique Chrétienne</h3><p class="text-sm leading-relaxed mb-0" style="color: rgba(255,255,255,0.7);">Bioéthique, éthique sociale, justice et paix, responsabilité chrétienne face aux enjeux contemporains.</p></div>
      <div class="card" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2);"><h3 class="font-serif font-bold mb-2" style="color: var(--primary-foreground);">Histoire de l'Église</h3><p class="text-sm leading-relaxed mb-0" style="color: rgba(255,255,255,0.7);">Histoire du christianisme en Afrique, mouvements missionnaires, protestantisme en RDC et en Afrique centrale.</p></div>
      <div class="card" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2);"><h3 class="font-serif font-bold mb-2" style="color: var(--primary-foreground);">Théologie Pratique</h3><p class="text-sm leading-relaxed mb-0" style="color: rgba(255,255,255,0.7);">Pastorale, liturgie, homilétique, éducation chrétienne, counseling et accompagnement spirituel.</p></div>
    </div>
    <?php endif; ?>
  </div>
</section>
<section class="container section">
  <h2 class="font-serif text-2xl md:text-3xl font-bold text-center mb-2">Instructions aux Auteurs</h2>
  <div class="divider center"></div>
  <div class="card max-w-3xl mx-auto p-8">
    <p class="text-muted text-sm leading-relaxed mb-0">Les articles doivent être rédigés en français ou en anglais. La longueur recommandée est de 5 000 à 8 000 mots. Chaque article soumis est évalué en double aveugle par au moins deux experts. <a href="<?= $base ?>/instructions-auteurs">Voir les instructions complètes</a>.</p>
  </div>
</section>
