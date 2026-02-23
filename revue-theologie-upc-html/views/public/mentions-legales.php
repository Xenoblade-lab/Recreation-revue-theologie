<?php $base = $base ?? ''; ?>
<div class="banner">
  <div class="container">
    <h1 class="font-serif text-3xl md:text-4xl font-bold text-balance"><?= htmlspecialchars(__('mentions.title')) ?></h1>
    <div class="divider"></div>
    <p><?= htmlspecialchars(__('mentions.intro')) ?></p>
  </div>
</div>
<div class="container section">
  <div class="card p-8 max-w-3xl mx-auto">
    <h2 class="font-serif text-xl font-bold mb-4"><?= htmlspecialchars(__('mentions.publisher')) ?></h2>
    <p class="text-muted text-sm leading-relaxed mb-6"><?= htmlspecialchars(__('mentions.publisher_text')) ?></p>
    <h2 class="font-serif text-xl font-bold mb-4"><?= htmlspecialchars(__('mentions.intellectual')) ?></h2>
    <p class="text-muted text-sm leading-relaxed mb-6"><?= htmlspecialchars(__('mentions.intellectual_text')) ?></p>
    <h2 class="font-serif text-xl font-bold mb-4"><?= htmlspecialchars(__('footer.contact')) ?></h2>
    <p class="text-muted text-sm leading-relaxed mb-0"><?= htmlspecialchars(__('contact.intro')) ?> <a href="mailto:revue.theologie@upc.ac.cd">revue.theologie@upc.ac.cd</a></p>
    <p class="mt-6"><a href="<?= $base ?>/contact" class="btn btn-outline-primary"><?= htmlspecialchars(__('nav.contact')) ?></a></p>
  </div>
</div>
