// Committee page filtering functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const memberCards = document.querySelectorAll('.member-card');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const role = this.getAttribute('data-role');
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('is-active'));
            this.classList.add('is-active');
            
            // Filter cards
            memberCards.forEach(card => {
                const cardRole = card.getAttribute('data-role');
                
                if (role === 'all' || cardRole === role) {
                    card.classList.remove('hidden');
                    // Add fade-in animation
                    card.style.animation = 'fadeIn 0.5s ease';
                } else {
                    card.classList.add('hidden');
                }
            });
        });
    });
});