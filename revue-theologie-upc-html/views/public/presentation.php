<?php
$base = $base ?? '';
$revueInfo = $revueInfo ?? null;
$desc = $revueInfo['description'] ?? '';
$objectifs = $revueInfo['objectifs'] ?? null;
$domaines = $revueInfo['domaines_couverts'] ?? null;
?>
<div class="page-content-compact page-presentation">
<div class="banner">
  <div class="container">
    <h1 class="font-serif text-xl md:text-2xl font-bold text-balance"><?= htmlspecialchars(__('presentation.title')) ?></h1>
    <div class="divider"></div>
    <p class="text-sm" style="color: rgba(255,255,255,0.9);"><?= htmlspecialchars(__('presentation.intro')) ?></p>
  </div>
</div>
<section class="container presentation-intro">
  <div>
    <h2 class="font-serif text-lg md:text-xl font-bold mb-4 text-balance"><?= htmlspecialchars(__('presentation.tradition')) ?></h2>
    <?php if ($desc): ?>
    <p class="text-muted leading-relaxed"><?= nl2br(htmlspecialchars($desc)) ?></p>
    <?php else: ?>
    <p class="text-muted leading-relaxed"><?= htmlspecialchars(__('presentation.desc_default_1')) ?></p>
    <p class="text-muted leading-relaxed mt-3 text-sm"><?= htmlspecialchars(__('presentation.desc_default_2')) ?></p>
    <?php endif; ?>
    <p class="text-muted leading-relaxed mt-3 text-sm"><?= __('presentation.motto') ?></p>
  </div>
  <div class="image-wrap">
    <img src="<?= $base ?>/images/revue-cover-upc.jpg" alt="Couverture Revue Congolaise de Théologie Protestante" width="500" height="600">
    <div class="floating-badge"><?= htmlspecialchars(__('presentation.since_1960')) ?></div>
  </div>
</section>
<section class="section bg-secondary presentation-stats">
  <div class="container">
    <div class="grid-3 presentation-stats-grid" style="grid-template-columns: repeat(4, 1fr);">
      <div class="text-center"><p class="text-2xl font-bold text-primary mb-0">65+</p><p class="text-xs text-muted mt-0"><?= htmlspecialchars(__('presentation.years')) ?></p></div>
      <div class="text-center"><p class="text-2xl font-bold text-primary mb-0">200+</p><p class="text-xs text-muted mt-0"><?= htmlspecialchars(__('presentation.articles_published')) ?></p></div>
      <div class="text-center"><p class="text-2xl font-bold text-primary mb-0">150+</p><p class="text-xs text-muted mt-0"><?= htmlspecialchars(__('presentation.contributors')) ?></p></div>
      <div class="text-center"><p class="text-2xl font-bold text-primary mb-0">12+</p><p class="text-xs text-muted mt-0"><?= htmlspecialchars(__('presentation.countries')) ?></p></div>
    </div>
  </div>
</section>
<section class="container section presentation-objectives">
  <h2 class="font-serif text-lg md:text-xl font-bold text-center mb-2"><?= htmlspecialchars(__('presentation.objectives')) ?></h2>
  <div class="divider center"></div>
  <?php if ($objectifs): ?>
  <div class="card max-w-3xl mx-auto p-4"><div class="text-muted leading-relaxed text-sm"><?= nl2br(htmlspecialchars($objectifs)) ?></div></div>
  <?php else: ?>
  <div class="grid-2 gap-3 max-w-4xl mx-auto">
    <div class="card flex" style="align-items: flex-start; gap: 0.75rem;"><span class="text-primary font-bold"><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#check"/></svg></span><p class="mb-0 text-sm"><?= htmlspecialchars(__('presentation.obj1')) ?></p></div>
    <div class="card flex" style="align-items: flex-start; gap: 0.75rem;"><span class="text-primary font-bold"><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#check"/></svg></span><p class="mb-0 text-sm"><?= htmlspecialchars(__('presentation.obj2')) ?></p></div>
    <div class="card flex" style="align-items: flex-start; gap: 0.75rem;"><span class="text-primary font-bold"><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#check"/></svg></span><p class="mb-0 text-sm"><?= htmlspecialchars(__('presentation.obj3')) ?></p></div>
    <div class="card flex" style="align-items: flex-start; gap: 0.75rem;"><span class="text-primary font-bold"><svg class="icon-svg icon-16" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#check"/></svg></span><p class="mb-0 text-sm"><?= htmlspecialchars(__('presentation.obj4')) ?></p></div>
  </div>
  <?php endif; ?>
</section>
<section class="section bg-primary presentation-domains">
  <div class="container">
    <h2 class="font-serif text-lg md:text-xl font-bold text-center mb-2" style="color: var(--primary-foreground);"><?= htmlspecialchars(__('presentation.domains')) ?></h2>
    <div class="divider center" style="background: var(--upc-gold);"></div>
    <?php if ($domaines): ?>
    <div class="card max-w-3xl mx-auto p-4" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2);"><div class="text-sm" style="color: rgba(255,255,255,0.9);"><?= nl2br(htmlspecialchars($domaines)) ?></div></div>
    <?php else: ?>
    <div class="grid-3 gap-4 presentation-domains-grid">
      <div class="card p-3" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2);"><h3 class="font-serif font-bold mb-1 text-sm" style="color: var(--primary-foreground);"><?= htmlspecialchars(__('presentation.domain_biblical')) ?></h3><p class="text-xs leading-relaxed mb-0" style="color: rgba(255,255,255,0.7);"><?= htmlspecialchars(__('presentation.domain_biblical_desc')) ?></p></div>
      <div class="card p-3" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2);"><h3 class="font-serif font-bold mb-1 text-sm" style="color: var(--primary-foreground);"><?= htmlspecialchars(__('presentation.domain_systematic')) ?></h3><p class="text-xs leading-relaxed mb-0" style="color: rgba(255,255,255,0.7);"><?= htmlspecialchars(__('presentation.domain_systematic_desc')) ?></p></div>
      <div class="card p-3" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2);"><h3 class="font-serif font-bold mb-1 text-sm" style="color: var(--primary-foreground);"><?= htmlspecialchars(__('presentation.domain_ethics')) ?></h3><p class="text-xs leading-relaxed mb-0" style="color: rgba(255,255,255,0.7);"><?= htmlspecialchars(__('presentation.domain_ethics_desc')) ?></p></div>
      <div class="card p-3" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2);"><h3 class="font-serif font-bold mb-1 text-sm" style="color: var(--primary-foreground);"><?= htmlspecialchars(__('presentation.domain_history')) ?></h3><p class="text-xs leading-relaxed mb-0" style="color: rgba(255,255,255,0.7);"><?= htmlspecialchars(__('presentation.domain_history_desc')) ?></p></div>
      <div class="card p-3" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2);"><h3 class="font-serif font-bold mb-1 text-sm" style="color: var(--primary-foreground);"><?= htmlspecialchars(__('presentation.domain_practical')) ?></h3><p class="text-xs leading-relaxed mb-0" style="color: rgba(255,255,255,0.7);"><?= htmlspecialchars(__('presentation.domain_practical_desc')) ?></p></div>
    </div>
    <?php endif; ?>
  </div>
</section>
<section class="container section">
  <h2 class="font-serif text-lg md:text-xl font-bold text-center mb-2"><?= htmlspecialchars(__('presentation.instructions_link')) ?></h2>
  <div class="divider center"></div>
  <div class="card max-w-3xl mx-auto p-4">
    <p class="text-muted text-sm leading-relaxed mb-0"><?= htmlspecialchars(__('presentation.instructions_short')) ?> <a href="<?= $base ?>/instructions-auteurs"><?= htmlspecialchars(__('nav.instructions')) ?></a>.</p>
  </div>
</section>
</div>
