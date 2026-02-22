<?php
$base = $base ?? '';
$error = $error ?? null;
$success = $success ?? false;
?>
<div class="mobile-logo">
  <img src="<?= $base ?>/images/logo_upc.png" alt="Logo UPC">
  <div>
    <p class="font-serif font-bold text-sm text-primary">Revue de Théologie</p>
    <p class="text-muted text-xs">UPC - Kinshasa</p>
  </div>
</div>
<h1 class="font-serif text-2xl font-bold mb-1">Mot de passe oublié</h1>
<p class="text-muted text-sm mb-8">Indiquez votre adresse email pour recevoir un lien de réinitialisation.</p>
<?php if ($success): ?>
<p class="text-sm mb-6" style="color: var(--primary);">Si un compte existe pour cette adresse, vous recevrez un email avec les instructions. Vérifiez aussi vos spams.</p>
<p class="text-center"><a href="<?= $base ?>/login" class="btn btn-primary">Retour à la connexion</a></p>
<?php else: ?>
<?php if ($error): ?>
<p class="text-sm mb-4" style="color: var(--destructive);"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<form method="post" action="<?= $base ?>/forgot-password" class="flex flex-col gap-5">
  <div class="form-group">
    <label for="email">Email</label>
    <input type="email" id="email" name="email" placeholder="votre@email.com" required class="h-11">
  </div>
  <button type="submit" class="btn btn-primary h-11">Envoyer le lien</button>
</form>
<?php endif; ?>
<p class="text-center mt-8 pt-6 border-t text-xs"><a href="<?= $base ?>/login" class="text-primary text-sm">Retour à la connexion</a></p>
