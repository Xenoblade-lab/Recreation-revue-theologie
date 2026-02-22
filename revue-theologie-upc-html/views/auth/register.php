<?php
$base = $base ?? '';
$error = $error ?? null;
$old = $old ?? [];
?>
<div class="mobile-logo">
  <img src="<?= $base ?>/images/logo_upc.png" alt="Logo UPC">
  <div>
    <p class="font-serif font-bold text-sm text-primary">Revue de Théologie</p>
    <p class="text-muted text-xs">UPC - Kinshasa</p>
  </div>
</div>
<h1 class="font-serif text-2xl font-bold mb-1">Créer un compte</h1>
<p class="text-muted text-sm mb-8">Inscrivez-vous pour soumettre vos articles et suivre vos publications.</p>
<?php if ($error): ?>
<p class="text-sm mb-4" style="color: var(--destructive);"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<form method="post" action="<?= $base ?>/register" class="flex flex-col gap-5">
  <div class="grid-2" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
    <div class="form-group">
      <label for="prenom">Prénom</label>
      <input type="text" id="prenom" name="prenom" placeholder="Votre prénom" required class="h-11" value="<?= htmlspecialchars($old['prenom'] ?? '') ?>">
    </div>
    <div class="form-group">
      <label for="nom">Nom</label>
      <input type="text" id="nom" name="nom" placeholder="Votre nom" required class="h-11" value="<?= htmlspecialchars($old['nom'] ?? '') ?>">
    </div>
  </div>
  <div class="form-group">
    <label for="email">Adresse email</label>
    <input type="email" id="email" name="email" placeholder="votre@email.com" required class="h-11" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
  </div>
  <div class="form-group">
    <label for="password">Mot de passe</label>
    <div class="password-wrap">
      <input type="password" id="password" name="password" placeholder="Choisissez un mot de passe" required class="h-11 pr-10" minlength="8">
      <button type="button" class="password-toggle" aria-label="Afficher le mot de passe"><svg class="icon-svg icon-20" aria-hidden="true"><use href="<?= $base ?>/images/icons.svg#eye"/></svg></button>
    </div>
    <p class="text-muted text-xs">Minimum 8 caractères</p>
  </div>
  <button type="submit" class="btn btn-primary h-11">Créer mon compte</button>
</form>
<p class="text-center text-muted text-sm mt-8">Déjà un compte ? <a href="<?= $base ?>/login" class="text-primary font-medium">Se connecter</a></p>
<p class="text-center mt-8 pt-6 border-t text-xs"><a href="<?= $base ?>/" class="text-muted">Retour à l'accueil</a></p>
