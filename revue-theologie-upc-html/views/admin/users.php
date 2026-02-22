<?php
$users = $users ?? [];
$base = $base ?? '';

$roleLabels = [
    'admin' => 'Administrateur',
    'auteur' => 'Auteur',
    'redacteur' => 'Rédacteur',
    'redacteur en chef' => 'Rédacteur en chef',
    'user' => 'Utilisateur',
];
?>
<div class="dashboard-header flex flex-wrap items-center justify-between gap-4">
  <div>
    <h1>Utilisateurs</h1>
    <p>Gérer les comptes utilisateurs.</p>
  </div>
  <a href="<?= $base ?>/admin/users/create" class="btn btn-primary">Créer un utilisateur</a>
</div>
<div class="dashboard-card">
  <div class="overflow-auto">
    <table class="dashboard-table">
      <thead>
        <tr>
          <th>Nom</th>
          <th>Email</th>
          <th>Rôle</th>
          <th>Statut</th>
          <th>Inscrit le</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($users)): ?>
          <tr><td colspan="6" class="text-muted">Aucun utilisateur.</td></tr>
        <?php else: ?>
          <?php foreach ($users as $u): ?>
            <tr>
              <td><?= htmlspecialchars(trim(($u['prenom'] ?? '') . ' ' . ($u['nom'] ?? ''))) ?></td>
              <td><?= htmlspecialchars($u['email'] ?? '') ?></td>
              <td><?= htmlspecialchars($roleLabels[$u['role'] ?? ''] ?? $u['role'] ?? '') ?></td>
              <td><span class="badge <?= ($u['statut'] ?? '') === 'actif' ? 'badge green' : 'badge' ?>"><?= htmlspecialchars($u['statut'] ?? '') ?></span></td>
              <td><?= !empty($u['created_at']) ? date('d/m/Y', strtotime($u['created_at'])) : '—' ?></td>
              <td><a href="<?= $base ?>/admin/users/<?= (int) ($u['id'] ?? 0) ?>/edit" class="btn btn-sm btn-outline">Modifier</a></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
