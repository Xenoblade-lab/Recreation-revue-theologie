<?php
$base = $base ?? '';
$error = $error ?? null;
?>
<div class="mobile-logo">
  <img src="<?= $base ?>/images/logo_upc.png" alt="Logo UPC">
  <div>
    <p class="font-serif font-bold text-sm text-primary">Revue de Théologie</p>
    <p class="text-muted text-xs">UPC - Kinshasa</p>
  </div>
</div>
<h1 class="font-serif text-2xl font-bold mb-1"><?= htmlspecialchars(__('auth.login_title')) ?></h1>
<p class="text-muted text-sm mb-8"><?= htmlspecialchars(__('auth.login_subtitle')) ?></p>
<?php if ($error): ?>
<p class="text-sm mb-4" style="color: var(--destructive);"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<form method="post" action="<?= $base ?>/login" class="flex flex-col gap-5">
  <div class="form-group">
    <label for="email"><?= htmlspecialchars(__('auth.email')) ?></label>
    <input type="email" id="email" name="email" placeholder="<?= htmlspecialchars(__('auth.email_placeholder')) ?>" required class="h-11" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
  </div>
  <div class="form-group">
    <div class="flex justify-between">
      <label for="password"><?= htmlspecialchars(__('auth.password')) ?></label>
      <a href="<?= $base ?>/forgot-password" class="text-xs text-primary"><?= htmlspecialchars(__('auth.forgot_password')) ?></a>
    </div>
    <div class="password-wrap">
      <input type="password" id="password" name="password" placeholder="<?= htmlspecialchars(__('auth.password_placeholder')) ?>" required class="h-11 pr-10">
      <button type="button" class="password-toggle" aria-label="<?= htmlspecialchars(__('auth.show_password')) ?>"><svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#eye"/></svg></button>
    </div>
  </div>
  <button type="submit" class="btn btn-primary h-11"><?= htmlspecialchars(__('auth.submit_login')) ?></button>
</form>
<p class="text-center text-muted text-sm mt-8"><?= htmlspecialchars(__('auth.no_account')) ?> <a href="<?= $base ?>/register" class="text-primary font-medium"><?= htmlspecialchars(__('auth.create_account')) ?></a></p>
<p class="text-center mt-8 pt-6 border-t text-xs"><a href="<?= $base ?>/" class="text-muted"><?= htmlspecialchars(__('auth.back_home')) ?></a></p>
<p class="text-center mt-2 text-xs text-muted"><?= htmlspecialchars(__('auth.access')) ?> <a href="<?= $base ?>/admin"><?= htmlspecialchars(__('auth.role_admin')) ?></a> · <a href="<?= $base ?>/author"><?= htmlspecialchars(__('auth.role_author')) ?></a> · <a href="<?= $base ?>/reviewer"><?= htmlspecialchars(__('auth.role_reviewer')) ?></a></p>
