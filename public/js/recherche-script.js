// Advanced search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('advanced-search-form');
    const resultsContainer = document.getElementById('search-results');
    const resultsSummary = document.querySelector('.results-summary');
    const filterChips = document.querySelectorAll('.chip');

    // Les données mockées ont été supprimées - la recherche utilise maintenant la base de données

    // Handle filter chips
    filterChips.forEach(chip => {
        chip.addEventListener('click', function() {
            filterChips.forEach(c => c.classList.remove('is-selected'));
            this.classList.add('is-selected');
        });
    });

    // Handle form submission
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const author = document.getElementById('search-author').value;
        const keyword = document.getElementById('search-keyword').value;
        const yearFrom = document.getElementById('year-from').value;
        const yearTo = document.getElementById('year-to').value;
        const selectedFilter = document.querySelector('.chip.is-selected').getAttribute('data-filter');

        // Show loading state
        resultsSummary.innerHTML = '<div class="loading-spinner">Recherche en cours...</div>';
        resultsContainer.innerHTML = '';

        // Préparer les données du formulaire
        const formData = new FormData();
        formData.append('author', author || '');
        formData.append('keyword', keyword || '');
        formData.append('year_from', yearFrom || '');
        formData.append('year_to', yearTo || '');
        if (selectedFilter && selectedFilter !== 'all') formData.append('type', selectedFilter);
        
        // Afficher les données envoyées dans la console
        console.log('Données envoyées:');
        for (let [key, value] of formData.entries()) {
            console.log(key + ':', value);
        }

        // Faire la requête AJAX
        const searchUrl = window.location.origin + window.location.pathname;
        
        console.log('Envoi de la recherche:', {
            author: author,
            keyword: keyword,
            yearFrom: yearFrom,
            yearTo: yearTo
        });
        
        fetch(searchUrl, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            console.log('Réponse reçue, status:', response.status);
            const contentType = response.headers.get('content-type');
            console.log('Content-Type:', contentType);
            
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                // Si ce n'est pas du JSON, recharger la page
                console.log('Réponse non-JSON, rechargement de la page');
                throw new Error('Réponse non-JSON, rechargement de la page');
            }
        })
        .then(data => {
            console.log('Données reçues:', data);
            if (data.success && Array.isArray(data.results)) {
                displayResults(data.results);
            } else {
                resultsSummary.innerHTML = '<p class="error">Aucun résultat trouvé</p>';
                resultsContainer.innerHTML = '';
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            // En cas d'erreur ou si ce n'est pas du JSON, soumettre le formulaire normalement
            console.log('Soumission normale du formulaire');
            searchForm.submit();
        });
    });

    // Handle form reset
    searchForm.addEventListener('reset', function() {
        setTimeout(() => {
            filterChips.forEach(c => c.classList.remove('is-selected'));
            filterChips[0].classList.add('is-selected');
            resultsSummary.textContent = 'Saisissez vos critères puis lancez la recherche pour afficher les résultats.';
            resultsContainer.innerHTML = '';
        }, 10);
    });

    function displayResults(results) {
        if (results.length === 0) {
            resultsSummary.innerHTML = `
                <div class="empty-results">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3>Aucun résultat trouvé</h3>
                    <p>Essayez de modifier vos critères de recherche</p>
                </div>
            `;
            resultsContainer.innerHTML = '';
            return;
        }

        resultsSummary.innerHTML = `<p class="results-count">${results.length} résultat${results.length > 1 ? 's' : ''} trouvé${results.length > 1 ? 's' : ''}</p>`;
        
        resultsContainer.innerHTML = results.map(result => {
            const authorName = (result.auteur_prenom || '') + ' ' + (result.auteur_nom || '');
            const excerpt = result.contenu ? (result.contenu.length > 200 ? result.contenu.substring(0, 200) + '...' : result.contenu) : 'Aucune description disponible.';
            const year = result.volume_annee || (result.date_soumission ? new Date(result.date_soumission).getFullYear() : '');
            const issueInfo = result.issue_numero ? result.issue_numero : '';
            
            // Construire l'URL de base
            const pathParts = window.location.pathname.split('/');
            const publicIndex = pathParts.indexOf('public');
            const basePath = publicIndex >= 0 ? pathParts.slice(0, publicIndex + 1).join('/') : '/';
            const baseUrl = window.location.origin + basePath + '/';
            
            const pdfUrl = result.fichier_path ? baseUrl + escapeHtml(result.fichier_path) : '';
            const publicationsUrl = baseUrl + 'publications';
            
            // Nettoyer l'excerpt (supprimer les balises HTML)
            const cleanExcerpt = excerpt.replace(/<[^>]*>/g, '').trim();
            const displayExcerpt = cleanExcerpt.length > 200 ? cleanExcerpt.substring(0, 200) + '...' : cleanExcerpt;
            
            return `
                <li class="result-item">
                    <span class="result-category">Article publié</span>
                    <h3 class="result-title">
                        <a href="${publicationsUrl}">${escapeHtml(result.titre || 'Sans titre')}</a>
                    </h3>
                    <p class="result-authors">Par ${escapeHtml(authorName.trim() || 'Auteur inconnu')}</p>
                    <p class="result-excerpt">${escapeHtml(displayExcerpt)}</p>
                    <div class="result-meta">
                        ${issueInfo ? `<span>${escapeHtml(issueInfo)}</span>` : ''}
                        ${year ? `<span>${year}</span>` : ''}
                        ${pdfUrl ? `<a href="${pdfUrl}" target="_blank" class="btn btn-outline btn-sm">PDF</a>` : ''}
                    </div>
                </li>
            `;
        }).join('');
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});