<?php
namespace Models;

/**
 * Inscriptions newsletter (table newsletter_emails).
 * Créer la table si besoin : CREATE TABLE newsletter_emails (id INT AUTO_INCREMENT PRIMARY KEY, email VARCHAR(255) NOT NULL UNIQUE, created_at DATETIME DEFAULT CURRENT_TIMESTAMP);
 */
class NewsletterModel
{
    public static function subscribe(string $email): bool
    {
        $email = trim(strtolower($email));
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        $db = getDB();
        try {
            $stmt = $db->prepare("INSERT INTO newsletter_emails (email, created_at) VALUES (:email, NOW())");
            return $stmt->execute([':email' => $email]);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                return true; // déjà inscrit
            }
            throw $e;
        }
    }
}
