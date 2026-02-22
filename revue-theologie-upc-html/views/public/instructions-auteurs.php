<?php $base = $base ?? ''; ?>
<div class="banner">
  <div class="container">
    <h1 class="font-serif text-3xl md:text-4xl font-bold text-balance">Instructions aux auteurs</h1>
    <div class="divider"></div>
    <p>Format des manuscrits et consignes de soumission.</p>
  </div>
</div>
<div class="container section">
  <div class="card max-w-3xl mx-auto p-8">
    <h2 class="font-serif text-xl font-bold mb-4">Format des manuscrits</h2>
    <ul class="text-muted text-sm leading-relaxed mb-6" style="list-style: none; padding: 0;">
      <li class="flex mb-2" style="align-items: flex-start; gap: 0.5rem;"><span class="text-primary font-bold">–</span> Les articles doivent être rédigés en français ou en anglais.</li>
      <li class="flex mb-2" style="align-items: flex-start; gap: 0.5rem;"><span class="text-primary font-bold">–</span> La longueur recommandée est de 5 000 à 8 000 mots, notes incluses.</li>
      <li class="flex mb-2" style="align-items: flex-start; gap: 0.5rem;"><span class="text-primary font-bold">–</span> Le manuscrit doit inclure un résumé (150-250 mots) en français et en anglais.</li>
      <li class="flex mb-2" style="align-items: flex-start; gap: 0.5rem;"><span class="text-primary font-bold">–</span> Les références bibliographiques suivent le style Chicago (notes de bas de page).</li>
    </ul>
    <h2 class="font-serif text-xl font-bold mb-4">Processus d'évaluation</h2>
    <p class="text-muted text-sm leading-relaxed mb-0">Chaque article soumis est évalué en double aveugle par au moins deux experts du domaine concerné. Le comité éditorial se réserve le droit de demander des modifications ou de refuser les manuscrits ne répondant pas aux critères scientifiques de la revue.</p>
    <p class="mt-6"><a href="<?= $base ?>/soumettre" class="btn btn-primary">Soumettre un article</a> <a href="<?= $base ?>/politique-editoriale" class="btn btn-outline-primary">Politique éditoriale</a></p>
  </div>
</div>
