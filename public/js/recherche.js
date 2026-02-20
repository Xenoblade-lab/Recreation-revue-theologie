// Advanced search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('advanced-search-form');
    const resultsContainer = document.getElementById('search-results');
    const resultsSummary = document.querySelector('.results-summary');
    const filterChips = document.querySelectorAll('.chip');

    // Mock data for demonstration
    const mockResults = [
        {
            category: 'Théologie Systématique',
            title: 'La pneumatologie africaine : une réflexion trinitaire',
            authors: 'Dr. Jean-Baptiste Nkulu, Pr. Marie Kabongo',
            excerpt: 'Une exploration de la compréhension africaine de l\'Esprit Saint dans le contexte de la théologie trinitaire contemporaine...',
            year: '2025',
            volume: 'Vol. 28, No 1',
            pages: 'pp. 15-42',
            type: 'article'
        },
        {
            category: 'Études Bibliques',
            title: 'Herméneutique décoloniale et lecture africaine de l\'Exode',
            authors: 'Pr. Samuel Mulamba',
            excerpt: 'Comment les communautés africaines lisent-elles le récit de l\'Exode à travers leurs propres expériences historiques...',
            year: '2025',
            volume: 'Vol. 28, No 1',
            pages: 'pp. 43-68',
            type: 'article'
        },
        {
            category: 'Éthique Chrétienne',
            title: 'Justice environnementale et responsabilité écologique chrétienne',
            authors: 'Dr. Grace Mwamba, Dr. Paul Ilunga',
            excerpt: 'L\'urgence climatique interpelle la théologie africaine : quelle éthique environnementale pour les Églises congolaises...',
            year: '2025',
            volume: 'Vol. 28, No 1',
            pages: 'pp. 69-95',
            type: 'article'
        }
    ];

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
        resultsSummary.innerHTML = '<div class="loading-spinner"></div>';
        resultsContainer.innerHTML = '';

        // Simulate API call delay
        setTimeout(() => {
            displayResults(mockResults);
        }, 500);
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
        
        resultsContainer.innerHTML = results.map(result => `
            <li class="result-item">
                <span class="result-category">${result.category}</span>
                <h3 class="result-title">
                    <a href="#">${result.title}</a>
                </h3>
                <p class="result-authors">${result.authors}</p>
                <p class="result-excerpt">${result.excerpt}</p>
                <div class="result-meta">
                    <span>${result.volume}</span>
                    <span>${result.year}</span>
                    <span>${result.pages}</span>
                </div>
            </li>
        `).join('');
    }
});