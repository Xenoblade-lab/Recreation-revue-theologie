<?php $base = $base ?? ''; ?>
<div class="page-content-compact page-confidentialite">
<div class="banner">
  <div class="container">
    <h1 class="font-serif text-xl md:text-2xl font-bold text-balance"><?= htmlspecialchars(__('legal.confidentiality_title')) ?></h1>
    <div class="divider"></div>
    <p class="text-sm" style="color: rgba(255,255,255,0.9);"><?= htmlspecialchars(__('legal.confidentiality_intro')) ?></p>
  </div>
</div>
<div class="container section">
  <div class="card p-4 max-w-3xl mx-auto prose-custom confidentialite-card">
    <h2 class="font-serif text-base font-bold mb-2"><?= htmlspecialchars(__('legal.confidentiality_data')) ?></h2>
    <p class="text-muted text-sm leading-relaxed mb-3"><?= htmlspecialchars(__('legal.confidentiality_data_text')) ?></p>
    <h2 class="font-serif text-base font-bold mb-2"><?= htmlspecialchars(__('legal.confidentiality_use')) ?></h2>
    <p class="text-muted text-sm leading-relaxed mb-3"><?= htmlspecialchars(__('legal.confidentiality_use_text')) ?></p>
    <h2 class="font-serif text-base font-bold mb-2"><?= htmlspecialchars(__('legal.confidentiality_rights')) ?></h2>
    <p class="text-muted text-sm leading-relaxed mb-3"><?= htmlspecialchars(__('legal.confidentiality_rights_text')) ?></p>
    <h2 class="font-serif text-base font-bold mb-2"><?= htmlspecialchars(__('footer.contact')) ?></h2>
    <p class="text-muted text-sm leading-relaxed mb-0"><?= htmlspecialchars(__('contact.intro')) ?> <a href="mailto:revue.theologie@upc.ac.cd">revue.theologie@upc.ac.cd</a></p>
    <p class="mt-4 mb-0"><a href="<?= $base ?>/contact" class="btn btn-sm btn-outline-primary"><?= htmlspecialchars(__('nav.contact')) ?></a></p>
  </div>
</div>
</div>
