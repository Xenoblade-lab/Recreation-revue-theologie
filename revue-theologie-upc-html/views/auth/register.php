<?php
$base = $base ?? '';
$error = $error ?? null;
$old = $old ?? [];
?>
<div class="mobile-logo">
  <img src="<?= $base ?>/images/logo_upc.png" alt="Logo UPC">
  <div>
    <p class="font-serif font-bold text-sm text-primary">Revue Congolaise de Théologie Protestante</p>
    <p class="text-muted text-xs">UPC - Kinshasa</p>
  </div>
</div>
<h1 class="font-serif text-2xl font-bold mb-1"><?= htmlspecialchars(__('auth.register_title')) ?></h1>
<p class="text-muted text-sm mb-8"><?= htmlspecialchars(__('auth.register_subtitle')) ?></p>
<?php if ($error): ?>
<p class="text-sm mb-4" style="color: var(--destructive);"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<form method="post" action="<?= $base ?>/register" class="flex flex-col gap-5">
  <div class="grid-2" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
    <div class="form-group">
      <label for="prenom"><?= htmlspecialchars(__('auth.firstname')) ?></label>
      <input type="text" id="prenom" name="prenom" placeholder="<?= htmlspecialchars(__('auth.firstname_placeholder')) ?>" required class="h-11" value="<?= htmlspecialchars($old['prenom'] ?? '') ?>">
    </div>
    <div class="form-group">
      <label for="nom"><?= htmlspecialchars(__('auth.lastname')) ?></label>
      <input type="text" id="nom" name="nom" placeholder="<?= htmlspecialchars(__('auth.lastname_placeholder')) ?>" required class="h-11" value="<?= htmlspecialchars($old['nom'] ?? '') ?>">
    </div>
  </div>
  <div class="form-group">
    <label for="email"><?= htmlspecialchars(__('auth.email')) ?></label>
    <input type="email" id="email" name="email" placeholder="<?= htmlspecialchars(__('auth.email_placeholder')) ?>" required class="h-11" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
  </div>
  <div class="form-group">
    <label for="password"><?= htmlspecialchars(__('auth.password')) ?></label>
    <div class="password-wrap">
      <input type="password" id="password" name="password" placeholder="<?= htmlspecialchars(__('auth.password_placeholder_register')) ?>" required class="h-11 pr-10" minlength="8">
      <button type="button" class="password-toggle" aria-label="<?= htmlspecialchars(__('auth.show_password')) ?>"><svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#eye"/></svg></button>
    </div>
    <p class="text-muted text-xs"><?= htmlspecialchars(__('auth.password_min')) ?></p>
  </div>
  <button type="submit" class="btn btn-primary h-11"><?= htmlspecialchars(__('auth.submit_register')) ?></button>
</form>
<p class="text-center text-muted text-sm mt-8"><?= htmlspecialchars(__('auth.has_account')) ?> <a href="<?= $base ?>/login" class="text-primary font-medium"><?= htmlspecialchars(__('auth.submit_login')) ?></a></p>
<p class="text-center mt-8 pt-6 border-t text-xs"><a href="<?= $base ?>/" class="text-muted"><?= htmlspecialchars(__('auth.back_home')) ?></a></p>
