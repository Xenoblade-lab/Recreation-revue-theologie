<?php
$user = $user ?? null;
$error = $error ?? null;
$old = $old ?? [];
$base = $base ?? '';
$isEdit = $user !== null;

$nom = $isEdit ? ($user['nom'] ?? '') : ($old['nom'] ?? '');
$prenom = $isEdit ? ($user['prenom'] ?? '') : ($old['prenom'] ?? '');
$email = $isEdit ? ($user['email'] ?? '') : ($old['email'] ?? '');
$role = $isEdit ? ($user['role'] ?? 'user') : ($old['role'] ?? 'user');
$statut = $isEdit ? ($user['statut'] ?? 'actif') : 'actif';
?>
<div class="dashboard-header">
  <h1><?= $isEdit ? 'Modifier l\'utilisateur' : 'Créer un utilisateur' ?></h1>
  <p><a href="<?= $base ?>/admin/users" class="text-primary">← Retour à la liste</a></p>
</div>
<div class="dashboard-card">
  <?php if ($error): ?>
    <div class="alert alert-error mb-4"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <form method="post" action="<?= $isEdit ? $base . '/admin/users/' . (int) $user['id'] . '/edit' : $base . '/admin/users/create' ?>" class="space-y-4">
    <div>
      <label for="nom" class="block text-sm font-medium mb-1">Nom</label>
      <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($nom) ?>" required class="input w-full">
    </div>
    <div>
      <label for="prenom" class="block text-sm font-medium mb-1">Prénom</label>
      <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($prenom) ?>" required class="input w-full">
    </div>
    <div>
      <label for="email" class="block text-sm font-medium mb-1">Email</label>
      <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required class="input w-full" <?= $isEdit ? '' : 'autocomplete="email"' ?>>
    </div>
    <div>
      <label for="role" class="block text-sm font-medium mb-1">Rôle</label>
      <select id="role" name="role" class="input w-full">
        <option value="user" <?= $role === 'user' ? 'selected' : '' ?>>Utilisateur</option>
        <option value="auteur" <?= $role === 'auteur' ? 'selected' : '' ?>>Auteur</option>
        <option value="redacteur" <?= $role === 'redacteur' ? 'selected' : '' ?>>Rédacteur</option>
        <option value="redacteur en chef" <?= $role === 'redacteur en chef' ? 'selected' : '' ?>>Rédacteur en chef</option>
        <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Administrateur</option>
      </select>
    </div>
    <?php if ($isEdit): ?>
    <div>
      <label for="statut" class="block text-sm font-medium mb-1">Statut</label>
      <select id="statut" name="statut" class="input w-full">
        <option value="actif" <?= $statut === 'actif' ? 'selected' : '' ?>>Actif</option>
        <option value="inactif" <?= $statut === 'inactif' ? 'selected' : '' ?>>Inactif</option>
      </select>
    </div>
    <div>
      <label for="password" class="block text-sm font-medium mb-1">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
      <input type="password" id="password" name="password" class="input w-full" autocomplete="new-password">
    </div>
    <?php else: ?>
    <div>
      <label for="password" class="block text-sm font-medium mb-1">Mot de passe</label>
      <input type="password" id="password" name="password" required minlength="6" class="input w-full" autocomplete="new-password">
    </div>
    <?php endif; ?>
    <div class="flex gap-2">
      <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Enregistrer' : 'Créer' ?></button>
      <a href="<?= $base ?>/admin/users" class="btn btn-outline">Annuler</a>
    </div>
  </form>
</div>
