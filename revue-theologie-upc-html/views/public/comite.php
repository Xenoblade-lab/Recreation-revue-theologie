<?php
$base = $base ?? '';
$revueInfo = $revueInfo ?? null;
$comiteRedac = $revueInfo['comite_redaction'] ?? null;
$comiteSci = $revueInfo['comite_scientifique'] ?? null;
?>
<div class="banner">
  <div class="container">
    <h1 class="font-serif text-3xl md:text-4xl font-bold text-balance">Comité Editorial</h1>
    <div class="divider"></div>
    <p>Les membres du comité de rédaction et du comité scientifique de la revue.</p>
  </div>
</div>
<section class="container section">
  <div class="card max-w-3xl mx-auto p-8" style="border-width: 2px; border-color: var(--primary);">
    <div class="flex items-center gap-3 mb-4">
      <div class="card-icon" style="width: 3.5rem; height: 3.5rem; border-radius: 9999px; background: var(--primary); color: var(--primary-foreground);"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#award"/></svg></div>
      <div>
        <h2 class="font-serif text-xl font-bold">Directeur de la Revue</h2>
        <p class="text-accent font-medium text-sm">Faculté de Théologie, UPC - Kinshasa</p>
      </div>
    </div>
    <?php if ($comiteRedac): ?>
    <div class="text-muted text-sm leading-relaxed"><?= nl2br(htmlspecialchars($comiteRedac)) ?></div>
    <?php else: ?>
    <p class="text-muted text-sm">Le comité de rédaction assure la gestion éditoriale de la revue. Contact : <a href="mailto:revue.theologie@upc.ac.cd">revue.theologie@upc.ac.cd</a></p>
    <?php endif; ?>
  </div>
</section>
<section class="section bg-secondary">
  <div class="container">
    <h2 class="font-serif text-2xl md:text-3xl font-bold text-center mb-2">Comité de Rédaction</h2>
    <div class="divider center"></div>
    <p class="text-muted text-center max-w-2xl mx-auto mb-8">Les membres internes de la Faculté de Théologie qui assurent la gestion éditoriale de la revue.</p>
    <?php if ($comiteRedac): ?>
    <div class="card max-w-3xl mx-auto p-6"><div class="text-muted leading-relaxed"><?= nl2br(htmlspecialchars($comiteRedac)) ?></div></div>
    <?php else: ?>
    <div class="grid-3">
      <div class="card p-6"><div class="card-icon mb-4"><svg class="icon-svg icon-24" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#graduation-cap"/></svg></div><h3 class="font-serif font-bold">Comité de rédaction</h3><p class="text-muted text-sm mt-2">UPC - Kinshasa</p></div>
    </div>
    <?php endif; ?>
  </div>
</section>
<section class="container section">
  <h2 class="font-serif text-2xl md:text-3xl font-bold text-center mb-2">Comité Scientifique</h2>
  <div class="divider center"></div>
  <p class="text-muted text-center max-w-2xl mx-auto mb-8">Des experts qui garantissent la qualité scientifique et la rigueur des publications.</p>
  <?php if ($comiteSci): ?>
  <div class="card max-w-3xl mx-auto p-6"><div class="text-muted leading-relaxed"><?= nl2br(htmlspecialchars($comiteSci)) ?></div></div>
  <?php else: ?>
  <div class="card max-w-3xl mx-auto p-6"><p class="text-muted text-sm mb-0">Le comité scientifique international accompagne la revue dans son processus d'évaluation.</p></div>
  <?php endif; ?>
</section>
<section class="section bg-primary">
  <div class="container text-center">
    <h2 class="font-serif text-xl md:text-2xl font-bold mb-3" style="color: var(--primary-foreground);">Contacter le Comité Editorial</h2>
    <p class="mb-6 max-w-xl mx-auto text-sm" style="color: rgba(255,255,255,0.7);">Pour toute question concernant la soumission d'articles ou la politique éditoriale.</p>
    <a href="mailto:revue.theologie@upc.ac.cd" class="btn" style="background: var(--upc-gold); color: var(--foreground);"><svg class="icon-svg icon-16" style="vertical-align: middle;" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#mail"/></svg> revue.theologie@upc.ac.cd</a>
  </div>
</section>
