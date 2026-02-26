-- Inscriptions newsletter (Phase C1)
CREATE TABLE IF NOT EXISTS newsletter_emails (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uk_email (email)
);
