<?php $base = $base ?? ''; ?>
<div class="banner">
  <div class="container">
    <h1 class="font-serif text-3xl md:text-4xl font-bold text-balance">Contact</h1>
    <div class="divider"></div>
    <p>N'hésitez pas à nous contacter pour toute question relative à la revue.</p>
  </div>
</div>
<div class="container section">
  <div class="grid-2" style="grid-template-columns: 1fr 1.5fr; gap: 3rem;">
    <div>
      <h2 class="font-serif text-xl font-bold mb-6">Informations de Contact</h2>
      <div class="contact-block">
        <div class="icon"><svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#map-pin"/></svg></div>
        <div>
          <p class="font-medium text-sm mb-0">Adresse</p>
          <p class="text-muted text-sm mt-1">Université Protestante au Congo, Faculté de Théologie, Kinshasa, République Démocratique du Congo</p>
        </div>
      </div>
      <div class="contact-block">
        <div class="icon"><svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#mail"/></svg></div>
        <div>
          <p class="font-medium text-sm mb-0">Email</p>
          <a href="mailto:revue.theologie@upc.ac.cd" class="text-primary text-sm">revue.theologie@upc.ac.cd</a>
        </div>
      </div>
      <div class="contact-block">
        <div class="icon"><svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#phone"/></svg></div>
        <div>
          <p class="font-medium text-sm mb-0">Téléphone</p>
          <p class="text-muted text-sm mt-1">+243 000 000 000</p>
        </div>
      </div>
      <div class="contact-block">
        <div class="icon"><svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#clock"/></svg></div>
        <div>
          <p class="font-medium text-sm mb-0">Heures d'ouverture</p>
          <p class="text-muted text-sm mt-1">Lundi - Vendredi: 8h00 - 16h00<br>Samedi - Dimanche: Fermé</p>
        </div>
      </div>
    </div>
    <div class="card p-8">
      <h2 class="font-serif text-xl font-bold mb-6">Envoyez-nous un message</h2>
      <p class="text-muted text-sm mb-4">Pour toute demande, écrivez-nous à <a href="mailto:revue.theologie@upc.ac.cd">revue.theologie@upc.ac.cd</a>.</p>
      <a href="mailto:revue.theologie@upc.ac.cd?subject=Contact%20Revue%20Th%C3%A9ologie" class="btn btn-primary">Ouvrir votre client mail</a>
    </div>
  </div>
</div>
