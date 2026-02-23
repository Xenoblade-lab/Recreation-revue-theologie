<?php $base = $base ?? ''; ?>
<div class="banner">
  <div class="container">
    <h1 class="font-serif text-3xl md:text-4xl font-bold"><?= htmlspecialchars(__('faq.title')) ?></h1>
    <div class="divider"></div>
    <p><?= htmlspecialchars(__('faq.intro')) ?></p>
  </div>
</div>
<div class="container section">
  <div class="card p-8 max-w-3xl mx-auto">
    <h2 class="font-serif text-lg font-bold mb-2"><?= htmlspecialchars(__('faq.how_submit_q')) ?></h2>
    <p class="text-muted text-sm mb-6"><?= htmlspecialchars(__('faq.how_submit_a')) ?><a href="<?= $base ?>/soumettre"><?= htmlspecialchars(__('nav.submit')) ?></a><?= htmlspecialchars(__('faq.and')) ?><a href="<?= $base ?>/instructions-auteurs"><?= htmlspecialchars(__('nav.instructions')) ?></a>.</p>
    <h2 class="font-serif text-lg font-bold mb-2"><?= htmlspecialchars(__('faq.delay_q')) ?></h2>
    <p class="text-muted text-sm mb-6"><?= htmlspecialchars(__('faq.delay_a')) ?></p>
    <h2 class="font-serif text-lg font-bold mb-2"><?= htmlspecialchars(__('faq.open_access_q')) ?></h2>
    <p class="text-muted text-sm mb-6"><?= htmlspecialchars(__('faq.open_access_a')) ?></p>
    <p class="mt-8"><a href="<?= $base ?>/contact" class="btn btn-primary"><?= htmlspecialchars(__('faq.contact_us')) ?></a></p>
  </div>
</div>
