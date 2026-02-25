<?php
$base = $base ?? '';
$revueInfo = $revueInfo ?? null;
$comiteRedac = $revueInfo['comite_redaction'] ?? null;
$comiteSci = $revueInfo['comite_scientifique'] ?? null;
?>
<div class="page-content-compact page-comite">
<div class="banner">
  <div class="container">
    <h1 class="font-serif text-xl md:text-2xl font-bold text-balance"><?= htmlspecialchars(__('comite.title')) ?></h1>
    <div class="divider"></div>
    <p class="text-sm" style="color: rgba(255,255,255,0.9);"><?= htmlspecialchars(__('comite.intro')) ?></p>
  </div>
</div>
<section class="container section">
  <div class="card max-w-3xl mx-auto p-4 comite-director-card" style="border-width: 2px; border-color: var(--primary);">
    <div class="flex items-center gap-3 mb-3">
      <div class="card-icon comite-director-icon" style="width: 2.75rem; height: 2.75rem; border-radius: 9999px; background: var(--primary); color: var(--primary-foreground);"><svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#award"/></svg></div>
      <div>
        <h2 class="font-serif text-base font-bold"><?= htmlspecialchars(__('comite.director')) ?></h2>
        <p class="text-accent font-medium text-xs"><?= htmlspecialchars(__('comite.faculty')) ?></p>
      </div>
    </div>
    <?php if ($comiteRedac): ?>
    <div class="text-muted text-sm leading-relaxed"><?= nl2br(htmlspecialchars($comiteRedac)) ?></div>
    <?php else: ?>
    <p class="text-muted text-sm"><?= htmlspecialchars(__('comite.redac_default')) ?> <a href="mailto:revue.theologie@upc.ac.cd">revue.theologie@upc.ac.cd</a></p>
    <?php endif; ?>
  </div>
</section>
<section class="section bg-secondary comite-redaction">
  <div class="container">
    <h2 class="font-serif text-lg md:text-xl font-bold text-center mb-2"><?= htmlspecialchars(__('comite.redaction')) ?></h2>
    <div class="divider center"></div>
    <p class="text-muted text-center max-w-2xl mx-auto mb-4 text-sm"><?= htmlspecialchars(__('comite.redaction_intro')) ?></p>
    <?php if ($comiteRedac): ?>
    <div class="card max-w-3xl mx-auto p-4"><div class="text-muted leading-relaxed text-sm"><?= nl2br(htmlspecialchars($comiteRedac)) ?></div></div>
    <?php else: ?>
    <div class="grid-3 comite-cards-grid">
      <div class="card p-4"><div class="card-icon mb-2"><svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#graduation-cap"/></svg></div><h3 class="font-serif font-bold text-sm"><?= htmlspecialchars(__('comite.redaction')) ?></h3><p class="text-muted text-xs mt-1">UPC - Kinshasa</p></div>
    </div>
    <?php endif; ?>
  </div>
</section>
<section class="container section comite-scientific">
  <h2 class="font-serif text-lg md:text-xl font-bold text-center mb-2"><?= htmlspecialchars(__('comite.scientific')) ?></h2>
  <div class="divider center"></div>
  <p class="text-muted text-center max-w-2xl mx-auto mb-4 text-sm"><?= htmlspecialchars(__('comite.scientific_intro')) ?></p>
  <?php if ($comiteSci): ?>
  <div class="card max-w-3xl mx-auto p-4"><div class="text-muted leading-relaxed text-sm"><?= nl2br(htmlspecialchars($comiteSci)) ?></div></div>
  <?php else: ?>
  <div class="card max-w-3xl mx-auto p-4"><p class="text-muted text-sm mb-0"><?= htmlspecialchars(__('comite.scientific_default')) ?></p></div>
  <?php endif; ?>
</section>
<section class="section bg-primary comite-contact">
  <div class="container text-center">
    <h2 class="font-serif text-base md:text-lg font-bold mb-2" style="color: var(--primary-foreground);"><?= htmlspecialchars(__('comite.contact_title')) ?></h2>
    <p class="mb-4 max-w-xl mx-auto text-xs" style="color: rgba(255,255,255,0.7);"><?= htmlspecialchars(__('comite.contact_intro')) ?></p>
    <a href="mailto:revue.theologie@upc.ac.cd" class="btn btn-sm" style="background: var(--upc-gold); color: var(--foreground);"><svg class="icon-svg icon-16" style="vertical-align: middle;" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#mail"/></svg> revue.theologie@upc.ac.cd</a>
  </div>
</section>
</div>
