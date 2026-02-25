<?php $base = $base ?? ''; ?>
<div class="page-content-compact page-contact">
<div class="banner">
  <div class="container">
    <h1 class="font-serif text-xl md:text-2xl font-bold text-balance"><?= htmlspecialchars(__('contact.title')) ?></h1>
    <div class="divider"></div>
    <p class="text-sm" style="color: rgba(255,255,255,0.9);"><?= htmlspecialchars(__('contact.intro')) ?></p>
  </div>
</div>
<div class="container section">
  <div class="grid-2 contact-grid" style="grid-template-columns: 1fr 1.5fr; gap: 1.5rem;">
    <div>
      <h2 class="font-serif text-base font-bold mb-3"><?= htmlspecialchars(__('contact.info_title')) ?></h2>
      <div class="contact-block">
        <div class="icon"><svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#map-pin"/></svg></div>
        <div>
          <p class="font-medium text-sm mb-0"><?= htmlspecialchars(__('contact.address')) ?></p>
          <p class="text-muted text-sm mt-1"><?= htmlspecialchars(__('contact.address_value')) ?></p>
        </div>
      </div>
      <div class="contact-block">
        <div class="icon"><svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#mail"/></svg></div>
        <div>
          <p class="font-medium text-sm mb-0"><?= htmlspecialchars(__('contact.email')) ?></p>
          <a href="mailto:revue.theologie@upc.ac.cd" class="text-primary text-sm">revue.theologie@upc.ac.cd</a>
        </div>
      </div>
      <div class="contact-block">
        <div class="icon"><svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#phone"/></svg></div>
        <div>
          <p class="font-medium text-sm mb-0"><?= htmlspecialchars(__('contact.phone')) ?></p>
          <p class="text-muted text-sm mt-1">+243 000 000 000</p>
        </div>
      </div>
      <div class="contact-block">
        <div class="icon"><svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#clock"/></svg></div>
        <div>
          <p class="font-medium text-sm mb-0"><?= htmlspecialchars(__('contact.hours')) ?></p>
          <p class="text-muted text-sm mt-1"><?= htmlspecialchars(__('contact.hours_value')) ?></p>
        </div>
      </div>
    </div>
    <div class="card p-4 contact-form-card">
      <h2 class="font-serif text-base font-bold mb-3"><?= htmlspecialchars(__('contact.send_message')) ?></h2>
      <p class="text-muted text-sm mb-3"><?= htmlspecialchars(__('contact.send_intro')) ?> <a href="mailto:revue.theologie@upc.ac.cd">revue.theologie@upc.ac.cd</a>.</p>
      <a href="mailto:revue.theologie@upc.ac.cd?subject=Contact%20Revue%20Th%C3%A9ologie" class="btn btn-sm btn-primary"><?= htmlspecialchars(__('contact.open_mail')) ?></a>
    </div>
  </div>
</div>
</div>
