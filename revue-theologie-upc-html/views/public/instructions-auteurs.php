<?php $base = $base ?? ''; ?>
<div class="banner">
  <div class="container">
    <h1 class="font-serif text-3xl md:text-4xl font-bold text-balance"><?= htmlspecialchars(__('instructions.title')) ?></h1>
    <div class="divider"></div>
    <p><?= htmlspecialchars(__('instructions.intro')) ?></p>
  </div>
</div>
<div class="container section">
  <div class="card max-w-3xl mx-auto p-8">
    <h2 class="font-serif text-xl font-bold mb-4"><?= htmlspecialchars(__('instructions.format')) ?></h2>
    <ul class="text-muted text-sm leading-relaxed mb-6" style="list-style: none; padding: 0;">
      <li class="flex mb-2" style="align-items: flex-start; gap: 0.5rem;"><span class="text-primary font-bold">–</span> <?= htmlspecialchars(__('instructions.format1')) ?></li>
      <li class="flex mb-2" style="align-items: flex-start; gap: 0.5rem;"><span class="text-primary font-bold">–</span> <?= htmlspecialchars(__('instructions.format2')) ?></li>
      <li class="flex mb-2" style="align-items: flex-start; gap: 0.5rem;"><span class="text-primary font-bold">–</span> <?= htmlspecialchars(__('instructions.format3')) ?></li>
      <li class="flex mb-2" style="align-items: flex-start; gap: 0.5rem;"><span class="text-primary font-bold">–</span> <?= htmlspecialchars(__('instructions.format4')) ?></li>
    </ul>
    <h2 class="font-serif text-xl font-bold mb-4"><?= htmlspecialchars(__('instructions.process')) ?></h2>
    <p class="text-muted text-sm leading-relaxed mb-0"><?= htmlspecialchars(__('instructions.process_text')) ?></p>
    <p class="mt-6"><a href="<?= $base ?>/soumettre" class="btn btn-primary"><?= htmlspecialchars(__('nav.submit')) ?></a> <a href="<?= $base ?>/politique-editoriale" class="btn btn-outline-primary"><?= htmlspecialchars(__('nav.politique')) ?></a></p>
  </div>
</div>
