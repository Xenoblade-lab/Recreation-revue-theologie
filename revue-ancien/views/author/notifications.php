<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Dashboard Auteur</title>
    <link rel="stylesheet" href="<?= Router\Router::$defaultUri ?>css/styles.css">
    <link rel="stylesheet" href="<?= Router\Router::$defaultUri ?>css/dashboard-styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-layout">
        <?php include __DIR__ . DIRECTORY_SEPARATOR . '_sidebar.php'; ?>
        
        <main class="dashboard-main">
            <div class="dashboard-header fade-up">
                <div class="header-title">
                    <h1>Notifications</h1>
                    <p>Restez informé de l'état de vos soumissions</p>
                </div>
                <?php if ($unreadCount > 0): ?>
                    <div class="header-actions">
                        <button onclick="markAllAsRead()" class="btn btn-outline">Marquer tout comme lu</button>
                    </div>
                <?php endif; ?>
            </div>

            <div class="content-card fade-up">
                <?php if (!empty($notifications)): ?>
                    <div class="notifications-list">
                        <?php foreach ($notifications as $notification): ?>
                            <div class="notification-item <?= $notification['is_read'] ? '' : 'unread' ?>" data-id="<?= $notification['id'] ?>">
                                <div class="notification-icon">
                                    <?php if (!$notification['is_read']): ?>
                                        <div class="unread-indicator"></div>
                                    <?php endif; ?>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                                    </svg>
                                </div>
                                <div class="notification-content">
                                    <h3><?= htmlspecialchars($notification['title']) ?></h3>
                                    <p><?= nl2br(htmlspecialchars($notification['message'])) ?></p>
                                    <small><?= date('d M Y à H:i', strtotime($notification['created_at'])) ?></small>
                                    <?php if ($notification['related_article_id']): ?>
                                        <a href="<?= Router\Router::route('author') ?>/article/<?= $notification['related_article_id'] ?>" class="btn btn-outline btn-sm" style="margin-top: 0.5rem;">
                                            Voir l'article
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <?php if (!$notification['is_read']): ?>
                                    <button class="notification-mark-read" onclick="markAsRead(<?= $notification['id'] ?>)">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                        <h3>Aucune notification</h3>
                        <p>Vous n'avez pas encore de notifications.</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="<?= Router\Router::$defaultUri ?>js/script.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/author-notify.js"></script>
    <script src="<?= Router\Router::$defaultUri ?>js/dashboard-script.js"></script>
    <script>
        function markAsRead(notificationId) {
            fetch('<?= Router\Router::route("author") ?>/notification/' + notificationId + '/read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const item = document.querySelector(`[data-id="${notificationId}"]`);
                    if (item) {
                        item.classList.remove('unread');
                        const markBtn = item.querySelector('.notification-mark-read');
                        if (markBtn) markBtn.remove();
                        const indicator = item.querySelector('.unread-indicator');
                        if (indicator) indicator.remove();
                    }
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
        }

        async function markAllAsRead() {
            const ok = await showConfirm({
                title: 'Marquer comme lues',
                message: 'Marquer toutes les notifications comme lues ?',
                confirmText: 'Marquer tout',
                cancelText: 'Annuler'
            });
            if (!ok) return;
            
            fetch('<?= Router\Router::route("author") ?>/notifications/read-all', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelectorAll('.notification-item.unread').forEach(item => {
                        item.classList.remove('unread');
                        const markBtn = item.querySelector('.notification-mark-read');
                        if (markBtn) markBtn.remove();
                        const indicator = item.querySelector('.unread-indicator');
                        if (indicator) indicator.remove();
                    });
                    showToast('Toutes les notifications ont été marquées comme lues', 'success');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
        }
    </script>
    <style>
        .notifications-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .notification-item {
            display: flex;
            gap: 1rem;
            padding: 1rem;
            border: 1px solid var(--color-gray-200);
            border-radius: 8px;
            background: var(--color-white);
            transition: all 0.2s;
        }
        .notification-item.unread {
            background: rgba(59, 130, 246, 0.05);
            border-color: var(--color-blue);
        }
        .notification-icon {
            position: relative;
            flex-shrink: 0;
        }
        .unread-indicator {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 12px;
            height: 12px;
            background: var(--color-red);
            border-radius: 50%;
            border: 2px solid var(--color-white);
        }
        .notification-content {
            flex: 1;
        }
        .notification-content h3 {
            margin: 0 0 0.5rem 0;
            font-size: 1rem;
            color: var(--color-gray-900);
        }
        .notification-content p {
            margin: 0 0 0.5rem 0;
            color: var(--color-gray-600);
            line-height: 1.5;
        }
        .notification-content small {
            color: var(--color-gray-500);
            font-size: 0.875rem;
        }
        .notification-mark-read {
            background: transparent;
            border: none;
            color: var(--color-blue);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 4px;
            transition: background 0.2s;
        }
        .notification-mark-read:hover {
            background: var(--color-gray-100);
        }
    </style>
</body>
</html>

