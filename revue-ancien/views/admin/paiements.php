<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiements - Admin</title>
    <link rel="stylesheet" href="<?= Router\Router::$defaultUri ?>css/styles.css">
    <link rel="stylesheet" href="<?= Router\Router::$defaultUri ?>css/dashboard-styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-layout">
        <?php include __DIR__ . DIRECTORY_SEPARATOR . '_sidebar.php'; ?>

        <main class="dashboard-main">
            <div class="dashboard-header fade-up">
                <div class="header-title">
                    <h1>Paiements</h1>
                    <p>Gestion des paiements validés / en attente</p>
                </div>
            </div>

            <div class="content-card fade-up">
                <div class="card-header">
                    <h2>Liste des paiements</h2>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Utilisateur</th>
                            <th>Email</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Date paiement</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($paiements)): ?>
                            <?php foreach ($paiements as $p): ?>
                                <tr>
                                    <td><?= htmlspecialchars($p['id']) ?></td>
                                    <td><?= htmlspecialchars(trim(($p['prenom'] ?? '') . ' ' . ($p['nom'] ?? ''))) ?></td>
                                    <td><?= htmlspecialchars($p['email'] ?? '') ?></td>
                                    <td><?= number_format($p['montant'] ?? 0, 2, ',', ' ') ?> $</td>
                                    <td><span class="status-badge <?= 
                                        ($p['statut'] ?? '') === 'valide' ? 'accepted' : 
                                        (($p['statut'] ?? '') === 'refuse' ? 'rejected' : 'pending')
                                    ?>"><?= htmlspecialchars($p['statut'] ?? '') ?></span></td>
                                    <td><?= !empty($p['date_paiement']) ? date('d M Y', strtotime($p['date_paiement'])) : '—' ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <?php if (($p['statut'] ?? '') === 'en_attente'): ?>
                                                <button class="action-btn edit" title="Valider" onclick="updatePaymentStatus(<?= $p['id'] ?>, 'valide')" style="color: var(--color-green);">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                                <button class="action-btn delete" title="Refuser" onclick="updatePaymentStatus(<?= $p['id'] ?>, 'refuse')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            <?php endif; ?>
                                            <button class="action-btn view" title="Voir les détails" onclick="viewPayment(<?= $p['id'] ?>)">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" style="text-align:center; padding:1.5rem;">Aucun paiement trouvé.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <button class="mobile-menu-btn" id="mobile-menu-btn">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <style>
        /* Mode compact (admin/paiements) */
        .dashboard-main { padding: 1.25rem; overflow-x: hidden; }

        .dashboard-header { margin-bottom: 1rem; flex-wrap: wrap; gap: 0.75rem; }
        .dashboard-header .header-title h1 { font-size: 1.5rem; line-height: 1.2; margin-bottom: 0.25rem; }
        .dashboard-header .header-title p { font-size: 0.95rem; }

        .content-card .card-header { padding: 0.9rem 1rem; flex-wrap: wrap; gap: 0.75rem; }
        .content-card .card-header h2 { font-size: 1.1rem; }

        .data-table th, .data-table td { padding: 0.6rem 0.75rem; font-size: 0.92rem; }

        .action-buttons { gap: 0.4rem; }
        .action-btn { width: 34px; height: 34px; }

        /* Si l'écran est étroit, autoriser le scroll horizontal du tableau */
        .content-card { overflow-x: auto; }
        .data-table { min-width: 900px; }

        @media (max-width: 768px) {
            .dashboard-main { padding: 1rem; }
        }
    </style>

    <script src="<?= Router\Router::$defaultUri ?>js/script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/dashboard-script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/user-dropdown.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/author-notify.js"></script>
    <script>
        function viewPayment(id) {
            showToast('Détails du paiement #' + id + ' (Fonctionnalité à implémenter)', 'info');
        }

        async function updatePaymentStatus(id, statut) {
            const action = statut === 'valide' ? 'valider' : 'refuser';
            const ok = typeof showConfirm === 'function'
                ? await showConfirm({
                    title: 'Confirmation',
                    message: 'Êtes-vous sûr de vouloir ' + action + ' ce paiement ?',
                    confirmText: action === 'valider' ? 'Valider' : 'Refuser',
                    cancelText: 'Annuler'
                })
                : confirm('Êtes-vous sûr de vouloir ' + action + ' ce paiement ?');
            if (!ok) return;

            fetch('<?= Router\Router::route("admin") ?>/paiement/' + id + '/update-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ statut: statut })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success || data.message) {
                    showToast(data.message || 'Statut mis à jour avec succès', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast(data.error || 'Une erreur est survenue', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Une erreur est survenue', 'error');
            });
        }
    </script>
</body>
</html>

