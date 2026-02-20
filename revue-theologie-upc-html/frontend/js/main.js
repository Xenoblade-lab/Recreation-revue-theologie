/**
 * Revue de la Faculte de Theologie - UPC - Script principal
 */
(function () {
  'use strict';

  // Menu mobile
  var menuToggle = document.getElementById('menu-toggle');
  var navMobile = document.getElementById('nav-mobile');
  if (menuToggle && navMobile) {
    menuToggle.addEventListener('click', function () {
      navMobile.classList.toggle('open');
      var aria = navMobile.classList.contains('open') ? 'true' : 'false';
      menuToggle.setAttribute('aria-expanded', aria);
    });
  }

  // Fermer le menu mobile au clic sur un lien (navigation)
  var mobileLinks = document.querySelectorAll('#nav-mobile a');
  mobileLinks.forEach(function (link) {
    link.addEventListener('click', function () {
      if (navMobile) navMobile.classList.remove('open');
    });
  });

  // Filtres publications : bouton Filtres
  var filterBtn = document.getElementById('filter-toggle');
  var filterPanel = document.getElementById('filter-panel');
  if (filterBtn && filterPanel) {
    filterBtn.addEventListener('click', function () {
      filterPanel.classList.toggle('open');
    });
  }

  // Filtres publications : chips catégorie et année
  var categoryChips = document.querySelectorAll('[data-filter="category"]');
  var yearChips = document.querySelectorAll('[data-filter="year"]');
  var articlesContainer = document.getElementById('articles-list');
  var searchInput = document.getElementById('search-input');

  function getSelectedCategory() {
    var c = document.querySelector('[data-filter="category"].active');
    return c ? c.getAttribute('data-value') : 'Toutes';
  }
  function getSelectedYear() {
    var y = document.querySelector('[data-filter="year"].active');
    return y ? y.getAttribute('data-value') : 'Toutes';
  }
  function getSearchQuery() {
    return searchInput ? searchInput.value.trim().toLowerCase() : '';
  }

  function filterArticles() {
    if (!articlesContainer) return;
    var cat = getSelectedCategory();
    var year = getSelectedYear();
    var q = getSearchQuery();
    var items = articlesContainer.querySelectorAll('[data-article]');
    var count = 0;
    items.forEach(function (el) {
      var articleCat = el.getAttribute('data-category') || '';
      var articleYear = el.getAttribute('data-year') || '';
      var title = (el.getAttribute('data-title') || '').toLowerCase();
      var author = (el.getAttribute('data-author') || '').toLowerCase();
      var abstract = (el.getAttribute('data-abstract') || '').toLowerCase();
      var matchCat = cat === 'Toutes' || articleCat === cat;
      var matchYear = year === 'Toutes' || articleYear === year;
      var matchSearch = !q || title.indexOf(q) >= 0 || author.indexOf(q) >= 0 || abstract.indexOf(q) >= 0;
      var show = matchCat && matchYear && matchSearch;
      el.style.display = show ? '' : 'none';
      if (show) count++;
    });
    var countEl = document.getElementById('results-count');
    if (countEl) {
      countEl.textContent = count + ' article' + (count !== 1 ? 's' : '') + ' trouvé' + (count !== 1 ? 's' : '');
    }
  }

  categoryChips.forEach(function (btn) {
    btn.addEventListener('click', function () {
      categoryChips.forEach(function (b) { b.classList.remove('active'); });
      btn.classList.add('active');
      filterArticles();
    });
  });
  yearChips.forEach(function (btn) {
    btn.addEventListener('click', function () {
      yearChips.forEach(function (b) { b.classList.remove('active'); });
      btn.classList.add('active');
      filterArticles();
    });
  });
  if (searchInput) {
    searchInput.addEventListener('input', filterArticles);
    searchInput.addEventListener('keyup', filterArticles);
  }

  // Formulaire contact
  var contactForm = document.getElementById('contact-form');
  var contactSuccess = document.getElementById('contact-success');
  var contactFormWrap = document.getElementById('contact-form-wrap');
  if (contactForm && contactSuccess && contactFormWrap) {
    contactForm.addEventListener('submit', function (e) {
      e.preventDefault();
      contactFormWrap.style.display = 'none';
      contactSuccess.style.display = 'block';
    });
  }
  var sendAnotherBtn = document.getElementById('send-another');
  if (sendAnotherBtn && contactSuccess && contactFormWrap) {
    sendAnotherBtn.addEventListener('click', function () {
      contactSuccess.style.display = 'none';
      contactFormWrap.style.display = 'block';
    });
  }

  // Login/Register : toggle mot de passe (swap eye / eye-off icon)
  var iconPath = document.querySelector('.password-toggle use');
  var iconBase = iconPath ? (iconPath.getAttribute('href') || '').split('#')[0] : '';
  document.querySelectorAll('.password-toggle').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var wrap = btn.closest('.password-wrap');
      if (!wrap) return;
      var input = wrap.querySelector('input[type="password"], input[type="text"]');
      if (!input) return;
      var use = btn.querySelector('use');
      if (input.type === 'password') {
        input.type = 'text';
        btn.setAttribute('aria-label', 'Masquer le mot de passe');
        if (use) use.setAttribute('href', (iconBase || 'images/icons.svg') + '#eye-off');
      } else {
        input.type = 'password';
        btn.setAttribute('aria-label', 'Afficher le mot de passe');
        if (use) use.setAttribute('href', (iconBase || 'images/icons.svg') + '#eye');
      }
    });
  });
})();
