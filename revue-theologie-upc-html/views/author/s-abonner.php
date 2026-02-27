<?php
$formules = $formules ?? [];
$error = $error ?? null;
$base = $base ?? '';
$hasPendingRequest = $hasPendingRequest ?? false;
$tarifs = array_column($formules, 'montant', 'id');
?>
<div class="dashboard-header">
  <h1><?= htmlspecialchars(__('author.subscribe_title')) ?></h1>
  <p><?= htmlspecialchars(__('author.subscribe_intro')) ?></p>
</div>
<?php if ($error): ?>
<p class="text-accent mb-4"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<?php if ($hasPendingRequest): ?>
<div class="dashboard-card" style="background: #eff6ff; border-color: var(--primary);">
  <h2><?= htmlspecialchars(function_exists('__') ? __('author.subscribe_one_pending_title') : 'Demande déjà en cours') ?></h2>
  <p class="mb-4"><?= htmlspecialchars(function_exists('__') ? __('author.subscribe_one_pending_message') : 'Vous avez déjà une demande d\'abonnement en attente de validation. Vous ne pouvez soumettre qu\'une seule demande à la fois. Attendez la réponse de l\'équipe ou annulez votre demande en cours depuis la page Mon abonnement.') ?></p>
  <p class="mb-0">
    <a href="<?= $base ?>/author/abonnement" class="btn btn-primary"><?= htmlspecialchars(__('author.my_subscription')) ?></a>
    <a href="<?= $base ?>/author" class="btn btn-outline-primary" style="margin-left: 0.5rem;"><?= htmlspecialchars(__('author.back_dashboard')) ?></a>
  </p>
</div>
<?php else: ?>
<div class="dashboard-card region-choice-card">
  <h2 class="region-choice-title"><?= htmlspecialchars(__('author.subscribe_choose')) ?></h2>
  <p class="region-choice-subtitle"><?= htmlspecialchars(function_exists('__') ? __('author.subscribe_region_choice') : 'Choisissez votre région (tarif pour 1 an).') ?></p>
  <p class="region-choice-hint" id="regionHint"><?= htmlspecialchars(function_exists('__') ? __('author.subscribe_click_region') : 'Cliquez sur une région ci-dessous pour la sélectionner.') ?></p>
  <div class="region-cards-grid" id="region-cards">
    <?php foreach ($formules as $f): ?>
    <button type="button" class="region-card pricing-card text-left w-full" data-region="<?= htmlspecialchars($f['id'] ?? '') ?>" data-montant="<?= (float)($f['montant'] ?? 0) ?>" data-region-label="<?= htmlspecialchars($f['region'] ?? $f['id']) ?>">
      <span class="region-card-name"><?= htmlspecialchars($f['region'] ?? $f['duree_label'] ?? $f['id']) ?></span>
      <span class="region-card-duration"><?= htmlspecialchars($f['duree_label'] ?? '1 an') ?></span>
      <span class="region-card-price"><?= number_format((float)($f['montant'] ?? 0), 0, ',', ' ') ?> <span class="region-card-currency"><?= htmlspecialchars($f['currency'] ?? 'USD') ?></span></span>
    </button>
    <?php endforeach; ?>
  </div>
  <div class="region-choice-actions">
    <button type="button" class="btn btn-primary btn-lg" id="btnOpenModal" disabled aria-describedby="regionHint">
      <?= htmlspecialchars(function_exists('__') ? __('author.subscribe_btn_choose_region') : 'Choisir une région et s\'abonner') ?>
    </button>
    <p class="region-choice-help hidden" id="pleaseSelectRegion"><?= htmlspecialchars(function_exists('__') ? __('author.subscribe_select_first') : 'Veuillez d\'abord cliquer sur une région ci-dessus.') ?></p>
  </div>
  <p class="mt-4 mb-0"><a href="<?= $base ?>/author/abonnement" class="btn btn-outline-primary"><?= htmlspecialchars(__('author.back_dashboard')) ?></a></p>
</div>
<?php endif; ?>

<?php if (!$hasPendingRequest): ?>
<!-- Modal Choisir un moyen de paiement -->
<div class="payment-modal-overlay" id="paymentModal" style="display: none;" aria-modal="true" role="dialog" aria-labelledby="paymentModalTitle">
  <div class="payment-modal-box">
    <div class="payment-modal-header">
      <h2 class="payment-modal-title" id="paymentModalTitle"><?= htmlspecialchars(function_exists('__') ? __('author.payment_modal_title') : 'Choisir un moyen de paiement') ?></h2>
      <button type="button" class="payment-modal-close" id="closeModal" aria-label="Fermer">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="payment-modal-summary">
      <span class="payment-modal-summary-label"><?= htmlspecialchars(function_exists('__') ? __('author.subscription_summary') : 'Résumé de l\'abonnement') ?></span>
      <p class="payment-modal-summary-text"><span id="summaryRegion">—</span> · 1 an · <strong id="summaryAmount">—</strong></p>
    </div>
    <p class="payment-modal-step"><?= htmlspecialchars(function_exists('__') ? __('author.payment_choose_method') : 'Sélectionnez un moyen de paiement') ?></p>
    <div class="payment-options-grid" id="paymentOptionsGrid">
      <button type="button" class="payment-option" data-moyen="orange_money">
        <span class="payment-option-img-wrap"><img src="<?= $base ?>/assets/orange_money.jpeg" alt="" class="payment-option-img" width="80" height="44"></span>
        <span class="payment-option-label">Orange Money</span>
      </button>
      <button type="button" class="payment-option" data-moyen="mpesa">
        <span class="payment-option-img-wrap"><img src="<?= $base ?>/assets/mpesa.jpeg" alt="" class="payment-option-img" width="80" height="44"></span>
        <span class="payment-option-label">M-Pesa</span>
      </button>
      <button type="button" class="payment-option" data-moyen="airtel_money">
        <span class="payment-option-img-wrap"><img src="<?= $base ?>/assets/airtel_money.jpeg" alt="" class="payment-option-img" width="80" height="44"></span>
        <span class="payment-option-label">Airtel Money</span>
      </button>
      <button type="button" class="payment-option" data-moyen="bancaire">
        <span class="payment-option-icon" aria-hidden="true">
          <svg xmlns="http://www.w3.org/2000/svg" width="40" height="28" viewBox="0 0 24 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect width="22" height="14" x="1" y="1" rx="2"/><line x1="1" x2="23" y1="6" y2="6"/></svg>
        </span>
        <span class="payment-option-label"><?= htmlspecialchars(function_exists('__') ? __('author.payment_bank') : 'Paiement Bancaire') ?></span>
      </button>
    </div>
    <form method="post" action="<?= $base ?>/author/s-abonner" id="paymentForm" class="payment-modal-form">
      <?= csrf_field() ?>
      <input type="hidden" name="formule_id" id="inputFormuleId" value="">
      <input type="hidden" name="moyen" id="inputMoyen" value="">
      <div class="payment-info-section" id="paymentInfoSection">
        <h3 class="payment-info-title"><?= htmlspecialchars(function_exists('__') ? __('author.payment_info_title') : 'Informations de paiement') ?></h3>
        <p class="payment-info-placeholder" id="paymentInfoPlaceholder"><?= htmlspecialchars(function_exists('__') ? __('author.payment_choose_method_first') : 'Choisissez un moyen de paiement ci-dessus pour afficher les champs.') ?></p>
        <div id="mobileForm" class="payment-form-hidden" aria-hidden="true">
          <p class="payment-form-subtitle"><?= htmlspecialchars(function_exists('__') ? __('author.payment_mobile_info') : 'Mobile Money (Orange, M-Pesa, Airtel)') ?></p>
          <label class="payment-field-label"><?= htmlspecialchars(function_exists('__') ? __('author.phone_number') : 'Numéro de téléphone') ?></label>
          <input type="tel" name="phoneNumber" id="phoneNumber" class="input payment-input" placeholder="Ex: +243 900 000 000">
          <p class="payment-field-hint"><?= htmlspecialchars(function_exists('__') ? __('author.phone_help') : 'Numéro associé à votre compte mobile money') ?></p>
        </div>
        <div id="bankForm" class="payment-form-hidden" aria-hidden="true">
          <p class="payment-form-subtitle"><?= htmlspecialchars(function_exists('__') ? __('author.payment_card_info') : 'Carte bancaire') ?></p>
          <label class="payment-field-label"><?= htmlspecialchars(function_exists('__') ? __('author.card_number') : 'Numéro de carte') ?></label>
          <input type="text" name="cardNumber" id="cardNumber" class="input payment-input" placeholder="1234 5678 9012 3456" maxlength="19">
          <div class="payment-card-row">
            <div class="payment-field-group">
              <label class="payment-field-label"><?= htmlspecialchars(function_exists('__') ? __('author.card_expiry') : 'MM/AA') ?></label>
              <input type="text" name="cardExpiry" id="cardExpiry" class="input payment-input" placeholder="MM/AA" maxlength="5">
            </div>
            <div class="payment-field-group">
              <label class="payment-field-label">CVC</label>
              <input type="text" name="cardCVC" id="cardCVC" class="input payment-input" placeholder="123" maxlength="4">
            </div>
          </div>
          <label class="payment-field-label"><?= htmlspecialchars(function_exists('__') ? __('author.card_name') : 'Nom sur la carte') ?></label>
          <input type="text" name="cardName" id="cardName" class="input payment-input">
        </div>
      </div>
      <button type="submit" class="payment-submit-btn" id="btnSubmitPayment" disabled>
        <?= htmlspecialchars(function_exists('__') ? __('author.validate_payment') : 'Valider le paiement') ?>
      </button>
    </form>
  </div>
</div>
<?php endif; ?>

<style>
/* Section choix de région — cartes tarifaires */
.region-choice-card { max-width: 720px; }
.region-choice-title { margin-bottom: 0.25rem; font-size: 1.25rem; }
.region-choice-subtitle { color: var(--muted-foreground, #64748b); font-size: 0.9375rem; margin-bottom: 0.5rem; }
.region-choice-hint {
  font-size: 0.8125rem; color: var(--muted-foreground, #64748b); margin-bottom: 1rem;
  display: flex; align-items: center; gap: 0.375rem;
}
.region-choice-hint::before {
  content: ''; display: inline-block; width: 4px; height: 4px; border-radius: 50%;
  background: var(--primary, #2760A8);
}
.region-cards-grid {
  display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem;
}
@media (max-width: 640px) {
  .region-cards-grid { grid-template-columns: 1fr; }
}
.region-card {
  cursor: pointer; border: 2px solid var(--border, #e2e8f0); border-radius: 12px;
  padding: 1.25rem 1rem; background: #fff; text-align: left; transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
  display: flex; flex-direction: column; gap: 0.25rem; min-height: 120px;
}
.region-card:hover {
  border-color: var(--primary, #2760A8); box-shadow: 0 4px 12px rgba(39, 96, 168, 0.12);
}
.region-card.selected {
  border-color: var(--primary, #2760A8); background: linear-gradient(135deg, rgba(39, 96, 168, 0.06) 0%, rgba(39, 96, 168, 0.02) 100%);
  box-shadow: 0 4px 16px rgba(39, 96, 168, 0.15);
}
.region-card.selected .region-card-name { color: var(--primary, #2760A8); font-weight: 700; }
.region-card.selected .region-card-price { color: var(--primary, #2760A8); }
.region-card:focus { outline: 2px solid var(--primary, #2760A8); outline-offset: 2px; }
.region-card-name { font-size: 1.0625rem; font-weight: 600; color: var(--foreground, #1e293b); line-height: 1.3; }
.region-card-duration { font-size: 0.8125rem; color: var(--muted-foreground, #64748b); }
.region-card-price { font-size: 1.375rem; font-weight: 700; color: var(--foreground, #1e293b); margin-top: auto; }
.region-card-currency { font-size: 0.875rem; font-weight: 600; color: var(--muted-foreground, #64748b); }
.region-choice-actions { display: flex; flex-wrap: wrap; align-items: center; gap: 0.75rem; }
.region-choice-actions .btn-lg { padding: 0.625rem 1.25rem; font-size: 1rem; }
.region-choice-help { font-size: 0.8125rem; color: var(--accent, #dc2626); margin: 0; }
.region-choice-card .hidden { display: none !important; }
@keyframes region-pulse { 0%, 100% { box-shadow: 0 4px 12px rgba(39, 96, 168, 0.12); } 50% { box-shadow: 0 0 0 3px rgba(39, 96, 168, 0.25); } }
.region-cards-pulse .region-card { animation: region-pulse 0.6s ease-in-out 2; }

/* Modal paiement — overlay et conteneur */
.payment-modal-overlay {
  display: flex !important;
  align-items: center;
  justify-content: center;
  position: fixed;
  inset: 0;
  z-index: 1000;
  padding: 1rem;
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(4px);
  -webkit-backdrop-filter: blur(4px);
}
.payment-modal-overlay[style*="none"] { display: none !important; }
.payment-modal-box {
  width: 100%;
  max-width: 420px;
  max-height: 90vh;
  overflow-y: auto;
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  padding: 1.5rem;
}

/* En-tête modal */
.payment-modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  margin-bottom: 1.25rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid #e5e7eb;
}
.payment-modal-title {
  margin: 0;
  font-size: 1.25rem;
  font-weight: 700;
  color: #111827;
  line-height: 1.3;
}
.payment-modal-close {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  padding: 0;
  border: none;
  border-radius: 10px;
  background: #f3f4f6;
  color: #6b7280;
  cursor: pointer;
  transition: background 0.2s, color 0.2s;
}
.payment-modal-close:hover {
  background: #e5e7eb;
  color: #111827;
}

/* Résumé abonnement */
.payment-modal-summary {
  background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
  border: 1px solid #bae6fd;
  border-radius: 12px;
  padding: 0.875rem 1rem;
  margin-bottom: 1.25rem;
}
.payment-modal-summary-label {
  display: block;
  font-size: 0.75rem;
  font-weight: 600;
  color: #0369a1;
  text-transform: uppercase;
  letter-spacing: 0.03em;
  margin-bottom: 0.25rem;
}
.payment-modal-summary-text {
  margin: 0;
  font-size: 0.9375rem;
  color: #0c4a6e;
}

/* Étape “Sélectionnez un moyen” */
.payment-modal-step {
  font-size: 0.8125rem;
  font-weight: 600;
  color: #374151;
  margin: 0 0 0.75rem 0;
}

/* Grille moyens de paiement */
.payment-options-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.75rem;
  margin-bottom: 1.5rem;
}
.payment-option {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  min-height: 110px;
  padding: 1rem 0.75rem;
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  background: #fff;
  cursor: pointer;
  text-align: center;
  transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
}
.payment-option:hover {
  border-color: #93c5fd;
  background: #f8fafc;
}
.payment-option.selected {
  border-color: var(--primary, #2760A8);
  background: #eff6ff;
  box-shadow: 0 0 0 3px rgba(39, 96, 168, 0.15);
}
.payment-option-img-wrap {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 44px;
}
.payment-option-img {
  max-width: 100%;
  max-height: 44px;
  width: auto;
  height: auto;
  object-fit: contain;
}
.payment-option-icon {
  color: var(--primary, #2760A8);
  display: flex;
  align-items: center;
  justify-content: center;
}
.payment-option-label {
  font-size: 0.8125rem;
  font-weight: 600;
  color: #374151;
}

/* Section informations de paiement */
.payment-modal-form { margin-top: 0; }
.payment-info-section {
  padding-top: 1.25rem;
  margin-top: 1.25rem;
  border-top: 1px solid #e5e7eb;
}
.payment-info-title {
  font-size: 0.9375rem;
  font-weight: 600;
  color: #111827;
  margin: 0 0 0.75rem 0;
}
.payment-info-placeholder {
  font-size: 0.8125rem;
  color: #6b7280;
  background: #f9fafb;
  border-radius: 8px;
  padding: 0.75rem 1rem;
  margin: 0 0 0.5rem 0;
  border: 1px dashed #d1d5db;
}
.payment-form-subtitle {
  font-size: 0.75rem;
  color: #6b7280;
  margin: 0 0 0.5rem 0;
}
.payment-field-label {
  display: block;
  font-size: 0.8125rem;
  font-weight: 500;
  color: #374151;
  margin-bottom: 0.25rem;
}
.payment-input {
  width: 100%;
  margin-bottom: 0.75rem;
  padding: 0.5rem 0.75rem;
  border-radius: 8px;
  border: 1px solid #d1d5db;
}
.payment-field-hint {
  font-size: 0.75rem;
  color: #6b7280;
  margin: 0 0 0.5rem 0;
}
.payment-card-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.75rem;
  margin-bottom: 0.75rem;
}
.payment-field-group { margin-bottom: 0; }

/* Bouton Valider */
.payment-submit-btn {
  width: 100%;
  margin-top: 1.25rem;
  padding: 0.875rem 1.25rem;
  font-size: 1rem;
  font-weight: 600;
  color: #fff;
  background: var(--primary, #2760A8);
  border: none;
  border-radius: 10px;
  cursor: pointer;
  transition: background 0.2s, opacity 0.2s;
}
.payment-submit-btn:hover:not(:disabled) {
  background: var(--upc-blue-dark, #1e4a82);
}
.payment-submit-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* Masquage conditionnel des formulaires */
.payment-form-hidden { display: none !important; }
#mobileForm:not(.payment-form-hidden),
#bankForm:not(.payment-form-hidden) { display: block !important; }
#paymentInfoPlaceholder.payment-form-hidden { display: none !important; }
</style>
<?php if (!$hasPendingRequest): ?>
<script>
(function() {
  const modal = document.getElementById('paymentModal');
  const btnOpen = document.getElementById('btnOpenModal');
  const closeBtn = document.getElementById('closeModal');
  const regionCards = document.querySelectorAll('.pricing-card');
  const inputFormuleId = document.getElementById('inputFormuleId');
  const inputMoyen = document.getElementById('inputMoyen');
  const summaryRegion = document.getElementById('summaryRegion');
  const summaryAmount = document.getElementById('summaryAmount');
  const paymentOptions = document.querySelectorAll('.payment-option');
  const mobileForm = document.getElementById('mobileForm');
  const bankForm = document.getElementById('bankForm');
  const paymentInfoPlaceholder = document.getElementById('paymentInfoPlaceholder');
  const phoneNumber = document.getElementById('phoneNumber');
  const cardNumber = document.getElementById('cardNumber');
  const cardExpiry = document.getElementById('cardExpiry');
  const cardCVC = document.getElementById('cardCVC');
  const cardName = document.getElementById('cardName');
  const btnSubmit = document.getElementById('btnSubmitPayment');

  let selectedRegion = null;
  let selectedMontant = 0;
  let selectedRegionLabel = '';
  let selectedMoyen = null;

  regionCards.forEach(function(card) {
    card.addEventListener('click', function() {
      regionCards.forEach(function(c) { c.classList.remove('selected'); });
      this.classList.add('selected');
      selectedRegion = (this.getAttribute('data-region') || '').trim();
      if (!selectedRegion && this.dataset && this.dataset.region) selectedRegion = String(this.dataset.region);
      selectedMontant = parseFloat(this.getAttribute('data-montant') || 0) || 0;
      if (!selectedMontant && this.dataset && this.dataset.montant) selectedMontant = parseFloat(this.dataset.montant) || 0;
      selectedRegionLabel = (this.getAttribute('data-region-label') || '').trim();
      if (!selectedRegionLabel && this.dataset && this.dataset.regionLabel) selectedRegionLabel = String(this.dataset.regionLabel);
      if (!selectedRegionLabel) selectedRegionLabel = selectedRegion;
      btnOpen.disabled = false;
      btnOpen.textContent = "S'abonner - " + selectedMontant.toFixed(2) + " $";
      if (inputFormuleId) inputFormuleId.value = selectedRegion;
      var pleaseSelect = document.getElementById('pleaseSelectRegion');
      if (pleaseSelect) pleaseSelect.classList.add('hidden');
    });
  });

  btnOpen.addEventListener('click', function() {
    if (!selectedRegion) {
      var hint = document.getElementById('pleaseSelectRegion');
      var container = document.getElementById('region-cards');
      if (hint) { hint.classList.remove('hidden'); hint.setAttribute('aria-live', 'polite'); }
      if (container) { container.scrollIntoView({ behavior: 'smooth', block: 'center' }); container.classList.add('region-cards-pulse'); setTimeout(function() { container.classList.remove('region-cards-pulse'); }, 2000); }
      return;
    }
    if (inputFormuleId) inputFormuleId.value = selectedRegion;
    summaryRegion.textContent = selectedRegionLabel;
    summaryAmount.textContent = selectedMontant.toFixed(2) + " $";
    selectedMoyen = null;
    inputMoyen.value = '';
    paymentOptions.forEach(function(o) { o.classList.remove('selected'); });
    mobileForm.classList.add('payment-form-hidden');
    mobileForm.setAttribute('aria-hidden', 'true');
    bankForm.classList.add('payment-form-hidden');
    bankForm.setAttribute('aria-hidden', 'true');
    if (paymentInfoPlaceholder) { paymentInfoPlaceholder.classList.remove('payment-form-hidden'); }
    phoneNumber.value = '';
    phoneNumber.removeAttribute('required');
    cardNumber.value = '';
    cardExpiry.value = '';
    cardCVC.value = '';
    cardName.value = '';
    [cardNumber, cardExpiry, cardCVC, cardName].forEach(function(el) { if (el) el.removeAttribute('required'); });
    btnSubmit.disabled = true;
    modal.style.display = 'flex';
    var pleaseSelect = document.getElementById('pleaseSelectRegion');
    if (pleaseSelect) pleaseSelect.classList.add('hidden');
  });

  closeBtn.addEventListener('click', function() { modal.style.display = 'none'; });
  modal.addEventListener('click', function(e) { if (e.target === modal) modal.style.display = 'none'; });

  paymentOptions.forEach(function(opt) {
    opt.addEventListener('click', function() {
      paymentOptions.forEach(function(o) { o.classList.remove('selected'); });
      this.classList.add('selected');
      selectedMoyen = this.getAttribute('data-moyen') || this.dataset.moyen;
      inputMoyen.value = selectedMoyen;
      mobileForm.classList.add('payment-form-hidden');
      mobileForm.setAttribute('aria-hidden', 'true');
      bankForm.classList.add('payment-form-hidden');
      bankForm.setAttribute('aria-hidden', 'true');
      phoneNumber.removeAttribute('required');
      [cardNumber, cardExpiry, cardCVC, cardName].forEach(function(el) { if (el) el.removeAttribute('required'); });
      if (paymentInfoPlaceholder) paymentInfoPlaceholder.classList.add('payment-form-hidden');
      if (selectedMoyen === 'bancaire') {
        bankForm.classList.remove('payment-form-hidden');
        bankForm.setAttribute('aria-hidden', 'false');
        [cardNumber, cardExpiry, cardCVC, cardName].forEach(function(el) { if (el) el.setAttribute('required', 'required'); });
      } else {
        mobileForm.classList.remove('payment-form-hidden');
        mobileForm.setAttribute('aria-hidden', 'false');
        phoneNumber.setAttribute('required', 'required');
      }
      checkCanSubmit();
    });
  });

  function checkCanSubmit() {
    if (!selectedMoyen) { btnSubmit.disabled = true; return; }
    if (selectedMoyen === 'bancaire') {
      var cn = (cardNumber.value || '').replace(/\s/g, '');
      var ok = cn.length === 16 && /^\d{2}\/\d{2}$/.test((cardExpiry.value || '')) && /^\d{3,4}$/.test((cardCVC.value || '')) && (cardName.value || '').trim().length > 0;
      btnSubmit.disabled = !ok;
    } else {
      var ph = (phoneNumber.value || '').trim();
      btnSubmit.disabled = ph.length < 9;
    }
  }
  if (phoneNumber) phoneNumber.addEventListener('input', checkCanSubmit);
  if (cardNumber) cardNumber.addEventListener('input', function(e) {
    var v = e.target.value.replace(/\s/g, '');
    if (v.length > 0) e.target.value = v.match(/.{1,4}/g).join(' ');
    checkCanSubmit();
  });
  if (cardExpiry) cardExpiry.addEventListener('input', function(e) {
    var v = e.target.value.replace(/\D/g, '');
    if (v.length >= 2) v = v.substring(0,2) + '/' + v.substring(2,4);
    e.target.value = v;
    checkCanSubmit();
  });
  if (cardCVC) cardCVC.addEventListener('input', checkCanSubmit);
  if (cardName) cardName.addEventListener('input', checkCanSubmit);
})();
</script>
<?php endif; ?>
