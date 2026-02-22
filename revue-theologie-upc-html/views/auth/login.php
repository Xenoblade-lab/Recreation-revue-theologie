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
<h1 class="font-serif text-2xl font-bold mb-1">Connexion</h1>
<p class="text-muted text-sm mb-8">Accédez à votre espace personnel pour gérer vos soumissions.</p>
<?php if ($error): ?>
<p class="text-sm mb-4" style="color: var(--destructive);"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<form method="post" action="<?= $base ?>/login" class="flex flex-col gap-5">
  <div class="form-group">
    <label for="email">Adresse email</label>
    <input type="email" id="email" name="email" placeholder="votre@email.com" required class="h-11" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
  </div>
  <div class="form-group">
    <div class="flex justify-between">
      <label for="password">Mot de passe</label>
      <a href="<?= $base ?>/forgot-password" class="text-xs text-primary">Mot de passe oublié ?</a>
    </div>
    <div class="password-wrap">
      <input type="password" id="password" name="password" placeholder="Votre mot de passe" required class="h-11 pr-10">
      <button type="button" class="password-toggle" aria-label="Afficher le mot de passe"><svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#eye"/></svg></button>
    </div>
  </div>
  <button type="submit" class="btn btn-primary h-11">Se connecter</button>
</form>
<p class="text-center text-muted text-sm mt-8">Pas encore de compte ? <a href="<?= $base ?>/register" class="text-primary font-medium">Créer un compte</a></p>
<p class="text-center mt-8 pt-6 border-t text-xs"><a href="<?= $base ?>/" class="text-muted">Retour à l'accueil</a></p>
<p class="text-center mt-2 text-xs text-muted">Accès : <a href="<?= $base ?>/admin">Admin</a> · <a href="<?= $base ?>/author">Auteur</a> · <a href="<?= $base ?>/reviewer">Évaluateur</a></p>
