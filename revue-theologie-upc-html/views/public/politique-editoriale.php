<?php $base = $base ?? ''; ?>
<div class="banner">
  <div class="container">
    <h1 class="font-serif text-3xl md:text-4xl font-bold text-balance"><?= htmlspecialchars(__('politique.title')) ?></h1>
    <div class="divider"></div>
    <p><?= htmlspecialchars(__('politique.intro')) ?></p>
  </div>
</div>
<div class="container section">
  <div class="card p-8 max-w-3xl mx-auto">
    <h2 class="font-serif text-xl font-bold mb-4"><?= htmlspecialchars(__('politique.evaluation')) ?></h2>
    <p class="text-muted text-sm leading-relaxed mb-4"><?= htmlspecialchars(__('politique.evaluation_text')) ?></p>
    <h2 class="font-serif text-xl font-bold mb-4"><?= htmlspecialchars(__('politique.copyright')) ?></h2>
    <p class="text-muted text-sm leading-relaxed mb-4"><?= htmlspecialchars(__('politique.copyright_text')) ?></p>
    <p class="mt-6"><a href="<?= $base ?>/instructions-auteurs" class="btn btn-outline-primary"><?= htmlspecialchars(__('nav.instructions')) ?></a> <a href="<?= $base ?>/contact" class="btn btn-primary"><?= htmlspecialchars(__('nav.contact')) ?></a></p>
  </div>
</div>
