-- Création de la table chat_messages
CREATE TABLE IF NOT EXISTS chat_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    user_name VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    is_from_admin TINYINT(1) DEFAULT 0,
    is_read TINYINT(1) DEFAULT 0,
    created_at DATETIME NOT NULL,
    INDEX idx_user (user_id),
    INDEX idx_read (is_read),
    INDEX idx_admin (is_from_admin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
