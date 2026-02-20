// User Dropdown & Notifications Management
document.addEventListener('DOMContentLoaded', function() {
    const userBtn = document.getElementById('userBtn');
    const userMenu = document.getElementById('userMenu');
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationsMenu = document.getElementById('notificationsMenu');
    const notificationsList = document.getElementById('notificationsList');
    const notificationBadge = document.getElementById('notificationBadge');
    const markAllReadBtn = document.getElementById('markAllRead');
    const logoutBtn = document.getElementById('logoutBtn');
    
    // Toggle User Dropdown
    if (userBtn && userMenu) {
        const userDropdown = userBtn.closest('.user-dropdown');
        
        userBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const isActive = userMenu.classList.contains('active');
            
            // Fermer les autres menus
            if (notificationsMenu) {
                notificationsMenu.classList.remove('active');
            }
            
            // Toggle user menu
            userMenu.classList.toggle('active', !isActive);
            if (userDropdown) {
                userDropdown.classList.toggle('active', !isActive);
            }
        });
    }
    
    // Toggle Notifications Dropdown
    if (notificationBtn && notificationsMenu) {
        notificationBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const isActive = notificationsMenu.classList.contains('active');
            
            // Fermer les autres menus
            if (userMenu) {
                userMenu.classList.remove('active');
            }
            const userDropdown = userBtn?.closest('.user-dropdown');
            if (userDropdown) {
                userDropdown.classList.remove('active');
            }
            
            // Toggle notifications menu
            notificationsMenu.classList.toggle('active', !isActive);
            
            // Charger les notifications si le menu est ouvert
            if (!isActive) {
                loadNotifications();
            }
        });
    }
    
    // Fermer les menus en cliquant à l'extérieur
    document.addEventListener('click', function(e) {
        const userDropdown = userBtn?.closest('.user-dropdown');
        
        if (userMenu && !userMenu.contains(e.target) && !userBtn?.contains(e.target)) {
            userMenu.classList.remove('active');
            if (userDropdown) {
                userDropdown.classList.remove('active');
            }
        }
        if (notificationsMenu && !notificationsMenu.contains(e.target) && !notificationBtn?.contains(e.target)) {
            notificationsMenu.classList.remove('active');
        }
    });
    
    // Logout avec confirmation
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
                window.location.href = logoutBtn.getAttribute('href');
            }
        });
    }
    
    // Marquer toutes les notifications comme lues
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function() {
            markAllNotificationsAsRead();
        });
    }
    
    // Charger les notifications
    function loadNotifications() {
        fetch('http://localhost/Revue-Theologie-Upc/public/api/notifications')
            .then(response => response.json())
            .then(data => {
                if (data.notifications && data.notifications.length > 0) {
                    displayNotifications(data.notifications);
                    updateNotificationBadge(data.notifications.filter(n => !n.read).length);
                } else {
                    displayEmptyNotifications();
                    updateNotificationBadge(0);
                }
            })
            .catch(error => {
                console.error('Erreur lors du chargement des notifications:', error);
                displayEmptyNotifications();
            });
    }
    
    // Afficher les notifications
    function displayNotifications(notifications) {
        notificationsList.innerHTML = '';
        
        notifications.forEach(notification => {
            const notificationItem = document.createElement('div');
            notificationItem.className = `notification-item ${notification.read ? '' : 'unread'}`;
            notificationItem.innerHTML = `
                <div class="notification-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                </div>
                <div class="notification-content">
                    <div class="notification-title">${escapeHtml(notification.title || 'Notification')}</div>
                    <div class="notification-message">${escapeHtml(notification.message || '')}</div>
                    <div class="notification-time">${formatTime(notification.created_at || notification.date)}</div>
                </div>
            `;
            
            notificationItem.addEventListener('click', function() {
                markNotificationAsRead(notification.id);
            });
            
            notificationsList.appendChild(notificationItem);
        });
    }
    
    // Afficher message vide
    function displayEmptyNotifications() {
        notificationsList.innerHTML = '<div class="notification-empty">Aucune notification</div>';
    }
    
    // Mettre à jour le badge de notifications
    function updateNotificationBadge(count) {
        if (notificationBadge) {
            if (count > 0) {
                notificationBadge.textContent = count > 99 ? '99+' : count;
                notificationBadge.classList.remove('hidden');
            } else {
                notificationBadge.classList.add('hidden');
            }
        }
    }
    
    // Marquer une notification comme lue
    function markNotificationAsRead(notificationId) {
        fetch(`http://localhost/Revue-Theologie-Upc/public/api/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Recharger les notifications
            loadNotifications();
        })
        .catch(error => {
            console.error('Erreur lors de la mise à jour de la notification:', error);
        });
    }
    
    // Marquer toutes les notifications comme lues
    function markAllNotificationsAsRead() {
        // TODO: Implémenter l'endpoint pour marquer toutes comme lues
        loadNotifications();
    }
    
    // Formater le temps
    function formatTime(dateString) {
        if (!dateString) return '';
        
        const date = new Date(dateString);
        const now = new Date();
        const diff = now - date;
        const minutes = Math.floor(diff / 60000);
        const hours = Math.floor(diff / 3600000);
        const days = Math.floor(diff / 86400000);
        
        if (minutes < 1) return 'À l\'instant';
        if (minutes < 60) return `Il y a ${minutes} min`;
        if (hours < 24) return `Il y a ${hours} h`;
        if (days < 7) return `Il y a ${days} j`;
        
        return date.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' });
    }
    
    // Échapper le HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Charger les notifications au chargement de la page
    if (notificationBtn) {
        loadNotifications();
        
        // Recharger les notifications toutes les 30 secondes
        setInterval(loadNotifications, 30000);
    }
});

