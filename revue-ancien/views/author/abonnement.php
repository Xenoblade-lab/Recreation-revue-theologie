<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abonnement & Paiements - Dashboard Auteur</title>
    <link rel="stylesheet" href="<?= Router\Router::$defaultUri ?>css/styles.css">
    <link rel="stylesheet" href="<?= Router\Router::$defaultUri ?>css/dashboard-styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <?php include __DIR__ . DIRECTORY_SEPARATOR . '_sidebar.php'; ?>
        
        <!-- Main Content -->
        <main class="dashboard-main">
            <!-- Header -->
            <div class="dashboard-header fade-up">
                <div class="header-title">
                    <h1>Abonnement & Paiements</h1>
                    <p>Gérez votre abonnement et consultez l'historique de vos paiements</p>
                </div>
            </div>

            <!-- Abonnement Status -->
            <?php if (isset($abonnement) && $abonnement): ?>
                <div class="content-card fade-up">
                    <div class="card-header">
                        <h2>Statut de l'abonnement</h2>
                    </div>
                    <div class="subscription-status">
                        <div class="status-badge <?= 
                            $abonnement['statut'] === 'actif' ? 'accepted' : 
                            (($abonnement['statut'] === 'expire' || $abonnement['statut'] === 'refuse') ? 'rejected' : 'pending')
                        ?>">
                            <?= ucfirst(str_replace('_', ' ', $abonnement['statut'])) ?>
                        </div>
                        <?php if ($abonnement['statut'] === 'actif'): ?>
                            <?php 
                            $daysLeft = (strtotime($abonnement['date_fin']) - time()) / (60 * 60 * 24);
                            $daysLeft = max(0, $daysLeft); // S'assurer que c'est positif
                            ?>
                            <div class="subscription-dates">
                                <p><strong>Date de début:</strong> <?= date('d M Y', strtotime($abonnement['date_debut'])) ?></p>
                                <p><strong>Date de fin:</strong> <?= date('d M Y', strtotime($abonnement['date_fin'])) ?></p>
                                <?php if ($daysLeft > 0): ?>
                                    <p class="days-left"><strong><?= ceil($daysLeft) ?> jours</strong> restants</p>
                                <?php else: ?>
                                    <p class="days-left" style="color: #dc2626;"><strong>Expiré</strong></p>
                                <?php endif; ?>
                            </div>
                            <div style="margin-top: 1.5rem; display: flex; gap: 1rem; flex-wrap: wrap;">
                                <?php if ($daysLeft <= 30 && $daysLeft > 0): ?>
                                    <button onclick="window.location.href='<?= Router\Router::route('author') ?>/subscribe'" class="btn btn-primary" style="padding: 0.75rem 1.5rem;">
                                        Renouveler l'abonnement
                                    </button>
                                <?php endif; ?>
                                <?php if ($daysLeft > 0): ?>
                                    <button onclick="cancelSubscription(<?= $abonnement['id'] ?>)" class="btn btn-danger" style="padding: 0.75rem 1.5rem; background: #dc2626; border-color: #dc2626;">
                                        Résilier l'abonnement
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php elseif ($abonnement['statut'] === 'expire'): ?>
                            <div style="margin-top: 1.5rem;">
                                <p style="color: #dc2626; margin-bottom: 1rem;">Votre abonnement a expiré. Renouvelez-le pour continuer à publier.</p>
                                <button onclick="window.location.href='<?= Router\Router::route('author') ?>/subscribe'" class="btn btn-primary" style="padding: 0.75rem 1.5rem;">
                                    Renouveler l'abonnement
                                </button>
                            </div>
                        <?php elseif ($abonnement['statut'] === 'en_attente'): ?>
                            <div style="margin-top: 1.5rem;">
                                <p style="color: #f59e0b; margin-bottom: 1rem;">Votre demande d'abonnement est en attente de validation.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="content-card fade-up">
                    <div class="empty-state">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        <h3>Aucun abonnement actif</h3>
                        <p>Vous n'avez pas d'abonnement actif pour le moment. Abonnez-vous pour devenir auteur et publier vos articles.</p>
                        <button onclick="window.location.href='<?= Router\Router::route('author') ?>/subscribe'" class="btn btn-primary" style="margin-top: 1.5rem; padding: 0.75rem 2rem;">
                            S'abonner maintenant
                        </button>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Paiements History -->
            <div class="content-card fade-up">
                <div class="card-header">
                    <h2>Historique des paiements</h2>
                </div>
                
                <?php if (isset($paiements) && !empty($paiements)): ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Moyen de paiement</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Initialiser le tableau JavaScript pour stocker les données des paiements
                            $paiementsJson = [];
                            foreach ($paiements as $paiement): 
                                $paiementsJson[$paiement['id']] = $paiement;
                            ?>
                                <tr>
                                    <td><?= date('d M Y', strtotime($paiement['date_paiement'] ?? $paiement['created_at'])) ?></td>
                                    <td><strong><?= number_format($paiement['montant'], 2, ',', ' ') ?> $</strong></td>
                                    <td><?= ucfirst(str_replace('_', ' ', $paiement['moyen'])) ?></td>
                                    <td>
                                        <span class="status-badge <?= 
                                            $paiement['statut'] === 'valide' ? 'accepted' : 
                                            ($paiement['statut'] === 'refuse' ? 'rejected' : 'pending')
                                        ?>">
                                            <?= ucfirst(str_replace('_', ' ', $paiement['statut'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                                            <!-- Bouton Voir les détails pour tous les paiements -->
                                            <button onclick="showPaymentDetails(<?= $paiement['id'] ?>)" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 0.25rem;">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                                Détails
                                            </button>
                                            
                                            <!-- Bouton Télécharger le reçu -->
                                            <?php if (!empty($paiement['recu_path'])): ?>
                                                <!-- Si le reçu existe, afficher le lien de téléchargement -->
                                                <a href="<?= Router\Router::$defaultUri . htmlspecialchars($paiement['recu_path']) ?>" class="btn btn-outline" target="_blank" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 0.25rem;">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                        <polyline points="7 10 12 15 17 10"></polyline>
                                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                                    </svg>
                                                    Télécharger le reçu
                                                </a>
                                            <?php else: ?>
                                                <!-- Si le reçu n'existe pas, permettre de le générer/télécharger -->
                                                <button onclick="downloadReceipt(<?= $paiement['id'] ?>)" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 0.25rem;">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                        <polyline points="7 10 12 15 17 10"></polyline>
                                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                                    </svg>
                                                    Télécharger le reçu
                                                </button>
                                            <?php endif; ?>
                                            
                                            <!-- Bouton Annuler pour les paiements en attente -->
                                            <?php if ($paiement['statut'] === 'en_attente'): ?>
                                                <button onclick="cancelPayment(<?= $paiement['id'] ?>)" class="btn btn-danger" style="padding: 0.5rem 1rem; font-size: 0.875rem; background: #dc2626; border-color: #dc2626;">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 0.25rem;">
                                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                                    </svg>
                                                    Annuler
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        <h3>Aucun paiement enregistré</h3>
                        <p>Vous n'avez pas encore effectué de paiement.</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
    
    <!-- Script pour initialiser les données des paiements -->
    <script>
        // Initialiser le tableau des paiements pour JavaScript
        window.paiementsData = <?= json_encode($paiementsJson ?? []) ?>;
    </script>

    <!-- Modal pour afficher les détails du paiement -->
    <div id="paymentDetailsModal" class="modal" style="display: none;">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h2>Détails du paiement</h2>
                <span class="close-modal" onclick="closePaymentModal()">&times;</span>
            </div>
            <div class="modal-body" id="paymentDetailsContent">
                <!-- Le contenu sera rempli dynamiquement -->
            </div>
            <div class="modal-footer">
                <button onclick="closePaymentModal()" class="btn btn-outline">Fermer</button>
            </div>
        </div>
    </div>

    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 0;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-header h2 {
            margin: 0;
            font-size: 1.5rem;
            color: #1f2937;
        }
        .close-modal {
            color: #6b7280;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            line-height: 1;
        }
        .close-modal:hover {
            color: #1f2937;
        }
        .modal-body {
            padding: 1.5rem;
        }
        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }
        .payment-detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .payment-detail-row:last-child {
            border-bottom: none;
        }
        .payment-detail-label {
            font-weight: 600;
            color: #6b7280;
        }
        .payment-detail-value {
            color: #1f2937;
            text-align: right;
        }
    </style>

    <script src="<?= Router\Router::$defaultUri ?>js/script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/author-notify.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/dashboard-script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/user-dropdown.js"></script>
    <script>
        // Fonction pour résilier l'abonnement
        async function cancelSubscription(abonnementId) {
            const ok = await showConfirm({
                title: 'Résilier l\'abonnement',
                message: 'Êtes-vous sûr de vouloir résilier votre abonnement ? Cette action est irréversible et vous perdrez votre statut d\'auteur.',
                confirmText: 'Résilier',
                cancelText: 'Annuler'
            });
            if (!ok) return;
            
            try {
                const response = await fetch('<?= Router\Router::route("author") ?>/abonnement/cancel', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ abonnement_id: abonnementId })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showToast(data.message || 'Abonnement résilié avec succès', 'success');
                    window.location.reload();
                } else {
                    showToast(data.error || 'Erreur lors de la résiliation de l\'abonnement', 'error');
                }
            } catch (error) {
                console.error('Erreur:', error);
                showToast('Une erreur est survenue. Veuillez réessayer.', 'error');
            }
        }
        
        // Fonction pour annuler un paiement
        async function cancelPayment(paiementId) {
            const ok = await showConfirm({
                title: 'Annuler le paiement',
                message: 'Êtes-vous sûr de vouloir annuler ce paiement ?',
                confirmText: 'Annuler le paiement',
                cancelText: 'Retour'
            });
            if (!ok) return;
            
            try {
                const response = await fetch('<?= Router\Router::route("author") ?>/paiement/cancel', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ paiement_id: paiementId })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showToast(data.message || 'Paiement annulé avec succès', 'success');
                    window.location.reload();
                } else {
                    showToast(data.error || 'Erreur lors de l\'annulation du paiement', 'error');
                }
            } catch (error) {
                console.error('Erreur:', error);
                showToast('Une erreur est survenue. Veuillez réessayer.', 'error');
            }
        }
        
        // Fonction pour télécharger le reçu
        async function downloadReceipt(paiementId) {
            try {
                const response = await fetch('<?= Router\Router::route("author") ?>/paiement/receipt/' + paiementId, {
                    method: 'GET',
                });
                
                if (response.ok) {
                    // Récupérer le blob (HTML ou autre format)
                    const blob = await response.blob();
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'recu_paiement_' + paiementId + '.html';
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                } else {
                    // Si ce n'est pas OK, essayer de lire le texte de l'erreur
                    const text = await response.text();
                    let errorMessage = 'Erreur lors du téléchargement du reçu';
                    
                    // Essayer de parser comme JSON si possible
                    try {
                        const data = JSON.parse(text);
                        errorMessage = data.error || errorMessage;
                    } catch (e) {
                        // Si ce n'est pas du JSON, utiliser le texte brut ou un message par défaut
                        if (text.includes('error') || text.includes('Error')) {
                            errorMessage = 'Erreur serveur: ' + text.substring(0, 100);
                        }
                    }
                    
                    showToast(errorMessage, 'error');
                }
            } catch (error) {
                console.error('Erreur:', error);
                showToast('Une erreur est survenue lors du téléchargement du reçu: ' + error.message, 'error');
            }
        }
        
        // Fonction pour afficher les détails du paiement
        function showPaymentDetails(paiementId) {
            const modal = document.getElementById('paymentDetailsModal');
            const content = document.getElementById('paymentDetailsContent');
            
            // Récupérer les données du paiement
            const paiement = window.paiementsData && window.paiementsData[paiementId];
            if (!paiement) {
                showToast('Impossible de charger les détails du paiement', 'error');
                return;
            }
            
            // Formater la date
            const datePaiement = paiement.date_paiement || paiement.created_at;
            const formattedDate = new Date(datePaiement).toLocaleDateString('fr-FR', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            // Formater le moyen de paiement
            const moyenPaiement = paiement.moyen ? paiement.moyen.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) : 'Non spécifié';
            
            // Formater le statut
            const statut = paiement.statut ? paiement.statut.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) : 'Non spécifié';
            
            // Formater le montant
            const montant = paiement.montant ? parseFloat(paiement.montant).toFixed(2).replace('.', ',') + ' $' : '0,00 $';
            
            // Construire le HTML
            let html = `
                <div class="payment-detail-row">
                    <span class="payment-detail-label">Date du paiement</span>
                    <span class="payment-detail-value">${formattedDate}</span>
                </div>
                <div class="payment-detail-row">
                    <span class="payment-detail-label">Montant</span>
                    <span class="payment-detail-value"><strong>${montant}</strong></span>
                </div>
                <div class="payment-detail-row">
                    <span class="payment-detail-label">Moyen de paiement</span>
                    <span class="payment-detail-value">${moyenPaiement}</span>
                </div>
                <div class="payment-detail-row">
                    <span class="payment-detail-label">Statut</span>
                    <span class="payment-detail-value">
                        <span class="status-badge ${paiement.statut === 'valide' ? 'accepted' : (paiement.statut === 'refuse' ? 'rejected' : 'pending')}">
                            ${statut}
                        </span>
                    </span>
                </div>
            `;
            
            // Ajouter les informations supplémentaires si disponibles
            if (paiement.numero_transaction) {
                html += `
                    <div class="payment-detail-row">
                        <span class="payment-detail-label">Numéro de transaction</span>
                        <span class="payment-detail-value">${paiement.numero_transaction}</span>
                    </div>
                `;
            }
            
            if (paiement.region) {
                html += `
                    <div class="payment-detail-row">
                        <span class="payment-detail-label">Région</span>
                        <span class="payment-detail-value">${paiement.region.replace(/\b\w/g, l => l.toUpperCase())}</span>
                    </div>
                `;
            }
            
            if (paiement.numero_telephone) {
                html += `
                    <div class="payment-detail-row">
                        <span class="payment-detail-label">Numéro de téléphone</span>
                        <span class="payment-detail-value">${paiement.numero_telephone}</span>
                    </div>
                `;
            }
            
            if (paiement.numero_carte) {
                // Masquer partiellement le numéro de carte pour la sécurité
                const cardNumber = paiement.numero_carte.toString();
                const maskedCard = '**** **** **** ' + cardNumber.slice(-4);
                html += `
                    <div class="payment-detail-row">
                        <span class="payment-detail-label">Numéro de carte</span>
                        <span class="payment-detail-value">${maskedCard}</span>
                    </div>
                `;
            }
            
            if (paiement.created_at) {
                const createdAt = new Date(paiement.created_at).toLocaleDateString('fr-FR', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                html += `
                    <div class="payment-detail-row">
                        <span class="payment-detail-label">Date de création</span>
                        <span class="payment-detail-value">${createdAt}</span>
                    </div>
                `;
            }
            
            if (paiement.updated_at && paiement.updated_at !== paiement.created_at) {
                const updatedAt = new Date(paiement.updated_at).toLocaleDateString('fr-FR', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                html += `
                    <div class="payment-detail-row">
                        <span class="payment-detail-label">Dernière mise à jour</span>
                        <span class="payment-detail-value">${updatedAt}</span>
                    </div>
                `;
            }
            
            content.innerHTML = html;
            modal.style.display = 'block';
        }
        
        // Fonction pour fermer le modal
        function closePaymentModal() {
            const modal = document.getElementById('paymentDetailsModal');
            modal.style.display = 'none';
        }
        
        // Fermer le modal en cliquant en dehors
        window.onclick = function(event) {
            const modal = document.getElementById('paymentDetailsModal');
            if (event.target === modal) {
                closePaymentModal();
            }
        }
    </script>
</body>
</html>

