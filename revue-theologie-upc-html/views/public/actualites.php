<?php $base = $base ?? ''; ?>
<div class="page-content-compact page-actualites">
<div class="banner">
  <div class="container">
    <h1 class="font-serif text-xl md:text-2xl font-bold"><?= htmlspecialchars(__('actualites.title')) ?></h1>
    <div class="divider"></div>
    <p class="text-sm" style="color: rgba(255,255,255,0.9);"><?= htmlspecialchars(__('actualites.intro')) ?></p>
  </div>
</div>
<div class="container section">
  <div class="card p-4 max-w-3xl mx-auto actualites-card">
    <p class="text-muted text-sm mb-0"><?= htmlspecialchars(__('actualites.placeholder')) ?></p>
    <p class="mt-3 mb-0"><a href="<?= $base ?>/archives" class="btn btn-sm btn-outline-primary"><?= htmlspecialchars(__('actualites.view_archives')) ?></a> <a href="<?= $base ?>/contact" class="btn btn-sm btn-primary"><?= htmlspecialchars(__('nav.contact')) ?></a></p>
  </div>
</div>
</div>
