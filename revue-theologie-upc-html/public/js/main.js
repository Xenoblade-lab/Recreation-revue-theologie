/**
 * Revue de la Faculte de Theologie - UPC - Script page test (index_2.html)
 * Menu mobile, carousels, formulaire newsletter, sondage.
 */
(function () {
  'use strict';

  // Dropdown langue : empêcher navigation sur le lien #
  var langToggle = document.getElementById('lang-toggle');
  if (langToggle) {
    langToggle.addEventListener('click', function (e) { e.preventDefault(); });
  }

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

  var mobileLinks = document.querySelectorAll('#nav-mobile a');
  mobileLinks.forEach(function (link) {
    link.addEventListener('click', function () {
      if (navMobile) navMobile.classList.remove('open');
    });
  });

  // Carousels : boutons prev/next pour .issues-carousel et .coming-blocks
  function scrollCarousel(container, direction) {
    if (!container) return;
    var step = 240;
    if (direction === 'next') container.scrollBy({ left: step, behavior: 'smooth' });
    else container.scrollBy({ left: -step, behavior: 'smooth' });
  }

  document.querySelectorAll('.section-previous-issues .btn-prev').forEach(function (btn) {
    btn.addEventListener('click', function () {
      scrollCarousel(document.getElementById('issues-carousel'), 'prev');
    });
  });
  document.querySelectorAll('.section-previous-issues .btn-next').forEach(function (btn) {
    btn.addEventListener('click', function () {
      scrollCarousel(document.getElementById('issues-carousel'), 'next');
    });
  });

  // Newsletter (index_2)
  var newsletterForm = document.getElementById('newsletter-form');
  if (newsletterForm) {
    newsletterForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var input = newsletterForm.querySelector('.newsletter-input');
      if (input && input.value.trim()) {
        alert('Merci ! Vous recevrez nos actualites a l\'adresse : ' + input.value.trim());
        input.value = '';
      }
    });
  }

  // Sondage "Question de la semaine"
  var pollForm = document.getElementById('widget-poll');
  if (pollForm) {
    pollForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var checked = pollForm.querySelector('input[name="question_week"]:checked');
      if (checked) {
        alert('Merci d\'avoir participe au sondage.');
        pollForm.reset();
      } else {
        alert('Veuillez choisir une option.');
      }
    });
  }

  // Recherche accueil (redirection simple vers publications avec query)
  var homeSearch = document.getElementById('home-search');
  var btnSearchSubmit = document.querySelector('.btn-search-submit');
  if (homeSearch && btnSearchSubmit) {
    function doSearch() {
      var q = homeSearch.value.trim();
      if (q) window.location.href = 'publications.html?q=' + encodeURIComponent(q);
    }
    btnSearchSubmit.addEventListener('click', doSearch);
    homeSearch.addEventListener('keypress', function (e) {
      if (e.key === 'Enter') { e.preventDefault(); doSearch(); }
    });
  }

  // Filtres publications (si présents sur la page)
  var filterBtn = document.getElementById('filter-toggle');
  var filterPanel = document.getElementById('filter-panel');
  if (filterBtn && filterPanel) {
    filterBtn.addEventListener('click', function () { filterPanel.classList.toggle('open'); });
  }

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
    if (countEl) countEl.textContent = count + ' article' + (count !== 1 ? 's' : '') + ' trouvé' + (count !== 1 ? 's' : '');
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

  // Toggle mot de passe (login/register)
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
