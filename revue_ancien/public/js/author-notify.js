/**
 * Côté auteur : toasts (messages informatifs) et modale de confirmation
 * Remplace alert() et confirm() par des UI cohérentes.
 */
(function() {
    'use strict';

    var toastContainer = null;
    var confirmModal = null;
    var confirmResolve = null;

    function getToastContainer() {
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'author-toast-container';
            toastContainer.setAttribute('aria-live', 'polite');
            document.body.appendChild(toastContainer);
        }
        return toastContainer;
    }

    /**
     * Affiche un message informatif (toast)
     * @param {string} message - Texte à afficher
     * @param {string} type - 'success' | 'error' | 'info'
     */
    window.showToast = function(message, type) {
        type = type || 'info';
        var container = getToastContainer();
        var toast = document.createElement('div');
        toast.className = 'author-toast author-toast--' + type;
        toast.setAttribute('role', 'status');
        var icon = type === 'success' ? '✓' : (type === 'error' ? '!' : 'ℹ');
        toast.innerHTML = '<span class="author-toast__icon">' + icon + '</span><span class="author-toast__text">' + escapeHtml(message) + '</span>';
        container.appendChild(toast);

        requestAnimationFrame(function() {
            toast.classList.add('author-toast--visible');
        });

        setTimeout(function() {
            toast.classList.remove('author-toast--visible');
            setTimeout(function() {
                if (toast.parentNode) toast.parentNode.removeChild(toast);
            }, 300);
        }, 4000);
    };

    function escapeHtml(text) {
        if (!text) return '';
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function getConfirmModal() {
        if (!confirmModal) {
            confirmModal = document.createElement('div');
            confirmModal.className = 'author-confirm-modal';
            confirmModal.id = 'author-confirm-modal';
            confirmModal.setAttribute('role', 'dialog');
            confirmModal.setAttribute('aria-modal', 'true');
            confirmModal.setAttribute('aria-labelledby', 'author-confirm-title');
            confirmModal.innerHTML = [
                '<div class="author-confirm-backdrop"></div>',
                '<div class="author-confirm-content">',
                '  <div class="author-confirm-header">',
                '    <h2 id="author-confirm-title" class="author-confirm-title"></h2>',
                '    <button type="button" class="author-confirm-close" aria-label="Fermer">&times;</button>',
                '  </div>',
                '  <div class="author-confirm-body">',
                '    <p class="author-confirm-message"></p>',
                '  </div>',
                '  <div class="author-confirm-footer">',
                '    <button type="button" class="btn btn-outline author-confirm-cancel">Annuler</button>',
                '    <button type="button" class="btn btn-primary author-confirm-ok">Confirmer</button>',
                '  </div>',
                '</div>'
            ].join('');
            document.body.appendChild(confirmModal);

            // Styles inline pour que le popup soit toujours visible (overlay + boîte)
            var backdrop = confirmModal.querySelector('.author-confirm-backdrop');
            var contentBox = confirmModal.querySelector('.author-confirm-content');
            var headerEl = confirmModal.querySelector('.author-confirm-header');
            var bodyEl = confirmModal.querySelector('.author-confirm-body');
            var footerEl = confirmModal.querySelector('.author-confirm-footer');
            backdrop.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;background:rgba(0,0,0,0.55);cursor:pointer;z-index:0;';
            contentBox.style.cssText = 'position:relative;z-index:1;background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);max-width:440px;width:100%;min-width:280px;box-sizing:border-box;';
            headerEl.style.cssText = 'display:flex;justify-content:space-between;align-items:center;padding:1.5rem 2rem;border-bottom:1px solid #e5e7eb;';
            bodyEl.style.cssText = 'padding:1.5rem 2rem;';
            footerEl.style.cssText = 'display:flex;justify-content:flex-end;gap:1rem;padding:1rem 2rem 1.5rem;border-top:1px solid #e5e7eb;';
            confirmModal.querySelector('.author-confirm-title').style.cssText = 'font-size:1.25rem;font-weight:600;color:#1a3365;margin:0;';
            confirmModal.querySelector('.author-confirm-message').style.cssText = 'margin:0;font-size:1rem;color:#374151;line-height:1.5;';
            confirmModal.querySelector('.author-confirm-close').style.cssText = 'background:none;border:none;font-size:1.5rem;color:#4b5563;cursor:pointer;padding:0;line-height:1;';

            var titleEl = confirmModal.querySelector('.author-confirm-title');
            var messageEl = confirmModal.querySelector('.author-confirm-message');
            var btnCancel = confirmModal.querySelector('.author-confirm-cancel');
            var btnOk = confirmModal.querySelector('.author-confirm-ok');
            var btnClose = confirmModal.querySelector('.author-confirm-close');

            function close(result) {
                confirmModal.classList.remove('author-confirm-modal--open');
                confirmModal.style.cssText = '';
                if (confirmResolve) {
                    confirmResolve(result);
                    confirmResolve = null;
                }
                btnOk.disabled = false;
            }

            btnOk.addEventListener('click', function() {
                btnOk.disabled = true;
                close(true);
            });
            btnCancel.addEventListener('click', function() { close(false); });
            btnClose.addEventListener('click', function() { close(false); });
            backdrop.addEventListener('click', function() { close(false); });
            confirmModal.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') close(false);
            });
        }
        return confirmModal;
    }

    /**
     * Affiche une modale de confirmation (remplace confirm())
     * @param {Object} options - { title, message, confirmText, cancelText }
     * @returns {Promise<boolean>} - true si confirmé, false si annulé
     */
    window.showConfirm = function(options) {
        options = options || {};
        var modal = getConfirmModal();
        var title = options.title || 'Confirmation';
        var message = options.message || 'Êtes-vous sûr ?';
        var confirmText = options.confirmText || 'Confirmer';
        var cancelText = options.cancelText || 'Annuler';

        modal.querySelector('.author-confirm-title').textContent = title;
        modal.querySelector('.author-confirm-message').textContent = message;
        modal.querySelector('.author-confirm-ok').textContent = confirmText;
        modal.querySelector('.author-confirm-cancel').textContent = cancelText;

        return new Promise(function(resolve) {
            confirmResolve = resolve;
            // Réinsérer la modale à la fin du body pour la mettre au-dessus de tout
            if (modal.parentNode) modal.parentNode.removeChild(modal);
            document.body.appendChild(modal);
            // Forcer l’affichage (secours si le CSS ne charge pas)
            modal.style.cssText = 'display:flex!important;position:fixed!important;inset:0!important;width:100%!important;height:100%!important;z-index:10000!important;align-items:center!important;justify-content:center!important;padding:1.5rem!important;box-sizing:border-box!important;pointer-events:auto!important;';
            modal.classList.add('author-confirm-modal--open');
            modal.querySelector('.author-confirm-ok').focus();
        });
    };
})();
