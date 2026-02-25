<?php $base = $base ?? ''; ?>
<div class="page-content-compact page-faq">
<div class="banner">
  <div class="container">
    <h1 class="font-serif text-xl md:text-2xl font-bold"><?= htmlspecialchars(__('faq.title')) ?></h1>
    <div class="divider"></div>
    <p class="text-sm" style="color: rgba(255,255,255,0.9);"><?= htmlspecialchars(__('faq.intro')) ?></p>
  </div>
</div>
<div class="container section">
  <div class="card p-4 max-w-3xl mx-auto faq-card">
    <h2 class="font-serif text-base font-bold mb-1"><?= htmlspecialchars(__('faq.how_submit_q')) ?></h2>
    <p class="text-muted text-sm mb-4"><?= htmlspecialchars(__('faq.how_submit_a')) ?><a href="<?= $base ?>/soumettre"><?= htmlspecialchars(__('nav.submit')) ?></a><?= htmlspecialchars(__('faq.and')) ?><a href="<?= $base ?>/instructions-auteurs"><?= htmlspecialchars(__('nav.instructions')) ?></a>.</p>
    <h2 class="font-serif text-base font-bold mb-1"><?= htmlspecialchars(__('faq.delay_q')) ?></h2>
    <p class="text-muted text-sm mb-4"><?= htmlspecialchars(__('faq.delay_a')) ?></p>
    <h2 class="font-serif text-base font-bold mb-1"><?= htmlspecialchars(__('faq.open_access_q')) ?></h2>
    <p class="text-muted text-sm mb-0"><?= htmlspecialchars(__('faq.open_access_a')) ?></p>
    <p class="mt-4 mb-0"><a href="<?= $base ?>/contact" class="btn btn-sm btn-primary"><?= htmlspecialchars(__('faq.contact_us')) ?></a></p>
  </div>
</div>
</div>
