<?php $base = $base ?? ''; ?>
<div class="page-content-compact page-politique-editoriale">
<div class="banner">
  <div class="container">
    <h1 class="font-serif text-xl md:text-2xl font-bold text-balance"><?= htmlspecialchars(__('politique.title')) ?></h1>
    <div class="divider"></div>
    <p class="text-sm" style="color: rgba(255,255,255,0.9);"><?= htmlspecialchars(__('politique.intro')) ?></p>
  </div>
</div>
<div class="container section">
  <div class="card p-4 max-w-3xl mx-auto politique-editoriale-card">
    <h2 class="font-serif text-base font-bold mb-2"><?= htmlspecialchars(__('politique.evaluation')) ?></h2>
    <p class="text-muted text-sm leading-relaxed mb-3"><?= htmlspecialchars(__('politique.evaluation_text')) ?></p>
    <h2 class="font-serif text-base font-bold mb-2"><?= htmlspecialchars(__('politique.copyright')) ?></h2>
    <p class="text-muted text-sm leading-relaxed mb-0"><?= htmlspecialchars(__('politique.copyright_text')) ?></p>
    <p class="mt-4 mb-0"><a href="<?= $base ?>/instructions-auteurs" class="btn btn-sm btn-outline-primary"><?= htmlspecialchars(__('nav.instructions')) ?></a> <a href="<?= $base ?>/contact" class="btn btn-sm btn-primary"><?= htmlspecialchars(__('nav.contact')) ?></a></p>
  </div>
</div>
</div>
