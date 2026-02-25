<?php
$base = $base ?? '';
$error = $error ?? null;
$success = $success ?? false;
?>
<div class="mobile-logo">
  <img src="<?= $base ?>/images/logo_upc.png" alt="Logo UPC">
  <div>
    <p class="font-serif font-bold text-sm text-primary">Revue Congolaise de Théologie Protestante</p>
    <p class="text-muted text-xs">UPC - Kinshasa</p>
  </div>
</div>
<h1 class="font-serif text-2xl font-bold mb-1"><?= htmlspecialchars(__('auth.forgot_title')) ?></h1>
<p class="text-muted text-sm mb-8"><?= htmlspecialchars(__('auth.forgot_subtitle')) ?></p>
<?php if ($success): ?>
<p class="text-sm mb-6" style="color: var(--primary);"><?= htmlspecialchars(__('auth.forgot_success')) ?></p>
<p class="text-center"><a href="<?= $base ?>/login" class="btn btn-primary"><?= htmlspecialchars(__('auth.back_login')) ?></a></p>
<?php else: ?>
<?php if ($error): ?>
<p class="text-sm mb-4" style="color: var(--destructive);"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<form method="post" action="<?= $base ?>/forgot-password" class="flex flex-col gap-5">
  <?= csrf_field() ?>
  <div class="form-group">
    <label for="email"><?= htmlspecialchars(__('auth.email_short')) ?></label>
    <input type="email" id="email" name="email" placeholder="<?= htmlspecialchars(__('auth.email_placeholder')) ?>" required class="h-11">
  </div>
  <button type="submit" class="btn btn-primary h-11"><?= htmlspecialchars(__('auth.send_link')) ?></button>
</form>
<?php endif; ?>
<p class="text-center mt-8 pt-6 border-t text-xs"><a href="<?= $base ?>/login" class="text-primary text-sm"><?= htmlspecialchars(__('auth.back_login')) ?></a></p>
