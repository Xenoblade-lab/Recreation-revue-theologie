<?php $base = $base ?? ''; ?>
<div class="banner">
  <div class="container">
    <h1 class="font-serif text-3xl md:text-4xl font-bold"><?= htmlspecialchars(__('actualites.title')) ?></h1>
    <div class="divider"></div>
    <p><?= htmlspecialchars(__('actualites.intro')) ?></p>
  </div>
</div>
<div class="container section">
  <div class="card p-8 max-w-3xl mx-auto">
    <p class="text-muted"><?= htmlspecialchars(__('actualites.placeholder')) ?></p>
    <p class="mt-4"><a href="<?= $base ?>/archives" class="btn btn-outline-primary"><?= htmlspecialchars(__('actualites.view_archives')) ?></a> <a href="<?= $base ?>/contact" class="btn btn-primary"><?= htmlspecialchars(__('nav.contact')) ?></a></p>
  </div>
</div>
