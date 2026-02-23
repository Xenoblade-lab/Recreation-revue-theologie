<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S'abonner - Revue Congolaise de Théologie protestante</title>
    <link rel="stylesheet" href="<?= Router\Router::$defaultUri ?>css/styles.css">
    <link rel="stylesheet" href="<?= Router\Router::$defaultUri ?>css/dashboard-styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .subscribe-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
        }
        .subscribe-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        .subscribe-header h1 {
            font-size: 2.5rem;
            color: var(--color-primary, #2563eb);
            margin-bottom: 1rem;
        }
        .subscribe-header p {
            font-size: 1.1rem;
            color: #666;
        }
        .pricing-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        .pricing-card {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
        }
        .pricing-card:hover {
            border-color: var(--color-primary, #2563eb);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            transform: translateY(-5px);
        }
        .pricing-card.selected {
            border-color: var(--color-primary, #2563eb);
            background: #eff6ff;
        }
        .pricing-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #1f2937;
        }
        .pricing-card .price {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--color-primary, #2563eb);
            margin: 1rem 0;
        }
        .pricing-card .price span {
            font-size: 1rem;
            color: #666;
        }
        .subscribe-btn {
            width: 100%;
            padding: 1rem 2rem;
            background: var(--color-primary, #2563eb);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 2rem;
            transition: all 0.3s;
        }
        .subscribe-btn:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
        }
        .subscribe-btn:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }
        
        /* Modal Styles */
        .payment-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 10000;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .payment-modal.active {
            display: flex;
        }
        .payment-modal-content {
            background: white;
            border-radius: 16px;
            max-width: 600px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            padding: 2rem;
            position: relative;
        }
        .payment-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .payment-modal-header h2 {
            font-size: 1.8rem;
            color: #1f2937;
        }
        .close-modal {
            background: none;
            border: none;
            font-size: 2rem;
            color: #9ca3af;
            cursor: pointer;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .close-modal:hover {
            color: #1f2937;
        }
        .payment-options {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .payment-option {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: white;
        }
        .payment-option:hover {
            border-color: var(--color-primary, #2563eb);
            transform: translateY(-2px);
        }
        .payment-option.selected {
            border-color: var(--color-primary, #2563eb);
            background: #eff6ff;
        }
        .payment-option img {
            width: 100px;
            height: 60px;
            object-fit: contain;
            margin: 0 auto 0.5rem;
            display: block;
        }
        .payment-option .logo-placeholder {
            width: 100px;
            height: 60px;
            margin: 0 auto 0.5rem;
            background: #f3f4f6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #6b7280;
        }
        .payment-form {
            display: none;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #e5e7eb;
        }
        .payment-form.active {
            display: block;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #1f2937;
        }
        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            outline: none;
            border-color: var(--color-primary, #2563eb);
        }
        .form-group .help-text {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .payment-option h4 {
            font-size: 1rem;
            margin: 0.5rem 0 0;
            color: #1f2937;
        }
        .payment-summary {
            background: #f9fafb;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .payment-summary h3 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            color: #1f2937;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        .summary-row.total {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--color-primary, #2563eb);
            padding-top: 1rem;
            border-top: 2px solid #e5e7eb;
            margin-top: 1rem;
        }
        .confirm-btn {
            width: 100%;
            padding: 1rem;
            background: var(--color-primary, #2563eb);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .confirm-btn:hover {
            background: #1d4ed8;
        }
        .confirm-btn:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }
        .loading {
            display: none;
            text-align: center;
            padding: 2rem;
        }
        .loading.active {
            display: block;
        }
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--color-primary, #2563eb);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <?php include __DIR__ . DIRECTORY_SEPARATOR . '_sidebar.php'; ?>
        
        <!-- Main Content -->
        <main class="dashboard-main">
            <div class="subscribe-container">
                <div class="subscribe-header">
                    <h1>Devenez Auteur</h1>
                    <p>Abonnez-vous pour publier vos articles dans la Revue Congolaise de Théologie protestante</p>
                </div>

                <div class="pricing-cards">
                    <div class="pricing-card" data-region="afrique">
                        <h3>Afrique</h3>
                        <div class="price">25,00 <span>$</span></div>
                        <p>Tarif pour les auteurs résidant en Afrique</p>
                    </div>
                    <div class="pricing-card" data-region="europe">
                        <h3>Europe</h3>
                        <div class="price">30,00 <span>$</span></div>
                        <p>Tarif pour les auteurs résidant en Europe</p>
                    </div>
                    <div class="pricing-card" data-region="amerique">
                        <h3>Amérique</h3>
                        <div class="price">35,00 <span>$</span></div>
                        <p>Tarif pour les auteurs résidant en Amérique</p>
                    </div>
                </div>

                <button class="subscribe-btn" id="subscribeBtn" disabled>
                    Choisir une région et s'abonner
                </button>
            </div>
        </main>
    </div>

    <!-- Payment Modal -->
    <div class="payment-modal" id="paymentModal">
        <div class="payment-modal-content">
            <div class="payment-modal-header">
                <h2>Choisir un moyen de paiement</h2>
                <button class="close-modal" id="closeModal">&times;</button>
            </div>

            <div class="payment-summary">
                <h3>Résumé de l'abonnement</h3>
                <div class="summary-row">
                    <span>Région sélectionnée:</span>
                    <span id="summaryRegion">-</span>
                </div>
                <div class="summary-row">
                    <span>Durée:</span>
                    <span>1 an</span>
                </div>
                <div class="summary-row total">
                    <span>Total:</span>
                    <span id="summaryAmount">-</span>
                </div>
            </div>

            <div class="payment-options">
                <div class="payment-option" data-moyen="orange_money">
                    <img src="<?= Router\Router::$defaultUri ?>assets/orange_money.jpeg" alt="Orange Money" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="logo-placeholder" style="display:none;">Orange</div>
                    <h4>Orange Money</h4>
                </div>
                <div class="payment-option" data-moyen="mpesa">
                    <img src="<?= Router\Router::$defaultUri ?>assets/mpesa.jpeg" alt="M-Pesa" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="logo-placeholder" style="display:none;">M-Pesa</div>
                    <h4>M-Pesa</h4>
                </div>
                <div class="payment-option" data-moyen="airtel_money">
                    <img src="<?= Router\Router::$defaultUri ?>assets/airtel_money.jpeg" alt="Airtel Money" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="logo-placeholder" style="display:none;">Airtel</div>
                    <h4>Airtel Money</h4>
                </div>
                <div class="payment-option" data-moyen="bancaire">
                    <div class="logo-placeholder">🏦</div>
                    <h4>Paiement Bancaire</h4>
                </div>
            </div>

            <!-- Formulaire de paiement -->
            <div class="payment-form" id="paymentForm">
                <h3 style="margin-bottom: 1.5rem; color: #1f2937;">Informations de paiement</h3>
                
                <!-- Formulaire pour Mobile Money -->
                <div id="mobileMoneyForm" style="display: none;">
                    <div class="form-group">
                        <label for="phoneNumber">Numéro de téléphone</label>
                        <input type="tel" id="phoneNumber" name="phoneNumber" placeholder="Ex: +243 900 000 000" required>
                        <div class="help-text">Entrez votre numéro de téléphone associé à votre compte mobile money</div>
                    </div>
                </div>

                <!-- Formulaire pour Paiement Bancaire -->
                <div id="bankForm" style="display: none;">
                    <div class="form-group">
                        <label for="cardNumber">Numéro de carte</label>
                        <input type="text" id="cardNumber" name="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19" required>
                        <div class="help-text">Entrez les 16 chiffres de votre carte bancaire</div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="cardExpiry">Date d'expiration</label>
                            <input type="text" id="cardExpiry" name="cardExpiry" placeholder="MM/AA" maxlength="5" required>
                        </div>
                        <div class="form-group">
                            <label for="cardCVC">CVC</label>
                            <input type="text" id="cardCVC" name="cardCVC" placeholder="123" maxlength="4" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cardName">Nom sur la carte</label>
                        <input type="text" id="cardName" name="cardName" placeholder="Nom complet" required>
                    </div>
                </div>
            </div>

            <div class="loading" id="loading">
                <div class="spinner"></div>
                <p>Traitement du paiement...</p>
            </div>

            <button class="confirm-btn" id="confirmBtn" disabled>
                Valider le paiement
            </button>
        </div>
    </div>

    <script src="<?= Router\Router::$defaultUri ?>js/author-notify.js"></script>
    <script>
        const tarifs = {
            afrique: 25.00,
            europe: 30.00,
            amerique: 35.00
        };

        let selectedRegion = null;
        let selectedMoyen = null;

        // Sélection de la région
        document.querySelectorAll('.pricing-card').forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.pricing-card').forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                selectedRegion = this.dataset.region;
                document.getElementById('subscribeBtn').disabled = false;
                document.getElementById('subscribeBtn').textContent = `S'abonner - ${tarifs[selectedRegion].toFixed(2)} $`;
            });
        });

        // Ouvrir le modal
        document.getElementById('subscribeBtn').addEventListener('click', function() {
            if (!selectedRegion) return;
            
            document.getElementById('summaryRegion').textContent = selectedRegion.charAt(0).toUpperCase() + selectedRegion.slice(1);
            document.getElementById('summaryAmount').textContent = tarifs[selectedRegion].toFixed(2) + ' $';
            document.getElementById('paymentModal').classList.add('active');
        });

        // Fermer le modal
        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('paymentModal').classList.remove('active');
            selectedMoyen = null;
            document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('selected'));
            document.getElementById('paymentForm').classList.remove('active');
            document.getElementById('mobileMoneyForm').style.display = 'none';
            document.getElementById('bankForm').style.display = 'none';
            document.getElementById('confirmBtn').disabled = true;
            // Réinitialiser les champs
            document.getElementById('phoneNumber').value = '';
            document.getElementById('cardNumber').value = '';
            document.getElementById('cardExpiry').value = '';
            document.getElementById('cardCVC').value = '';
            document.getElementById('cardName').value = '';
        });

        // Sélection du moyen de paiement
        document.querySelectorAll('.payment-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
                selectedMoyen = this.dataset.moyen;
                
                // Afficher le formulaire approprié
                const paymentForm = document.getElementById('paymentForm');
                const mobileMoneyForm = document.getElementById('mobileMoneyForm');
                const bankForm = document.getElementById('bankForm');
                
                paymentForm.classList.add('active');
                
                if (selectedMoyen === 'bancaire') {
                    mobileMoneyForm.style.display = 'none';
                    bankForm.style.display = 'block';
                } else {
                    mobileMoneyForm.style.display = 'block';
                    bankForm.style.display = 'none';
                }
                
                // Réinitialiser les champs
                document.getElementById('phoneNumber').value = '';
                document.getElementById('cardNumber').value = '';
                document.getElementById('cardExpiry').value = '';
                document.getElementById('cardCVC').value = '';
                document.getElementById('cardName').value = '';
                
                // Valider le formulaire (sera false car les champs sont vides)
                validateForm();
            });
        });

        // Validation du formulaire en temps réel
        function validateForm() {
            if (!selectedMoyen) {
                document.getElementById('confirmBtn').disabled = true;
                return false;
            }
            
            let isValid = false;
            
            if (selectedMoyen === 'bancaire') {
                const cardNumber = document.getElementById('cardNumber').value.replace(/\s/g, '');
                const cardExpiry = document.getElementById('cardExpiry').value;
                const cardCVC = document.getElementById('cardCVC').value;
                const cardName = document.getElementById('cardName').value;
                
                isValid = cardNumber.length === 16 && 
                         /^\d{16}$/.test(cardNumber) &&
                         /^\d{2}\/\d{2}$/.test(cardExpiry) &&
                         /^\d{3,4}$/.test(cardCVC) &&
                         cardName.trim().length > 0;
            } else {
                const phoneNumber = document.getElementById('phoneNumber').value.trim();
                // Validation basique du numéro de téléphone
                isValid = phoneNumber.length >= 9 && /^[\d\s\+\-\(\)]+$/.test(phoneNumber);
            }
            
            document.getElementById('confirmBtn').disabled = !isValid;
            return isValid;
        }

        // Écouteurs pour la validation en temps réel
        document.getElementById('phoneNumber').addEventListener('input', validateForm);
        document.getElementById('cardNumber').addEventListener('input', function(e) {
            // Formater le numéro de carte avec des espaces
            let value = e.target.value.replace(/\s/g, '');
            if (value.length > 0) {
                value = value.match(/.{1,4}/g).join(' ');
            }
            e.target.value = value;
            validateForm();
        });
        document.getElementById('cardExpiry').addEventListener('input', function(e) {
            // Formater la date d'expiration
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
            validateForm();
        });
        document.getElementById('cardCVC').addEventListener('input', validateForm);
        document.getElementById('cardName').addEventListener('input', validateForm);

        // Confirmer le paiement
        document.getElementById('confirmBtn').addEventListener('click', async function() {
            if (!selectedRegion || !selectedMoyen) return;

            // Valider le formulaire avant de continuer
            if (!validateForm()) {
                showToast('Veuillez remplir correctement tous les champs requis.', 'error');
                return;
            }

            // Afficher le loading
            document.getElementById('loading').classList.add('active');
            document.getElementById('confirmBtn').disabled = true;

            try {
                const formData = new FormData();
                formData.append('moyen', selectedMoyen);
                formData.append('region', selectedRegion);
                
                // Ajouter les données du formulaire selon le mode de paiement
                if (selectedMoyen === 'bancaire') {
                    formData.append('cardNumber', document.getElementById('cardNumber').value.replace(/\s/g, ''));
                    formData.append('cardExpiry', document.getElementById('cardExpiry').value);
                    formData.append('cardCVC', document.getElementById('cardCVC').value);
                    formData.append('cardName', document.getElementById('cardName').value);
                } else {
                    formData.append('phoneNumber', document.getElementById('phoneNumber').value);
                }

                const response = await fetch('<?= Router\Router::route("author") ?>/subscribe', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    showToast(data.message || 'Abonnement créé avec succès !', 'success');
                    window.location.href = data.redirect || '<?= Router\Router::route("author") ?>';
                } else {
                    showToast(data.error || 'Erreur lors de la création de l\'abonnement', 'error');
                    document.getElementById('loading').classList.remove('active');
                    document.getElementById('confirmBtn').disabled = false;
                }
            } catch (error) {
                console.error('Erreur:', error);
                showToast('Une erreur est survenue. Veuillez réessayer.', 'error');
                document.getElementById('loading').classList.remove('active');
                document.getElementById('confirmBtn').disabled = false;
            }
        });

        // Fermer le modal en cliquant en dehors
        document.getElementById('paymentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                document.getElementById('closeModal').click();
            }
        });
    </script>
    <script src="<?= Router\Router::$defaultUri ?>js/script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/dashboard-script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/user-dropdown.js"></script>
</body>
</html>
