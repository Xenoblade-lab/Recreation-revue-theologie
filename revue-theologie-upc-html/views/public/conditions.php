<?php $base = $base ?? ''; ?>
<div class="banner">
  <div class="container">
    <h1 class="font-serif text-3xl md:text-4xl font-bold text-balance"><?= htmlspecialchars(__('legal.conditions_title')) ?></h1>
    <div class="divider"></div>
    <p><?= htmlspecialchars(__('legal.conditions_intro')) ?></p>
  </div>
</div>
<div class="container section">
  <div class="card p-8 max-w-3xl mx-auto prose-custom">
    <h2 class="font-serif text-xl font-bold mb-4"><?= htmlspecialchars(__('legal.conditions_scope')) ?></h2>
    <p class="text-muted text-sm leading-relaxed mb-6"><?= htmlspecialchars(__('legal.conditions_scope_text')) ?></p>
    <h2 class="font-serif text-xl font-bold mb-4"><?= htmlspecialchars(__('legal.conditions_use')) ?></h2>
    <p class="text-muted text-sm leading-relaxed mb-6"><?= htmlspecialchars(__('legal.conditions_use_text')) ?></p>
    <h2 class="font-serif text-xl font-bold mb-4"><?= htmlspecialchars(__('legal.conditions_liability')) ?></h2>
    <p class="text-muted text-sm leading-relaxed mb-6"><?= htmlspecialchars(__('legal.conditions_liability_text')) ?></p>
    <h2 class="font-serif text-xl font-bold mb-4"><?= htmlspecialchars(__('footer.contact')) ?></h2>
    <p class="text-muted text-sm leading-relaxed mb-0"><?= htmlspecialchars(__('contact.intro')) ?> <a href="mailto:revue.theologie@upc.ac.cd">revue.theologie@upc.ac.cd</a></p>
    <p class="mt-6"><a href="<?= $base ?>/contact" class="btn btn-outline-primary"><?= htmlspecialchars(__('nav.contact')) ?></a></p>
  </div>
</div>
