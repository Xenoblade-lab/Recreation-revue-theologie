<?php $base = $base ?? ''; ?>
<div class="page-content-compact page-instructions-auteurs">
<div class="banner">
  <div class="container">
    <h1 class="font-serif text-xl md:text-2xl font-bold text-balance"><?= htmlspecialchars(__('instructions.title')) ?></h1>
    <div class="divider"></div>
    <p class="text-sm" style="color: rgba(255,255,255,0.9);"><?= htmlspecialchars(__('instructions.intro')) ?></p>
  </div>
</div>
<div class="container section">
  <div class="card max-w-3xl mx-auto p-4 instructions-auteurs-card">
    <h2 class="font-serif text-base font-bold mb-2"><?= htmlspecialchars(__('instructions.format')) ?></h2>
    <ul class="text-muted text-sm leading-relaxed mb-4 instructions-list" style="list-style: none; padding: 0;">
      <li class="flex mb-1" style="align-items: flex-start; gap: 0.5rem;"><span class="text-primary font-bold">–</span> <?= htmlspecialchars(__('instructions.format1')) ?></li>
      <li class="flex mb-1" style="align-items: flex-start; gap: 0.5rem;"><span class="text-primary font-bold">–</span> <?= htmlspecialchars(__('instructions.format2')) ?></li>
      <li class="flex mb-1" style="align-items: flex-start; gap: 0.5rem;"><span class="text-primary font-bold">–</span> <?= htmlspecialchars(__('instructions.format3')) ?></li>
      <li class="flex mb-1" style="align-items: flex-start; gap: 0.5rem;"><span class="text-primary font-bold">–</span> <?= htmlspecialchars(__('instructions.format4')) ?></li>
    </ul>
    <h2 class="font-serif text-base font-bold mb-2"><?= htmlspecialchars(__('instructions.download_models')) ?></h2>
    <p class="text-muted text-sm leading-relaxed mb-3"><?= htmlspecialchars(__('instructions.download_models_intro')) ?></p>
    <div class="template-downloads">
      <a href="<?= $base ?>/templates/template.docx" class="template-download-card" download="template-revue-theologie-upc.docx">
        <span class="template-download-icon template-download-icon-word"><svg class="icon-svg" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#file-text"/></svg></span>
        <span class="template-download-label"><?= htmlspecialchars(__('instructions.download_word')) ?></span>
        <span class="template-download-format">.docx</span>
      </a>
      <a href="<?= $base ?>/templates/template.tex" class="template-download-card" download="template-revue-theologie-upc.tex">
        <span class="template-download-icon template-download-icon-latex"><svg class="icon-svg" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#file-text"/></svg></span>
        <span class="template-download-label"><?= htmlspecialchars(__('instructions.download_latex')) ?></span>
        <span class="template-download-format">.tex</span>
      </a>
    </div>
    <h2 class="font-serif text-base font-bold mb-2"><?= htmlspecialchars(__('instructions.process')) ?></h2>
    <p class="text-muted text-sm leading-relaxed mb-0"><?= htmlspecialchars(__('instructions.process_text')) ?></p>
    <p class="mt-4 mb-0"><a href="<?= $base ?>/soumettre" class="btn btn-sm btn-primary"><?= htmlspecialchars(__('nav.submit')) ?></a> <a href="<?= $base ?>/politique-editoriale" class="btn btn-sm btn-outline-primary"><?= htmlspecialchars(__('nav.politique')) ?></a></p>
  </div>
</div>
</div>
