<?php
$user = $user ?? null;
$error = $error ?? null;
$success = $success ?? false;
$base = $base ?? '';
if (!$user) return;
?>
<div class="dashboard-header">
  <h1><?= htmlspecialchars(__('reviewer.profil_title')) ?></h1>
  <p><?= htmlspecialchars(__('reviewer.profil_intro')) ?></p>
</div>
<?php if ($error): ?>
<p class="text-accent mb-4"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<?php if ($success): ?>
<p class="mb-4" style="color: var(--primary);"><?= htmlspecialchars(__('reviewer.profil_success')) ?></p>
<?php endif; ?>
<div class="dashboard-card">
  <form method="post" action="<?= $base ?>/reviewer/profil" class="space-y-4">
    <?= csrf_field() ?>
    <div>
      <label for="nom" class="block font-medium mb-1"><?= htmlspecialchars(__('reviewer.profil_nom')) ?> *</label>
      <input type="text" id="nom" name="nom" required value="<?= htmlspecialchars($user['nom'] ?? '') ?>"
             class="w-full px-3 py-2 border rounded" maxlength="255">
    </div>
    <div>
      <label for="prenom" class="block font-medium mb-1"><?= htmlspecialchars(__('reviewer.profil_prenom')) ?> *</label>
      <input type="text" id="prenom" name="prenom" required value="<?= htmlspecialchars($user['prenom'] ?? '') ?>"
             class="w-full px-3 py-2 border rounded" maxlength="255">
    </div>
    <div>
      <label for="email" class="block font-medium mb-1"><?= htmlspecialchars(__('reviewer.profil_email')) ?> *</label>
      <input type="email" id="email" name="email" required value="<?= htmlspecialchars($user['email'] ?? '') ?>"
             class="w-full px-3 py-2 border rounded" maxlength="255">
    </div>
    <div>
      <label for="password" class="block font-medium mb-1"><?= htmlspecialchars(__('reviewer.profil_password')) ?></label>
      <input type="password" id="password" name="password" class="w-full px-3 py-2 border rounded" minlength="6"
             placeholder="<?= htmlspecialchars(__('reviewer.profil_password_placeholder')) ?>"
             autocomplete="new-password">
      <p class="text-sm text-muted mt-1"><?= htmlspecialchars(__('reviewer.profil_password_hint')) ?></p>
    </div>
    <div class="flex gap-3">
      <button type="submit" class="btn btn-primary"><?= htmlspecialchars(__('reviewer.profil_save')) ?></button>
      <a href="<?= $base ?>/reviewer" class="btn btn-outline-primary"><?= htmlspecialchars(__('reviewer.back_dashboard')) ?></a>
    </div>
  </form>
</div>
